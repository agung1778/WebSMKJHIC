<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Writing; // Import Model Writing

class PublicAboutController extends Controller
{
    public function index()
    {
        $aboutLinks = [
            [
                'title' => 'Sejarah',
                'description' => 'Perjalanan kami dari awal hingga sekarang.',
                'url' => '/tentang-kami/sejarah',
                'icon' => 'fa-landmark' // Ikon untuk sejarah/institusi
            ],
            [
                'title' => 'Visi & Misi',
                'description' => 'Tujuan dan cita-cita yang menjadi panduan kami.',
                'url' => '/tentang-kami/visi-misi',
                'icon' => 'fa-bullseye' // Ikon untuk target/visi
            ],
            [
                'title' => 'Yayasan',
                'description' => 'Mengenal lebih dalam fondasi yang menaungi kami.',
                'url' => '/tentang-kami/yayasan',
                'icon' => 'fa-building-columns' // Ikon untuk fondasi/yayasan
            ],
            [
                'title' => 'Struktur Organisasi',
                'description' => 'Tim solid yang bekerja di balik layar kesuksesan.',
                'url' => '/tentang-kami/struktur-organisasi',
                'icon' => 'fa-sitemap' // Ikon untuk struktur/hierarki
            ],
            [
                'title' => 'Fasilitas',
                'description' => 'Sarana dan prasarana penunjang kegiatan.',
                'url' => '/fasilitas',
                'icon' => 'fa-school' // Ikon untuk gedung/fasilitas sekolah
            ],
            [
                'title' => 'Tenaga Pendidik',
                'description' => 'Profil para pendidik profesional & berdedikasi kami.',
                'url' => '/pendidik',
                'icon' => 'fa-chalkboard-teacher' // Ikon untuk guru/pendidik
            ],
        ];


        $aboutContent = Writing::where('title', 'About')
            ->orderBy('release_date', 'desc')
            ->first();

        $visionContent = Writing::where('title', 'VisiMisi')
            ->orderBy('release_date', 'desc')
            ->first();

        // 2. Logika Pengambilan Gambar (yang sudah ada)
        $mainImages = Image::whereIn('title', ['main'])->get();
        $hasImages = !$mainImages->isEmpty();


        return view('PublicSide.about.index', compact('hasImages', 'mainImages', 'aboutContent', 'visionContent', 'aboutLinks',));
    }

    public function vision()
    {
        $visionContent = Writing::where('title', 'VisiMisi')
            ->orderBy('release_date', 'desc')
            ->first();

        $mainImages = Image::whereIn('title', ['main'])->get();
        $hasImages = !$mainImages->isEmpty();

        return view('PublicSide.about.vision', compact('hasImages', 'mainImages', 'visionContent'));
    }

    public function history()
    {
        $historyContent = Writing::where('title', 'Sejarah')
            ->orderBy('release_date', 'desc')
            ->first();

        $mainImages = Image::whereIn('title', ['main'])->get();
        $hasImages = !$mainImages->isEmpty();

        return view('PublicSide.about.history', compact('hasImages', 'mainImages', 'historyContent'));
    }
}
