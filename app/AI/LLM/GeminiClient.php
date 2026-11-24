<?php

namespace App\AI\LLM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class GeminiClient
{
    protected string $apiKey;
    protected string $model;
    protected int $timeout;
    protected float $temperature;
    protected int $maxTokens;
    protected ?string $customSystemPrompt;

    public function __construct(
        ?string $apiKey = null,
        ?string $model = null,
        ?int $timeout = null,
        ?float $temperature = null,
        ?int $maxTokens = null,
        ?string $customSystemPrompt = null
    ) {
        $this->apiKey = $apiKey ?? (string) config('ai.llm.api_key', '');
        $this->model = $model ?? (string) config('ai.llm.model', 'gemini-1.5-flash');
        $this->timeout = $timeout ?? (int) config('ai.llm.timeout', 8);
        $this->temperature = $temperature ?? (float) config('ai.llm.temperature', 0.4);
        $this->maxTokens = $maxTokens ?? (int) config('ai.llm.max_tokens', 800);
        $this->customSystemPrompt = $customSystemPrompt ?? config('ai.llm.system_prompt');
    }

    /**
     * Memeriksa apakah client siap digunakan (ada API Key & driver aktif).
     */
    public function isAvailable(): bool
    {
        return !empty($this->apiKey) && (bool) config('ai.llm.enabled', true);
    }

    /**
     * Menghasilkan jawaban menggunakan Google Gemini REST API dengan konteks RAG.
     *
     * @param string $question Pertanyaan pengguna
     * @param string $context Konteks data sekolah yang diambil dari database/web
     * @param array<int, array{role: string, message: string}> $history Riwayat percakapan sebelumnya
     * @return string|null Jawaban teks atau null jika gagal/fallback
     */
    public function generate(string $question, string $context = '', array $history = []): ?string
    {
        if (!$this->isAvailable()) {
            return null;
        }

        $endpoint = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            urlencode($this->model),
            $this->apiKey
        );

        $systemInstruction = $this->buildSystemInstruction($context);
        $contents = $this->buildContents($question, $history);

        $payload = [
            'systemInstruction' => [
                'parts' => [
                    ['text' => $systemInstruction],
                ],
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => $this->temperature,
                'maxOutputTokens' => $this->maxTokens,
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->timeout($this->timeout)
            ->post($endpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if (!empty($reply)) {
                    return trim($reply);
                }
            }

            Log::warning('Gemini API call failed', [
                'status' => $response->status(),
                'body' => $response->json() ?? $response->body(),
            ]);

            return null;
        } catch (Throwable $e) {
            Log::warning('Gemini API exception occurred: ' . $e->getMessage(), [
                'model' => $this->model,
                'question' => $question,
            ]);

            return null;
        }
    }

    /**
     * Membangun instruksi sistem dengan konteks RAG.
     */
    protected function buildSystemInstruction(string $context): string
    {
        if ($this->customSystemPrompt) {
            $base = $this->customSystemPrompt;
        } else {
            $school = config('ai.school', 'SMK Amaliah 1 & 2 Ciawi');
            $name = config('ai.name', 'Tanya Amaliah');

            $base = "Anda adalah {$name}, asisten kecerdasan buatan resmi untuk website {$school}.\n"
                . "Tugas utama Anda adalah membantu calon siswa, siswa, orang tua, dan masyarakat umum dalam mendapatkan informasi yang tepat mengenai sekolah.\n\n"
                . "Panduan Sikap & Format Jawaban:\n"
                . "1. Berbicaralah dalam Bahasa Indonesia yang ramah, sopan, antusias, dan mudah dipahami.\n"
                . "2. Gunakan format markdown yang rapi (seperti poin/bullet list, tebal pada kata kunci penting) jika jawaban memuat beberapa item atau langkah-langkah.\n"
                . "3. Berikan jawaban yang ringkas, to-the-point, dan informatif tanpa bertele-tele.\n"
                . "4. Jika pengguna menanyakan hal yang sama sekali di luar topik sekolah, jawab secara singkat dan sopan bahwa Anda adalah asisten khusus {$school}, lalu ajak mereka untuk menanyakan info seputar sekolah (seperti jurusan, pendaftaran SPMB, fasilitas, guru, eskul, dll.).\n"
                . "5. JANGAN PERNAH membuat fakta palsu (halusinasi) mengenai biaya, tanggal, atau nomor kontak yang tidak tercantum dalam konteks data sekolah.";
        }

        if (trim($context) !== '') {
            $base .= "\n\n--- KONTEKS DATA SEKOLAH (FAKTA UTAMA DARI DATABASE WEBSITE) ---\n"
                . $context
                . "\n--- AKHIR KONTEKS ---"
                . "\nGunakan informasi di atas sebagai acuan utama jawaban Anda.";
        }

        return $base;
    }

    /**
     * Membangun payload riwayat percakapan dan pertanyaan terakhir.
     *
     * @param array<int, array{role: string, message: string}> $history
     * @return array<int, array{role: string, parts: array<int, array{text: string}>}>
     */
    protected function buildContents(string $question, array $history = []): array
    {
        $contents = [];

        // Masukkan riwayat percakapan (maksimal 6 interaksi terakhir)
        $recentHistory = array_slice($history, -6);
        foreach ($recentHistory as $turn) {
            $role = ($turn['role'] ?? 'user') === 'user' ? 'user' : 'model';
            $text = trim($turn['message'] ?? '');
            if ($text !== '') {
                $contents[] = [
                    'role' => $role,
                    'parts' => [
                        ['text' => $text],
                    ],
                ];
            }
        }

        // Pertanyaan terakhir
        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $question],
            ],
        ];

        return $contents;
    }
}
