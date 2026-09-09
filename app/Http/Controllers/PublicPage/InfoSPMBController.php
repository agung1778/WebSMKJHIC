<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SpmbSetting;

class InfoSPMBController extends Controller
{
    /**
     * Menampilkan halaman Info SPMB (Penerimaan Murid Baru).
     */
    public function index()
    {
        $spmbSetting = SpmbSetting::first();
        return view('PublicSide.infospmb', compact('spmbSetting'));
    }
}
