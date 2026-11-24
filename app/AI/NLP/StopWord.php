<?php

namespace App\AI\NLP;

/**
 * Daftar stopword Bahasa Indonesia.
 *
 * Berisi kata umum, kata tanya, kata sapaan, preposisi, dan bentuk santai
 * yang tidak membawa informasi. Sengaja TIDAK memuat kata bermakna domain
 * seperti "jurusan", "guru", "pembina", atau "siswa" karena kata tersebut
 * justru menentukan relevansi jawaban.
 */
class StopWord
{
    public const WORDS = [
        // Preposisi & konjungsi
        'yang', 'untuk', 'dengan', 'dari', 'pada', 'di', 'ke', 'dalam', 'ke',
        'dan', 'atau', 'tetapi', 'tapi', 'juga', 'saja', 'hanya', 'sangat',
        'itu', 'ini', 'adalah', 'ada', 'adanya', 'yaitu', 'bahwa', 'agar',
        // Kata tanya
        'apa', 'apakah', 'siapa', 'siapakah', 'mana', 'dimana', 'kapan',
        'berapa', 'berapakah', 'kenapa', 'mengapa', 'bagaimana', 'gimana',
        'apamana', 'siapamana', 'berapamana',
        // Permintaan / sapaan
        'bisa', 'boleh', 'dapat', 'dapatkah', 'silakan', 'tolong', 'mohon',
        'kasih', 'tau', 'dong', 'ya', 'sih', 'deh', 'kok', 'lah', 'kah', 'pun',
        'nih', 'tuh', 'aja', 'banget', 'agak', 'lumayan', 'pake', 'pakai',
        'mending', 'mendingan', 'kayaknya', 'sepertinya', 'kemungkinan',
        'mungkin', 'kadang', 'jarang', 'sering', 'selalu',
        // Kondisi
        'apabila', 'jika', 'kalau', 'kalo', 'bila', 'misal', 'misalnya',
        'contoh', 'contohnya', 'seperti', 'tertentu', 'lain', 'lainnya',
        // Kata kerja sambung
        'jadi', 'lalu', 'terus', 'kemudian', 'setelah', 'sebelum', 'saat',
        'ketika', 'waktu', 'tanggal', 'hari', 'tahun', 'bulan', 'minggu',
        // Kata ganti & sapaan
        'saya', 'aku', 'kamu', 'kita', 'kalian', 'mereka', 'anda', 'lo', 'gue',
        'gua', 'kak', 'mas', 'mba', 'min', 'pak', 'bu', 'abang', 'kakak',
        'adik', 'nya', 'ku', 'mu',
        // Umum
        'smk', 'smp', 'sma', 'smu', 'sme', 'sekolah', 'sayaa', 'yaa', 'iya',
        'iyaa', 'betul', 'memang', 'emang', 'oke', 'ok', 'okey', 'baik',
        'terima', 'makasih', 'selesai', 'sana', 'sini', 'kesana', 'kesini',
        'disini', 'dikampus', 'please', 'info', 'tolongin', 'minta', 'mintain',
        'beritahu', 'bantu', 'caranya', 'carik', 'donggg', 'dongg',
    ];

    /** @var array<string,true>|null */
    protected static ?array $index = null;

    /** @return array<string,true> */
    protected static function index(): array
    {
        if (self::$index === null) {
            $index = [];

            foreach (self::WORDS as $word) {
                $word = trim($word);

                if ($word !== '') {
                    $index[$word] = true;
                }
            }

            self::$index = $index;
        }

        return self::$index;
    }

    public static function isStopWord(string $token): bool
    {
        return isset(self::index()[Tokenizer::lower($token)]);
    }

    /**
     * @param  array<int,string>  $tokens
     * @return array<int,string>
     */
    public static function filter(array $tokens): array
    {
        return array_values(array_filter(
            $tokens,
            static fn (string $token): bool => !self::isStopWord($token)
        ));
    }

    /** @return array<int,string> */
    public static function all(): array
    {
        return array_keys(self::index());
    }
}