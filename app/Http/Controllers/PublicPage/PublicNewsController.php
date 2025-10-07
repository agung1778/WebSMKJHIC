<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use App\Models\Image; // Pastikan untuk mengimpor model Image

class PublicNewsController extends Controller
{
    /**
     * Display a listing of the news for the public page.
     * Menampilkan daftar semua berita.
     */
    public function index()
    {
        // Mengambil semua berita, diurutkan dari yang terbaru
        $news = News::latest()->paginate(10);

        $newsImages = Image::whereIn('title', ['NewsImage', 'main'])->get();

        return view('PublicSide.news.index', [
            'news' => $news,
            'newsImages' => $newsImages,
        ]);
    }

    /**
     * Display the specified news detail.
     * Menampilkan detail satu berita.
     */

    public function show(News $news)
    {
        $randomNews = News::where('id', '!=', $news->id)->latest()->take(4)->get();

        // 2. Kirim variabel '$news' (berita utama) dan '$randomNews' (untuk sugesti) ke view.
        return view('PublicSide.news.show', compact('news', 'randomNews'));
    }
}
