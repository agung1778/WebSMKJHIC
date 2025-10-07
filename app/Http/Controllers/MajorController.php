<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $majors = Major::all();
        return view('admin.tables.major.index', compact('majors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tables.major.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'tag' => 'nullable|string', // BARU
            'advantage' => 'nullable|string', // BARU
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:512',
            'competency_head' => 'required|string|max:255',
            'competency_head_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama jurusan harus diisi.',
            'description.required' => 'Deskripsi harus diisi.',
            'image.required' => 'Gambar harus diunggah.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, atau jpg.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            'logo.required' => 'Logo harus diunggah.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Format logo yang diizinkan adalah jpeg, png, jpg, svg, atau webp.',
            'logo.max' => 'Ukuran logo tidak boleh lebih dari 512KB.',
            'competency_head.required' => 'Nama kepala kompetensi harus diisi.',
            'competency_head_photo.required' => 'Foto kepala kompetensi harus diunggah.',
            'competency_head_photo.image' => 'File harus berupa gambar.',
            'competency_head_photo.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, atau jpg.',
            'competency_head_photo.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        $validatedData['publisher'] = Auth::user()->name;

        // Proses Uploads
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('major_images', 'public');
            $validatedData['image'] = $imagePath;
        }
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('major_logos', 'public');
            $validatedData['logo'] = $logoPath;
        }
        if ($request->hasFile('competency_head_photo')) {
            $photoPath = $request->file('competency_head_photo')->store('head_photos', 'public');
            $validatedData['competency_head_photo'] = $photoPath;
        }

        Major::create($validatedData);

        return redirect()->route('admin.majors.index')->with('success', 'Jurusan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Major $major)
    {
        return view('admin.tables.major.show', compact('major'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Major $major)
    {
        return view('admin.tables.major.edit', compact('major'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Major $major)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'tag' => 'nullable|string', // BARU
            'advantage' => 'nullable|string', // BARU
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:3048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:512',
            'competency_head' => 'required|string|max:255',
            'competency_head_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama jurusan harus diisi.',
            'description.required' => 'Deskripsi harus diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, atau jpg.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 3MB.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Format logo yang diizinkan adalah jpeg, png, jpg, svg, atau webp.',
            'logo.max' => 'Ukuran logo tidak boleh lebih dari 512KB.',
            'competency_head.required' => 'Nama kepala kompetensi harus diisi.',
            'competency_head_photo.image' => 'File harus berupa gambar.',
            'competency_head_photo.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, atau jpg.',
            'competency_head_photo.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        $validatedData['publisher'] = Auth::user()->name;

        // Logika Upload dan Penghapusan Gambar Lama
        if ($request->hasFile('image')) {
            if ($major->image) { 
                Storage::disk('public')->delete($major->image);
            }
            $imagePath = $request->file('image')->store('major_images', 'public');
            $validatedData['image'] = $imagePath;
        }

        if ($request->hasFile('logo')) {
            if ($major->logo) {
                Storage::disk('public')->delete($major->logo);
            }
            $logoPath = $request->file('logo')->store('major_logos', 'public');
            $validatedData['logo'] = $logoPath;
        }

        if ($request->hasFile('competency_head_photo')) {
            if ($major->competency_head_photo) {
                Storage::disk('public')->delete($major->competency_head_photo);
            }
            $photoPath = $request->file('competency_head_photo')->store('head_photos', 'public');
            $validatedData['competency_head_photo'] = $photoPath;
        }

        // Hapus file dari validatedData jika tidak diunggah agar tidak menimpa data lama
        if (!$request->hasFile('logo')) {
            unset($validatedData['logo']);
        }
        if (!$request->hasFile('image')) {
            unset($validatedData['image']);
        }
        if (!$request->hasFile('competency_head_photo')) {
            unset($validatedData['competency_head_photo']);
        }

        // Perbarui data
        $major->update($validatedData);

        return redirect()->route('admin.majors.index')->with('success', 'Jurusan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Major $major)
    {
        if ($major->image) {
            Storage::disk('public')->delete($major->image);
        }
        if ($major->logo) {
            Storage::disk('public')->delete($major->logo);
        }
        if ($major->competency_head_photo) {
            Storage::disk('public')->delete($major->competency_head_photo);
        }

        $major->delete();

        return redirect()->route('admin.majors.index')->with('success', 'Jurusan berhasil dihapus!');
    }
}
