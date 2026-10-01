<?php

namespace App\AI\Core;

use App\AI\NLP\Normalizer;
use App\AI\NLP\Tokenizer;

/**
 * Membangun konteks untuk generasi jawaban (context builder).
 *
 * Menggabungkan beberapa hasil relevan teratas, menambahkan metadata
 * (source_url, category, score), dan membatasi panjang agar respons
 * tetap ringkas sesuai target performa.
 */
class ContextBuilder
{
    /**
     * @param  array<int,array{knowledge:\App\Models\AiKnowledge,score:float,confidence:float,support:int}>  $results
     * @return array{
     *     context: string,
     *     sources: array<int,array{title:string,url:?string,category:string,score:float,confidence:float}>,
     *     count: int
     * }
     */
    public function build(array $results, int $maxChars = 2000, int $maxItems = 4): array
    {
        if ($results === []) {
            return ['context' => '', 'sources' => [], 'count' => 0];
        }

        $parts = [];
        $sources = [];

        foreach (array_slice($results, 0, $maxItems) as $item) {
            $knowledge = $item['knowledge'];

            $snippet = trim($knowledge->content);

            // Potong snippet agar tidak terlalu panjang per item.
            if (mb_strlen($snippet) > 500) {
                $snippet = mb_substr($snippet, 0, 497) . '...';
            }

            $line = sprintf(
                "[%s] %s. %s",
                strtoupper($knowledge->category),
                $knowledge->title ?: '-',
                $snippet
            );

            $parts[] = $line;

            $sources[] = [
                'title' => $knowledge->title ?: $knowledge->category,
                'url' => $knowledge->source_url,
                'category' => $knowledge->category,
                'score' => (float) round($item['score'], 4),
                'confidence' => (float) round($item['confidence'], 4),
            ];
        }

        $context = implode("\n\n---\n\n", $parts);

        if (mb_strlen($context) > $maxChars) {
            $context = mb_substr($context, 0, $maxChars - 3) . '...';
        }

        return [
            'context' => $context,
            'sources' => $sources,
            'count' => count($sources),
        ];
    }
}