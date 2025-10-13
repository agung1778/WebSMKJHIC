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
                'url' => '/about/history',
                'icon' => 'fa-landmark' // Ikon untuk sejarah/institusi
            ],
            [
                'title' => 'Visi & Misi',
                'description' => 'Tujuan dan cita-cita yang menjadi panduan kami.',
                'url' => '/about/vision',
                'icon' => 'fa-bullseye' // Ikon untuk target/visi
            ],
            [
                'title' => 'Yayasan',
                'description' => 'Mengenal lebih dalam fondasi yang menaungi kami.',
                'url' => '/about/foundation',
                'icon' => 'fa-building-columns' // Ikon untuk fondasi/yayasan
            ],
            [
                'title' => 'Major Competency',
                'description' => 'Lihat lebih dalam mengenai jurusan disekolah kami.',
                'url' => '/majors',
                'icon' => 'fa-sitemap' // Ikon untuk struktur/hierarki
            ],
            [
                'title' => 'Fasilitas',
                'description' => 'Sarana dan prasarana penunjang kegiatan.',
                'url' => '/facilities',
                'icon' => 'fa-school' // Ikon untuk gedung/fasilitas sekolah
            ],
            [
                'title' => 'Tenaga Pendidik',
                'description' => 'Profil para pendidik profesional & berdedikasi kami.',
                'url' => '/teachers',
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
        $historyContent = Writing::where('title', 'History')
            ->orderBy('release_date', 'desc')
            ->first();

        $mainImages = Image::whereIn('title', ['main'])->get();
        $hasImages = !$mainImages->isEmpty();

        return view('PublicSide.about.history', compact('hasImages', 'mainImages', 'historyContent'));
    }

    public function foundation()
    {
        $foundationContent = Writing::where('title', 'Foundation')
            ->orderBy('release_date', 'desc')
            ->first();

        $mainImages = Image::whereIn('title', ['main'])->get();
        $hasImages = !$mainImages->isEmpty();

        return view('PublicSide.about.foundation', compact('hasImages', 'mainImages', 'foundationContent'));
    }

}
