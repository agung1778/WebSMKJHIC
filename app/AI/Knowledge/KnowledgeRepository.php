<?php

namespace App\AI\Knowledge;

use App\Models\AiDocument;
use App\Models\AiKeyword;
use App\Models\AiKnowledge;
use App\Models\AiRelationship;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Akses read-only ke tabel index AI.
 *
 * Menyediakan statistik korpus (jumlah dokumen, panjang dokumen, document
 * frequency) yang dibutuhkan SearchEngine untuk BM25, plus navigasi graf
 * relasi antar fakta.
 */
class KnowledgeRepository
{
    protected ?int $totalDocuments = null;

    protected ?float $averageLength = null;

    /** @var array<string,int>|null */
    protected ?array $documentFrequency = null;

    /** @var array<string,float>|null */
    protected ?array $idfCache = null;

    /** @return Collection<int,AiKnowledge> */
    public function all(): Collection
    {
        return AiKnowledge::orderBy('id')->get();
    }

    /** @return Collection<int,AiKnowledge> */
    public function byCategory(string $category): Collection
    {
        return AiKnowledge::where('category', $category)->orderBy('id')->get();
    }

    public function totalDocuments(): int
    {
        return $this->totalDocuments ??= (int) AiDocument::count();
    }

    public function averageLength(): float
    {
        if ($this->averageLength === null) {
            $avg = AiDocument::avg('term_count');

            $this->averageLength = $avg ? (float) $avg : 1.0;
        }

        return max($this->averageLength, 1.0);
    }

    public function documentLength(int $knowledgeId): int
    {
        $value = AiDocument::where('knowledge_id', $knowledgeId)->value('term_count');

        return max((int) $value, 1);
    }

    /**
     * Document frequency per token: berapa dokumen yang memuat token tersebut.
     *
     * @return array<string,int>
     */
    public function documentFrequency(): array
    {
        if ($this->documentFrequency === null) {
            $this->documentFrequency = AiKeyword::pluck('document_frequency', 'token')->all();
        }

        return $this->documentFrequency;
    }

    /**
     * Inverse document frequency (rumus Robertson/Sparck Jones).
     *
     * @return array<string,float>
     */
    public function idf(): array
    {
        if ($this->idfCache === null) {
            $n = max($this->totalDocuments(), 1);
            $idf = [];

            foreach ($this->documentFrequency() as $token => $df) {
                $df = max((int) $df, 1);
                $idf[$token] = log(1 + ($n - $df + 0.5) / ($df + 0.5));
            }

            $this->idfCache = $idf;
        }

        return $this->idfCache;
    }

    public function idfFor(string $token): float
    {
        $n = max($this->totalDocuments(), 1);
        $df = (int) ($this->documentFrequency()[$token] ?? 0);

        if ($df === 0) {
            // Token yang tidak ada di korpus tetap diberi bobot maksimum
            // agar tidak otomatis dianggap tidak relevan.
            return log(1 + ($n + 0.5) / 0.5);
        }

        return log(1 + ($n - $df + 0.5) / ($df + 0.5));
    }

    /**
     * Relasi graf antar fakta (knowledge id -> knowledge id).
     *
     * @return array<int,array<int,float>>
     */
    public function relatedIds(int $knowledgeId): array
    {
        return AiRelationship::where('from_id', $knowledgeId)
            ->get()
            ->groupBy('to_id')
            ->map(fn ($rows) => (float) $rows->max('weight'))
            ->all();
    }

    /**
     * Kunci untuk mendeteksi apakah index masih fresh.
     */
    public function flushCaches(): void
    {
        $this->totalDocuments = null;
        $this->averageLength = null;
        $this->documentFrequency = null;
        $this->idfCache = null;
    }

    public function isEmpty(): bool
    {
        return AiKnowledge::count() === 0;
    }

    public function countByCategory(): array
    {
        return DB::table('ai_knowledge')
            ->select('category', DB::raw('COUNT(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->all();
    }
}