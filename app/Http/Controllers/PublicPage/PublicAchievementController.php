<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use App\Models\Achievement; // Model untuk data Prestasi
use App\Models\Image; // Model Image (sesuai contoh Anda)
use Illuminate\Http\Request;
use App\Models\Writing; // Import Model Writing

class PublicAchievementController extends Controller
{
    /**
     * Display a listing of the achievements for the public page.
     * Menampilkan daftar semua prestasi.
     */
    public function index()
    {
        // Mengambil semua prestasi, diurutkan dari yang terbaru, dengan paginasi
        $achievements = Achievement::latest()->paginate(10);

        $achievementContent = Writing::where('title', 'Achievement')
            ->orderBy('release_date', 'desc')
            ->first();

        // Mengambil gambar terkait, diasumsikan judul gambar 'AchievementImage' atau 'main'
        $achievementImages = Image::whereIn('title', ['AchievementImage', 'main'])->get();

        return view('PublicSide.achievement.index', [
            'achievements' => $achievements,
            'achievementImages' => $achievementImages,
            'achievementContent' => $achievementContent,
        ]);
    }

    /**
     * Display the specified achievement detail.
     * Menampilkan detail satu prestasi.
     *
     * @param  \App\Models\Achievement  $achievement
     * @return \Illuminate\View\View
     */
    public function show(Achievement $achievement)
    {
        // Mengambil 4 prestasi acak atau terbaru (selain yang sedang dilihat) untuk rekomendasi
        $otherAchievements = Achievement::where('id', '!=', $achievement->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('PublicSide.achievement.show', [
            'achievement' => $achievement,
            'otherAchievements' => $otherAchievements,
        ]);
    }
}
