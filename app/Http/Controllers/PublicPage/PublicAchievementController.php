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
        // Mengambil semua prestasi (tanpa paginasi agar semua kartu langsung tampil)
        $achievements = Achievement::latest()->get();

        $achievementContent = Writing::where('title', 'Achievement')
            ->orderBy('release_date', 'desc')
            ->first();

        // Mengambil gambar terkait, diasumsikan judul gambar 'AchievementImage' atau 'main'
        $achievementImages = Image::whereIn('title', ['AchievementImage', 'main'])->get();

        $all = Achievement::all(['category', 'level', 'winner', 'date']);

        $stats = [
            'total'          => $all->count(),
            'categories'     => $all->pluck('category')->filter()->unique()->values(),
            'levels'         => $all->pluck('level')->filter()->unique()->values(),
            'categoriesCount'=> $all->pluck('category')->filter()->unique()->count(),
            'levelsCount'    => $all->pluck('level')->filter()->unique()->count(),
            'yearMin'        => $all->pluck('date')->filter()->map(fn ($d) => \Carbon\Carbon::parse($d)->year)->min(),
            'yearMax'        => $all->pluck('date')->filter()->map(fn ($d) => \Carbon\Carbon::parse($d)->year)->max(),
        ];

        return view('PublicSide.achievement.index', [
            'achievements' => $achievements,
            'achievementImages' => $achievementImages,
            'achievementContent' => $achievementContent,
            'stats' => $stats,
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
