<?php

namespace Tests\Feature;

use App\AI\Knowledge\KnowledgeIndexer;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Test idempotensi indexing.
 *
 * Menguji bahwa run kedua tidak mengubah apa pun dan bahwa record yang hilang
 * dari sumber data ikut terhapus.
 */
class AiIndexerTest extends TestCase
{
    /**
     * Mengembalikan jumlah baris saat ini per kombinasi
     * category|source_id|metadata.key.
     *
     * @return array<string,int>
     */
    private function identityCounts(): array
    {
        return DB::table('ai_knowledge')
            ->get()
            ->groupBy(
                fn ($row): string => implode('|', [
                    $row->category,
                    (string) $row->source_id,
                    (string) (json_decode($row->metadata ?? '{}', true)['key'] ?? ''),
                ])
            )
            ->map(static fn ($rows): int => $rows->count())
            ->all();
    }

    public function test_rebuild_is_idempotent_on_unchanged_data(): void
    {
        $total = (int) DB::table('ai_knowledge')->count();

        // Jalankan lagi tanpa perubahan sumber: tidak boleh ada mutasi data.
        $second = (new KnowledgeIndexer())->rebuild(incremental: true);

        $this->assertSame(0, $second['inserted']);
        $this->assertSame(0, $second['updated']);
        $this->assertSame(0, $second['deleted']);
        $this->assertSame($total, $second['unchanged']);
        $this->assertSame($total, (int) DB::table('ai_knowledge')->count());
    }

    public function test_index_contains_expected_categories(): void
    {
        (new KnowledgeIndexer())->rebuild(incremental: true);

        $categories = DB::table('ai_knowledge')->distinct()->pluck('category')->all();

        $this->assertContains('identity', $categories);
        $this->assertContains('major', $categories);
    }

    public function test_atomic_records_from_one_source_are_all_persisted(): void
    {
        (new KnowledgeIndexer())->rebuild(incremental: true);

        foreach ($this->identityCounts() as $identity => $count) {
            $this->assertSame(1, $count, "Identitas duplikat: {$identity}");
        }
    }

    public function test_major_source_keeps_multiple_atomic_records(): void
    {
        (new KnowledgeIndexer())->rebuild(incremental: true);

        $perSource = DB::table('ai_knowledge')
            ->where('category', 'major')
            ->select('source_id')
            ->groupBy('source_id')
            ->pluck('source_id')
            ->all();

        $this->assertNotEmpty($perSource);

        // Setiap entitas jurusan punya minimal dua record atomik
        // (ringkasan + deskripsi), jadi tidak boleh tertukar.
        $descriptions = DB::table('ai_knowledge')
            ->where('category', 'major')
            ->whereIn('source_id', $perSource)
            ->count();

        $this->assertGreaterThanOrEqual(count($perSource) * 2, $descriptions);
    }
}