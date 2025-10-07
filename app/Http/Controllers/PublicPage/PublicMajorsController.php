<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Image;
use Illuminate\Http\Request;

class PublicMajorsController extends Controller
{
    /**
     * Menampilkan halaman daftar jurusan.
     */
    public function index()
    {
        // Mengambil semua data jurusan (Major) dengan eager loading untuk testimonials
        $majors = Major::with('testimonials')->latest()->get();

        // Mengambil gambar hero untuk halaman jurusan
        $majorsImages = Image::whereIn('title', ['MajorsImage', 'main'])->get();
        $hasImages = !$majorsImages->isEmpty();

        // Mengirimkan semua variabel yang diperlukan ke view
        return view('PublicSide.majors.index', compact('majors', 'majorsImages', 'hasImages'));
    }

    /**
     * Menampilkan detail satu jurusan.
     */
    public function show(Major $major)
    {
        // Ambil semua jurusan LAIN, dengan eager loading (jika diperlukan untuk sidebar card)
        $otherMajors = Major::where('id', '!=', $major->id)
            ->with('testimonials') // Contoh eager loading untuk sidebar
            ->latest()
            ->get();

        // Kirim data jurusan yang sedang dilihat DAN daftar jurusan lain ke view
        return view('PublicSide.majors.show', [
            'major'       => $major,
            'otherMajors' => $otherMajors,
        ]);
    }
}
