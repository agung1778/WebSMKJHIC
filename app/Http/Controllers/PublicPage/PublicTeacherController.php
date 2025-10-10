<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\Image;

class PublicTeacherController extends Controller
{
    /**
     * Display a listing of the teachers for the public page.
     * Mengambil data guru, dikelompokkan berdasarkan kategori (Produktif, Normatif, Adaptif, Umum).
     */
    public function index()
    {
        // Mengambil semua guru yang aktif
        $teachers = Teacher::all();

        // Mengelompokkan guru berdasarkan kategori untuk tampilan yang terstruktur
        $groupedTeachers = $teachers->groupBy('category');

        //hiraukan ini
        $mainImages = Image::whereIn('title', ['main'])->get();
        $hasImages = !$mainImages->isEmpty();

        // Mengirimkan data ke view di lokasi public-side.teachers.index
        return view('PublicSide.teachers.index', compact('groupedTeachers', 'hasImages', 'mainImages'));
    }

    public function show(Teacher $teacher)
    {
        // Route Model Binding otomatis mengambil data Teacher
        // Mengambil guru lain dari sekolah yang sama (opsional, untuk rekomendasi)
        $relatedTeachers = Teacher::where('school', $teacher->school)
            ->where('id', '!=', $teacher->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        $mainImages = Image::whereIn('title', ['main'])->get();
        $hasImages = !$mainImages->isEmpty();

        // Mengirimkan data ke view di lokasi public-side.teachers.show
        return view('PublicSide.teachers.show', compact('teacher', 'relatedTeachers', 'hasImages', 'mainImages'));
    }
}
