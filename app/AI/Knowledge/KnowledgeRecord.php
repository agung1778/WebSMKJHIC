<?php

namespace App\AI\Knowledge;

use App\AI\NLP\Normalizer;

/**
 * Satu fakta atau atribut atomik dari sebuah sumber data.
 *
 * Contoh: "Guru Budi mengajar Matematika" adalah satu record, bukan satu
 * dokumen berisi seluruh daftar guru. Ini memungkinkan pencarian dan
 * context building bekerja pada tingkat fakta.
 */
class KnowledgeRecord
{
    public string $key;
    public string $category;
    public string $title;
    public string $content;
    public ?string $sourceUrl = null;
    public ?string $sourceId = null;
    public array $keywords = [];
    public array $metadata = [];

    /** @var array<string,int> token -> frekuensi, dihitung lazy */
    protected ?array $tokenCounts = null;

    public function __construct(
        string $key,
        string $category,
        string $title,
        string $content,
        ?string $sourceUrl = null,
        ?string $sourceId = null,
        array $keywords = [],
        array $metadata = []
    ) {
        $this->key = $key;
        $this->category = $category;
        $this->title = $title;
        $this->content = trim(preg_replace('/\s+/u', ' ', $this->clean($content)) ?? '');
        $this->sourceUrl = $sourceUrl;
        $this->sourceId = $sourceId;
        $this->keywords = array_values(array_unique(array_filter(array_map(
            fn ($k) => trim((string) $k),
            $keywords
        ))));
        $this->metadata = $metadata;
    }

    public static function make(string $key, string $category, string $title, string $content): self
    {
        return new self($key, $category, $title, $content);
    }

    public function withSourceUrl(string $url): self
    {
        $this->sourceUrl = $url;
        return $this;
    }

    public function withSourceId(?string $id): self
    {
        $this->sourceId = $id;
        return $this;
    }

    public function withKeywords(array $keywords): self
    {
        $this->keywords = array_values(array_unique(array_filter(array_map(
            fn ($k) => trim((string) $k),
            $keywords
        ))));
        return $this;
    }

    public function withMetadata(array $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
    }

    protected function clean(string $html): string
    {
        $text = strip_tags($html);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace('/[ \t]+/u', ' ', $text) ?? '');
    }

    /** Frekuensi token untuk BM25/TF-IDF. */
    public function tokenCounts(): array
    {
        if ($this->tokenCounts === null) {
            $text = $this->title . ' ' . implode(' ', $this->keywords) . ' ' . $this->content;
            $this->tokenCounts = Normalizer::termFrequency($text);
        }

        return $this->tokenCounts;
    }

    public function termCount(): int
    {
        return array_sum($this->tokenCounts());
    }

    public function uniqueTermCount(): int
    {
        return count($this->tokenCounts());
    }

    /** Checksum untuk mendeteksi perubahan saat incremental index. */
    public function checksum(): string
    {
        return hash('sha256', implode('|', [
            $this->category,
            $this->title,
            $this->content,
            implode(',', $this->keywords),
        ]));
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'category' => $this->category,
            'source_type' => 'database',
            'source_id' => $this->sourceId,
            'title' => $this->title,
            'content' => $this->content,
            'source_url' => $this->sourceUrl,
            'keywords' => $this->keywords,
            // Key disimpan di dalam metadata agar menjadi identitas unik baris
            // di database: satu entitas sumber dapat menghasilkan banyak
            // record atomik (mis. satu jurusan -> deskripsi, tag, keunggulan).
            'metadata' => array_merge($this->metadata, ['key' => $this->key]),
            'checksum' => $this->checksum(),
        ];
    }
}