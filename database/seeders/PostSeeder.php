<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Major;
use App\Models\News;


class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Nonaktifkan sementara FK
        DB::table('majors')->truncate();
        DB::table('news')->truncate();
        DB::table('partners')->truncate();
        DB::table('testimonials')->truncate();

        Major::create([
            'name' => 'Pemrograman Perangkat Lunak Dan Gim',
            'description' => 'Jurusan yang fokus pada pengembangan perangkat lunak, termasuk perancangan, pembuatan, dan pemeliharaan aplikasi.',
            'image' => 'major_images/rpl_major.jpg',
            'competency_head' => '',
            'competency_head_photo' => '',
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Animasi',
            'description' => 'Jurusan yang mempelajari teknik pembuatan animasi dua dimensi dan tiga dimensi, mulai dari konsep, desain karakter, hingga produksi video animasi.',
            'image' => 'major_images/animasi_major.jpg',
            'competency_head' => '',
            'competency_head_photo' => '',
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Teknik Jaringan Komputer dan Telekomunikasi',
            'description' => 'Fokus pada instalasi, konfigurasi, dan pemeliharaan jaringan komputer serta sistem komunikasi data.',
            'image' => 'major_images/tjkt_major.jpg',
            'competency_head' => '',
            'competency_head_photo' => '',
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Desain Komunikasi Visual',
            'description' => 'Mempelajari seni dan teknik komunikasi visual melalui media grafis, ilustrasi, dan desain digital untuk menyampaikan pesan yang efektif.',
            'image' => 'major_images/dkv_major.jpg',
            'competency_head' => '',
            'competency_head_photo' => '',
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Manajemen Perkantoran',
            'description' => 'Mempelajari pengelolaan administrasi perkantoran, pelayanan publik, dan penggunaan teknologi informasi dalam kegiatan perkantoran.',
            'image' => 'major_images/mp_major.jpg',
            'competency_head' => '',
            'competency_head_photo' => '',
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Layanan Perbankan Syariah',
            'description' => 'Mempelajari sistem perbankan berbasis prinsip syariah, mencakup layanan keuangan, manajemen dana, dan produk bank syariah.',
            'image' => 'major_images/lps_major.jpg',
            'competency_head' => '',
            'competency_head_photo' => '',
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Bisnis Retail',
            'description' => 'Jurusan yang berfokus pada strategi penjualan, pelayanan pelanggan, dan pengelolaan bisnis ritel modern.',
            'image' => 'major_images/br_major.jpg',
            'competency_head' => '',
            'competency_head_photo' => '',
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Akuntansi',
            'description' => 'Mempelajari pencatatan, pengolahan, dan pelaporan transaksi keuangan untuk berbagai jenis organisasi.',
            'image' => 'major_images/akuntansi_major.jpg',
            'competency_head' => '',
            'competency_head_photo' => '',
            'publisher' => 'Admin Utama',
        ]);

        Major::create([
            'name' => 'Desain Permodelan Busana',
            'description' => 'Mempelajari teknik perancangan busana, pemilihan bahan, pola, serta pembuatan pakaian yang sesuai tren industri fashion.',
            'image' => 'major_images/busana_major.jpg',
            'competency_head' => '',
            'competency_head_photo' => '',
            'publisher' => 'Admin Utama',
        ]);

        DB::table('news')->insert([
            [
                'title' => 'SMK Amaliah Gelar Pameran Karya Siswa 2025',
                'image' => 'news_images/pameran_karya.jpg',
                'description' => 'SMK Amaliah sukses mengadakan Pameran Karya Siswa 2025 yang menampilkan inovasi dari berbagai jurusan seperti RPL, DKV, dan Animasi. Acara ini menjadi wadah bagi siswa untuk menunjukkan kreativitas dan kemampuan mereka di bidang teknologi dan desain.',
                'publisher' => 'Humas SMK Amaliah',
                'date_published' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Tim RPL SMK Amaliah Juara 1 Lomba Aplikasi Nasional',
                'image' => 'news_images/rpl_juara.jpg',
                'description' => 'Siswa jurusan Pemrograman Perangkat Lunak dan Gim SMK Amaliah berhasil meraih juara 1 dalam Lomba Aplikasi Nasional berkat aplikasi inovatif bertema pendidikan digital.',
                'publisher' => 'Humas SMK Amaliah',
                'date_published' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Kegiatan Bakti Sosial SMK Amaliah di Lingkungan Sekitar',
                'image' => 'news_images/baksos_amaliah.jpg',
                'description' => 'Dalam rangka memperingati Hari Pahlawan, SMK Amaliah mengadakan kegiatan bakti sosial dengan membagikan sembako kepada masyarakat sekitar sekolah.',
                'publisher' => 'OSIS SMK Amaliah',
                'date_published' => Carbon::now()->subDays(12),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Workshop Desain Grafis untuk Jurusan DKV dan Animasi',
                'image' => 'news_images/workshop_dkv.jpg',
                'description' => 'Jurusan DKV dan Animasi SMK Amaliah mengadakan workshop desain grafis bersama praktisi industri kreatif untuk meningkatkan kemampuan siswa dalam dunia digital art dan motion design.',
                'publisher' => 'Jurusan DKV SMK Amaliah',
                'date_published' => Carbon::now()->subDays(15),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'SMK Amaliah Luncurkan Program Digitalisasi Administrasi Sekolah',
                'image' => 'news_images/digitalisasi_amaliah.jpg',
                'description' => 'SMK Amaliah resmi meluncurkan program digitalisasi administrasi sekolah yang dikembangkan oleh siswa jurusan RPL, guna mempermudah proses pengelolaan data akademik dan kehadiran.',
                'publisher' => 'Admin Utama',
                'date_published' => Carbon::now()->subDays(20),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);


        DB::table('partners')->insert([
            [
                'name' => 'PT Teknologi Nusantara',
                'description' => 'Perusahaan teknologi yang bergerak di bidang pengembangan perangkat lunak, website, dan solusi AI.',
                'logo' => 'partner_logos/teknologi_nusantara.png',
                'sector' => 'Teknologi',
                'city' => 'Jakarta',
                'company_contact' => 'info@teknologinusantara.id',
                'publisher' => 'Admin',
                'partnership_date' => Carbon::now()->subMonths(3),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'CV Edukasi Maju',
                'description' => 'Lembaga pendidikan yang fokus pada pelatihan digital skill untuk pelajar dan mahasiswa.',
                'logo' => 'partner_logos/edukasi_maju.png',
                'sector' => 'Pendidikan',
                'city' => 'Bandung',
                'company_contact' => 'kontak@edukasimaju.com',
                'publisher' => 'Admin',
                'partnership_date' => Carbon::now()->subMonths(6),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'PT Energi Hijau Lestari',
                'description' => 'Mitra di bidang energi terbarukan yang mendukung program pemerintah menuju energi ramah lingkungan.',
                'logo' => 'partner_logos/energi_hijau.png',
                'sector' => 'Energi',
                'city' => 'Surabaya',
                'company_contact' => 'contact@energilestari.co.id',
                'publisher' => 'Admin',
                'partnership_date' => Carbon::now()->subYear(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bank Syariah Amanah',
                'description' => 'Lembaga keuangan syariah yang bekerja sama dengan SMK Amaliah untuk praktik kerja lapangan siswa jurusan Layanan Perbankan Syariah.',
                'logo' => 'partner_logos/bank_syariah.png',
                'sector' => 'Perbankan',
                'city' => 'Depok',
                'company_contact' => 'info@banksyariahamanah.co.id',
                'publisher' => 'Admin',
                'partnership_date' => Carbon::now()->subMonths(9),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Studio Kreasi Visual',
                'description' => 'Perusahaan desain grafis dan animasi yang menjadi mitra jurusan DKV dan Animasi dalam program magang industri.',
                'logo' => 'partner_logos/studio_kreasi.png',
                'sector' => 'Kreatif',
                'city' => 'Bogor',
                'company_contact' => 'hello@studiokreasi.id',
                'publisher' => 'Admin',
                'partnership_date' => Carbon::now()->subMonths(4),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'PT Busana Modestindo',
                'description' => 'Perusahaan fashion lokal yang bekerja sama dengan jurusan Desain Permodelan Busana untuk pelatihan dan magang siswa.',
                'logo' => 'partner_logos/busana_modestindo.png',
                'sector' => 'Fashion',
                'city' => 'Tangerang',
                'company_contact' => 'info@modestindo.co.id',
                'publisher' => 'Admin',
                'partnership_date' => Carbon::now()->subMonths(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'PT Smart Retail Indonesia',
                'description' => 'Perusahaan yang bergerak di bidang bisnis retail modern dan menjadi tempat praktik kerja siswa jurusan Bisnis Retail.',
                'logo' => 'partner_logos/smart_retail.png',
                'sector' => 'Retail',
                'city' => 'Jakarta',
                'company_contact' => 'partner@smartretail.co.id',
                'publisher' => 'Admin',
                'partnership_date' => Carbon::now()->subMonths(8),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'AutoTech Garage',
                'description' => 'Bengkel dan startup otomotif yang mendukung kegiatan siswa jurusan Teknik Jaringan dan Teknologi Elektronik.',
                'logo' => 'partner_logos/autotech_garage.png',
                'sector' => 'Otomotif',
                'city' => 'Bekasi',
                'company_contact' => 'contact@autotechgarage.com',
                'publisher' => 'Admin',
                'partnership_date' => Carbon::now()->subMonths(5),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Amaliah Digital Center',
                'description' => 'Inkubator internal SMK Amaliah yang menjadi wadah kolaborasi antara guru, siswa, dan dunia industri di bidang teknologi informasi.',
                'logo' => 'partner_logos/amaliah_digital.png',
                'sector' => 'Pendidikan dan Teknologi',
                'city' => 'Bogor',
                'company_contact' => 'adc@smkamaliah.sch.id',
                'publisher' => 'Admin',
                'partnership_date' => Carbon::now()->subWeeks(2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);


        DB::table('testimonials')->insert([
            [
                'name' => 'Andi Pratama',
                'alumni_year' => '2019',
                'major_id' => 1, // Pemrograman Perangkat Lunak dan Gim
                'description' => 'Belajar di SMK Amaliah membuat saya siap masuk dunia kerja. Banyak praktik langsung yang bermanfaat.',
                'photo' => 'testimonials/andi_pratama.jpg',
                'publisher' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Siti Aisyah',
                'alumni_year' => '2020',
                'major_id' => 2, // Animasi
                'description' => 'Guru-guru sangat mendukung dan suasana belajarnya menyenangkan. Saya jadi percaya diri menekuni dunia animasi.',
                'photo' => 'testimonials/siti_aisyah.jpg',
                'publisher' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Budi Santoso',
                'alumni_year' => '2018',
                'major_id' => 3, // Teknik Jaringan Komputer dan Telekomunikasi
                'description' => 'Saya mendapatkan banyak pengalaman teknis yang langsung terpakai di tempat kerja.',
                'photo' => 'testimonials/budi_santoso.jpg',
                'publisher' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Nabila Rahma',
                'alumni_year' => '2021',
                'major_id' => 4, // DKV
                'description' => 'Jurusan DKV-nya keren! Saya bisa belajar desain digital sekaligus menyalurkan hobi menggambar.',
                'photo' => 'testimonials/nabila_rahma.jpg',
                'publisher' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Rizky Maulana',
                'alumni_year' => '2019',
                'major_id' => 5, // Manajemen Perkantoran
                'description' => 'Pelajaran administrasi dan komunikasi di SMK Amaliah sangat membantu karier saya di dunia perkantoran.',
                'photo' => 'testimonials/rizky_maulana.jpg',
                'publisher' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Nurul Fadhilah',
                'alumni_year' => '2020',
                'major_id' => 6, // Layanan Perbankan Syariah
                'description' => 'Berkat bimbingan guru dan praktik magang, saya langsung diterima di bank setelah lulus.',
                'photo' => 'testimonials/nurul_fadhilah.jpg',
                'publisher' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Dian Setiawan',
                'alumni_year' => '2018',
                'major_id' => 7, // Bisnis Retail
                'description' => 'Pengalaman magang di toko retail membuat saya paham langsung cara kerja bisnis modern.',
                'photo' => 'testimonials/dian_setiawan.jpg',
                'publisher' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Fitri Handayani',
                'alumni_year' => '2021',
                'major_id' => 8, // Akuntansi
                'description' => 'Pelajaran akuntansi di SMK Amaliah mudah dipahami dan langsung bisa diterapkan di dunia kerja.',
                'photo' => 'testimonials/fitri_handayani.jpg',
                'publisher' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Laila Putri',
                'alumni_year' => '2019',
                'major_id' => 9, // Desain Permodelan Busana
                'description' => 'Belajar desain busana di SMK Amaliah memberi saya dasar kuat untuk membuka usaha sendiri.',
                'photo' => 'testimonials/laila_putri.jpg',
                'publisher' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);


        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Aktifkan lagi FK
    }
}
