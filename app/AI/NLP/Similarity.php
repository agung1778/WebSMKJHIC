<?php

namespace App\AI\NLP;

use Illuminate\Support\Facades\DB;

/**
 * Metrik kemiripan tanpa dependensi eksternal:
 * Levenshtein, cosine, token overlap (Jaccard), dan Dice n-gram.
 */
class Similarity
{
    public static function levenshtein(string $a, string $b): int
    {
        $aLen = mb_strlen($a);
        $bLen = mb_strlen($b);

        if ($aLen === 0) {
            return $bLen;
        }

        if ($bLen === 0) {
            return $aLen;
        }

        $prev = range(0, $bLen);

        for ($i = 1; $i <= $aLen; $i++) {
            $cur = [$i];

            for ($j = 1; $j <= $bLen; $j++) {
                $cost = mb_substr($a, $i - 1, 1) === mb_substr($b, $j - 1, 1) ? 0 : 1;
                $cur[$j] = min(
                    $prev[$j] + 1,
                    $cur[$j - 1] + 1,
                    $prev[$j - 1] + $cost
                );
            }

            $prev = $cur;
        }

        return (int) $prev[$bLen];
    }

    /** Skor kemiripan 0..1. */
    public static function ratio(string $a, string $b): float
    {
        $max = max(mb_strlen($a), mb_strlen($b));

        if ($max === 0) {
            return 1.0;
        }

        return max(0.0, 1.0 - (self::levenshtein($a, $b) / $max));
    }

    /**
     * Cosine similarity dua vektor sparse (token => bobot).
     *
     * @param  array<string,int|float>  $a
     * @param  array<string,int|float>  $b
     */
    public static function cosine(array $a, array $b): float
    {
        if ($a === [] || $b === []) {
            return 0.0;
        }

        $dot = 0.0;
        $magA = 0.0;
        $magB = 0.0;

        foreach ($a as $token => $weight) {
            $magA += $weight ** 2;

            if (isset($b[$token])) {
                $dot += $weight * $b[$token];
            }
        }

        foreach ($b as $weight) {
            $magB += $weight ** 2;
        }

        if ($magA <= 0.0 || $magB <= 0.0) {
            return 0.0;
        }

        return $dot / (sqrt($magA) * sqrt($magB));
    }

    /**
     * Token overlap (Jaccard).
     *
     * @param  array<int,string>  $a
     * @param  array<int,string>  $b
     */
    public static function overlap(array $a, array $b): float
    {
        $setA = array_unique($a);
        $setB = array_unique($b);

        if ($setA === [] || $setB === []) {
            return 0.0;
        }

        $union = count(array_unique(array_merge($setA, $setB)));

        if ($union === 0) {
            return 0.0;
        }

        return count(array_intersect($setA, $setB)) / $union;
    }

    /**
     * Dice coefficient atas n-gram karakter, toleran terhadap typo ringan.
     */
    public static function ngramSimilarity(string $a, string $b, int $n = 3): float
    {
        $gramsA = self::ngrams($a, $n);
        $gramsB = self::ngrams($b, $n);

        if ($gramsA === [] || $gramsB === []) {
            return 0.0;
        }

        $inter = count(array_intersect($gramsA, $gramsB));

        return (2 * $inter) / (count($gramsA) + count($gramsB));
    }

    /** @return array<int,string> */
    protected static function ngrams(string $text, int $n): array
    {
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? $text);
        $len = mb_strlen($text);

        if ($len < $n) {
            return $text === '' ? [] : [$text];
        }

        $grams = [];

        for ($i = 0; $i <= $len - $n; $i++) {
            $grams[] = mb_substr($text, $i, $n);
        }

        return array_values(array_unique($grams));
    }

    /**
     * Koreksi typo satu kata terhadap kamus lokal.
     *
     * Skor digabungkan dari Levenshtein ratio dan Dice n-gram, karena
     * lebih toleran terhadap huruf yang hilang/tertukar (mis.
     * "extracurricularnya" vs "ekstrakurikuler").
     *
     * @param  array<int,string>  $dictionary
     */
    public static function correctTypo(string $word, array $dictionary): ?string
    {
        $word = Tokenizer::lower($word);

        if (mb_strlen($word) <= 3 || Tokenizer::isNumeric($word) || $dictionary === []) {
            return null;
        }

        $best = null;
        $bestScore = 0.0;
        $wordLen = mb_strlen($word);

        foreach ($dictionary as $candidate) {
            if (! is_string($candidate) || $candidate === '' || $candidate === $word) {
                continue;
            }

            // Toleransi panjang grows bersama panjang kata.
            if (abs(mb_strlen($candidate) - $wordLen) > 4) {
                continue;
            }

            $ratio = self::ratio($word, $candidate);
            $dice = self::ngramSimilarity($word, $candidate, 3);
            $score = max($ratio, $dice * 0.92);

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $candidate;
            }
        }

        return $bestScore >= self::typoThreshold(mb_strlen($word)) ? $best : null;
    }

    /**
     * Ambang toleransi typo accordingi panjang kata.
     *
     * Kata panjang lebih rawan salah ketik sehingga toleransinya longgar
     * (mis. "exracurikuler" vs "ekstrakurikuler" hanya 0.73). Kata pendek
     * tetap memakai ambang konservatif agar tidak salah mengoreksi.
     */
    protected static function typoThreshold(int $length): float
    {
        if ($length >= 9) {
            return 0.72;
        }

        if ($length >= 6) {
            return 0.76;
        }

        return 0.82;
    }

    /**
     * Kamus kata dari knowledge index (tabel ai_keywords.token).
     * Menghindari kebutuhan kamus eksternal.
     *
     * @return array<int,string>
     */
    public static function dictionaryFromKnowledge(): array
    {
        try {
            return DB::table('ai_keywords')
                ->where('is_stopword', false)
                ->pluck('token')
                ->all();
        } catch (\Throwable) {
            // Index belum dibangun / tabel belum ada.
            return [];
        }
    }
}