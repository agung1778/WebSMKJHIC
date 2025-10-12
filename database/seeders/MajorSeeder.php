<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Major;
use Illuminate\Support\Facades\DB;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        Major::create([
            'name' => 'Pemrograman Perangkat Lunak Dan Gim',
            'description' => 'Konsentrasi Keahlian Rekayasa Perangkat Lunak atau yang sering dikenal dengan singkatan RPL. Konsentrasi keahlian ini fokus mempelajari proses penuh pengembangan perangkat lunak (software) dan game (gim) yaitu dari coding, desain antarmuka (UI/UX), algoritma hingga manajemen proyek. Jurusan ini juga terus diperbarui agar selaras dengan tren industri 4.0 dan society 5.0 dan menyesuaikan kurikulum dengan kebutuhan dunia usaha dan dunia industri (DU/DI).',
            'tag' => '', // Dikosoongkan
            'advantage' => '', // Dikosoongkan
            'image' => '', // Dikosoongkan
            'logo' => '', // Dikosoongkan
            'competency_head' => '', // Dikosoongkan
            'competency_head_photo' => '', // Dikosoongkan
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Animasi',
            'description' => 'Animasi merupakan salah satu Kompetensi Keahlian dari Program Keahlian Seni Rupa pada Bidang Keahlian: Seni dan Industri Kreatif. Selama masa pembelajaran 3 tahun, siswa akan dibekali dengan mata pelajaran yang terdiri dari Muatan Nasional, Muatan Kewilayahan, dan Muatan Peminatan Kejuruan. Muatan Peminatan Kejuruan terdiri dari Dasar Bidang Keahlian, Dasar Program Keahlian, dan Kompetensi Keahlian.',
            'tag' => '', // Dikosoongkan
            'advantage' => '', // Dikosoongkan
            'image' => '', // Dikosoongkan
            'logo' => '', // Dikosoongkan
            'competency_head' => '', // Dikosoongkan
            'competency_head_photo' => '', // Dikosoongkan
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Teknik Jaringan Komputer dan Telekomunikasi',
            'description' => 'Teknik Komputer dan Jaringan (TKJ) adalah salah satu konsentrasi keahlian di Sekolah Menengah Kejuruan (SMK) yang fokus pada penguasaan keterampilan di bidang teknologi informasi dan komunikasi, khususnya pengelolaan komputer, jaringan, dan sistem pendukungnya.',
            'tag' => '', // Dikosoongkan
            'advantage' => '', // Dikosoongkan
            'image' => '', // Dikosoongkan
            'logo' => '', // Dikosoongkan
            'competency_head' => '', // Dikosoongkan
            'competency_head_photo' => '', // Dikosoongkan
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Desain Komunikasi Visual',
            'description' => 'Mempelajari seni dan teknik komunikasi visual melalui media grafis, ilustrasi, dan desain digital untuk menyampaikan pesan yang efektif.',
            'tag' => '', // Dikosoongkan
            'advantage' => '', // Dikosoongkan
            'image' => '', // Dikosoongkan
            'logo' => '', // Dikosoongkan
            'competency_head' => '', // Dikosoongkan
            'competency_head_photo' => '', // Dikosoongkan
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Manajemen Perkantoran',
            'description' => 'Mempelajari pengelolaan administrasi perkantoran, pelayanan publik, dan penggunaan teknologi informasi dalam kegiatan perkantoran.',
            'tag' => '', // Dikosoongkan
            'advantage' => '', // Dikosoongkan
            'image' => '', // Dikosoongkan
            'logo' => '', // Dikosoongkan
            'competency_head' => '', // Dikosoongkan
            'competency_head_photo' => '', // Dikosoongkan
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Layanan Perbankan Syariah',
            'description' => 'Mempelajari sistem perbankan berbasis prinsip syariah, mencakup layanan keuangan, manajemen dana, dan produk bank syariah.',
            'tag' => '', // Dikosoongkan
            'advantage' => '', // Dikosoongkan
            'image' => '', // Dikosoongkan
            'logo' => '', // Dikosoongkan
            'competency_head' => '', // Dikosoongkan
            'competency_head_photo' => '', // Dikosoongkan
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Bisnis Retail',
            'description' => 'Jurusan yang berfokus pada strategi penjualan, pelayanan pelanggan, dan pengelolaan bisnis ritel modern.',
            'tag' => '', // Dikosoongkan
            'advantage' => '', // Dikosoongkan
            'image' => '', // Dikosoongkan
            'logo' => '', // Dikosoongkan
            'competency_head' => '', // Dikosoongkan
            'competency_head_photo' => '', // Dikosoongkan
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Akuntansi',
            'description' => 'Mempelajari pencatatan, pengolahan, dan pelaporan transaksi keuangan untuk berbagai jenis organisasi.',
            'tag' => '', // Dikosoongkan
            'advantage' => '', // Dikosoongkan
            'image' => '', // Dikosoongkan
            'logo' => '', // Dikosoongkan
            'competency_head' => '', // Dikosoongkan
            'competency_head_photo' => '', // Dikosoongkan
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Desain Permodelan Busana',
            'description' => 'Mempelajari teknik perancangan busana, pemilihan bahan, pola, serta pembuatan pakaian yang sesuai tren industri fashion.',
            'tag' => '', // Dikosoongkan
            'advantage' => '', // Dikosoongkan
            'image' => '', // Dikosoongkan
            'logo' => '', // Dikosoongkan
            'competency_head' => '', // Dikosoongkan
            'competency_head_photo' => '', // Dikosoongkan
            'publisher' => 'Admin Utama',
        ]);
    }
}
