<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;

class PublicLegalController extends Controller
{
    public function privacy()
    {
        return view('PublicSide.legal.privacy');
    }

    public function terms()
    {
        return view('PublicSide.legal.terms');
    }
}