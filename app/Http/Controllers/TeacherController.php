<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::latest()->get();
        return view('admin.tables.teacher.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tables.teacher.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:5048',
            'position' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'school' => 'required|in:Amaliah 1,Amaliah 2, Amaliah 1 & 2',
            'category' => 'required|in:Produktif,Normatif,Adaptif,Umum,Struktural',
        ], [
            'name.required' => 'Nama guru harus diisi.',
            'photo.required' => 'Foto guru harus diunggah.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, jpg, atau gif.',
            'photo.max' => 'Ukuran gambar tidak boleh lebih dari 5MB.',
            'position.required' => 'Jabatan harus diisi.',
            'subject.required' => 'Mata pelajaran harus diisi.',
            'school.required' => 'Sekolah mengajar harus dipilih.',
            'category.required' => 'Kategori pelajaran harus dipilih.',
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('teacher_photos', 'public');
            $validatedData['photo'] = $photoPath;
        }

        Teacher::create($validatedData);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        return view('admin.tables.teacher.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        return view('admin.tables.teacher.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048',
            'position' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'school' => 'required|in:Amaliah 1,Amaliah 2, Amaliah 1 & 2',
            'category' => 'required|in:Produktif,Normatif,Adaptif,Umum,Struktural',
        ], [
            'name.required' => 'Nama guru harus diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, jpg, atau gif.',
            'photo.max' => 'Ukuran gambar tidak boleh lebih dari 5MB.',
            'position.required' => 'Jabatan harus diisi.',
            'subject.required' => 'Mata pelajaran harus diisi.',
            'school.required' => 'Sekolah mengajar harus dipilih.',
            'category.required' => 'Kategori pelajaran harus dipilih.',
        ]);

        if ($request->hasFile('photo')) {
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $photoPath = $request->file('photo')->store('teacher_photos', 'public');
            $validatedData['photo'] = $photoPath;
        }

        $teacher->update($validatedData);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil dihapus!');
    }
}