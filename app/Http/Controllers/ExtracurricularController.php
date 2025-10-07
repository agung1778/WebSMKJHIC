<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExtracurricularController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $extracurriculars = Extracurricular::latest()->get();
        return view('admin.tables.extracurricular.index', compact('extracurriculars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tables.extracurricular.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Wajib,Pilihan',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'contact' => 'required|string|max:255',
            'coach' => 'required|string|max:255', // Penambahan validasi untuk pelatih
        ], [
            'name.required' => 'Nama ekstrakurikuler harus diisi.',
            'type.required' => 'Jenis ekstrakurikuler harus dipilih.',
            'type.in' => 'Jenis yang dipilih tidak valid.',
            'description.required' => 'Deskripsi harus diisi.',
            'image.required' => 'Foto harus diunggah.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, jpg, gif, atau svg.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            'contact.required' => 'Kontak harus diisi.',
            'coach.required' => 'Nama pelatih/pembina harus diisi.',
        ]);

        $validatedData['publisher'] = Auth::user()->name;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('extracurricular_images', 'public');
            $validatedData['image'] = $imagePath;
        }

        Extracurricular::create($validatedData);

        return redirect()->route('admin.extracurriculars.index')->with('success', 'Data ekstrakurikuler berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Extracurricular $extracurricular)
    {
        return view('admin.tables.extracurricular.show', compact('extracurricular'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Extracurricular $extracurricular)
    {
        return view('admin.tables.extracurricular.edit', compact('extracurricular'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Extracurricular $extracurricular)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Wajib,Pilihan',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'contact' => 'required|string|max:255',
            'coach' => 'required|string|max:255', // Penambahan validasi untuk pelatih
        ], [
            'name.required' => 'Nama ekstrakurikuler harus diisi.',
            'type.required' => 'Jenis ekstrakurikuler harus dipilih.',
            'type.in' => 'Jenis yang dipilih tidak valid.',
            'description.required' => 'Deskripsi harus diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, jpg, atau gif.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            'contact.required' => 'Kontak harus diisi.',
            'coach.required' => 'Nama pelatih/pembina harus diisi.',
        ]);

        $validatedData['publisher'] = Auth::user()->name;

        if ($request->hasFile('image')) {
            if ($extracurricular->image) {
                Storage::disk('public')->delete($extracurricular->image);
            }
            $imagePath = $request->file('image')->store('extracurricular_images', 'public');
            $validatedData['image'] = $imagePath;
        }

        $extracurricular->update($validatedData);

        return redirect()->route('admin.extracurriculars.index')->with('success', 'Data ekstrakurikuler berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Extracurricular $extracurricular)
    {
        if ($extracurricular->image) {
            Storage::disk('public')->delete($extracurricular->image);
        }

        $extracurricular->delete();

        return redirect()->route('admin.extracurriculars.index')->with('success', 'Data ekstrakurikuler berhasil dihapus!');
    }
}