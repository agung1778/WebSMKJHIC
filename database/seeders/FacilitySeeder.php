<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('facilities')->truncate();
        DB::table('facilities')->insert([
            [
                'name' => 'Laboratorium Komputer',
                'description' => 'Laboratorium dengan fasilitas komputer modern untuk mendukung pembelajaran teknologi informasi.',
                'image' => 'lab-komputer.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Perpustakaan Digital',
                'description' => 'Perpustakaan dengan koleksi buku fisik dan digital yang dapat diakses oleh seluruh siswa dan guru.',
                'image' => 'perpustakaan.jpg',
                'type' => 'Umum',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Lapangan Serbaguna',
                'description' => 'Lapangan outdoor untuk kegiatan olahraga dan acara sekolah.',
                'image' => 'lapangan.jpg',
                'type' => 'Non-Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Ruang Multimedia',
                'description' => 'Ruang khusus dengan perangkat multimedia lengkap untuk presentasi dan kegiatan kreatif siswa.',
                'image' => 'multimedia.jpg',
                'type' => 'Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Kantin Sehat',
                'description' => 'Kantin dengan berbagai pilihan makanan sehat dan bersih untuk seluruh warga sekolah.',
                'image' => 'kantin.jpg',
                'type' => 'Non-Akademik',
                'publisher' => 'Admin SMK',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
