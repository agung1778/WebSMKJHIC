<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\Partner;

class PublicPartnersController extends Controller
{

    /**
     * Display a listing of the partners for the public page.
     * Menampilkan daftar semua partner.
     */
    public function index()
    {
        // Mengambil semua partner, diurutkan dari yang terbaru
        $partners = Partner::latest()->get();

        $partnersImages = Image::whereIn('title', ['PartnersImage', 'main'])->get();

        return view('PublicSide.partners.index', [
            'partners' => $partners,
            'partnersImages' => $partnersImages,
        ]);
    }

    /**
     * Display the specified partner detail.
     * Menampilkan detail satu partner.
     */ public function show(Partner $partner)
    {
        // Mengambil 4 mitra lain untuk sugesti, KECUALI mitra yang sedang dibuka
        $randomPartners = Partner::where('id', '!=', $partner->id)
            ->inRandomOrder() // Lebih baik daripada latest() untuk sugesti
            ->take(4)
            ->get();

        // Gambar banner bisa tetap diambil jika diperlukan di halaman detail
        $partnersImages = Image::whereIn('title', ['PartnersImage', 'main'])->get();

        // Kirim data partner UTAMA ($partner) dan data SUGGESTI ($randomPartners)
        return view('PublicSide.partners.show', [
            'partner'        => $partner,
            'randomPartners' => $randomPartners,
            'partnersImages' => $partnersImages,
        ]);
    }
}
