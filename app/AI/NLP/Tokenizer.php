<?php

namespace App\AI\NLP;

/**
 * Tokenizer Bahasa Indonesia.
 *
 * Menghasilkan token lowercase bebas tanda baca (semua karakter non
 * huruf/angka dihapus, termasuk tanda baca Unicode) sambil mempertahankan
 * angka (mis. "3 jurusan").
 *
 * Catatan: demi kehati-hatian, token ini TIDAK memotong kata di tengahnya.
 * Pemenggalan kata majemuk tanpa kamus berisiko merusak nama khas
 * (mis. "lokasi" -> "kasi"). Kasus seperti "jurusanapaaja" ditangani di
 * tahap koreksi typo, yang memakai kamus dari knowledge index.
 */
class Tokenizer
{
    /**
     * Pecah teks menjadi token lowercase bebas tanda baca.
     *
     * @return array<int,string>
     */
    public static function tokenize(string $text): array
    {
        $text = self::collapseRepeatedLetters($text);

        // Buang semua karakter yang bukan huruf/angka (menangani tanda baca
        // ASCII maupun Unicode, termasuk ideograf CJK).
        $text = (string) preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $text);
        $text = self::lower($text);

        $chunks = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $tokens = [];

        foreach ($chunks as $chunk) {
            $chunk = trim($chunk);

            if ($chunk !== '') {
                $tokens[] = $chunk;
            }
        }

        return $tokens;
    }

    /**
     * Token untuk keperluan pencarian: token bebas stopword.
     * Angka tetap dipertahankan.
     *
     * @return array<int,string>
     */
    public static function tokensForSearch(string $text): array
    {
        $out = [];

        foreach (self::tokenize($text) as $token) {
            if (self::isNumeric($token) || ! StopWord::isStopWord($token)) {
                $out[] = $token;
            }
        }

        return array_values(array_unique($out));
    }

    /**
     * Rapatkan huruf berulang yang biasanya hasil salah ketik: "sekolahhh" ->
     * "sekolah". Hanya berlaku untuk tiga+ huruf identik berurutan, sehingga
     * huruf kembar yang sah (mis. "ll" pada "sekolah") tetap utuh.
     */
    public static function collapseRepeatedLetters(string $text): string
    {
        return (string) preg_replace('/(\p{L})\1{2,}/u', '$1', $text);
    }

    /** @return array<int,string> */
    public static function keywords(string $text): array
    {
        return self::tokensForSearch($text);
    }

    public static function isNumeric(string $token): bool
    {
        return (bool) preg_match('/^\d+$/', $token);
    }

    public static function lower(string $text): string
    {
        return function_exists('mb_strtolower') ? mb_strtolower($text, 'UTF-8') : strtolower($text);
    }
}