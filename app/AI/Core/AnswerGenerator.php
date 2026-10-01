<?php

namespace App\AI\Core;

use App\AI\NLP\Similarity;
use App\AI\Support\AIConfig;
use Illuminate\Support\Str;

/**
 * Generator jawaban berbasis konteks (extractive + template ringan).
 *
 * Tidak ada hallucination: jika tidak cukup konteks -> jawaban "tidak tahu"
 * sesuai mode strict/normal. Bahasa Indonesia, ringkas.
 */
class AnswerGenerator
{
    protected array $greetingReplies = [
        'Halo! Saya asisten informasi SMK Amaliah. Ada yang bisa saya bantu terkait sekolah, jurusan, pendaftaran, atau fasilitas?',
        'Hai! Bisa saya bantu mencari informasi tentang SMK Amaliah?',
        'Assalamu’alaikum! Ada yang ingin Anda tanyakan seputar SMK Amaliah?',
    ];

    protected array $thanksReplies = [
        'Sama-sama! Senang bisa membantu.',
        'Terima kasih kembali. Jika ada pertanyaan lain, jangan ragu untuk bertanya.',
    ];

    protected array $byeReplies = [
        'Sampai jumpa! Semoga bermanfaat.',
        'Dadah! Jika ada pertanyaan lain, saya siap membantu kapan saja.',
    ];

    /** @param  array<int,array{title:string,url:?string,category:string,score:float,confidence:float}>  $sources */
    public function generate(
        string $question,
        string $intent,
        string $context,
        array $sources,
        float $topConfidence,
        float $topScore
    ): string {
        // Greeting/thanks/bye
        if ($intent === 'greeting') {
            return $this->greetingReplies[array_rand($this->greetingReplies)];
        }

        if ($intent === 'thanks') {
            return $this->thanksReplies[array_rand($this->thanksReplies)];
        }

        if ($intent === 'bye') {
            return $this->byeReplies[array_rand($this->byeReplies)];
        }

        // Tidak ada konteks relevan
        if ($context === '' || $topConfidence < AIConfig::minConfidence() || $topScore < AIConfig::minRelevance()) {
            if (AIConfig::isStrict()) {
                return 'Maaf, saya tidak menemukan informasi yang cukup akurat untuk menjawab pertanyaan tersebut berdasarkan data SMK Amaliah.';
            }

            return 'Maaf, saya belum menemukan informasi yang sesuai di data sekolah. Bisa Anda coba mengutarakan dengan kata lain atau tanyakan seputar jurusan, pendaftaran, fasilitas, atau kegiatan sekolah?';
        }

        // Jawaban berbasis konteks (extractive ringkas)
        $answer = $this->buildFromContext($question, $context);

        if ($answer === '') {
            return 'Maaf, saya tidak bisa menyusun jawaban yang jelas dari data yang tersedia.';
        }

        return trim($answer);
    }

    /**
     * Pemecah kalimat yang aman terhadap singkatan Bahasa Indonesia.
     *
     * @return array<int,string>
     */
    protected function splitSentences(string $text): array
    {
        $protected = (string) preg_replace_callback(
            '/\b(Jl|No|Rt|Rw|Kp|Kec|Kab|Prov|dsb|dll|utsb|pt|_cv|tb)\./iu',
            static fn (array $m): string => str_replace('.', "\u{2024}", $m[0]),
            $text
        );

        $parts = preg_split('/(?<=[.!?])\s+/u', $protected) ?: [];
        $out = [];

        foreach ($parts as $part) {
            $out[] = trim(str_replace("\u{2024}", '.', $part));
        }

        return $out;
    }

    protected function buildFromContext(string $question, string $context): string
    {
        $blocks = preg_split('/\R{2,}/u', $context, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $sentences = [];

        foreach ($blocks as $block) {
            // Buang prefix kategori [MAJOR] dan separator.
            $block = (string) preg_replace('/^\[[A-Z_]+\]\s*/u', '', trim($block));
            $block = str_replace('---', ' ', $block);
            $block = trim($block);

            if ($block === '') {
                continue;
            }

            // Pecah menjadi kalimat. Abbreviation umum (Jl., No., dsb.) tidak
            // dianggap sebagai akhir kalimat.
            $parts = $this->splitSentences($block);

            foreach ($parts as $part) {
                $part = trim($part);

                if ($part === '' || mb_strlen($part) <= 8) {
                    continue;
                }

                $sentences[] = $part;
            }
        }

        if ($sentences === []) {
            return '';
        }

        // Buang kalimat yang isinya sama dengan kalimat sebelumnya
        // (serang terjadi karena title dan content memakai frasa mirip).
        $unique = [];

        foreach ($sentences as $sentence) {
            $isDuplicate = false;

            foreach ($unique as $kept) {
                if (Similarity::ratio($kept, $sentence) >= 0.82) {
                    $isDuplicate = true;
                    break;
                }
            }

            if (! $isDuplicate) {
                $unique[] = $sentence;
            }
        }

        $picked = array_slice($unique, 0, 2);

        // Bila kalimat pertama hanya berupa judul tanpa titik, gabungkan
        // dengan kalimat berikutnya agar terbaca seperti jawaban.
        return trim(implode(' ', $picked));
    }
}