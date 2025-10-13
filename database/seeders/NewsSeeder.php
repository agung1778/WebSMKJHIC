<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('news')->insert([
            [
                'title' => 'Wujudkan Sinergi dengan Orang Tua/Wali Murid, SMK Amaliah 1 dan 2 Ciawi Gelar Sosialisasi Program Tahun Ajaran 2025-2026',
                'image' => 'news_images/file.jpg',
                'description' => '-',
                'publisher' => 'Admin',
                'date_published' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'SMK Amaliah 1 dan 2 Ciawi Terapkan Sistem Paperless Test Online (PTO) dalam Pelaksanaan Sumatif Tengah Semester',
                'image' => 'news_images/file.jpg',
                'description' => '-',
                'publisher' => 'Admin',
                'date_published' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Lantik Anggota Baru, SMK Amaliah 1 dan 2 Ciawi Laksanakan Penerimaan Tamu Ambalan',
                'image' => 'news_images/file.jpg',
                'description' => '-',
                'publisher' => 'Admin',
                'date_published' => Carbon::now()->subDays(12),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Bekali Pemahaman Budaya Kerja, SMK Amaliah 2 hadirkan Guru Tamu dari Industri',
                'image' => 'news_images/file.jpg',
                'description' => '-',
                'publisher' => 'Admin',
                'date_published' => Carbon::now()->subDays(15),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'SMK Amaliah 1 dan 2 Ciawi Gelar Santunan Yatim dan Dhuafa dalam Rangka Memperingati Tahun Baru Islam 1447 H',
                'image' => 'news_images/file.jpg',
                'description' => '-',
                'publisher' => 'Admin',
                'date_published' => Carbon::now()->subDays(20),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Tingkatkan Wawasan Manajemen Perkantoran, SMK Amaliah 2 lakukan Kunjungan ke ANRI',
                'image' => 'news_images/file.jpg',
                'description' => '-',
                'publisher' => 'Admin',
                'date_published' => Carbon::now()->subDays(20),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
             [
                'title' => 'Jalin Kerja Sama Internasional, SMK Amaliah 1 dan 2 Ciawi Bogor Gaet Dua Sekolah di Thailand',
                'image' => 'news_images/file.jpg',
                'description' => '-',
                'publisher' => 'Admin',
                'date_published' => Carbon::now()->subDays(20),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
             [
                'title' => 'SMK Amaliah Kenalkan Dunia Pasar Modal Lewat Kunjungan Edukatif ke BEI dan Museum Bank Indonesia',
                'image' => 'news_images/file.jpg',
                'description' => '-',
                'publisher' => 'Admin',
                'date_published' => Carbon::now()->subDays(20),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
