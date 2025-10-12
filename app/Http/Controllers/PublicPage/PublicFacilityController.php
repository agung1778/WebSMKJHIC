<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Facility;
use Illuminate\Http\Request;

class PublicFacilityController extends Controller
{
    /**
     * Menampilkan halaman daftar fasilitas.
     */
    public function index(Request $request)
    {
        // Mengambil semua tipe fasilitas yang unik untuk tombol filter
        $types = Facility::select('type')->distinct()->pluck('type');

        $gridImages = Image::where('title', 'PortraitImage')->take(3)->get();

        // Query dasar untuk fasilitas
        $facilityQuery = Facility::query();
        // Ambil semua fasilitas dan kelompokkan berdasarkan kolom 'type'
        $groupedFacilities = Facility::latest()->get()->groupBy('type');

        // Terapkan filter jika ada parameter 'type' di URL
        if ($request->has('type') && $request->type != '') {
            $facilityQuery->where('type', $request->type);
        }

        // Ambil data fasilitas yang sudah difilter atau semua data
        $facilities = $facilityQuery->latest()->get();


        $facilityImages = Image::where('title', 'FacilityImage')->first();

        return view('PublicSide.facilities.index', [
            'facilities'     => $facilities,
            'types'          => $types,
            'facilityImages' => $facilityImages,
            'currentType'    => $request->type,
            'groupedFacilities' => $groupedFacilities,
            'gridImages' => $gridImages,
        ]);
    }

    /**
     * Menampilkan detail satu fasilitas.
     */
    public function show(Facility $facility)
    {
        // Ambil semua fasilitas LAIN, dengan eager loading (jika diperlukan untuk sidebar card)
        $otherFacilities = Facility::where('id', '!=', $facility->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('PublicSide.facilities.show', compact('facility', 'otherFacilities'));
    }
}
