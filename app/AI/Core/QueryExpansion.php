<?php

namespace App\AI\Core;

use App\AI\NLP\Normalizer;
use App\AI\NLP\Similarity;
use App\AI\NLP\Tokenizer;
use App\Models\AiSynonym;

/**
 * Ekspansi query untuk meningkatkan recall (mendapatkan lebih banyak
 * dokumen relevan) tanpa mengurangi presisi secara signifikan.
 *
 * Langkah:
 * - Tokenisasi & normalisasi (stem)
 * - Penambahan sinonim (kekuatan bobot berdasarkan weight)
 * - Koreksi typo terhadap kamus lokal
 * - Variasi stemming (raw, stem)
 */
class QueryExpansion
{
    /**
     * @return array<string>
     */
    public function expand(string $question, int $maxTerms = 12): array
    {
        $terms = [];
        $normalized = Normalizer::normalize($question);
        $tokens = Tokenizer::tokensForSearch($question);

        // Term raw yang sudah dibersihkan
        foreach ($tokens as $token) {
            if (! in_array($token, $terms, true)) {
                $terms[] = $token;
            }
        }

        // Term stemmed
        foreach ($normalized as $token) {
            if (! in_array($token, $terms, true)) {
                $terms[] = $token;
            }
        }

        // Sinonim
        try {
            $synonyms = AiSynonym::all();

            foreach ($terms as $term) {
                foreach ($synonyms as $syn) {
                    if (strcasecmp((string) $syn->term, $term) === 0) {
                        $s = (string) $syn->synonym;
                        if (! in_array($s, $terms, true)) {
                            $terms[] = $s;
                        }
                    }

                    if (strcasecmp((string) $syn->synonym, $term) === 0) {
                        $s = (string) $syn->term;
                        if (! in_array($s, $terms, true)) {
                            $terms[] = $s;
                        }
                    }
                }
            }
        } catch (\Throwable) {
            // Tabel belum ada atau kosong; abaikan.
        }

        // Koreksi typo. Token yang sudah ada di kamus tidak perlu dikoreksi
        // (menghindari false positive seperti "paskibra" -> "paskibrah").
        $dictionary = array_keys((new \App\AI\Knowledge\KnowledgeRepository())->documentFrequency());

        if ($dictionary === []) {
            $dictionary = Similarity::dictionaryFromKnowledge();
        }

        $lookup = array_flip($dictionary);
        $corrected = [];

        foreach ($tokens as $token) {
            if (mb_strlen($token) <= 3 || is_numeric($token)) {
                continue;
            }

            if (isset($lookup[$token])) {
                continue;
            }

            $fixed = Similarity::correctTypo($token, $dictionary);

            if ($fixed && ! in_array($fixed, $terms, true) && ! in_array($fixed, $corrected, true)) {
                $corrected[] = $fixed;
            }
        }

        foreach ($corrected as $c) {
            $terms[] = $c;
        }

        return array_values(array_slice(array_unique($terms), 0, $maxTerms));
    }

    /**
     * @return array<string>
     */
    public function expandedAsQuery(array $terms): string
    {
        return implode(' ', $terms);
    }
}