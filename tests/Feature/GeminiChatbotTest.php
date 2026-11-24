<?php

namespace Tests\Feature;

use App\AI\LLM\GeminiClient;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeminiChatbotTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('ai.llm.enabled', true);
        Config::set('ai.llm.api_key', 'test-gemini-key-123');
        Config::set('ai.llm.model', 'gemini-1.5-flash');
    }

    public function test_gemini_client_is_available_when_configured(): void
    {
        $client = new GeminiClient();
        $this->assertTrue($client->isAvailable());

        $clientNoKey = new GeminiClient(apiKey: '');
        $this->assertFalse($clientNoKey->isAvailable());
    }

    public function test_it_answers_via_gemini_with_rag_context(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                [
                                    'text' => 'Halo! SMK Amaliah memiliki beberapa jurusan unggulan seperti Rekayasa Perangkat Lunak (RPL), Teknik Komputer dan Jaringan (TKJ), dan lainnya. Pendaftaran dapat dilakukan langsung melalui website kami ya! 😊',
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->postJson('/public/ai/ask', [
            'question' => 'apa saja jurusan yang ada di smk amaliah',
        ]);

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertTrue($data['answered']);
        $this->assertSame('gemini', $data['engine']);
        $this->assertStringContainsString('Rekayasa Perangkat Lunak', $data['reply']);

        // Pastikan Gemini API dipanggil dengan request yang memuat pertanyaan dan konteks
        Http::assertSent(function ($request) {
            $payload = json_decode($request->body(), true);
            $hasSystemInstruction = isset($payload['systemInstruction']['parts'][0]['text']);
            $hasQuestion = isset($payload['contents'][0]['parts'][0]['text']);
            return str_contains($request->url(), 'generativelanguage.googleapis.com')
                && $hasSystemInstruction
                && $hasQuestion;
        });
    }

    public function test_it_gracefully_falls_back_when_gemini_fails_or_rate_limited(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'error' => [
                    'code' => 429,
                    'message' => 'Resource has been exhausted (e.g. check quota).',
                ],
            ], 429),
        ]);

        $response = $this->postJson('/public/ai/ask', [
            'question' => 'apa saja jurusan yang ada',
        ]);

        $response->assertStatus(200);
        $data = $response->json();

        // Tidak boleh error 500, harus tetap dijawab oleh fallback lokal
        $this->assertTrue($data['answered']);
        $this->assertNotSame('gemini', $data['engine']);
        $this->assertNotEmpty($data['reply']);
    }

    public function test_it_gracefully_falls_back_when_gemini_times_out(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => function () {
                throw new \Illuminate\Http\Client\ConnectionException('Connection timed out');
            },
        ]);

        $response = $this->postJson('/public/ai/ask', [
            'question' => 'dimana lokasi sekolah',
        ]);

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertTrue($data['answered']);
        $this->assertNotSame('gemini', $data['engine']);
        $this->assertNotEmpty($data['reply']);
    }
}
