<?php

namespace App\Http\Controllers;

use App\Models\SchoolProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SchoolProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programs = SchoolProgram::latest()->get();
        return view('admin.tables.program.index', compact('programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tables.program.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Nama program harus diisi.',
            'description.required' => 'Deskripsi harus diisi.',
            'image.required' => 'Foto program harus diunggah.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, jpg, gif, atau svg.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        $validatedData['publisher'] = Auth::user()->name;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('program_images', 'public');
            $validatedData['image'] = $imagePath;
        }

        SchoolProgram::create($validatedData);

        return redirect()->route('admin.programs.index')->with('success', 'Program sekolah berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolProgram $program)
    {
        return view('admin.tables.program.show', compact('program'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolProgram $program)
    {
        return view('admin.tables.program.edit', compact('program'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolProgram $program)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Nama program harus diisi.',
            'description.required' => 'Deskripsi harus diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, jpg, gif, atau svg.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        $validatedData['publisher'] = Auth::user()->name;

        if ($request->hasFile('image')) {
            if ($program->image) {
                Storage::disk('public')->delete($program->image);
            }
            $imagePath = $request->file('image')->store('program_images', 'public');
            $validatedData['image'] = $imagePath;
        }

        $program->update($validatedData);

        return redirect()->route('admin.programs.index')->with('success', 'Program sekolah berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolProgram $program)
    {
        if ($program->image) {
            Storage::disk('public')->delete($program->image);
        }

        $program->delete();

        return redirect()->route('admin.programs.index')->with('success', 'Program sekolah berhasil dihapus!');
    }
}