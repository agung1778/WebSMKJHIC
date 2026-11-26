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

    /**
     * Unduh logo perusahaan mitra industri dalam kualitas asli.
     */
    public function downloadLogo(Partner $partner): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $fullPath = $partner->getLogoFullPath();
        abort_unless($fullPath && is_file($fullPath), 404, 'Logo perusahaan tidak ditemukan atau belum diunggah.');

        $ext = pathinfo($fullPath, PATHINFO_EXTENSION) ?: 'png';
        $slug = \Illuminate\Support\Str::slug($partner->name) ?: 'perusahaan';
        $downloadName = 'Logo-' . $slug . '.' . $ext;

        $mime = @mime_content_type($fullPath) ?: 'application/octet-stream';

        return response()->download($fullPath, $downloadName, [
            'Content-Type' => $mime,
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
