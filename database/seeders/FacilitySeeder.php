<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('facilities')->truncate();
        DB::table('facilities')->insert([
            // Fasilitas Umum & Pendukung
            [
                'name' => 'Taman Mini',
                'description' => 'Area taman hijau untuk bersantai dan kegiatan luar ruangan siswa.',
                'image' => 'taman-mini.jpg',
                'type' => 'Umum',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Ruang Musik',
                'description' => 'Studio musik kedap suara dengan berbagai instrumen untuk pengembangan bakat siswa.',
                'image' => 'ruang-musik.jpg',
                'type' => 'Umum',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Aula SMK',
                'description' => 'Aula serbaguna untuk acara besar, seminar, dan pertemuan seluruh warga sekolah.',
                'image' => 'aula-smk.jpg',
                'type' => 'Umum',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Aula Gedung C',
                'description' => 'Aula tambahan di Gedung C untuk kegiatan skala kecil hingga menengah.',
                'image' => 'aula-gedung-c.jpg',
                'type' => 'Umum',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Mushola',
                'description' => 'Tempat ibadah yang nyaman untuk siswa dan guru yang beragama Islam.',
                'image' => 'mushola.jpg',
                'type' => 'Umum',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Ruang Tunggu',
                'description' => 'Area tunggu yang representatif dan nyaman bagi tamu dan orang tua siswa.',
                'image' => 'ruang-tunggu.jpg',
                'type' => 'Umum',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Ruang Podcast',
                'description' => 'Studio mini untuk produksi konten audio dan siaran podcast kreatif siswa.',
                'image' => 'ruang-podcast.jpg',
                'type' => 'Umum',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Fasilitas Olahraga
            [
                'name' => 'Lapangan Futsal',
                'description' => 'Lapangan futsal standar untuk kegiatan olahraga dan ekstrakurikuler.',
                'image' => 'lapangan-futsal.jpg',
                'type' => 'Olahraga',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Lapangan Basket',
                'description' => 'Lapangan bola basket outdoor untuk menunjang kegiatan olahraga siswa.',
                'image' => 'lapangan-basket.jpg',
                'type' => 'Olahraga',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Kolam Renang',
                'description' => 'Fasilitas kolam renang untuk pembelajaran dan ekstrakurikuler olahraga air.',
                'image' => 'kolam-renang.jpg',
                'type' => 'Olahraga',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Ruang Gym',
                'description' => 'Pusat kebugaran dengan peralatan fitness lengkap untuk menjaga kesehatan siswa.',
                'image' => 'ruang-gym.jpg',
                'type' => 'Olahraga',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Fasilitas Kewirausahaan
            [
                'name' => 'Mini Bank',
                'description' => 'Fasilitas simulasi perbankan untuk praktik siswa jurusan perbankan dan akuntansi.',
                'image' => 'mini-bank.jpg',
                'type' => 'Kewirausahaan',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'AM Mart',
                'description' => 'Minimarket sekolah sebagai sarana praktik kewirausahaan dan pemasaran.',
                'image' => 'am-mart.jpg',
                'type' => 'Kewirausahaan',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Fasilitas Akademik & Literasi
            [
                'name' => 'Perpustakaan',
                'description' => 'Pusat sumber belajar dengan ribuan koleksi buku, jurnal, dan referensi digital.',
                'image' => 'perpustakaan.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Pojok Baca',
                'description' => 'Sudut-sudut literasi yang nyaman di berbagai lokasi sekolah untuk meningkatkan minat baca.',
                'image' => 'pojok-baca.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Ruang P5',
                'description' => 'Ruang khusus untuk mendukung implementasi Proyek Penguatan Profil Pelajar Pancasila.',
                'image' => 'ruang-p5.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Teaching Factory',
                'description' => 'Fasilitas pembelajaran berbasis produksi yang mengadopsi standar industri.',
                'image' => 'teaching-factory.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Laboratorium PPLG',
                'description' => 'Lab untuk praktik jurusan Pengembangan Perangkat Lunak dan Gim (PPLG).',
                'image' => 'lab-pplg.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Laboratorium Animasi',
                'description' => 'Lab dengan perangkat keras dan lunak canggih untuk produksi animasi.',
                'image' => 'lab-animasi.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Laboratorium TJKT',
                'description' => 'Lab untuk praktik jurusan Teknik Jaringan Komputer dan Telekomunikasi (TJKT).',
                'image' => 'lab-tjkt.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Laboratorium DKV',
                'description' => 'Lab untuk praktik jurusan Desain Komunikasi Visual (DKV).',
                'image' => 'lab-dkv.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Laboratorium MP',
                'description' => 'Lab untuk praktik jurusan Manajemen Perkantoran (MP).',
                'image' => 'lab-mp.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Laboratorium LPS',
                'description' => 'Lab untuk praktik jurusan Layanan Perbankan Syariah (LPS).',
                'image' => 'lab-lps.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Laboratorium BR',
                'description' => 'Lab untuk praktik jurusan Broadcasting (BR).',
                'image' => 'lab-br.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Laboratorium DPB',
                'description' => 'Lab untuk praktik jurusan Desain dan Produksi Busana (DPB).',
                'image' => 'lab-dpb.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Laboratorium AK',
                'description' => 'Lab untuk praktik jurusan Akuntansi Keuangan (AK).',
                'image' => 'lab-ak.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Laboratorium Fiber Optik',
                'description' => 'Lab khusus untuk praktik instalasi dan pemeliharaan jaringan fiber optik.',
                'image' => 'lab-fiber-optik.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
