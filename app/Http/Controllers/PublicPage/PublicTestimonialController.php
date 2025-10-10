<?php
namespace App\Http\Controllers\PublicPage;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\Image;
use App\Models\Major;
use Illuminate\Http\Request;

class PublicTestimonialController extends Controller
{
    /**
     * Menampilkan daftar semua testimoni dengan paginasi.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $majors = Major::whereHas('testimonials')->get();

        // Memulai query builder untuk testimoni
        $testimonialQuery = Testimonial::with('major')->latest();

        // Cek apakah ada request untuk filter berdasarkan major_id
        if ($request->has('major') && $request->major != '') {
            $testimonialQuery->where('major_id', $request->major);
        }

        // Eksekusi query
        $testimonials = $testimonialQuery->get();

        // Simpan major_id yang sedang aktif untuk menandai tombol filter
        $selectedMajor = $request->major;
        $majorsImages = Image::whereIn('title', ['MajorsImage', 'main'])->get();
        $hasImages = !$majorsImages->isEmpty();

        // Mengirim data testimoni ke view 'public.testimonials.index'
        return view('PublicSide.testimonials.index', compact('testimonials', 'hasImages', 'majorsImages', 'majors', 'selectedMajor'));
    }

    public function show(Testimonial $testimonial)
    {
        // Eager load relasi 'major' untuk memastikan data jurusan tersedia.
        $testimonial->load('major');

        // Mengirim data testimoni yang dipilih ke view 'public.testimonials.show'
        return view('PublicSide.testimonials.show', compact('testimonial'));
    }
}
