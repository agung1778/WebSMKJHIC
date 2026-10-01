<?php

namespace App\AI\NLP;

/**
 * Stemmer ringkas Bahasa Indonesia.
 *
 * Bukan algoritma Nazief & Adriani lengkap (terlalu berat untuk realtime),
 * tapi cukup untuk menyamakan bentuk jamak: me-, pe-, ke-, se-, ber-, per-,
 * peng-, peny-, di-, ke-an, ke-i, -kan, -nya, -lah, -kah, -pun.
 *
 * Prefix/suffix diproses dari yang paling panjang lebih dulu supaya
 * "pendaftaran" tidak salah dipotong.
 */
class Stemmer
{
    /** Prefix urut dari yang terpanjang. */
    private const PREFIXES = [
        'memper', 'menge', 'menye', 'mempe', 'peng', 'peny',
        'meng', 'meny', 'men', 'mem', 'pem', 'pen', 'per', 'ber', 'ter',
        'pe', 'di', 'ke', 'se', 'me',
    ];

    /** Suffix urut dari yang terpanjang. */
    private const SUFFIXES = ['kannya', 'annya', 'kan', 'an', 'in', 'i'];

    /** Partikel yang dilepas lebih dulu. */
    private const PARTICLES = ['kah', 'lah', 'pun', 'tah'];

    /**
     * Kata yang tidak boleh di-stem (sudah bentuk akhir atau singkatan).
     *
     * @var array<int,string>
     */
    private const KEEP = [
        'guru', 'smp', 'smk', 'rpl', 'tkj', 'dkv', 'akuntansi', 'pkn', 'pai',
        'sains', 'ipa', 'ipas', 'ips', 'pjok', 'seni', 'bahasa', 'inggris',
        'matematika', 'fisika', 'kimia', 'biologi', 'sejarah', 'informatika',
        'ekstrakurikuler', 'pramuka', 'paskibra', 'futsal', 'basket', 'voli',
        'badminton', 'tennis', 'karate', 'taekwondo', 'penjaskes', 'budaya',
        'kwu', 'pmr', 'osis', 'karang', 'taruna', 'sekolah', 'siswa', 'murid',
        // "keahlian" -> stem Suffix "-an" akan jadi "ahli"; kata ini terlalu
        // sering muncul di data website sehingga harus tetap utuh.
        'keahlian', 'ahli', 'program', 'prodi', 'ekstrakurikuler', 'prakarya',
        'industri', 'manajemen', 'komunikasi', 'desain', 'perbankan', 'ritel',
        'retail', 'akuntansi', 'animasi', 'jaringan', 'gambar', 'video', 'animasi',
    ];

    public function stem(string $word): string
    {
        $word = mb_strtolower(trim($word));

        if ($word === '' || mb_strlen($word) <= 3) {
            return $word;
        }

        if (in_array($word, self::KEEP, true)) {
            return $word;
        }

        $stem = $word;

        foreach (self::PARTICLES as $p) {
            if (mb_strlen($stem) > mb_strlen($p) + 3 && str_ends_with($stem, $p)) {
                $stem = mb_substr($stem, 0, mb_strlen($stem) - mb_strlen($p));
                break;
            }
        }

        foreach (self::SUFFIXES as $s) {
            if (mb_strlen($stem) > mb_strlen($s) + 3 && str_ends_with($stem, $s)) {
                $stem = mb_substr($stem, 0, mb_strlen($stem) - mb_strlen($s));
                break;
            }
        }

        foreach (self::PREFIXES as $p) {
            if (str_starts_with($stem, $p) && mb_strlen($stem) > mb_strlen($p) + 2) {
                $stem = mb_substr($stem, mb_strlen($p));
                break;
            }
        }

        if (mb_strlen($stem) > 5 && str_ends_with($stem, 'nya')) {
            $stem = mb_substr($stem, 0, mb_strlen($stem) - 3);
        }

        return $stem !== '' ? $stem : $word;
    }

    /**
     * @param  array<int,string>  $tokens
     * @return array<int,string>
     */
    public function stemMany(array $tokens): array
    {
        return array_map(fn ($t) => $this->stem($t), $tokens);
    }
}