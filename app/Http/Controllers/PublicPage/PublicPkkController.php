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
        // Ambil data project terbaru, load relasi jurusan, dan paginasi 9 item per halaman
        $projects = PkkProject::with('major')
            ->latest()
            ->paginate(9);

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