<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TiktokPost;

class TiktokPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (TiktokPost::count() >= 9) {
            return;
        }

        $videoIds = [
            '7701197482784541973',
            '7700908599970647316',
            '7698562646173355272',
            '7697123456789012345',
            '7696234567890123456',
            '7695345678901234567',
            '7694456789012345678',
            '7693567890123456789',
            '7692678901234567890',
        ];

        $covers = [
            'assets/image/DroneView.jpg',
            'assets/image/Classmeet.JPG',
            'assets/image/17Agustus.JPG',
            'assets/image/Stackup.png',
            'assets/image/Brosur.jpeg',
            'assets/image/Classmeet.JPG',
            'assets/image/DroneView.jpg',
            'assets/image/17Agustus.JPG',
            'assets/image/Stackup.png',
        ];

        $captions = [
            'Keseruan siswa-siswi SMK Amaliah 1 & 2 di kampus tercinta! 🎓✨',
            'Momen spektakuler Classmeeting & ajang unjuk bakat! 🏆🔥',
            'Upacara Hari Kemerdekaan: Jiwa nasionalisme generasi vokasi! 🇮🇩',
            'Praktikum Kejuruan: Kreativitas tanpa batas anak PPLG & Animasi! 💻🎨',
            'Penerimaan Peserta Didik Baru (PPDB) SMK Amaliah: Ayo gabung! 🎒🚀',
            'Behind the scenes kegiatan seru di laboratorium sekolah 🎬',
            'Eksplorasi fasilitas modern dan kampus asri SMK Amaliah 🏢🌿',
            'Penampilan ekstrakurikuler favorit siswa: Penuh semangat! 🌟',
            'Vokasi unggul, siap kerja, kuliah, dan wirausaha sukses! 💼🤝',
        ];

        foreach ($videoIds as $i => $id) {
            TiktokPost::create([
                'caption'   => $captions[$i] ?? 'Video TikTok SMK Amaliah',
                'path'      => $covers[$i] ?? 'assets/image/DroneView.jpg',
                'video_url' => 'https://www.tiktok.com/@smk.amaliah/video/' . $id,
                'video_id'  => $id,
                'is_active' => true,
            ]);
        }
    }
}
