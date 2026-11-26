<?php

namespace App\Http\Controllers;

use App\Models\TiktokPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TiktokPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = TiktokPost::latest()->get();
        return view('admin.tiktok-posts.index', compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image_file' => 'required|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'caption'    => 'nullable|string|max:2000',
            'video_url'  => 'nullable|url|max:500',
            'video_id'   => 'nullable|string|max:100',
        ], [
            'image_file.required' => 'Cover video TikTok wajib diunggah.',
            'image_file.image'    => 'File harus berupa gambar.',
            'image_file.mimes'    => 'Format yang diizinkan: jpeg, png, jpg, webp, avif.',
            'image_file.max'      => 'Ukuran file maksimal 5MB.',
            'video_url.url'       => 'URL video TikTok tidak valid.',
        ]);

        $path = $request->file('image_file')->store('uploads/tiktok', 'public');

        // Ekstrak video_id dari URL jika ada
        $videoUrl = $request->video_url;
        $videoId = $request->video_id;
        if ($videoUrl && empty($videoId)) {
            if (preg_match('/video\/(\d+)/', $videoUrl, $matches)) {
                $videoId = $matches[1];
            }
        }

        TiktokPost::create([
            'caption'   => $request->caption,
            'path'      => $path,
            'video_url' => $videoUrl,
            'video_id'  => $videoId,
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->trimToLimit(9);

        return redirect()
            ->route('admin.tiktok-posts.index')
            ->with('success', 'Video TikTok berhasil ditambahkan.');
    }

    /**
     * Batasi jumlah data maksimal 9. Data lama otomatis dihapus permanen jika melebihi kuota.
     */
    protected function trimToLimit(int $limit = 9): void
    {
        $oldestFirst = TiktokPost::orderBy('created_at')->orderBy('id')->get();

        if ($oldestFirst->count() > $limit) {
            $oldestFirst
                ->take($oldestFirst->count() - $limit)
                ->each(function (TiktokPost $post) {
                    if ($post->path && Storage::disk('public')->exists($post->path)) {
                        Storage::disk('public')->delete($post->path);
                    }
                    $post->delete();
                });
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TiktokPost $tiktokPost)
    {
        $request->validate([
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'caption'    => 'nullable|string|max:2000',
            'video_url'  => 'nullable|url|max:500',
            'video_id'   => 'nullable|string|max:100',
        ], [
            'image_file.image' => 'File harus berupa gambar.',
            'image_file.mimes' => 'Format yang diizinkan: jpeg, png, jpg, webp, avif.',
            'image_file.max'   => 'Ukuran file maksimal 5MB.',
            'video_url.url'    => 'URL video TikTok tidak valid.',
        ]);

        if ($request->hasFile('image_file')) {
            Storage::disk('public')->delete($tiktokPost->path);
            $tiktokPost->path = $request->file('image_file')->store('uploads/tiktok', 'public');
        }

        $videoUrl = $request->video_url;
        $videoId = $request->video_id ?: $tiktokPost->video_id;
        if ($videoUrl && empty($request->video_id)) {
            if (preg_match('/video\/(\d+)/', $videoUrl, $matches)) {
                $videoId = $matches[1];
            }
        }

        $tiktokPost->caption   = $request->caption;
        $tiktokPost->video_url = $videoUrl;
        $tiktokPost->video_id  = $videoId;
        $tiktokPost->is_active = $request->boolean('is_active');
        $tiktokPost->save();

        return redirect()
            ->route('admin.tiktok-posts.index')
            ->with('success', 'Video TikTok berhasil diperbarui.');
    }

    /**
     * Toggle active status.
     */
    public function toggle(TiktokPost $tiktokPost)
    {
        $tiktokPost->is_active = !$tiktokPost->is_active;
        $tiktokPost->save();

        return redirect()
            ->route('admin.tiktok-posts.index')
            ->with('success', $tiktokPost->is_active ? 'Video TikTok diaktifkan.' : 'Video TikTok disembunyikan dari tampilan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TiktokPost $tiktokPost)
    {
        Storage::disk('public')->delete($tiktokPost->path);
        $tiktokPost->delete();

        return redirect()
            ->route('admin.tiktok-posts.index')
            ->with('success', 'Video TikTok berhasil dihapus.');
    }
}
