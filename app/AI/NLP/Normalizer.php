<?php

namespace App\AI\NLP;

/**
 * Pintasan facade untuk pipeline normalisasi teks Bahasa Indonesia.
 *
 * Tahapan: lowercase -> hapus tanda baca -> buang stopword -> stem.
 * Semua method bersifat statis karena tokenizer, stopword, dan stemmer
 * tidak memiliki state.
 */
class Normalizer
{
    /**
     * Token untuk indexing/pencarian (sudah stemmed, stopword dibuang).
     *
     * @return array<int,string>
     */
    public static function normalize(string $text): array
    {
        $tokens = Tokenizer::tokensForSearch($text);

        if ($tokens === []) {
            return [];
        }

        // Stemming dapat menghasilkan kata yang ternyata stopword
        // (mis. "sekolahnya" -> "sekolah"), jadi dibersihkan sekali lagi.
        return StopWord::filter(Stemmer::stemAll($tokens));
    }

    /**
     * Frasa yang tetap terbaca manusia (tanpa stemming penuh).
     */
    public static function normalizePhrase(string $text): string
    {
        return implode(' ', Tokenizer::tokensForSearch($text));
    }

    /**
     * Frekuensi token untuk TF-IDF / BM25.
     *
     * @return array<string,int>
     */
    public static function termFrequency(string $text): array
    {
        $counts = [];

        foreach (self::normalize($text) as $token) {
            $counts[$token] = ($counts[$token] ?? 0) + 1;
        }

        return $counts;
    }

    /**
     * Token tanpa stemming (berguna untuk pencocokan frasa/nama).
     *
     * @return array<int,string>
     */
    public static function tokenize(string $text): array
    {
        return Tokenizer::tokenize($text);
    }

    public static function stem(string $word): string
    {
        return Stemmer::stem($word);
    }

    public static function isStopWord(string $token): bool
    {
        return StopWord::isStopWord($token);
    }

    public static function stripPunctuation(string $text): string
    {
        return (string) preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
    }
}