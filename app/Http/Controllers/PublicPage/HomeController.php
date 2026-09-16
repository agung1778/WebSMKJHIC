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
use App\Models\SchoolSetting;
use App\Models\Teacher;
use App\Models\InstaPost;
use App\Models\SchoolLeader;

class HomeController extends Controller
{
    public function index()
    {
        $latestNews = News::latest('date_published')->take(4)->get();

        $partners = Partner::latest()->get();

        $testimonials = Testimonial::with('major')->latest()->take(6)->get();

        $mainImages = Image::whereIn('title', ['MainImage'])->get();

        $gridImages = Image::where('title', 'GridImage')->take(5)->get();

        $majorGridImages = Image::where('title', 'MajorGrid')->latest()->take(2)->get();
        $majorGridImages_padded = $majorGridImages->pad(2, null);

        $facilities = Facility::latest()->take(5)->get();
        $facilities_padded = $facilities->pad(5, null);

        $majors = Major::orderBy('id', 'asc')->get();

        $schoolSettings = SchoolSetting::first();

        $teachersCount = Teacher::count();

        $facilitiesCount = Facility::count();

        $instaPosts = InstaPost::where('is_active', true)->latest()->take(16)->get();

        $leaders = SchoolLeader::where('is_active', true)
            ->orderBy('order_column')
            ->orderBy('id')
            ->get();

        return view('welcome', [
            'latestNews'      => $latestNews,
            'partners'        => $partners,
            'testimonials'    => $testimonials,
            'mainImages'      => $mainImages,
            'gridImages'      => $gridImages,
            'majorGridImages' => $majorGridImages_padded,
            'facilities'      => $facilities_padded,
            'majors'          => $majors,
            'studentsCount'   => optional($schoolSettings)->jumlah_siswa ?? 0,
            'teachersCount'   => $teachersCount,
            'facilitiesCount' => $facilitiesCount,
            'instaPosts'      => $instaPosts,
            'leaders'         => $leaders,
        ]);
    }
}