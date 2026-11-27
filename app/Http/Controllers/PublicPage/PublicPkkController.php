<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use App\Models\PkkProject;
use Illuminate\Http\Request;

class PublicPkkController extends Controller
{
    /**
     * Menampilkan halaman daftar project (Index)
     */
    public function index(Request $request)
    {
        $query = PkkProject::with('major');

        if ($q = trim($request->input('q', ''))) {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('brand_name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('student_names', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhereHas('major', fn ($m) => $m->where('name', 'like', "%{$q}%"));
            });
        }

        $projects = $query->latest()->paginate(9)->withQueryString();

        return view('PublicSide.PKK.index', compact('projects'));
    }

    /**
     * Menampilkan detail project
     */
    public function show($id)
    {
        // Cari project berdasarkan ID, jika tidak ketemu tampilkan 404
        $project = PkkProject::with('major')->findOrFail($id);

        return view('PublicSide.PKK.detail', compact('project'));
    }
}