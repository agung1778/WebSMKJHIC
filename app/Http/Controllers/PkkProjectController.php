<?php

namespace App\Http\Controllers;

use App\Models\PkkProject;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PkkProjectController extends Controller
{
    public function index()
    {
        // Menggunakan with('major') agar query lebih efisien (Eager Loading)
        $projects = PkkProject::with('major')->latest()->get();
        return view('admin.tables.pkk.index', compact('projects'));
    }

    public function create()
    {
        // Kita butuh data jurusan untuk Dropdown
        $majors = Major::all();
        return view('admin.tables.pkk.create', compact('majors'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'major_id' => 'required|exists:majors,id',
            'title' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255', // Baru
            'student_names' => 'required|string|max:255',
            'student_class' => 'required|string|max:50',
            'category' => 'required|string|max:100',
            'price' => 'nullable|numeric',
            'contact_info' => 'nullable|string|max:20', // Baru
            'social_media' => 'nullable|url', // Baru (harus format link)
            'description' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // Baru
        ]);

        // Upload Foto Produk
        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')->store('pkk_gallery', 'public');
        }

        // Upload Logo Brand (Baru)
        if ($request->hasFile('logo')) {
            $validatedData['logo'] = $request->file('logo')->store('pkk_logos', 'public');
        }

        PkkProject::create($validatedData);

        return redirect()->route('admin.pkk.index')->with('success', 'Project PKK berhasil ditambahkan!');
    }

    public function show(PkkProject $pkk)
    {
        return view('admin.tables.pkk.show', compact('pkk'));
    }

    public function edit(PkkProject $pkk)
    {
        $majors = Major::all();
        return view('admin.tables.pkk.edit', compact('pkk', 'majors'));
    }

    public function update(Request $request, PkkProject $pkk)
    {
        $validatedData = $request->validate([
            'major_id' => 'required|exists:majors,id',
            'title' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'student_names' => 'required|string|max:255',
            'student_class' => 'required|string|max:50',
            'category' => 'required|string|max:100',
            'price' => 'nullable|numeric',
            'contact_info' => 'nullable|string|max:20',
            'social_media' => 'nullable|url',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        ]);

        // Update Foto Produk
        if ($request->hasFile('photo')) {
            if ($pkk->photo) Storage::disk('public')->delete($pkk->photo);
            $validatedData['photo'] = $request->file('photo')->store('pkk_gallery', 'public');
        }

        // Update Logo Brand
        if ($request->hasFile('logo')) {
            if ($pkk->logo) Storage::disk('public')->delete($pkk->logo);
            $validatedData['logo'] = $request->file('logo')->store('pkk_logos', 'public');
        }

        $pkk->update($validatedData);

        return redirect()->route('admin.pkk.index')->with('success', 'Project PKK berhasil diperbarui!');
    }

    public function destroy(PkkProject $pkk)
    {
        if ($pkk->photo) Storage::disk('public')->delete($pkk->photo);
        if ($pkk->logo) Storage::disk('public')->delete($pkk->logo); // Hapus logo

        $pkk->delete();
        return redirect()->route('admin.pkk.index')->with('success', 'Project PKK berhasil dihapus!');
    }
}
