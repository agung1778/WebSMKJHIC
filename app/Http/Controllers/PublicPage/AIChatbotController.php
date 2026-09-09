<?php

namespace App\Http\Controllers\PublicPage;

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

    public function __construct(ChatbotService $chatbot)
    {
        $this->chatbot = $chatbot;
    }

    /**
     * Menerima pertanyaan user dan mengembalikan jawaban chatbot (JSON).
     * Endpoint publik, tanpa autentikasi/CSRF. Memiliki rate limiting & cache.
     */
    public function ask(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
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
        $cacheKey = 'chatbot:reply:' . sha1(mb_strtolower($question));

        $result = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($question) {
            return $this->chatbot->ask($question);
        });

        return response()->json($result);
    }
}
