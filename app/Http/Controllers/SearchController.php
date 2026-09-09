<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Teacher;
use App\Models\Major;
use App\Models\Partner;
use App\Models\Extracurricular;
use App\Models\Facility;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // Ambil query pencarian dari input
        $query = $request->input('query');

        // Escape karakter wildcard % dan _ untuk mencegah wildcard injection
        $escapedQuery = addcslashes($query, '%_');

        // Lakukan pencarian jika ada query
        if ($query) {
            $newsResults = News::where('title', 'LIKE', "%{$escapedQuery}%")
                               ->orWhere('description', 'LIKE', "%{$escapedQuery}%")
                               ->latest()->limit(10)->get();

            $teacherResults = Teacher::where('name', 'LIKE', "%{$escapedQuery}%")
                                     ->orWhere('subject', 'LIKE', "%{$escapedQuery}%")
                                     ->limit(10)->get();

            $majorResults = Major::where('name', 'LIKE', "%{$escapedQuery}%")
                                   ->orWhere('description', 'LIKE', "%{$escapedQuery}%")
                                   ->limit(10)->get();
            
            $partnerResults = Partner::where('name', 'LIKE', "%{$escapedQuery}%")
                                     ->orWhere('description', 'LIKE', "%{$escapedQuery}%")
                                     ->limit(10)->get();

            $extracurricularResults = Extracurricular::where('name', 'LIKE', "%{$escapedQuery}%")
                                                     ->orWhere('description', 'LIKE', "%{$escapedQuery}%")
                                                     ->limit(10)->get();
            
            $facilityResults = Facility::where('name', 'LIKE', "%{$escapedQuery}%")
                                       ->orWhere('description', 'LIKE', "%{$escapedQuery}%")
                                       ->limit(10)->get();

        } else {
            // Jika tidak ada query, kembalikan koleksi kosong
            $newsResults = collect();
            $teacherResults = collect();
            $majorResults = collect();
            $partnerResults = collect();
            $extracurricularResults = collect();
            $facilityResults = collect();
        }

        // Hitung total hasil yang ditemukan
        $totalResults = $newsResults->count() + $teacherResults->count() + $majorResults->count() + $partnerResults->count() + $extracurricularResults->count() + $facilityResults->count();

        // Kirim data ke view hasil pencarian
        return view('PublicSide.search-results', compact(
            'query',
            'totalResults',
            'newsResults',
            'teacherResults',
            'majorResults',
            'partnerResults',
            'extracurricularResults',
            'facilityResults'
        ));
    }
}
