<?php

namespace App\Http\Controllers\PublicPage;

use App\AI\Core\AIEngine;
use App\Http\Controllers\Controller;
use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class AIChatbotController extends Controller
{
    /**
     * Batas request per IP per menit.
     */
    protected const RATE_MAX = 20;

    /**
     * TTL cache jawaban untuk pertanyaan sama (detik).
     */
    protected const CACHE_TTL = 3600;

    protected ChatbotService $chatbot;

    protected AIEngine $engine;

    public function __construct(ChatbotService $chatbot, AIEngine $engine)
    {
        $this->chatbot = $chatbot;
        $this->engine = $engine;
    }

    /**
     * Menerima pertanyaan user dan mengembalikan jawaban chatbot (JSON).
     * Endpoint publik, tanpa autentikasi/CSRF. Memiliki rate limiting & cache.
     *
     * Jawaban diambil dari AI Engine (retrieval lokal). Bila engine tidak
     * menemukan jawaban yang memenuhi ambang relevansi, sistem kembali ke
     * ChatbotService lama sebagai cadangan agar nunca kehilangan jawaban.
     *
     * Kontrak response tetap `{ reply, answered }` agar UI existing tidak
     * perlu diubah; field tambahan hanya bersifat opsional.
     */
    public function ask(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'session_id' => ['nullable', 'string', 'max:64'],
        ]);

        $ip = $request->ip() ?? 'unknown';
        $key = 'tanya-ai:' . $ip;

        if (RateLimiter::tooManyAttempts($key, self::RATE_MAX)) {
            return response()->json([
                'reply'    => 'Terlalu banyak pertanyaan. Silakan coba lagi beberapa saat ya 😊',
                'answered' => false,
            ], 429);
        }

        RateLimiter::hit($key, 60);

        $question = trim($validated['question']);
        $sessionId = $validated['session_id'] ?? null;

        // Cache per sesi agar pertanyaan lanjutan ("terus bagaimana?")
        // tidak tertukar dengan jawaban pertanyaan pertama.
        $cacheKey = 'chatbot:reply:' . sha1(mb_strtolower($question) . '|' . ($sessionId ?? '-'));

        $result = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($question, $sessionId) {
            return $this->answer($question, $sessionId);
        });

        return response()->json($result);
    }

    /**
     * @return array<string,mixed>
     */
    protected function answer(string $question, ?string $sessionId): array
    {
        $response = $this->engine->chat($question, $sessionId);

        if (($response['answered'] ?? false) === true) {
            return [
                'reply'      => $response['answer'],
                'answered'   => true,
                'intent'     => $response['intent'],
                'confidence' => $response['confidence'],
                'sources'    => array_slice($response['sources'] ?? [], 0, 3),
                'engine'     => 'ai-engine',
            ];
        }

        if (config('ai.legacy_fallback', true)) {
            $legacy = $this->chatbot->ask($question);

            if (($legacy['answered'] ?? false) === true) {
                return $legacy + ['engine' => 'legacy'];
            }
        }

        return [
            'reply'      => $response['answer'],
            'answered'   => false,
            'intent'     => $response['intent'],
            'confidence' => $response['confidence'],
            'sources'    => [],
            'engine'     => 'ai-engine',
        ];
    }
}