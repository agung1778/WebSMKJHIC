<?php

namespace Tests\Unit;

use App\AI\Core\IntentEngine;
use App\AI\NLP\Normalizer;
use App\AI\NLP\Similarity;
use App\AI\NLP\Tokenizer;
use Tests\TestCase;

/**
 * Test unit untuk lapisan NLP dan deteksi intent.
 *
 * Tidak menyentuh database sehingga aman dijalankan kapan saja.
 */
class AiNlpTest extends TestCase
{
    public function test_tokenizer_splits_punctuation_and_lowercases(): void
    {
        $this->assertSame(
            ['jurusan', 'tkj', 'apa'],
            Tokenizer::tokenize('Jurusan TKJ, apa?')
        );
    }

    public function test_stopwords_are_removed_for_search(): void
    {
        $tokens = Tokenizer::tokensForSearch('apa saja jurusan yang ada');

        $this->assertContains('jurusan', $tokens);
        $this->assertNotContains('apa', $tokens);
    }

    public function test_normalizer_collapses_repeated_letters(): void
    {
        $this->assertSame('pramuka', Tokenizer::collapseRepeatedLetters('pramukaaaa'));
        $this->assertContains('pramuka', Normalizer::normalize('pramukaaaa teknik'));
    }

    public function test_normalizer_preserves_known_acronyms(): void
    {
        $tokens = Normalizer::normalize('TKJ dan RPL');

        $this->assertContains('tkj', $tokens);
        $this->assertContains('rpl', $tokens);
    }

    public function test_normalizer_does_not_split_words_into_fragments(): void
    {
        // "perpustakaan" di-stem ke "pustaka", bukan dipecah jadi fragmen
        // meaningless seperti "kasi".
        $tokens = Normalizer::normalize('perpustakaan dan laboratorium');

        $this->assertNotContains('kasi', $tokens);
        $this->assertContains('pustaka', $tokens);
        $this->assertContains('laboratorium', $tokens);
    }

    public function test_normalizer_treats_common_school_words_as_stopwords(): void
    {
        // "sekolah" adalah stopword yang disengaja agar tidak mendominasi query.
        $this->assertTrue(Normalizer::isStopWord('sekolah'));
        $this->assertNotContains('sekolah', Normalizer::normalize('lokasi sekolah Ciawi'));
    }

    public function test_similarity_ratio_is_symmetric(): void
    {
        $this->assertEqualsWithDelta(
            Similarity::ratio('ekstrakurikuler', 'ekstrakurikular'),
            Similarity::ratio('ekstrakurikular', 'ekstrakurikuler'),
            0.0001
        );
    }

    public function test_correct_typo_matches_long_words_with_missing_letters(): void
    {
        $dictionary = ['ekstrakurikuler', 'fasilitas', 'perpustakaan'];

        $this->assertSame(
            'ekstrakurikuler',
            Similarity::correctTypo('exracurikuler', $dictionary)
        );
    }

    public function test_correct_typo_ignores_unknown_words(): void
    {
        $dictionary = ['ekstrakurikuler', 'fasilitas'];

        $this->assertNull(Similarity::correctTypo('resep', $dictionary));
    }

    public function test_correct_typo_skips_numeric_tokens(): void
    {
        $this->assertNull(Similarity::correctTypo('2024', ['2024', '2025']));
    }

    public function test_intent_detection_covers_main_topics(): void
    {
        $engine = new IntentEngine();

        $expected = [
            'halo' => 'greeting',
            'terima kasih' => 'thanks',
            'apa saja jurusan' => 'major_list',
            'deskripsi jurusan tkj' => 'major_detail',
            'siapa guru' => 'teacher',
            'fasilitas apa saja' => 'facility',
            'ekstrakurikuler apa saja' => 'extracurricular',
            'cara daftar' => 'spmb',
            'alamat sekolah' => 'location',
            'nomor telepon' => 'contact',
            'berapa jumlah siswa' => 'statistic',
        ];

        foreach ($expected as $question => $intent) {
            $this->assertSame($intent, $engine->detect($question), $question);
        }
    }

    public function test_intent_matching_respects_word_boundaries(): void
    {
        $engine = new IntentEngine();

        // "lab" di dalam "syllabus" bukan intent fasilitas.
        $this->assertNotSame('facility', $engine->detect('how about its syllabus'));
    }

    public function test_intent_distinguishes_daftar_jurusan_from_pendaftaran(): void
    {
        $engine = new IntentEngine();

        $this->assertSame('major_list', $engine->detect('daftar jurusan'));
        $this->assertSame('spmb', $engine->detect('cara daftar'));
        $this->assertSame('spmb', $engine->detect('kapan daftar dibuka'));
    }
}