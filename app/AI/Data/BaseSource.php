<?php

namespace App\AI\Data;

/**
 * Base class untuk KnowledgeSource: menyediakan konversi baris DB -> record.
 */
abstract class BaseSource implements KnowledgeSource
{
    /** @var array<int,array> */
    protected array $cache = [];

    protected function makeRecord(
        string $uid,
        string $type,
        ?string $title,
        string $content,
        ?string $category = null,
        ?string $url = null,
        array $meta = []
    ): array {
        return [
            'uid'        => $uid,
            'type'       => $type,
            'title'      => $title ?? '(tanpa judul)',
            'content'    => trim((string) $content),
            'category'   => $category,
            'url'        => $url,
            'meta'       => $meta,
        ];
    }

    protected function url(string $path): ?string
    {
        $path = '/'.ltrim($path, '/');

        try {
            return url($path);
        } catch (\Throwable $e) {
            return $path;
        }
    }

    /** Buang tag HTML & whitespace berlebih dari konten HTML-rich. */
    protected function stripHtml(?string $html): string
    {
        $text = (string) preg_replace('~<(script|style)[^>]*>.*?</\1>~is', ' ', (string) $html);
        $text = (string) preg_replace('~<br\s*/?>|</p>|</div>|</li>~i', "\n", $text);
        $text = strip_tags($text);
        $text = (string) preg_replace('~[ \t]+~u', ' ', $text);
        $text = (string) preg_replace('~\n{3,}~u', "\n\n", $text);

        return trim($text);
    }
}