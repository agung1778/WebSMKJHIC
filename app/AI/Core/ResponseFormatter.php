<?php

namespace App\AI\Core;

use App\AI\Support\AIConfig;

/**
 * Memformat respons akhir sesuai API spek.
 *
 * Output wajib mencakup: success, answer, intent, confidence, sources,
 * disclaimer (opsional), dan meta saat mode debug.
 */
class ResponseFormatter
{
    /**
     * @param  array<int,array{title:string,url:?string,category:string,score:float,confidence:float}>  $sources
     */
    public function format(
        bool $success,
        string $answer,
        string $intent,
        float $confidence,
        float $topScore,
        array $sources,
        array $meta = []
    ): array {
        $response = [
            'success' => $success,
            'answered' => $sources !== [],
            'answer' => $answer,
            'intent' => $intent,
            'confidence' => (float) round($confidence, 4),
            'top_score' => (float) round($topScore, 4),
            'sources' => $sources,
            'disclaimer' => AIConfig::disclaimer(),
        ];

        if (AIConfig::isDebug()) {
            $response['meta'] = $meta;
        }

        return $response;
    }

    /**
     * Respons tanpa retrieval (sapaan, penolakan di luar cakupan).
     *
     * Confidence 0.0 dan sources kosong signalling ke klien bahwa jawaban ini
     * bukan hasil pencarian knowledge, sehingga UI tidak menampilkan daftar
     * sumber yang tidak relevan.
     */
    public function direct(string $answer, string $intent): array
    {
        return [
            'success' => true,
            'answered' => true,
            'answer' => $answer,
            'intent' => $intent,
            'confidence' => 0.0,
            'top_score' => 0.0,
            'sources' => [],
            'disclaimer' => AIConfig::disclaimer(),
        ];
    }

    /**
     * Format error aman (tidak expose detail internal).
     */
    public function error(string $message = 'Terjadi kesalahan saat memproses permintaan. Silakan coba lagi.'): array
    {
        return [
            'success' => false,
            'answered' => false,
            'answer' => $message,
            'intent' => 'error',
            'confidence' => 0.0,
            'top_score' => 0.0,
            'sources' => [],
            'disclaimer' => AIConfig::disclaimer(),
        ];
    }
}