<?php

namespace App\Http\Controllers\PublicPage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
class PublicHelpcenterController extends Controller
{
    public function faq()
    {
        $mainImages = Image::whereIn('title', ['main'])->get();
        $hasImages = !$mainImages->isEmpty();
        return view('PublicSide.help.faq', compact('mainImages', 'hasImages'));
    }

    public function feedback()
    {
        $mainImages = Image::whereIn('title', ['main'])->get();
        $hasImages = !$mainImages->isEmpty();
        return view('PublicSide.help.feedback', compact('mainImages', 'hasImages'));
    }
}
