<?php

namespace App\Http\Controllers;

use App\Models\Writing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WritingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $writings = Writing::latest()->get();
        return view('admin.tables.writing.index', compact('writings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tables.writing.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'publisher' => 'required|string|max:255',
            'release_date' => 'required|date', // BARU
        ], [
            'title.required' => 'Judul harus diisi.',
            'content.required' => 'Isi konten harus diisi.',
            'publisher.required' => 'Nama publisher harus diisi.',
            'release_date.required' => 'Tanggal rilis harus diisi.',
            'release_date.date' => 'Tanggal rilis harus berupa format tanggal yang valid.',
        ]);

        Writing::create($validatedData);

        return redirect()->route('admin.writings.index')->with('success', 'Konten berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Writing $writing)
    {
        return view('admin.tables.writing.show', compact('writing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Writing $writing)
    {
        return view('admin.tables.writing.edit', compact('writing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Writing $writing)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'publisher' => 'required|string|max:255',
            'release_date' => 'required|date', // BARU
        ], [
            'title.required' => 'Judul harus diisi.',
            'content.required' => 'Isi konten harus diisi.',
            'publisher.required' => 'Nama publisher harus diisi.',
            'release_date.required' => 'Tanggal rilis harus diisi.',
            'release_date.date' => 'Tanggal rilis harus berupa format tanggal yang valid.',
        ]);

        $writing->update($validatedData);

        return redirect()->route('admin.writings.index')->with('success', 'Konten berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Writing $writing)
    {
        // Tidak perlu menghapus gambar karena field image sudah dihapus

        $writing->delete();

        return redirect()->route('admin.writings.index')->with('success', 'Konten berhasil dihapus!');
    }
}