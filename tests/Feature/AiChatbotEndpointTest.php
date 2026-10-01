<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Kontrak endpoint chatbot publik yang dipakai UI existing.
 *
 * UI hanya bergantung pada field `reply` dan `answered`, sehingga field
 * tambahan dari AI Engine harus tetap kompatibel.
 */
class AiChatbotEndpointTest extends TestCase
{
    /**
     * @return array{status:int,body:array<string,mixed>}
     */
    private function ask(string $question, ?string $session = null): array
    {
        $response = $this->postJson('/public/ai/ask', array_filter([
            'question' => $question,
            'session_id' => $session,
        ], static fn ($v): bool => $v !== null));

        return [
            'status' => $response->getStatusCode(),
            'body' => (array) $response->json(),
        ];
    }

    public function test_endpoint_answers_a_domain_question(): void
    {
        $result = $this->ask('apa saja jurusan yang ada');

        $this->assertSame(200, $result['status']);
        $this->assertTrue($result['body']['answered']);
        $this->assertNotEmpty($result['body']['reply']);
    }

    public function test_endpoint_respects_the_ui_contract(): void
    {
        $result = $this->ask('apa saja jurusan yang ada');

        $this->assertArrayHasKey('reply', $result['body']);
        $this->assertArrayHasKey('answered', $result['body']);
        $this->assertIsString($result['body']['reply']);
        $this->assertIsBool($result['body']['answered']);
    }

    public function test_endpoint_declines_off_domain_questions(): void
    {
        $result = $this->ask('harga iphone terbaru');

        $this->assertSame(200, $result['status']);
        $this->assertSame('out_of_scope', $result['body']['intent']);
        $this->assertStringContainsString('SMK Amaliah', $result['body']['reply']);
    }

    public function test_endpoint_rejects_empty_questions(): void
    {
        $this->postJson('/public/ai/ask', ['question' => ''])
            ->assertStatus(422);
    }

    public function test_endpoint_rejects_overlong_questions(): void
    {
        $this->postJson('/public/ai/ask', ['question' => str_repeat('a', 501)])
            ->assertStatus(422);
    }

    public function test_endpoint_returns_sources_with_urls(): void
    {
        $result = $this->ask('dimana alamat sekolah');

        $this->assertTrue($result['body']['answered']);
        $this->assertNotEmpty($result['body']['sources']);
        $this->assertArrayHasKey('url', $result['body']['sources'][0]);
    }

    public function test_follow_up_questions_keep_the_same_contract(): void
    {
        $session = 'endpoint-followup-' . uniqid();

        $this->ask('jurusan tkj', $session);
        $result = $this->ask('itu fasilitasnya apa', $session);

        $this->assertTrue($result['body']['answered']);
        $this->assertSame('facility', $result['body']['intent']);
    }
}