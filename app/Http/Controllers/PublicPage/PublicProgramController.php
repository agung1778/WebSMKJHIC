<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use App\Models\SchoolProgram;
use Illuminate\Http\Request;    
use App\Models\Image; // Pastikan untuk mengimpor model Image

class PublicProgramController extends Controller
{
    /**
     * Display a listing of the news for the public page.
     * Menampilkan daftar semua berita.
     */
    public function index()
    {
        // Mengambil semua program, diurutkan dari yang terbaru
        $programs = SchoolProgram::latest()->paginate(10);

        $programImages = Image::whereIn('title', ['ProgramImage', 'main'])->get();

        return view('PublicSide.program.index', [
            'programs' => $programs,
            'programImages' => $programImages,
        ]);
    }

    /**
     * Display the specified news detail.
     * Menampilkan detail satu berita.
     */

    public function show(SchoolProgram $program)
    {
        $otherPrograms = SchoolProgram::where('id', '!=', $program->id)->latest()->take(4)->get();

        // 2. Kirim variabel '$program' (berita utama) dan '$randomPrograms' (untuk sugesti) ke view.
        return view('PublicSide.program.show', compact('program', 'otherPrograms'));
    }
}
