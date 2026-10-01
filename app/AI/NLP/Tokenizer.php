<?php

namespace App\AI\NLP;

/**
 * Tokenizer Bahasa Indonesia.
 *
 * Menangani:
 *  - huruf besar/kecil + tanda baca + simbol
 *  - contractions: "gimana" -> "gimana", "banget" ->|banget| (dipertahankan)
 *  - angka ("3", "2026")
 *  - frasa gabung dengan tanda hubung ("taylor-made" -> "taylormade")
 *  - email/URL tidak ikut dipecah jadi noise
 */
class Tokenizer
{
    /** @var Stemmer */
    private $stemmer;

    public function __construct(?Stemmer $stemmer = null)
    {
        $this->stemmer = $stemmer ?: new Stemmer();
    }

    /**
     * Bersihkan teks mentah: lowercase, buang tanda baca excess.
     */
    public function clean(string $text): string
    {
        $text = mb_strtolower(trim($text));

        // Buang URL & email agar domain tidak jadi tokenSEARCH sia-sia.
        $text = (string) preg_replace('~https?://\S+|www\.\S+|\b[\w.+-]+@[\w.-]+\.\w+\b~', ' ', $text);

        // Tanda baca jadi spasi, kecuali tanda dalam kata (mis. "3.500").
        $text = (string) preg_replace('~[^\p{L}\p{N}\s\.\,\-]+~u', ' ', $text);
        $text = (string) preg_replace('~[\.\,\-]+~u', ' ', $text);
        $text = (string) preg_replace('~\s+~u', ' ', $text);

        return trim($text);
    }

    /**
     * Tokenize menjadi kata individual (tanpa stemming).
     *
     * @return array<int,string>
     */
    public function tokenize(string $text): array
    {
        $clean = $this->clean($text);

        if ($clean === '') {
            return [];
        }

        return explode(' ', $clean);
    }

    /**
     * Tokenize + stem (untuk index & query alike supaya keduanya sebanding).
     *
     * @return array<int,string>
     */
    public function tokenizeStem(string $text): array
    {
        return $this->stemMany($this->tokenize($text));
    }

    /**
     * @param  array<int,string>  $tokens
     * @return array<int,string>
     */
    public function stemMany(array $tokens): array
    {
        return $this->stemmer->stemMany($tokens);
    }

    /**
     * Frasa (bigram/trigram) dari token — untuk semantic-like matching.
     *
     * @param  array<int,string>  $tokens
     * @return array<int,string>
     */
    public function ngrams(array $tokens, int $n = 2): array
    {
        $count = count($tokens);

        if ($n < 2 || $count < $n) {
            return $tokens;
        }

        $grams = [];
        for ($i = 0; $i <= $count - $n; $i++) {
            $grams[] = implode(' ', array_slice($tokens, $i, $n));
        }

        return $grams;
    }

    /**
     * @param  array<int,string>  $tokens
     * @return array<int,string>
     */
    public function allNgrams(array $tokens, int $max = 3): array
    {
        $grams = $tokens;

        for ($n = 2; $n <= $max; $n++) {
            $grams = array_merge($grams, $this->ngrams($tokens, $n));
        }

        return array_values(array_unique($grams));
    }

    public function stemmer(): Stemmer
    {
        return $this->stemmer;
    }
}