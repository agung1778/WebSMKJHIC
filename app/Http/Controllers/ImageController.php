<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = Image::latest()->get();

        $imageCounts = Image::query()
            ->select('title', DB::raw('count(*) as total'))
            ->whereNotNull('title')
            ->groupBy('title')
            ->pluck('total', 'title');

        return view('admin.tables.image.index', compact('images', 'imageCounts'));
    }

    /**
     * Show the form for creating a new resource (Redirect ke index).
     */
    public function create()
    {
        // Form upload ada di index view
        return redirect()->route('admin.image.index');
    }

    /**
     * Store a newly created resource in storage (Logika Upload).
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'image_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'image_file.required' => 'File gambar harus diunggah.',
            'image_file.image' => 'File harus berupa gambar.',
            'image_file.mimes' => 'Format yang diizinkan: jpeg, png, jpg, gif, svg, webp.',
            'image_file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filePath = $file->store('uploads/media', 'public');

            // 3. Simpan Metadata ke Database
            Image::create([
                'title' => $request->title,
                'description' => $request->description,
                'filename' => $file->getClientOriginalName(),
                'path' => $filePath,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);

            return redirect()->route('admin.image.index')->with('success', 'Gambar berhasil diunggah!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah gambar.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        return view('admin.tables.image.show', compact('image'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Image $image)
    {
        return view('admin.tables.image.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        $data = $request->validate([
            'filename' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',      // Validasi Title
            'description' => 'nullable|string',        // Validasi Description
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ], [
            'image_file.image' => 'File harus berupa gambar.',
            'image_file.mimes' => 'Format yang diizinkan: jpeg, png, jpg, gif, svg, webp.',
            'image_file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        // Pertahankan nama file jika tidak diganti
        $data['filename'] = $request->filename ?: $image->filename;
        $data['title'] = $request->title ?: $image->title;

        // Jika file baru diunggah, ganti file lama
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filePath = $file->store('uploads/media', 'public');

            // Hapus file lama dari storage
            if ($image->path && str_starts_with($image->path, 'uploads/')) {
                Storage::disk('public')->delete($image->path);
            }

            $data['path'] = $filePath;
            $data['mime_type'] = $file->getClientMimeType();
            $data['size'] = $file->getSize();
        }

        $image->update($data);

        return redirect()->route('admin.image.show', $image->id)->with('success', 'Gambar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        // Hapus file dari storage
        Storage::disk('public')->delete($image->path);

        // Hapus entri dari database
        $image->delete();

        return redirect()->route('admin.image.index')->with('success', 'Gambar berhasil dihapus.');
    }
}
