<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\PkkProject;
use Illuminate\Database\Seeder;

class PkkProjectSeeder extends Seeder
{
    public function run(): void
    {
        if (PkkProject::count() > 0) {
            return;
        }

        $majors = Major::all();
        if ($majors->isEmpty()) {
            return;
        }

        $pplg = $majors->first(fn($m) => str_contains(strtolower($m->name), 'pplg') || str_contains(strtolower($m->name), 'perangkat lunak')) ?? $majors->first();
        $dkv = $majors->first(fn($m) => str_contains(strtolower($m->name), 'dkv') || str_contains(strtolower($m->name), 'komunikasi visual')) ?? $majors->skip(1)->first() ?? $majors->first();
        $tjkt = $majors->first(fn($m) => str_contains(strtolower($m->name), 'tjkt') || str_contains(strtolower($m->name), 'jaringan') || str_contains(strtolower($m->name), 'tkj')) ?? $majors->last();

        $samples = [
            [
                'major_id' => $pplg->id,
                'brand_name' => 'Amaliah POS Tech',
                'title' => 'Aplikasi Kasir & Inventaris Toko Sekolah',
                'student_names' => 'Fahri Ramadhan, M. Rizky, Annisa Putri',
                'student_class' => 'XII PPLG 1',
                'description' => 'Sistem Point of Sale (POS) modern berbasis web yang terintegrasi dengan pencatatan stok otomatis dan cetak struk thermal untuk unit produksi koperasi sekolah.',
                'photo' => 'https://images.unsplash.com/photo-1556742049-0a67c5574f73?w=800&auto=format&fit=crop&q=80',
                'logo' => null,
                'category' => 'Teknologi',
                'price' => 250000.00,
                'contact_info' => '0856-4901-1449',
                'social_media' => 'https://instagram.com/smkamaliahciawi',
            ],
            [
                'major_id' => $dkv->id,
                'brand_name' => 'Kreatif Amaliah Studio',
                'title' => 'Branding & Kemasan Produk UMKM Ciawi',
                'student_names' => 'Salma Nuraini, Kevin Pratama',
                'student_class' => 'XI DKV 2',
                'description' => 'Paket desain identitas visual lengkap mulai dari logo, kemasan ramah lingkungan, standing pouch berdaya saing tinggi, dan konten feeds promosi UMKM lokal Bogor.',
                'photo' => 'https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?w=800&auto=format&fit=crop&q=80',
                'logo' => null,
                'category' => 'Jasa & Kreatif',
                'price' => 150000.00,
                'contact_info' => '0856-1922-827',
                'social_media' => 'https://instagram.com/smkamaliahciawi',
            ],
            [
                'major_id' => $tjkt->id,
                'brand_name' => 'NetPro Solutions',
                'title' => 'Layanan Instalasi Hotspot & Manajemen Bandwidth',
                'student_names' => 'Ahmad Dani, Bagas Setiawan, Dimas',
                'student_class' => 'XII TJKT 3',
                'description' => 'Jasa instalasi jaringan mikrotik voucher hotspot, perakitan kabel UTP bersertifikasi, dan manajemen bandwidth WiFi untuk perkantoran dan cafe.',
                'photo' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=800&auto=format&fit=crop&q=80',
                'logo' => null,
                'category' => 'Jasa & Teknologi',
                'price' => 300000.00,
                'contact_info' => '0856-4901-1449',
                'social_media' => 'https://instagram.com/smkamaliahciawi',
            ],
        ];

        foreach ($samples as $s) {
            PkkProject::create($s);
        }
    }
}
