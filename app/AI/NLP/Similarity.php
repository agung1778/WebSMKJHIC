<?php

namespace App\AI\NLP;

/**
 * Fuzzy matching & similarity metrics — implementasi sendiri, tanpa API.
 *
 *  - levenshteinDistance()  : edit distance untuk typo correction
 *  - similarity()           : 1 - distance/maxLen
 *  - jaccard()              : token overlap (diberi bobot IDF dari pemanggil)
 *  - cosine()               : cosine similarity atas vektor fitur
 *  - ngramSimilarity()      : kesamaan karakter bigram/trigram (akurat untuk nama)
 *  - diceCoefficient()      : 2*intersection/(a+b)
 */
class Similarity
{
    /**
     * Levenshtein distance (versi hemat memori: 2 baris).
     */
    public static function levenshtein(string $a, string $b): int
    {
        $a = mb_strtolower($a);
        $b = mb_strtolower($b);

        if ($a === $b) {
            return 0;
        }
        if ($a === '' || $b === '') {
            return max(mb_strlen($a), mb_strlen($b));
        }

        if (mb_strlen($a) < mb_strlen($b)) {
            [$a, $b] = [$b, $a];
        }
        if (mb_strlen($b) === 1) {
            return mb_strlen($a);
        }

        $previous = range(0, mb_strlen($b));

        $aChars = preg_split('//u', $a, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $bChars = preg_split('//u', $b, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $bLen   = count($bChars);

        foreach ($aChars as $i => $charA) {
            $current = [$i + 1];

            foreach ($bChars as $j => $charB) {
                $insert = $current[$j] + 1;
                $delete = $previous[$j + 1] + 1;
                $replace = $previous[$j] + ($charA === $charB ? 0 : 1);
                $current[$j + 1] = min($insert, $delete, $replace);
            }

            $previous = $current;
        }

        return $previous[$bLen];
    }

    /**
     * Similarity 0..1 berbasis edit distance.
     */
    public static function similarity(string $a, string $b): float
    {
        $max = max(mb_strlen($a), mb_strlen($b));

        if ($max === 0) {
            return 1.0;
        }

        return 1.0 - (self::levenshtein($a, $b) / $max);
    }

    /**
     * Token overlap (Jaccard). Berguna untuk menakar كمatic keyword query
     * muncul di sebuah dokumen.
     *
     * @param  array<int,string>  $a
     * @param  array<int,string>  $b
     */
    public static function jaccard(array $a, array $b): float
    {
        $setA = array_unique($a);
        $setB = array_unique($b);

        if ($setA === [] || $setB === []) {
            return 0.0;
        }

        $inter = count(array_intersect($setA, $setB));
        $union = count(array_unique(array_merge($setA, $setB)));

        return $union === 0 ? 0.0 : $inter / $union;
    }

    /**
     * Dice coefficient — sedikit lebih longgar dari Jaccard untuk frasa pendek.
     *
     * @param  array<int,string>  $a
     * @param  array<int,string>  $b
     */
    public static function dice(array $a, array $b): float
    {
        $setA = array_unique($a);
        $setB = array_unique($b);

        if ($setA === [] || $setB === []) {
            return 0.0;
        }

        $inter = count(array_intersect($setA, $setB));

        return (2 * $inter) / (count($setA) + count($setB));
    }

    /**
     * @param  array<string,float>  $vectorA
     * @param  array<string,float>  $vectorB
     */
    public static function cosine(array $vectorA, array $vectorB): float
    {
        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        foreach ($vectorA as $k => $v) {
            $dot += $v * ($vectorB[$k] ?? 0.0);
        }
        foreach ($vectorA as $v) {
            $normA += $v * $v;
        }
        foreach ($vectorB as $v) {
            $normB += $v * $v;
        }

        if ($normA == 0.0 || $normB == 0.0) {
            return 0.0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }

    /**
     * Kesamaan karakter bigram (bagus untuk nama orang / Stores).
     */
    public static function bigramSimilarity(string $a, string $b): float
    {
        $a = mb_strtolower($a);
        $b = mb_strtolower($b);

        if ($a === '' || $b === '') {
            return 0.0;
        }
        if ($a === $b) {
            return 1.0;
        }

        $gramsA = self::charNgrams($a, 2);
        $gramsB = self::charNgrams($b, 2);

        $inter = count(array_intersect($gramsA, $gramsB));
        $total = count($gramsA) + count($gramsB);

        return $total === 0 ? 0.0 : (2 * $inter) / $total;
    }

    /**
     * @return array<int,string>
     */
    private static function charNgrams(string $s, int $n): array
    {
        $len = mb_strlen($s);

        if ($len < $n) {
            return [$s];
        }

        $grams = [];
        for ($i = 0; $i <= $len - $n; $i++) {
            $grams[] = mb_substr($s, $i, $n);
        }

        return $grams;
    }
}