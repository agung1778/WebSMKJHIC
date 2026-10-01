<?php

namespace App\AI\Memory;

use App\Models\AiConversation;
use App\Models\AiMessage;
use App\Models\AiSearchLog;
use Illuminate\Support\Str;

/**
 * Memory percakapan & logging.
 *
 * Menyimpan riwayat percakapan (session-based) dan search log untuk debug/analitik.
 * Logging conversation dikontrol via config 'ai.log_conversation' (default false).
 */
class ConversationMemory
{
    /**
     * @return array<int,array{role:string,message:string}>
     */
    public function getHistory(string $sessionId, int $limit = 4): array
    {
        if (! config('ai.log_conversation', false)) {
            // Tanpa logging tidak ada transkrip; history dikembalikan kosong
            // agar tidak ada penulisan database sama sekali.
            return [];
        }

        $conversation = AiConversation::firstOrCreate(
            ['session_id' => $sessionId],
            ['session_id' => $sessionId]
        );

        return AiMessage::where('conversation_id', $conversation->id)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->reverse()
            ->map(static fn ($m) => [
                'role' => $m->role,
                'message' => $m->message,
            ])
            ->all();
    }

    public function rememberUser(string $sessionId, string $message): void
    {
        if (! config('ai.log_conversation', false)) {
            return;
        }

        $conversation = AiConversation::firstOrCreate(['session_id' => $sessionId]);

        AiMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'message' => Str::limit($message, 500),
        ]);
    }

    public function rememberAssistant(string $sessionId, string $message, array $meta = []): void
    {
        if (! config('ai.log_conversation', false)) {
            return;
        }

        $conversation = AiConversation::firstOrCreate(['session_id' => $sessionId]);

        AiMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'message' => Str::limit($message, 2000),
            'meta' => $meta,
        ]);
    }

    /**
     * Konteks percakapan singkat untuk menjawab pertanyaan lanjutan
     * ("itu bagaimana?", "terus syaratnya?").
     *
     * Sengaja hanya menyimpan pointer (intent, fokus, dan judul sumber
     * terakhir) dengan TTL pendek di cache — bukan transkrip penuh — agar
     * tidak bertentangan dengan pengaturan privasi ai.log_conversation.
     *
     * @param  array<string,mixed>  $context
     */
    public function rememberContext(string $sessionId, array $context): void
    {
        try {
            cache()->put(
                $this->contextKey($sessionId),
                [
                    'intent' => $context['intent'] ?? null,
                    'focus' => array_slice((array) ($context['focus'] ?? []), 0, 8),
                    'titles' => array_slice((array) ($context['titles'] ?? []), 0, 3),
                    'question' => Str::limit((string) ($context['question'] ?? ''), 200),
                ],
                now()->addMinutes((int) config('ai.memory_ttl_minutes', 15))
            );
        } catch (\Throwable) {
            // Cache tidak tersedia; follow-up tetap berjalan tanpa konteks.
        }
    }

    /**
     * @return array<string,mixed>
     */
    public function getContext(string $sessionId): array
    {
        try {
            $cached = cache()->get($this->contextKey($sessionId));

            return is_array($cached) ? $cached : [];
        } catch (\Throwable) {
            return [];
        }
    }

    public function forgetContext(string $sessionId): void
    {
        try {
            cache()->forget($this->contextKey($sessionId));
        } catch (\Throwable) {
            // Abaikan.
        }
    }

    protected function contextKey(string $sessionId): string
    {
        return 'ai:ctx:' . sha1($sessionId);
    }

    public function logSearch(array $payload): void
    {
        try {
            AiSearchLog::create([
                'question' => Str::limit($payload['question'] ?? '', 500),
                'intent' => $payload['intent'] ?? null,
                'entities' => $payload['entities'] ?? [],
                'expanded_terms' => $payload['expanded_terms'] ?? [],
                'method' => $payload['method'] ?? 'hybrid',
                'result_count' => $payload['result_count'] ?? 0,
                'top_score' => $payload['top_score'] ?? 0.0,
                'duration_ms' => $payload['duration_ms'] ?? 0.0,
            ]);
        } catch (\Throwable) {
            // Abaikan error logging agar tidak mengganggu jalur utama.
        }
    }

    public static function generateSessionId(): string
    {
        return 'sess_' . Str::random(32);
    }
}