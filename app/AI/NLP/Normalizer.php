<?php

namespace App\AI\NLP;

use App\AI\Support\AIConfig;

/**
 * Normalizer pertanyaan user: typo ringan + sinonim + pembungkusan grup konsep.
 */
class Normalizer
{
    /** @var Tokenizer */
    private $tokenizer;

    /** @var Stemmer */
    private $stemmer;

    /** @var array<string,string>|null */
    private $typoMap = null;

    public function __construct(?Tokenizer $tokenizer = null)
    {
        $this->tokenizer = $tokenizer ?: new Tokenizer();
        $this->stemmer   = $this->tokenizer->stemmer();
    }

    /**
     * Normalisasi teks lengkap: clean, typo light, sinonim ringan (hardcoded)
     * + buang spasi berlebih.
     */
    public function normalize(string $text): string
    {
        $text = mb_strtolower(trim($text));

        // Hilangkan karakter berulang berlebihan ("aaaa" -> "aa") untuk typo ringan
        $text = (string) preg_replace('~(.)\1{2,}~u', '$1$1', $text);

        // Map typo umum Bahasa Indonesia (sekolah)
        $map = $this->typoMapCommon();
        $words = $this->tokenizer->tokenize($text);
        $out = [];

        foreach ($words as $w) {
            $stem = $this->stemmer->stem($w);

            if (isset($map[$w])) {
                $out[] = $map[$w];
                continue;
            }
            if (isset($map[$stem])) {
                $out[] = $map[$stem];
                continue;
            }

            // Tutup kesalahan eja ringan "amalia" vs "amaliah"
            if ($stem === 'amalia') {
                $out[] = 'amaliah';
                continue;
            }
            if ($stem === 'jurus') {
                $out[] = 'jurusan';
                continue;
            }
            if (in_array($stem, ['pmb', 'smpb', 'smb'], true)) {
                $out[] = 'spmb';
                continue;
            }
            if ($stem === 'fasilit') {
                $out[] = 'fasilitas';
                continue;
            }
            if ($stem === 'ekstrakurikul') {
                $out[] = 'ekstrakurikuler';
                continue;
            }

            $out[] = $w;
        }

        $normalized = trim(implode(' ', $out));

        // Sinonim konsep (ringan, bisa diganti tabel ai_synonyms nanti)
        $normalized = $this->applyConceptSynonyms($normalized);

        return trim((string) preg_replace('~\s+~u', ' ', $normalized));
    }

    /**
     * Tokenize + stem + filter noise ringan (dipakai untuk query search).
     *
     * @return array<int,string>
     */
    public function queryTokens(string $text): array
    {
        $norm = $this->normalize($text);
        $tokens = $this->tokenizer->tokenize($norm);

        $result = [];
        foreach ($tokens as $t) {
            $s = $this->stemmer->stem($t);
            if ($s === '') {
                continue;
            }
            if (mb_strlen($s) < (int) AIConfig::typo('min_length', 2)) {
                // tetap pertahankan kata tanya pendek ("apa","siapa") untuk intent
                if (! in_array($s, ['apa', 'si', 'siapa', 'mana', 'kapan', 'berapa'], true)) {
                    continue;
                }
            }
            $result[] = $s;
        }

        return array_values(array_unique($result));
    }

    /**
     * @return array<string,string>
     */
    private function typoMapCommon(): array
    {
        if ($this->typoMap === null) {
            $this->typoMap = [
                'amlh'      => 'amaliah',
                'amliah'    => 'amaliah',
                'amalih'    => 'amaliah',
                'smkamalia' => 'smk amaliah',
                'smka'      => 'smk amaliah',
                'jurusn'    => 'jurusan',
                'prodi'     => 'program keahlian',
                'progam'    => 'program',
                'program'   => 'program',
                'jurusan'   => 'jurusan',
                'keahlian'  => 'keahlian',
                'kompetensi'=> 'kompetensi',
                'ekskul'    => 'ekstrakurikuler',
                'eskul'     => 'ekstrakurikuler',
                'exskul'    => 'ekstrakurikuler',
                'ppdb'      => 'ppdb',
                'spmb'      => 'spmb',
                'smb'       => 'spmb',
                'pmb'       => 'spmb',
                'smpb'      => 'spmb',
                'pendaftaran'=>'pendaftaran',
                'fasilitas' => 'fasilitas',
                'fasiltas'  => 'fasilitas',
                'fasillitas'=> 'fasilitas',
                'lab'       => 'laboratorium',
                'laborat'   => 'laboratorium',
                'laboratium'=> 'laboratorium',
                'laboratorium'=>'laboratorium',
                'guru'      => 'guru',
                'pengajar'  => 'guru',
                'pendidik'  => 'guru',
                'staf'      => 'staf',
                'staff'     => 'staf',
                'berita'    => 'berita',
                'beritaa'   => 'berita',
                'artikel'   => 'berita',
            ];
        }

        return $this->typoMap;
    }

    /**
     * Map konsep umum -> bentuk yang lebih konsisten untuk pencarian.
     */
    private function applyConceptSynonyms(string $text): string
    {
        $replacements = [
            '~\bjurusan apa saja\b~u'            => 'daftar jurusan',
            '~\bjurusan apa aja\b~u'              => 'daftar jurusan',
            '~\bprogram keahlian apa saja\b~u'    => 'daftar jurusan',
            '~\bkompetensi keahlian apa\b~u'      => 'daftar jurusan',
            '~\bdaftar program keahlian\b~u'     => 'daftar jurusan',
            '~\bapa saja jurusan\b~u'             => 'daftar jurusan',
            '~\bfasilitas apa saja\b~u'           => 'daftar fasilitas',
            '~\bfasilitas apa aja\b~u'            => 'daftar fasilitas',
            '~\blab apa saja\b~u'                 => 'laboratorium',
            '~\blaboratorium apa saja\b~u'        => 'laboratorium',
            '~\bapa saja ekstrakurikuler\b~u'     => 'daftar ekstrakurikuler',
            '~\bekskul apa saja\b~u'              => 'daftar ekstrakurikuler',
            '~\bekstrakurikuler apa saja\b~u'    => 'daftar ekstrakurikuler',
            '~\bguru apa saja\b~u'                => 'daftar guru',
            '~\bguru siapa saja\b~u'              => 'daftar guru',
            '~\bsiapa saja guru\b~u'              => 'daftar guru',
            '~\bsebutkan semua guru\b~u'          => 'daftar guru',
            '~\bpartner industri\b~u'             => 'mitra industri',
            '~\bmitra sekolah\b~u'                => 'mitra industri',
            '~\bkerja sama\b~u'                   => 'mitra industri',
            '~\bkerjasama\b~u'                    => 'mitra industri',
            '~\btestimoni alumni\b~u'             => 'testimoni',
            '~\bkepala sekolah\b~u'               => 'kepala sekolah',
            '~\bpimpinan sekolah\b~u'             => 'kepala sekolah',
            '~\bberapa guru\b~u'                  => 'jumlah guru',
            '~\bada berapa guru\b~u'              => 'jumlah guru',
            '~\bjml guru\b~u'                     => 'jumlah guru',
            '~\bjumlah guru\b~u'                  => 'jumlah guru',
            '~\bjam pelajaran\b~u'                => 'jam layanan',
            '~\bjam sekolah\b~u'                  => 'jam layanan',
            '~\bnilai rapor\b~u'                  => 'nilai rapor',
            '~\brapor\b~u'                        => 'nilai rapor',
            '~\bnilai\b~u'                        => 'nilai rapor',
            '~\bkritik dan saran\b~u'             => 'kritik saran',
            '~\bkeluhan\b~u'                      => 'kritik saran',
            '~\bsaran\b~u'                        => 'kritik saran',
        ];

        foreach ($replacements as $pattern => $replacement) {
            $text = (string) preg_replace($pattern, $replacement, $text);
        }

        return trim($text);
    }
}