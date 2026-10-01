<?php

namespace App\AI\Knowledge;

use App\AI\Data\DatabaseSource;
use App\AI\Data\KnowledgeSource;
use App\AI\Data\StaticSource;
use App\AI\NLP\Normalizer;
use App\AI\Support\AIConfig;
use App\Models\AiDocument;
use App\Models\AiKeyword;
use App\Models\AiKnowledge;
use App\Models\AiRelationship;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Membangun dan memelihara index knowledge.
 *
 * Sifat:
 * - Incremental: hanya record yang checksum-nya berubah yang ditulis ulang.
 * - Membersihkan record yang sudah tidak ada di sumber data (deleted).
 * - Menyimpan statistik korpus (ai_keywords, ai_documents) untuk BM25/TF-IDF.
 * - Membangun graf relasi antar fakta (ai_relationships).
 */
class KnowledgeIndexer
{
    public function __construct(
        protected KnowledgeRepository $repository = new KnowledgeRepository(),
    ) {
    }

    /**
     * @return array{
     *     inserted: int, updated: int, unchanged: int, deleted: int,
     *     relationships: int, errors: array<int,string>, duration_ms: float
     * }
     */
    public function rebuild(bool $incremental = true, bool $withRelationships = true): array
    {
        $start = microtime(true);
        $stats = [
            'inserted' => 0,
            'updated' => 0,
            'unchanged' => 0,
            'deleted' => 0,
            'relationships' => 0,
            'errors' => [],
            'duration_ms' => 0.0,
        ];

        $sources = $this->sources();
        $records = [];

        foreach ($sources as $name => $source) {
            try {
                foreach ($source->fetch() as $record) {
                    $records[$this->recordKey($record)] = $record;
                }
            } catch (\Throwable $e) {
                $stats['errors'][] = $name . ': ' . $e->getMessage();
                Log::warning('AI index: gagal membaca sumber ' . $name, ['error' => $e->getMessage()]);
            }
        }

        // Diagnostik per sumber database.
        if (isset($sources['database']) && $sources['database'] instanceof DatabaseSource) {
            foreach ($sources['database']->errors() as $error) {
                $stats['errors'][] = 'database -> ' . $error;
            }
        }

        $existing = AiKnowledge::get()->keyBy(
            fn (AiKnowledge $row): string => $this->rowKey($row)
        );

        $now = now();

        DB::transaction(function () use ($records, $existing, $incremental, &$stats, $now) {
            foreach ($records as $key => $record) {
                $checksum = $record->checksum();
                $row = $existing->get($key);

                if ($row && $row->checksum === $checksum) {
                    // Tidak berubah: cukup perbarui waktu index.
                    if (! $incremental) {
                        $row->forceFill(['checksum' => $checksum, 'indexed_at' => $now])->save();
                    }

                    $stats['unchanged']++;
                    continue;
                }

                $payload = array_merge($record->toArray(), [
                    'checksum' => $checksum,
                    'indexed_at' => $now,
                ]);
                unset($payload['key']);

                if ($row) {
                    $row->fill($payload)->save();
                    $stats['updated']++;
                } else {
                    AiKnowledge::create($payload);
                    $stats['inserted']++;
                }
            }
        });

        // Hapus record yang sudah hilang dari sumber data.
        $stale = AiKnowledge::all()
            ->filter(fn (AiKnowledge $row): bool => ! array_key_exists($this->rowKey($row), $records));

        if ($stale->isNotEmpty()) {
            $ids = $stale->pluck('id')->all();

            DB::transaction(function () use ($ids) {
                AiKnowledge::whereIn('id', $ids)->delete();
                AiDocument::whereIn('knowledge_id', $ids)->delete();
            });

            $stats['deleted'] = $stale->count();
        }

        $this->buildCorpusStats();
        $stats['relationships'] = $withRelationships ? $this->buildRelationships() : 0;

        $this->seedDefaults();
        $this->repository->flushCaches();

        $stats['duration_ms'] = round((microtime(true) - $start) * 1000, 2);

        return $stats;
    }

    /**
     * Siapkan kamus synonym dan definisi intent default (idempotent).
     */
    public function seedDefaults(): void
    {
        try {
            (new DefaultSeeder())->run();
        } catch (\Throwable $e) {
            Log::warning('AI index: gagal seeding default', ['error' => $e->getMessage()]);
        }
    }

    /**
     * @return array<string,KnowledgeSource>
     */
    protected function sources(): array
    {
        $sources = [
            'database' => new DatabaseSource(),
            'static' => new StaticSource(),
        ];

        if (config('ai.sources.document_path')) {
            $path = (string) config('ai.sources.document_path');

            if (is_readable($path)) {
                $sources['document'] = new \App\AI\Data\DocumentSource($path);
            }
        }

        return $sources;
    }

    protected function recordKey(KnowledgeRecord $record): string
    {
        return $record->category . '|' . ($record->sourceId ?? '') . '|' . $record->key;
    }

    protected function rowKey(AiKnowledge $row): string
    {
        return $row->category . '|' . ($row->source_id ?? '') . '|'
            . (string) (($row->metadata['key'] ?? ''));
    }

    /**
     * Hitung ulang document frequency dan statistik panjang dokumen.
     */
    public function buildCorpusStats(): void
    {
        $rows = AiKnowledge::orderBy('id')->get();

        $df = [];
        $termCount = [];
        $uniqueCount = [];

        foreach ($rows as $row) {
            $terms = Normalizer::termFrequency($this->searchableText($row));

            foreach (array_keys($terms) as $token) {
                $df[$token] = ($df[$token] ?? 0) + 1;
            }

            $termCount[$row->id] = array_sum($terms);
            $uniqueCount[$row->id] = count($terms);
        }

        DB::transaction(function () use ($df, $termCount, $uniqueCount, $rows) {
            // Document frequency.
            AiKeyword::query()->delete();
            $keywordRows = [];

            foreach ($df as $token => $frequency) {
                $keywordRows[] = [
                    'token' => $token,
                    'document_frequency' => $frequency,
                    'is_stopword' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            foreach (array_chunk($keywordRows, 500) as $chunk) {
                AiKeyword::insert($chunk);
            }

            // Statistik per dokumen.
            AiDocument::query()->delete();
            $documentRows = [];

            foreach ($rows as $row) {
                $documentRows[] = [
                    'knowledge_id' => $row->id,
                    'category' => $row->category,
                    'source_type' => $row->source_type,
                    'source_id' => $row->source_id,
                    'term_count' => $termCount[$row->id] ?? 0,
                    'unique_term_count' => $uniqueCount[$row->id] ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            foreach (array_chunk($documentRows, 500) as $chunk) {
                AiDocument::insert($chunk);
            }
        });
    }

    /**
     * Membangun graf relasi antar fakta.
     *
     * Relasi diturunkan dari dua sumber:
     * 1. Satu entitas sumber yang sama (mis. satu jurusan menghasilkan
     *    record deskripsi, tag, dan keunggulan -> saling terkait).
     * 2. Kemiripan kata kunci antar kategori berbeda (mis. seorang guru dan
     *    jurusan yang sama-sama menyebut mata pelajaran tersebut).
     */
    public function buildRelationships(): int
    {
        AiRelationship::query()->delete();

        $rows = AiKnowledge::orderBy('id')->get();
        if ($rows->count() < 2) {
            return 0;
        }

        $pairs = [];

        // 1. Relasi dalam entitas sumber yang sama.
        $bySource = $rows->groupBy(
            fn (AiKnowledge $row): string => $row->category . '::' . ($row->source_id ?? '')
        );

        foreach ($bySource as $group) {
            if ($group->count() < 2) {
                continue;
            }

            $ids = $group->pluck('id')->all();

            foreach ($ids as $a) {
                foreach ($ids as $b) {
                    if ($a < $b) {
                        $pairs[$a . ':' . $b] = ['from' => $a, 'to' => $b, 'relation' => 'same_entity', 'weight' => 1.0];
                    }
                }
            }
        }

        // 2. Relasi berbasis kesamaan kata kunci (batas agar tidak meledak).
        $keywordSets = [];

        foreach ($rows as $row) {
            $sets = [];

            foreach ($row->keywords ?? [] as $keyword) {
                foreach (Normalizer::normalize((string) $keyword) as $token) {
                    $sets[$token] = true;
                }
            }

            $keywordSets[$row->id] = array_keys($sets);
        }

        $ids = array_keys($keywordSets);

        foreach ($ids as $i => $a) {
            foreach (array_slice($ids, $i + 1) as $b) {
                if (isset($pairs[$a . ':' . $b]) || isset($pairs[$b . ':' . $a])) {
                    continue;
                }

                $common = count(array_intersect($keywordSets[$a], $keywordSets[$b]));

                if ($common >= 2) {
                    $pairs[$a . ':' . $b] = [
                        'from' => $a,
                        'to' => $b,
                        'relation' => 'shared_keywords',
                        'weight' => min(1.0, 0.4 + 0.2 * $common),
                    ];
                }
            }
        }

        if ($pairs === []) {
            return 0;
        }

        $rowsToInsert = [];

        foreach ($pairs as $pair) {
            $rowsToInsert[] = [
                'from_id' => $pair['from'],
                'to_id' => $pair['to'],
                'relation' => $pair['relation'],
                'weight' => $pair['weight'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach (array_chunk($rowsToInsert, 500) as $chunk) {
            AiRelationship::insert($chunk);
        }

        return count($rowsToInsert);
    }

    protected function searchableText(AiKnowledge $row): string
    {
        return trim(
            $row->title . ' '
            . implode(' ', $row->keywords ?? []) . ' '
            . $row->content
        );
    }
}