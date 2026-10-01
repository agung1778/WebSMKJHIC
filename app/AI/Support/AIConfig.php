<?php

namespace App\AI\Support;

class AIConfig
{
    /** Mode operasi: strict | normal | debug */
    public static function mode(): string
    {
        return (string) config('ai.mode', env('AI_MODE', 'normal'));
    }

    public static function logConversation(): bool
    {
        return (bool) config('ai.log_conversation', env('AI_LOG_CONVERSATION', false));
    }

    /** Ambang confidence minimum agar jawaban ditampilkan */
    public static function minConfidence(): float
    {
        return (float) config('ai.min_confidence', 0.55);
    }

    public static function maxResults(): int
    {
        return (int) config('ai.max_results', 5);
    }

    /** Bobot relevance (jumlah harus = 1.0) */
    public static function weights(): array
    {
        $w = (array) config('ai.weights', [
            'keyword' => 0.40,
            'title' => 0.25,
            'category' => 0.15,
            'content' => 0.20,
        ]);

        $total = array_sum($w);

        if ($total <= 0) {
            return ['keyword' => 0.4, 'title' => 0.25, 'category' => 0.15, 'content' => 0.2];
        }

        // normalisasi agar total = 1.0
        return array_map(fn ($v) => $v / $total, $w);
    }

    public static function bm25K1(): float
    {
        return (float) config('ai.bm25_k1', 1.5);
    }

    public static function bm25B(): float
    {
        return (float) config('ai.bm25_b', 0.75);
    }

    public static function cacheTtl(): int
    {
        return (int) config('ai.cache_ttl', 600);
    }

    public static function disclaimer(): string
    {
        return (string) config('ai.disclaimer',
            'AI Chatbot SMK Amaliah merupakan asisten informasi berbasis data website. '
            . 'Jawaban yang diberikan berdasarkan informasi yang tersedia pada sistem dan dapat berubah '
            . 'apabila data website diperbarui. Untuk informasi resmi yang bersifat penting atau berubah '
            . 'secara berkala, pengguna disarankan melakukan konfirmasi kepada pihak SMK Amaliah.');
    }

    public static function isStrict(): bool
    {
        return self::mode() === 'strict';
    }

    public static function isDebug(): bool
    {
        return self::mode() === 'debug';
    }

    /** Nama sekolah untuk jawaban */
    public static function schoolName(): string
    {
        return (string) config('ai.school_name', 'SMK Amaliah 1 & 2 Ciawi');
    }

    /** Batas panjang pertanyaan sebelum dipotong. */
    public static function maxQuestionLength(): int
    {
        return (int) config('ai.max_question_length', 500);
    }

    /** Jumlah turn riwayat yang diingat untuk follow-up. */
    public static function memoryTurns(): int
    {
        return (int) config('ai.memory_turns', 4);
    }

    /** Ambang relevansi minimum sebelum jawaban ditampilkan. */
    public static function minRelevance(): float
    {
        return (float) config('ai.min_relevance', 0.18);
    }
}