<?php

namespace App\AI\Data;

use App\AI\Support\AIConfig;
use Illuminate\Support\Facades\File;

/**
 * Sumber pengetahuan #2: HALAMAN WEBSITE (route publik).
 *
 * Engine membaca teks yang sudah dirender controller publik, lalu mengambil
 * hanya <main>/<article> supaya navigasi & footer tidak mengotori index.
 * Ini membuat pertanyaan seperti "apa isi halaman PPDB" bisa dijawab dari
 * konten halaman, bukan hanya dari tabel.
 */
class WebsiteSource extends BaseSource
{
    /** @var array<string,array>|null */
    protected ?array $pages = null;

    public function name(): string
    {
        return 'website';
    }

    public function enabled(): bool
    {
        return (bool) AIConfig::sources('website', 'enabled', true);
    }

    /** @return array<int,string> */
    public function paths(): array
    {
        return (array) AIConfig::sources('website', 'pages', []);
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

        foreach ($this->paths() as $path) {
            try {
                $record = $this->fetchPage($path);
                if ($record !== null) {
                    $records[] = $record;
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $records;
    }

    /**
     * @return array<string,mixed>|null
     */
    public function fetchPage(string $path): ?array
    {
        $url = $this->url($path);

        // Request loopback ke host sendiri (bukan panggilan keluar ke internet).
        $html = $this->requestHtml($url);

        if ($html === null || mb_strlen($html) < 100) {
            return null;
        }

        $title = $this->extractTitle($html);
        $body  = $this->extractMainText($html);

        if (mb_strlen($body) < 40) {
            return null;
        }

        return $this->makeRecord(
            'page:'.trim($path, '/'),
            'page',
            $title ?: $path,
            $body,
            'Halaman Website',
            $url,
            ['path' => $path]
        );
    }

    /**
     * Ambil HTML via HTTP internal (curl). Mengembalikan null bila gagal —
     * halaman yang gagal diambil tidak boleh menggagalkan indexing.
     */
    protected function requestHtml(string $url): ?string
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 3,
            CURLOPT_TIMEOUT        => 8,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_USERAGENT      => 'TanyaAmaliahIndexer/1.0 (internal knowledge sync)',
        ]);

        $html = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($html === false || $code >= 400) {
            return null;
        }

        return (string) $html;
    }

    protected function extractTitle(string $html): ?string
    {
        if (preg_match('~<title[^>]*>(.*?)</title>~is', $html, $m)) {
            $title = trim(html_entity_decode($this->stripHtml($m[1]), ENT_QUOTES));

            // Buang nama situs di ekor judul: "Judul - SMK Amaliah"
            $title = (string) preg_replace('~\s*[|\-–]\s*SMK Amaliah.*$~i', '', $title);

            return $title;
        }

        return null;
    }

    protected function extractMainText(string $html): string
    {
        // Ambil hanya konten utama.
        foreach (['~<main\b[^>]*>(.*?)</main>~is', '~<article\b[^>]*>(.*?)</article>~is'] as $pattern) {
            if (preg_match($pattern, $html, $m)) {
                $html = $m[1];
                break;
            }
        }

        // Buang blok yang tidak relevan (nav, footer, script, aside).
        $html = (string) preg_replace('~<(nav|header|footer|aside|form|script|style|noscript)\b[^>]*>.*?</\1>~is', ' ', $html);

        return $this->stripHtml($html);
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
        if ($this->pages === null) {
            $this->pages = $this->sync();
        }

        return $this->pages;
    }

    /** Cek apakah direktori dokumen AI tersedia. */
    public static function documentsExist(): bool
    {
        foreach ((array) AIConfig::sources('documents', 'paths', []) as $path) {
            if (File::isDirectory($path)) {
                return true;
            }
        }

        return false;
    }
}