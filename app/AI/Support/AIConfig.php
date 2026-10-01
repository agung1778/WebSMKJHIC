<?php

namespace App\AI\Support;

/**
 * Pembungkus konfigurasi AI supaya seluruh engine membaca setelan lewat
 * satu objek (bukan menyentuh config() di banyak tempat).
 */
class AIConfig
{
    /** @var array|null */
    private static $cached = null;

    public static function all(): array
    {
        if (self::$cached === null) {
            self::$cached = (array) config('ai', []);
        }

        return self::$cached;
    }

    public static function get(string $key, $default = null)
    {
        return self::all()[$key] ?? $default;
    }

    public static function mode(): string
    {
        return (string) self::get('mode', 'normal');
    }

    public static function isDebug(): bool
    {
        return self::mode() === 'debug';
    }

    public static function isStrict(): bool
    {
        return self::mode() === 'strict';
    }

    /** @return array<string,float> */
    public static function weights(): array
    {
        $w = (array) self::get('weights', []);

        return [
            'keyword'  => (float) ($w['keyword'] ?? 0.40),
            'title'    => (float) ($w['title'] ?? 0.25),
            'category' => (float) ($w['category'] ?? 0.15),
            'content'  => (float) ($w['content'] ?? 0.20),
        ];
    }

    public static function minConfidence(): float
    {
        return (float) self::get('min_confidence', 0.55);
    }

    /**
     * Label confidence untuk ditampilkan di admin/debugger.
     */
    public static function confidenceLabel(float $confidence): string
    {
        if ($confidence >= (float) self::get('very_strong', 0.90)) {
            return 'Very Strong';
        }
        if ($confidence >= (float) self::get('strong', 0.75)) {
            return 'Strong';
        }
        if ($confidence >= self::minConfidence()) {
            return 'Weak';
        }

        return 'Unknown';
    }

    public static function search(string $key, $default = null)
    {
        return ((array) self::get('search', []))[$key] ?? $default;
    }

    /** @return array<string,float> */
    public static function fieldBoost(): array
    {
        return (array) self::search('field_boost', [
            'title'    => 2.2,
            'category' => 1.5,
            'meta'     => 1.0,
            'body'     => 1.0,
        ]);
    }

    public static function memory(string $key, $default = null)
    {
        return ((array) self::get('memory', []))[$key] ?? $default;
    }

    public static function logging(string $key, $default = null)
    {
        return ((array) self::get('logging', []))[$key] ?? $default;
    }

    public static function typo(string $key, $default = null)
    {
        return ((array) self::get('typo', []))[$key] ?? $default;
    }

    public static function queryExpansion(string $key, $default = null)
    {
        return ((array) self::get('query_expansion', []))[$key] ?? $default;
    }

    public static function sources(string $connector, string $key, $default = null)
    {
        return (((array) self::get('sources', []))[$connector] ?? [])[$key] ?? $default;
    }

    /** Refresh cache (dipakai command & test). */
    public static function api(string $key, $default = null)
    {
        return ((array) self::get('api', []))[$key] ?? $default;
    }

    public static function maxQuestionLength(): int
    {
        return (int) self::api('max_message', 500);
    }

    public static function cacheTtl(): int
    {
        return (int) self::api('cache_ttl', 300);
    }

    /** Refresh cache (dipakai command & test). */
    public static function flush(): void
    {
        self::$cached = null;
    }
}