<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonial::with('major')->get();
        return view('admin.tables.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $majors = Major::all();
        return view('admin.tables.testimonials.create', compact('majors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'alumni_year' => 'required|integer|digits:4|min:1900|max:' . (date('Y') + 5),
            'major_id' => 'required|exists:majors,id',
            'description' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama harus diisi.',
            'alumni_year.required' => 'Tahun alumni harus diisi.',
            'alumni_year.integer' => 'Tahun alumni harus berupa angka.',
            'alumni_year.digits' => 'Tahun alumni harus 4 digit angka.',
            'alumni_year.min' => 'Tahun alumni tidak valid.',
            'alumni_year.max' => 'Tahun alumni tidak valid.',
            'major_id.required' => 'Jurusan harus dipilih.',
            'major_id.exists' => 'Jurusan yang dipilih tidak valid.',
            'description.required' => 'Deskripsi harus diisi.',
            'photo.required' => 'Foto harus diunggah.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, jpg, atau gif.',
            'photo.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        $validatedData['publisher'] = Auth::user()->name;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('testimonial_photos', 'public');
            $validatedData['photo'] = $photoPath;
        }

        Testimonial::create($validatedData);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $testimonial)
    {
        return view('admin.tables.testimonials.show', compact('testimonial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial)
    {
        $majors = Major::all();
        return view('admin.tables.testimonials.edit', compact('testimonial', 'majors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'alumni_year' => 'required|integer|digits:4|min:1900|max:' . (date('Y') + 5),
            'major_id' => 'required|exists:majors,id',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama harus diisi.',
            'alumni_year.required' => 'Tahun alumni harus diisi.',
            'alumni_year.integer' => 'Tahun alumni harus berupa angka.',
            'alumni_year.digits' => 'Tahun alumni harus 4 digit angka.',
            'alumni_year.min' => 'Tahun alumni tidak valid.',
            'alumni_year.max' => 'Tahun alumni tidak valid.',
            'major_id.required' => 'Jurusan harus dipilih.',
            'major_id.exists' => 'Jurusan yang dipilih tidak valid.',
            'description.required' => 'Deskripsi harus diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, jpg, atau gif.',
            'photo.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        $validatedData['publisher'] = Auth::user()->name;

        if ($request->hasFile('photo')) {
            if ($testimonial->photo) {
                Storage::disk('public')->delete($testimonial->photo);
            }
            $photoPath = $request->file('photo')->store('testimonial_photos', 'public');
            $validatedData['photo'] = $photoPath;
        }

        $testimonial->update($validatedData);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->photo) {
            Storage::disk('public')->delete($testimonial->photo);
        }

        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil dihapus!');
    }
}