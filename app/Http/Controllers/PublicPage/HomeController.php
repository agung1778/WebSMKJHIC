<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\Image;
use App\Models\Facility;
use App\Models\Major;
use Illuminate\Support\Facades\Cache; // <-- 1. IMPORT CACHE

class HomeController extends Controller
{
    public function index()
    {
        // 2. Tentukan durasi cache (misal: 10 menit)
        $cacheDuration = now()->addMinutes(5);

        // 3. Bungkus semua query dengan Cache::remember()
        $latestNews = Cache::remember('home_latest_news', $cacheDuration, function () {
            return News::latest('date_published')->take(4)->get();
        });

        $partners = Cache::remember('home_partners', $cacheDuration, function () {
            return Partner::latest()->get();
        });

        $testimonials = Cache::remember('home_testimonials', $cacheDuration, function () {
            return Testimonial::with('major')->latest()->take(6)->get();
        });

        $mainImages = Cache::remember('home_main_images', $cacheDuration, function () {
            return Image::whereIn('title', ['MainImage'])->get();
        });

        $gridImages = Cache::remember('home_grid_images', $cacheDuration, function () {
            return Image::where('title', 'GridImage')->take(5)->get();
        });

        // Cache query utama, baru lakukan padding
        $majorGridImages = Cache::remember('home_major_grid_images', $cacheDuration, function () {
            return Image::where('title', 'MajorGrid')->latest()->take(2)->get();
        });
        $majorGridImages_padded = $majorGridImages->pad(2, null);

        // Cache query utama, baru lakukan padding
        $facilities = Cache::remember('home_facilities', $cacheDuration, function () {
            return Facility::latest()->take(5)->get();
        });
        $facilities_padded = $facilities->pad(5, null);

        $majors = Cache::remember('home_majors', $cacheDuration, function () {
            return Major::orderBy('id', 'asc')->get();
        });

        // Kirim semua variabel ke view
        return view('welcome', [
            'latestNews'      => $latestNews,
            'partners'        => $partners,
            'testimonials'    => $testimonials,
            'mainImages'      => $mainImages,
            'gridImages'      => $gridImages,
            'majorGridImages' => $majorGridImages_padded,
            'facilities'      => $facilities_padded,
            'majors'          => $majors,
        ]);
    }
}