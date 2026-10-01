<?php

namespace Tests\Feature;

use App\AI\Core\AIEngine;
use Tests\TestCase;

/**
 * Memverifikasi perilaku retrieval engine terhadap index lokal (tabel ai_*).
 * Test bersifat read-only terhadap database.
 */
class AiRetrievalTest extends TestCase
{
    private function ask(string $question, ?string $session = null): array
    {
        return app(AIEngine::class)->ask($question, $session);
    }

    public function test_engine_answers_are_grounded_and_return_sources(): void
    {
        $result = $this->ask('apa saja jurusan yang ada');

        $this->assertTrue($result['answered']);
        $this->assertNotEmpty($result['sources']);
        $this->assertNotEmpty($result['answer']);
        $this->assertGreaterThan(0, $result['confidence']);
    }

    public function test_engine_refuses_questions_outside_school_domain(): void
    {
        foreach (['harga iphone terbaru', 'cuaca jakarta besok', 'berapa 1+1'] as $question) {
            $result = $this->ask($question);

            $this->assertSame('out_of_scope', $result['intent'], $question);
            $this->assertEmpty($result['sources'], $question);
        }
    }

    public function test_engine_declines_unknown_facts_inside_the_domain(): void
    {
        // Data lokal tidak memuat berita terbaru/perpustakaan.
        $result = $this->ask('ada perpustakaan di sekolah?');

        $this->assertStringContainsString('belum menemukan', $result['answer']);
    }

    public function test_engine_handles_typos_in_domain_terms(): void
    {
        $result = $this->ask('exracurikuler apa aja');

        $this->assertSame('extracurricular', $result['intent']);
        $this->assertNotEmpty($result['sources']);
    }

    public function test_engine_resolves_follow_up_questions_from_context(): void
    {
        $session = 'test-followup-' . uniqid();

        $first = $this->ask('jurusan tkj', $session);
        $second = $this->ask('itu fasilitasnya apa', $session);

        $this->assertSame('facility', $second['intent']);
        $this->assertNotSame($first['intent'], $second['intent']);
    }

    public function test_conversational_questions_do_not_return_irrelevant_sources(): void
    {
        $result = $this->ask('halo');

        $this->assertSame('greeting', $result['intent']);
        $this->assertEmpty($result['sources']);
        $this->assertStringContainsString('SMK Amaliah', $result['answer']);
    }

    public function test_response_never_reveals_internal_error_details(): void
    {
        $result = $this->ask('');

        $this->assertFalse($result['success']);
        $this->assertStringNotContainsString('Exception', $result['answer']);
        $this->assertStringNotContainsString('vendor', $result['answer']);
    }

    public function test_engine_execution_stays_within_performance_budget(): void
    {
        $start = microtime(true);

        $this->ask('apa saja fasilitas sekolah');

        $this->assertLessThan(500, (microtime(true) - $start) * 1000);
    }
}