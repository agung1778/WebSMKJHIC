<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Extracurricular;
use Illuminate\Http\Request;

class PublicExtracurricularController extends Controller
{
    public function index()
    {
        // Mengambil semua berita, diurutkan dari yang terbaru
        $extracurriculars = Extracurricular::latest()->paginate(10);

        $extracurricularImages = Image::whereIn('title', ['ExtracurricularImage', 'main'])->get();

        return view('PublicSide.extracurricular.index', [
            'extracurriculars' => $extracurriculars,
            'extracurricularImages' => $extracurricularImages,
        ]);
    }

    /**
     * Display the specified news detail.
     * Menampilkan detail satu berita.
     */

    public function show(Extracurricular $extracurriculars)
    {
        $randomExtracurricular = Extracurricular::where('id', '!=', $extracurriculars->id)->latest()->take(4)->get();

        // 2. Kirim variabel '$news' (berita utama) dan '$randomExtracurricular' (untuk sugesti) ke view.
        return view('PublicSide.extracurricular.show', compact('extracurriculars', 'randomExtracurricular'));
    }
}
