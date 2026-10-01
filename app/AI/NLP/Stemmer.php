<?php

namespace App\AI\NLP;

/**
 * Stemmer ringan Bahasa Indonesia.
 *
 * Menghapus awalan (prefix) dan akhiran (suffix), bukan Algoritma Porter
 * penuh, namun cukup andal untuk retrieval Bahasa Indonesia
 * (mis. "mengajar" -> "ajar", "pendaftaran" -> "daftar").
 *
 * Kata yang terlalu sering salah di-stem (kata umum dan kata domain utama)
 * dimasukkan ke PROTECTED agar hasil stemming konsisten di sisi indexing
 * maupun sisi query.
 */
class Stemmer
{
    /** Kata yang tidak boleh di-stem. */
    public const PROTECTED = [
        'sekolah', 'siswa', 'guru', 'jurusan', 'jurus', 'ekstrakurikuler',
        'ekstrakurikul', 'fasilitas', 'mata', 'pelajaran', 'pembina',
        'pendaftaran', 'prestasi', 'program', 'mitra', 'industri',
        'rekayasa', 'perangkat', 'lunak', 'animasi', 'multimedia', 'tata',
        'boga', 'phoenix', 'rpl', 'tkj', 'akuntansi', 'pemasaran', 'asl',
        'asal', 'tersedia', 'melekat', 'lokasi', 'alamat', 'kontak',
        'telepon', 'nomor', 'biaya', 'spmb', 'ppdb', 'beasiswa',
        'kurikulum', 'nilai', 'ranking', 'jam', 'pelajaran',
    ];

    protected const PREFIXES = [
        'memper', 'menge', 'meny', 'meng', 'menye', 'men', 'mem', 'peng',
        'peny', 'ber', 'per', 'pen', 'pem',
    ];

    /** Awalan pendek hanya dilepas dengan Jadwalkeu double-konsonan. */
    protected const SHORT_PREFIXES = ['di', 'ke', 'se', 'te'];

    protected const SUFFIXES = [
        'kannya', 'annya', 'inya', 'nya', 'kan', 'an', 'i',
    ];

    protected const MIN_STEM_LENGTH = 3;

    /**
     * Sisa minimal untuk melepas awalan pendek. Tanpa batas ini
     * "sekolahnya" -> "kolah" dan "ketika" -> "tika".
     */
    protected const MIN_SHORT_PREFIX_STEM = 6;

    /** @var array<string,true>|null */
    protected static ?array $protectedIndex = null;

    public static function stem(string $word): string
    {
        if (mb_strlen($word) <= self::MIN_STEM_LENGTH || Tokenizer::isNumeric($word)) {
            return $word;
        }

        if (self::isProtected($word)) {
            return $word;
        }

        $original = $word;

        // Suffiks lebih dulu agar "mempelajari" -> "pelajar" (bukan "lajar").
        $word = self::stripSuffix($word);
        $word = self::stripPrefix($word);

        // Hasil yang terlalu pendek atau tidak masuk akal dibuang.
        if ($word === '' || mb_strlen($word) < self::MIN_STEM_LENGTH) {
            return $original;
        }

        return self::fixDoubleFinal($word);
    }

    /**
     * @param  array<int,string>  $words
     * @return array<int,string>
     */
    public static function stemAll(array $words): array
    {
        return array_map(static fn (string $w): string => self::stem($w), $words);
    }

    public static function isProtected(string $word): bool
    {
        if (self::$protectedIndex === null) {
            $index = [];

            foreach (self::PROTECTED as $item) {
                $index[$item] = true;
            }

            self::$protectedIndex = $index;
        }

        return isset(self::$protectedIndex[$word]);
    }

    protected static function stripPrefix(string $word): string
    {
        $prefixes = self::PREFIXES;
        usort($prefixes, static fn (string $a, string $b): int => mb_strlen($b) <=> mb_strlen($a));

        foreach ($prefixes as $prefix) {
            if (
                str_starts_with($word, $prefix)
                && mb_strlen($word) - mb_strlen($prefix) >= self::MIN_STEM_LENGTH
                && ! self::looksBroken(mb_substr($word, mb_strlen($prefix)))
            ) {
                return mb_substr($word, mb_strlen($prefix));
            }
        }

        // Awalan pendek (di-, ke-, se-, te-) hanya dilepas bila sisa kata
        // mulai dengan vokal atau tidak diawali konsonan ganda. Tanpa guard ini
        // "tersedia" akan menjadi "rsedia".
        foreach (self::SHORT_PREFIXES as $prefix) {
            if (! str_starts_with($word, $prefix)) {
                continue;
            }

            $rest = mb_substr($word, mb_strlen($prefix));

            if (
                mb_strlen($rest) < self::MIN_SHORT_PREFIX_STEM
                || self::startsWithDoubleConsonant($rest)
            ) {
                continue;
            }

            return $rest;
        }

        return $word;
    }

    protected static function stripSuffix(string $word): string
    {
        foreach (self::SUFFIXES as $suffix) {
            if (
                str_ends_with($word, $suffix)
                && mb_strlen($word) - mb_strlen($suffix) >= self::MIN_STEM_LENGTH
            ) {
                $rest = mb_substr($word, 0, mb_strlen($word) - mb_strlen($suffix));

                if (! self::looksBroken($rest)) {
                    return $rest;
                }
            }
        }

        return $word;
    }

    /** Sisa kata dianggap rusak jika diawali dua konsonan berturut-turut. */
    protected static function startsWithDoubleConsonant(string $word): bool
    {
        return (bool) preg_match('/^[bcdfghjklmnpqrstvwxyz]{2}/u', $word);
    }

    protected static function looksBroken(string $word): bool
    {
        return $word === '' || self::startsWithDoubleConsonant($word);
    }

    /** Ratakan akhiran kembar: "sekolahhh" -> "sekolah", "klass" -> "klas". */
    protected static function fixDoubleFinal(string $word): string
    {
        $word = (string) preg_replace('/([bcdfghjklmnpqrstvwxyz])\1$/u', '$1', $word);

        return (string) preg_replace('/([aeiou])\1$/u', '$1', $word);
    }
}