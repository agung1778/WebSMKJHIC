<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Kontrak endpoint JSON `POST /api/ai/chat`.
 */
class AiApiEndpointTest extends TestCase
{
    public function test_api_returns_the_documented_contract(): void
    {
        $response = $this->postJson('/api/ai/chat', [
            'message' => 'apa saja jurusan yang ada',
        ]);

        $response->assertOk();

        $body = (array) $response->json();

        foreach (['success', 'answered', 'answer', 'intent', 'confidence', 'top_score', 'sources', 'disclaimer'] as $key) {
            $this->assertArrayHasKey($key, $body);
        }

        $this->assertTrue($body['success']);
        $this->assertIsString($body['answer']);
        $this->assertIsFloat($body['confidence']);
    }

    public function test_api_keeps_sources_shaped_as_title_and_url(): void
    {
        $response = $this->postJson('/api/ai/chat', [
            'message' => 'dimana alamat sekolah',
        ]);

        $body = (array) $response->json();

        $this->assertNotEmpty($body['sources']);
        $this->assertArrayHasKey('title', $body['sources'][0]);
        $this->assertArrayHasKey('url', $body['sources'][0]);
        $this->assertArrayHasKey('category', $body['sources'][0]);
    }

    public function test_api_rejects_a_missing_message(): void
    {
        $this->postJson('/api/ai/chat', [])->assertStatus(422);
    }

    public function test_api_rejects_an_overlong_message(): void
    {
        $this->postJson('/api/ai/chat', [
            'message' => str_repeat('a', 501),
        ])->assertStatus(422);
    }

    public function test_api_refuses_out_of_domain_questions(): void
    {
        $body = (array) $this->postJson('/api/ai/chat', [
            'message' => 'berapa harga iphone terbaru',
        ])->json();

        $this->assertSame('out_of_scope', $body['intent']);
        $this->assertEmpty($body['sources']);
    }

    public function test_api_keeps_follow_up_context_per_session(): void
    {
        $session = 'api-session-' . uniqid();

        $this->postJson('/api/ai/chat', [
            'message' => 'jurusan tkj',
            'session_id' => $session,
        ])->assertOk();

        $second = $this->postJson('/api/ai/chat', [
            'message' => 'itu fasilitasnya apa',
            'session_id' => $session,
        ]);

        $this->assertSame('facility', $second->json('intent'));
    }

    public function test_api_rejects_a_whitespace_only_message(): void
    {
        // Validasi Lapisan HTTP menolak pesan kosong lebih awal; yang diuji
        // di sini adalah tidak ada detail internal yang bocor ke klien.
        $response = $this->postJson('/api/ai/chat', ['message' => '   ']);

        $response->assertStatus(422);

        $body = (array) $response->json();

        $this->assertArrayNotHasKey('exception', $body);
        $this->assertStringNotContainsString('vendor', json_encode($body) ?: '');
    }
}