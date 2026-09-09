<?php

namespace App\Http\Controllers;

use App\Models\InstaPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstaPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = InstaPost::latest()->get();
        return view('admin.insta-posts.index', compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image_file' => 'required|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'caption'    => 'nullable|string|max:2000',
            'post_url'   => 'nullable|url|max:500',
        ], [
            'image_file.required' => 'Foto Instagram wajib diunggah.',
            'image_file.image'    => 'File harus berupa gambar.',
            'image_file.mimes'    => 'Format yang diizinkan: jpeg, png, jpg, webp, avif.',
            'image_file.max'      => 'Ukuran file maksimal 5MB.',
            'post_url.url'        => 'URL postingan Instagram tidak valid.',
        ]);

        $path = $request->file('image_file')->store('uploads/instagram', 'public');

        InstaPost::create([
            'caption'   => $request->caption,
            'path'      => $path,
            'post_url'  => $request->post_url,
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->trimToLimit();

        return redirect()
            ->route('admin.insta-posts.index')
            ->with('success', 'Postingan Instagram berhasil ditambahkan.');
    }

    /**
     * Batasi jumlah data maksimal 16. Data lama otomatis dihapus jika melebihi.
     */
    protected function trimToLimit(int $limit = 16): void
    {
        $oldestFirst = InstaPost::orderBy('created_at')->orderBy('id')->get();

        if ($oldestFirst->count() > $limit) {
            $oldestFirst
                ->take($oldestFirst->count() - $limit)
                ->each(function (InstaPost $post) {
                    Storage::disk('public')->delete($post->path);
                    $post->delete();
                });
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InstaPost $instaPost)
    {
        $request->validate([
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'caption'    => 'nullable|string|max:2000',
            'post_url'   => 'nullable|url|max:500',
        ], [
            'image_file.image' => 'File harus berupa gambar.',
            'image_file.mimes' => 'Format yang diizinkan: jpeg, png, jpg, webp, avif.',
            'image_file.max'   => 'Ukuran file maksimal 5MB.',
            'post_url.url'     => 'URL postingan Instagram tidak valid.',
        ]);

        if ($request->hasFile('image_file')) {
            Storage::disk('public')->delete($instaPost->path);
            $instaPost->path = $request->file('image_file')->store('uploads/instagram', 'public');
        }

        $instaPost->caption   = $request->caption;
        $instaPost->post_url  = $request->post_url;
        $instaPost->is_active = $request->boolean('is_active');
        $instaPost->save();

        return redirect()
            ->route('admin.insta-posts.index')
            ->with('success', 'Postingan Instagram berhasil diperbarui.');
    }

    /**
     * Toggle active status.
     */
    public function toggle(InstaPost $instaPost)
    {
        $instaPost->is_active = !$instaPost->is_active;
        $instaPost->save();

        return redirect()
            ->route('admin.insta-posts.index')
            ->with('success', $instaPost->is_active ? 'Postingan diaktifkan.' : 'Postingan disembunyikan dari tampilan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstaPost $instaPost)
    {
        Storage::disk('public')->delete($instaPost->path);
        $instaPost->delete();

        return redirect()
            ->route('admin.insta-posts.index')
            ->with('success', 'Postingan Instagram berhasil dihapus.');
    }
}
