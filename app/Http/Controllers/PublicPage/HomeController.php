<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Partner;
use App\Models\Testimonial; // <-- 1. IMPORT MODEL TESTIMONIAL
use App\Models\Image; // Pastikan untuk mengimpor model Image
use App\Models\Facility; // Pastikan Anda sudah membuat model Facility
use App\Models\Major; // Pastikan model Major sudah di-import

class HomeController extends Controller
{
    public function index()
    {
        $latestNews = News::latest('date_published')->take(4)->get();
        $partners = Partner::latest()->get();
        $testimonials = Testimonial::with('major')->latest()->take(6)->get();

        // 1. Ambil semua gambar dari database yang memiliki title 'MainImage'
        $mainImages = Image::whereIn('title', ['MainImage'])->get();
        $gridImages = Image::where('title', 'GridImage')->take(5)->get();
        $majorGridImages = Image::where('title', 'MajorGrid')->latest()->take(2)->get();
        $majorGridImages_padded = $majorGridImages->pad(2, null);

        $facilities = Facility::latest()->take(5)->get();
        $facilities_padded = $facilities->pad(5, null);

        $majors = Major::orderBy('id', 'asc')->get();


        // Kode di bawah ini tidak akan dijalankan karena dd() akan menghentikan program
        return view('welcome', [
            'latestNews'   => $latestNews,
            'partners'     => $partners,
            'testimonials' => $testimonials,

            'mainImages' => $mainImages,
            'gridImages' => $gridImages,
             'majorGridImages' => $majorGridImages_padded, // <-- Variabel baru

            'facilities' => $facilities_padded,

            'majors' => $majors,
        ]);
    }
}
