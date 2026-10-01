<?php

namespace App\AI\Data;

<<<<<<< HEAD
use App\AI\Support\AIConfig;
use Illuminate\Support\Facades\File;

/**
 * Sumber pengetahuan #3: DOKUMEN LOKAL.
 *
 * Format: .txt, .md, .json, .csv di folder config('ai.sources.documents.paths').
 * Tujuannya agar dokumen resmi (brosur, panduan, SOP internal) bisa masuk
 * knowledge index tanpa harus dih-trad ke tabel database.
 */
class DocumentSource extends BaseSource
{
    /** @var array<int,array>|null */
    protected ?array $documents = null;

    public function name(): string
    {
        return 'document';
    }

    public function enabled(): bool
    {
        if (! (bool) AIConfig::sources('documents', 'enabled', true)) {
            return false;
        }

        foreach ($this->paths() as $path) {
            if (File::isDirectory($path)) {
                return true;
            }
        }

        return false;
    }

    /** @return array<int,string> */
    public function paths(): array
    {
        return array_values(array_filter(
            (array) AIConfig::sources('documents', 'paths', []),
            fn ($p) => File::isDirectory($p)
        ));
    }

    /** @return array<int,string> */
    public function formats(): array
    {
        return (array) AIConfig::sources('documents', 'formats', ['txt', 'md', 'json', 'csv']);
    }

    /**
     * @return array<int,array>
     */
    public function sync(): array
    {
        if (! $this->enabled()) {
            return [];
        }

        $records = [];
        $formats = $this->formats();

        foreach ($this->paths() as $dir) {
            foreach (File::allFiles($dir) as $file) {
                if (! in_array(strtolower($file->getExtension()), $formats, true)) {
                    continue;
                }

                try {
                    $record = $this->parseFile($file->getPathname());
                    if ($record !== null) {
                        $records[] = $record;
                    }
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        return $records;
    }

    /**
     * @return array<string,mixed>|null
     */
    public function parseFile(string $path): ?array
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $raw = File::get($path);

        switch ($ext) {
            case 'json':
                $data = json_decode($raw, true);
                if (! is_array($data)) {
                    return null;
                }

                $title = $data['title'] ?? $data['name'] ?? basename($path);
                $body  = $data['content'] ?? $data['description'] ?? $data['text'] ?? json_encode($data, JSON_UNESCAPED_UNICODE);

                return $this->makeRecord(
                    'doc:'.md5($path),
                    'document',
                    (string) $title,
                    $this->stripHtml((string) $body),
                    'Dokumen',
                    null,
                    ['file' => basename($path)]
                );

            case 'csv':
                return $this->parseCsv($raw, $path);

            case 'md':
            case 'txt':
            default:
                $text = $this->stripHtml($raw);
                $title = $this->guessTitle($text) ?: basename($path);

                return $this->makeRecord(
                    'doc:'.md5($path),
                    'document',
                    $title,
                    $text,
                    'Dokumen',
                    null,
                    ['file' => basename($path)]
                );
        }
    }

    /**
     * @return array<string,mixed>|null
     */
    protected function parseCsv(string $raw, string $path): ?array
    {
        $rows = array_map('str_getcsv', array_filter(array_map('trim', explode("\n", $raw))));

        if ($rows === []) {
            return null;
        }

        $header = array_shift($rows);
        $lines = [];

        foreach ($rows as $row) {
            $pairs = [];
            foreach ($header as $i => $col) {
                $pairs[] = $col.': '.($row[$i] ?? '');
            }
            $lines[] = implode(' — ', $pairs);
        }

        return $this->makeRecord(
            'doc:'.md5($path),
            'document',
            basename($path, '.csv'),
            implode("\n", $lines),
            'Dokumen',
            null,
            ['file' => basename($path)]
        );
    }

    protected function guessTitle(string $text): ?string
    {
        foreach (explode("\n", $text) as $line) {
            $line = trim($line, "# *");

            if (mb_strlen($line) > 3 && mb_strlen($line) < 120) {
                return $line;
            }
        }

        return null;
    }

    /**
     * @param  array<int,string>  $tokens
     * @return array<int,array>
     */
    public function search(string $query, array $tokens): array
    {
        if ($tokens === []) {
            return [];
        }

        $needle = mb_strtolower($query);
        $hits = [];

        foreach ($this->getAll() as $record) {
            $haystack = mb_strtolower($record['title'].' '.$record['content']);
            $hit = $needle !== '' && str_contains($haystack, $needle) ? 2 : 0;

            foreach ($tokens as $token) {
                if (mb_strlen($token) >= 2 && str_contains($haystack, $token)) {
                    $hit++;
                }
            }

            if ($hit > 0) {
                $hits[] = ['record' => $record, 'hits' => $hit];
            }
        }

        usort($hits, fn ($a, $b) => $b['hits'] <=> $a['hits']);

        return array_column($hits, 'record');
    }

    public function get(string $uid): ?array
    {
        foreach ($this->getAll() as $record) {
            if ($record['uid'] === $uid) {
                return $record;
            }
        }

        return null;
    }

    /**
     * @return array<int,array>
     */
    public function getAll(): array
    {
        if ($this->documents === null) {
            $this->documents = $this->sync();
        }

        return $this->documents;
=======
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
>>>>>>> 6370583c48bc189c4dbb3cee9a4971f0925a062c
    }
}