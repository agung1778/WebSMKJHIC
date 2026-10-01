<?php

namespace App\AI\Knowledge;

use App\Models\AiIntent;
use App\Models\AiSynonym;

/**
 * Menyiapkan data bootstrap untuk tabel ai_intents dan ai_synonyms.
 *
 * Idempotent: aman dipanggil setiap kali index dibangun ulang. Tujuannya
 * agar admin dapat menambah/menyesuaikan kamus lewat database tanpa perlu
 * menyentuh kode.
 */
class DefaultSeeder
{
    /**
     * Sinonym menghadap dua arah (a<->b).
     *
     * @var array<int,array{0:string,1:string,2?:float}>
     */
    public const SYNONYMS = [
        ['daftar', 'pendaftaran'],
        ['pendaftaran', 'spmb'],
        ['spmb', 'ppdb'],
        ['ppdb', 'admisi'],
        ['jurusan', 'prodi'],
        ['prodi', 'program studi'],
        ['program studi', 'jurusan'],
        ['guru', 'pengajar'],
        ['pengajar', 'dosen'],
        ['mapel', 'mata pelajaran'],
        ['pelajaran', 'mapel'],
        ['ekstrakurikuler', 'ekskul'],
        ['ekskul', 'kegiatan'],
        ['alamat', 'lokasi'],
        ['lokasi', 'tempat'],
        ['wa', 'whatsapp'],
        ['whatsapp', 'nomor wa'],
        ['telepon', 'telp'],
        ['nama', 'nm'],
        ['jumlah', 'banyak'],
        ['banyak', 'jumlah'],
        ['fasilitas', 'sarana'],
        ['sarana', 'fasilitas'],
        ['prestasi', 'juara'],
        ['kegiatan', 'acara'],
        ['berita', 'informasi'],
        ['jam pelajaran', 'jam belajar'],
        ['biaya', 'bayar'],
        ['beasiswa', 'bantuan biaya'],
        ['mata pelajaran', 'mapel'],
        ['kepala sekolah', 'kepsek'],
        ['ranking', 'peringkat'],
        ['nilai', 'nilai rapor'],

        // Varian ejaan bahasa Inggris yang sering diketik pengguna.
        ['ekstrakurikuler', 'extracurricular'],
        ['jurusan', 'major'],
        ['sekolah', 'school'],
        ['guru', 'teacher'],
        ['pengajar', 'teacher'],
        ['fasilitas', 'facility'],
        ['sarana', 'facility'],
        ['pendaftaran', 'registration'],
        ['biaya', 'tuition'],
        ['uang pangkal', 'tuition fee'],
        ['alamat', 'address'],
        ['telepon', 'phone'],
        ['kontak', 'contact'],
        ['informasi', 'information'],
        ['daftar', 'list'],
        ['beasiswa', 'scholarship'],
        ['mata pelajaran', 'subject'],
        ['pramuka', 'scout'],
        ['perpustakaan', 'library'],
        ['kantin', 'cafeteria'],
        ['laboratorium', 'lab'],
        ['kegiatan', 'event'],
        ['mitra', 'partner'],
        ['prestasi', 'achievement'],
        ['kurikulum', 'curriculum'],
    ];

    public function run(): void
    {
        $this->seedSynonyms();
        $this->seedIntents();
    }

    protected function seedSynonyms(): void
    {
        foreach (self::SYNONYMS as $synonym) {
            $term = mb_strtolower(trim($synonym[0]));
            $word = mb_strtolower(trim($synonym[1]));
            $weight = (float) ($synonym[2] ?? 1.0);

            if ($term === '' || $word === '') {
                continue;
            }

            AiSynonym::updateOrCreate(
                ['term' => $term, 'synonym' => $word],
                ['weight' => $weight]
            );
        }
    }

    protected function seedIntents(): void
    {
        $intents = [
            ['name' => 'greeting', 'description' => 'Sapaan pembuka', 'patterns' => ['halo', 'hai', 'selamat pagi', 'selamat siang', 'assalamualaikum']],
            ['name' => 'thanks', 'description' => 'Ucapan terima kasih', 'patterns' => ['terima kasih', 'makasih']],
            ['name' => 'bye', 'description' => 'Penutup percakapan', 'patterns' => ['sampai jumpa', 'dadah', 'wassalam']],
            ['name' => 'spmb', 'description' => 'Informasi pendaftaran', 'patterns' => ['spmb', 'ppdb', 'cara daftar', 'pendaftaran', 'daftar']],
            ['name' => 'major_list', 'description' => 'Daftar jurusan', 'patterns' => ['jurusan apa aja', 'daftar jurusan', 'pilihan jurusan']],
            ['name' => 'major_detail', 'description' => 'Detail satu jurusan', 'patterns' => ['tentang jurusan', 'deskripsi jurusan']],
            ['name' => 'teacher', 'description' => 'Informasi guru', 'patterns' => ['guru', 'pengajar', 'siapa yang mengajar']],
            ['name' => 'facility', 'description' => 'Fasilitas sekolah', 'patterns' => ['fasilitas', 'lab', 'perpustakaan', 'lapangan']],
            ['name' => 'extracurricular', 'description' => 'Ekstrakurikuler', 'patterns' => ['ekstrakurikuler', 'ekskul', 'pramuka', 'paskibra']],
            ['name' => 'achievement', 'description' => 'Prestasi sekolah', 'patterns' => ['prestasi', 'juara', 'pemenang']],
            ['name' => 'news', 'description' => 'Berita dan kegiatan', 'patterns' => ['berita terbaru', 'kegiatan sekolah']],
            ['name' => 'partner', 'description' => 'Mitra industri', 'patterns' => ['mitra industri', 'perusahaan mitra']],
            ['name' => 'testimonial', 'description' => 'Testimoni alumni', 'patterns' => ['testimoni', 'opini alumni']],
            ['name' => 'program', 'description' => 'Program sekolah', 'patterns' => ['program sekolah', 'kegiatan unggulan']],
            ['name' => 'location', 'description' => 'Alamat dan lokasi', 'patterns' => ['alamat sekolah', 'lokasi sekolah', 'dimana sekolah']],
            ['name' => 'contact', 'description' => 'Kontak sekolah', 'patterns' => ['nomor telepon', 'kontak', 'hubungi']],
            ['name' => 'statistic', 'description' => 'Statistik siswa', 'patterns' => ['jumlah siswa', 'banyak siswa']],
            ['name' => 'vision_mission', 'description' => 'Visi dan misi', 'patterns' => ['visi misi', 'visi sekolah', 'misi sekolah']],
            ['name' => 'history', 'description' => 'Sejarah sekolah', 'patterns' => ['sejarah sekolah', 'sejarah smk amaliah']],
            ['name' => 'about', 'description' => 'Profil sekolah', 'patterns' => ['tentang sekolah', 'profil sekolah']],
        ];

        foreach ($intents as $intent) {
            AiIntent::updateOrCreate(
                ['name' => $intent['name']],
                [
                    'description' => $intent['description'],
                    'patterns' => $intent['patterns'],
                    'is_active' => true,
                ]
            );
        }
    }
}