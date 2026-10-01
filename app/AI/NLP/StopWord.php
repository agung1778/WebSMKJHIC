<?php

namespace App\AI\NLP;

/**
 * Stopword Bahasa Indonesia + kata umum website.
 *
 * Stopword TIDAK dihapus total sebelum stemming — mesin tetap butuh
 * kata tanya ("apa", "siapa", "berapa") untuk intent. Fungsi stopword di
 * sini adalah menurunkan bobot kata tersebut saat menghitung relevansi.
 */
class StopWord
{
    /** Kata_COMMAND yang tidak pernah jadi sinyal relevansi. */
    public const HARD = [
        'yang', 'dan', 'atau', 'di', 'ke', 'dari', 'untuk', 'pada', 'dengan',
        'itu', 'ini', 'atau', 'adalah', 'yaitu', 'serta', 'dan', 'sudah',
        'telah', 'akan', 'dapat', 'bisa', 'boleh', 'harus', 'mau', 'ingin',
        'kalian', 'kami', 'kita', 'saya', 'aku', 'anda', 'kamu', 'silakan',
        'tolong', 'mohon', 'banget', 'sih', 'dong', 'deh', 'ya', 'iya', 'oke',
        'eh', 'wah', 'nih', 'tuh', 'kok', 'lah', 'kah', 'pun', 'saja', 'juga',
        'aja', 'gitu', 'gini', 'begini', 'begitu', 'banyak', 'semua', 'seluruh',
        'tentang', 'mengenai', 'terhadap', 'oleh', 'karena', 'supaya', 'agar',
    ];

    /** Kata umum yang tidak relevan tapi tetap dibiarkan untuk intent. */
    public const SOFT = [
        'apa', 'apakah', 'apaan', 'siapa', 'siapakah', 'mana', 'manakah',
        'dimana', 'di mana', 'kapan', 'kapankah', 'berapa', 'berapakah',
        'bagaimana', 'bagaimanakah', 'mengapa', 'kenapa', 'boleh', 'dong',
        'ya', 'saya', 'ingin', 'tahu', 'mau', 'bisa', 'dapat', 'itu', 'ini',
    ];

    /** Kata yang tak pernah dianggap sebagai entitas/nama. */
    public const NOISE = [
        'sih', 'dong', 'deh', 'ya', 'kak', 'kakak', 'bang', 'mas', 'bro',
        'smk', 'amaliah', 'sekolah', 'website', 'site', 'halaman', 'page',
        'info', 'informasi', 'tolong', 'mohon', 'terima', 'kasih',
    ];

    /** @var array<string,bool>|null */
    private static ?array $index = null;

    /**
     * @return array<string,bool>
     */
    public static function index(): array
    {
        if (self::$index === null) {
            $index = [];
            foreach (array_merge(self::HARD, self::SOFT, self::NOISE) as $w) {
                $index[$w] = true;
            }
            self::$index = $index;
        }

        return self::$index;
    }

    public static function isStop(string $token): bool
    {
        return isset(self::index()[$token]);
    }

    public static function isHard(string $token): bool
    {
        return in_array($token, self::HARD, true);
    }

    public static function isNoise(string $token): bool
    {
        return in_array($token, self::NOISE, true);
    }

    /**
     * Buang stopword dari daftar token.
     *
     * @param  array<int,string>  $tokens
     * @return array<int,string>
     */
    public static function remove(array $tokens): array
    {
        return array_values(array_filter($tokens, fn ($t) => ! self::isStop($t)));
    }

    /**
     * Pembobotan manual untuk kata tanya — dipakai IntentEngine, bukan dihapus.
     */
    public static function weight(string $token): float
    {
        if (in_array($token, ['apa', 'siapa', 'berapa', 'kapan', 'mana', 'dimana'], true)) {
            return 1.6; // pertanyaan = sinyal intent kuat
        }
        if (self::isHard($token)) {
            return 0.1;
        }
        if (self::isNoise($token)) {
            return 0.3;
        }

        return 1.0;
    }

    public static function flush(): void
    {
        self::$index = null;
    }
}