<?php

namespace App\AI\Data;

use App\AI\Knowledge\KnowledgeRecord;

/**
 * Sumber knowledge dari berkas teks/markdown lokal (mis. FAQ, profil,
 * prosedur). Membaca folder atau satu berkas, tanpa koneksi jaringan.
 */
class DocumentSource implements KnowledgeSource
{
    protected array $records = [];

    public function __construct(protected string $path)
    {
    }

    public function fetch(): array
    {
        $this->records = [];

        foreach ($this->files() as $file) {
            $this->readFile($file);
        }

        return $this->records;
    }

    /** @return array<int,string> */
    protected function files(): array
    {
        if (! file_exists($this->path)) {
            return [];
        }

        if (is_file($this->path)) {
            return [$this->path];
        }

        $files = [];

        foreach (glob(rtrim($this->path, '/\\') . '/*.{txt,md}', GLOB_BRACE) ?: [] as $file) {
            $files[] = $file;
        }

        return $files;
    }

    protected function readFile(string $file): void
    {
        $raw = @file_get_contents($file);

        if ($raw === false || trim($raw) === '') {
            return;
        }

        $name = pathinfo($file, PATHINFO_FILENAME);
        $slug = preg_replace('/[^\p{L}\p{N}]+/u', '_', strtolower($name)) ?: 'document';
        $slug = trim((string) $slug, '_');

        // Judul: baris pertama bertanda "#", atau nama berkas.
        $title = $name;
        if (preg_match('/^#\s*(.+)$/m', $raw, $matches)) {
            $title = trim($matches[1]);
        }

        $this->records[] = KnowledgeRecord::make('document', 'document', $title, $raw)
            ->withSourceId($slug)
            ->withKeywords(array_filter([$title, 'dokumen', 'informasi']))
            ->withMetadata(['field' => 'document', 'file' => basename($file)]);
    }
}