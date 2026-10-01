<?php

namespace App\AI\Core;

use App\AI\NLP\Normalizer;
use App\AI\NLP\Tokenizer;

/**
 * Ekstraksi entitas sederhana (rule + keyword).
 *
 * Fokus pada domain sekolah: jurusan, guru, mata pelajaran, ekstrakurikuler,
 * lokasi, waktu, angka.
 */
class EntityExtractor
{
    /** @return array<string,mixed> */
    public function extract(string $question): array
    {
        $q = strtolower($question);
        $normalized = Normalizer::normalize($question);
        $tokens = Tokenizer::tokensForSearch($question);

        $entities = [
            'majors' => [],
            'teachers' => [],
            'subjects' => [],
            'extracurriculars' => [],
            'numbers' => [],
            'locations' => [],
        ];

        // Jurusan (nama umum + singkatan)
        $majorMap = [
            'rpl' => 'Rekayasa Perangkat Lunak',
            'tkj' => 'Teknik Komputer dan Jaringan',
            'mm' => 'Multimedia',
            'multimedia' => 'Multimedia',
            'animasi' => 'Animasi',
            'tata boga' => 'Tata Boga',
            'tb' => 'Tata Boga',
            'akuntansi' => 'Akuntansi',
            'pemasaran' => 'Pemasaran',
            'bdp' => 'Bisnis Daring dan Pemasaran',
            'otkp' => 'Otomatisasi Tata Kelola Perkantoran',
            'atph' => 'Agribisnis Tanaman Pangan dan Hortikultura',
        ];

        foreach ($majorMap as $key => $label) {
            if (str_contains($q, $key)) {
                $entities['majors'][] = $label;
            }
        }

        // Mata pelajaran
        $subjects = ['matematika', 'ipa', 'ips', 'bahasa indonesia', 'b indo', 'bahasa inggris', 'b inggris',
            'fisika', 'kimia', 'biologi', 'sejarah', 'geografi', 'ekonomi', 'sosiologi',
            'agama', 'pkn', 'ppkn', 'olahraga', 'pjok', 'produktif', 'informatika'];

        foreach ($subjects as $s) {
            if (str_contains($q, str_replace(' ', '', $s))) {
                $entities['subjects'][] = $s;
            }
        }

        // Ekstrakurikuler
        $extras = ['pramuka', 'paskibra', 'osis', 'rohis', 'pmr', 'futsal', 'basket', 'voli', 'silat', 'teater', 'band', 'paduan suara'];

        foreach ($extras as $e) {
            if (str_contains($q, $e)) {
                $entities['extracurriculars'][] = $e;
            }
        }

        // Angka
        if (preg_match_all('/\d+/', $question, $m)) {
            $entities['numbers'] = array_values(array_unique($m[0]));
        }

        // Lokasi
        $locations = ['ciawi', 'bogor', 'jagorawi', 'cilengsi', 'sukabumi', 'depok'];

        foreach ($locations as $l) {
            if (str_contains($q, $l)) {
                $entities['locations'][] = $l;
            }
        }

        // Hapus duplikat
        foreach ($entities as $key => $value) {
            if (is_array($value)) {
                $entities[$key] = array_values(array_unique($value));
            }
        }

        return $entities;
    }
}