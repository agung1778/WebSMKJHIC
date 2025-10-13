<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data lama untuk menghindari duplikasi saat seeding ulang
        DB::table('partners')->truncate();

        $partnerNames = [
            '21 Computer', 'Agro Wisata Gunung Mas', 'Akbid Prima Husada', 'Ark Studio',
            'Arsip Perpustakaan Kota Bogor', 'Badan Kepegawaian Negara',
            'Badan Pengelolaan Keuangan dan Aset Daerah Kab. Bogor',
            'Bagian Direktorat Sumber Daya Insani (DSDI) Universitas Djuanda',
            'Balai Besar Perakitan dan Modernisasi Veteriner',
            'Balai Besar Standardisasi dan Pelayanan Jasa Industri Agro (BBSPJIA)',
            'Balai Pengujian Standar Instrumen Unggas dan Aneka Ternak', 'Bank DKI Syariah Bogor',
            'Bank Syariah Indonesia KC Bogor Pajajaran Sukasari', 'Bank Syariah Indonesia KCP Bantarjati',
            'Bawaslu Kota Bogor', 'BBPMKP', 'Best Computer', 'Bina Sena Maritime Training Course',
            'BJB Syariah KCP Jembatan Merah', 'BMKG Citeko', 'Bogor Studio', 'BPKP',
            'BPR Dana Mandiri', 'BPRS As Salam', 'Cimory Dairyland Riverside', 'Cinta Quran',
            'CV Karya Winazar', 'Denta Studio’s Mode', 'Desa Wisata Ciderum',
            'Diklat Reserse Lemdiklat POLRI', 'Dinas Perhubungan Kota Bogor', 'Fila Abadi Computer',
            'FPIK Institut Pertanian Bogor', 'Ginvo Studio', 'Himalaya Komputindo', 'Hotel Amarsya',
            'Hotel Arimbi', 'Hotel New Ayuda 2', 'Hypermart Lippo Plaza Ekalokasari',
            'Imaji Animation Studios', 'Institut Bisnis dan Informatika Kesatuan (IBIK)',
            'Javiera Studio', 'JSI Resort', 'Kantor Dinas Perhubungan Kota Bogor',
            'Kantor Kecamatan Ciawi', 'Kementerian Keuangan RI Gadog', 'Kin’n Ken Patchwork',
            'Kominfo Kota Bogor', 'Konveksi Semi Garment', 'KPU Kota Bogor',
            'KSPPS Berkah Mandiri Sejahtera', 'Matik Creative Technology', 'MNC Lido Hotel',
            'Neera Studio', 'Patopo Studio', 'PDAM Kota Bogor', 'Pelindo Pembelajaran dan Konsultasi',
            'Pengadilan Agama Kota Bogor', 'Pengadilan Negeri Bekasi', 'Personal Computer',
            'PT Abadi Gemilang Investama', 'PT Agricon', 'PT Asuransi Bhakti Bhayangkara',
            'PT Asuransi Syariah Keluarga Indonesia', 'PT Bangun Karya Filadelfia/Nite & Day MDC Gadog',
            'PT Bangunindo Teknusa Jaya', 'PT Enhaii Mandiri 186', 'PT ITEC Solution Indonesia',
            'PT Jiva Samudera Biru', 'PT Kalbe Milko Indonesia', 'PT Marui Solusindo Atmadja',
            'PT Max Samesta Group', 'PT Orbit Eka Semesta', 'PT Organo Science Laboratory',
            'PT Parsaroan Global Datatrans', 'PT PLN (Persero) UPDL Bogor', 'PT Real Media Lab',
            'PT Robust', 'PT Sunstar Prima Motor', 'PT Triwala Mitra Bestari',
            'PT WAN Teknologi Internasional', 'PT Yudhistira Ghalia Indonesia',
            'Pusat Pengembangan SDM (PPSDM) BNN', 'Puskesmas Ciawi', 'PUTIK Universitas Pakuan',
            'Rahayu Fashion', 'Rumah Sakit Azra', 'Salak Hospitality & Bisnis Unit', 'Samsat P3DW',
            'Satya Energy Solution', 'Savero Style Hotel Bogor', 'Seameo Biotrop',
            'Sekolah Tinggi Pariwisata (BHI)', 'Sekretaris Badan Strategi Kebijakan MA RI',
            'Seven Inc', 'Superindo Tajur', 'Sweatbox Animation Studio', 'Swiss Belcourt Hotel',
            'The Green Peak Artotel Curated Hotel', 'The Jungle',
            'Unit Percetakan Al-Qura’n Kementrian Agama RI', 'UPTD Metrologi Legal Kota Bogor',
            'Whiz Prime Hotel Pajajaran', 'YBM BRILiaN', 'Zeus Computer'
        ];

        $partners = [];

        foreach ($partnerNames as $name) {
            $slug = Str::slug($name, '_');

            $partners[] = [
                'name' => $name,
                'description' => 'Mitra kerja sama untuk program praktik kerja lapangan dan pengembangan kompetensi siswa.',
                'logo' => '',
                'sector' => '', // Diubah sesuai permintaan
                'city' => '', // Diubah sesuai permintaan
                'company_contact' => '', // Diubah sesuai permintaan
                'publisher' => 'Admin',
                'partnership_date' => Carbon::now()->subDays(rand(30, 730)),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('partners')->insert($partners);
    }
}