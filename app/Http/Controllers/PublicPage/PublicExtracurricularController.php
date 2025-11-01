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

            $mainImages = Image::whereIn('title', ['ExtracurricularImage', 'main'])->get();

            return view('PublicSide.extracurricular.index', [
                'extracurriculars' => $extracurriculars,
                'mainImages' => $mainImages,
            ]);
        }

        /**
         * Display the specified news detail.
         * Menampilkan detail satu berita.
         */

        // BENAR - Menggunakan nama tunggal untuk satu objek
        public function show(Extracurricular $extracurricular) // <-- Diubah
        {
            // Mengambil data lain, kecuali data yang sedang dibuka
            $suggestedExtracurriculars = Extracurricular::where('id', '!=', $extracurricular->id) // <-- Diubah
                ->latest()
                ->take(4)
                ->get();

            // Kirim data ke view dengan nama yang benar
            return view('PublicSide.extracurricular.show', [
                'extracurricular' => $extracurricular, // <-- Diubah
                'suggestedExtracurriculars' => $suggestedExtracurriculars
            ]);
        }
    }
