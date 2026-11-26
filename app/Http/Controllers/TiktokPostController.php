<?php

namespace App\Http\Controllers;

use App\Models\TiktokPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        $videoUrl = $request->video_url;
        $videoId = $request->video_id;
        if ($videoUrl && empty($videoId)) {
            if (preg_match('/video\/(\d+)/', $videoUrl, $matches)) {
                $videoId = $matches[1];
            }
        }

        $caption = $request->caption;

        $path = null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('uploads/tiktok', 'public');
        } elseif ($videoUrl) {
            $resolved = $this->resolveCoverFromUrl($videoUrl, $videoId);
            $path = $resolved['path'] ?? null;
            if (blank($caption) && !blank($resolved['caption'] ?? null)) {
                $caption = $resolved['caption'];
            }
        }

        if (!$path) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Unggah cover secara manual, atau isi link video TikTok yang valid agar cover bisa diambil otomatis.');
        }

        TiktokPost::create([
            'caption'   => $caption,
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

    /**
     * Ambil cover video TikTok dari link (OG image) bila admin tidak mengunggah cover manual.
     *
     * @return array{path: string, caption: ?string}|null
     */
    private function resolveCoverFromUrl(string $videoUrl, ?string $videoId = null): ?array
    {
        if (!filter_var($videoUrl, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'facebookexternalhit/1.1 (+https://smkamaliah.sch.id)',
                    'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                ])
                ->get($videoUrl);
        } catch (\Throwable $e) {
            report($e);
            return null;
        }

        if (!$response->successful()) {
            return null;
        }

        $html = (string) $response->body();
        $imageUrl = $this->extractMetaContent($html, 'og:image')
            ?: $this->extractMetaContent($html, 'twitter:image');

        if (!$imageUrl) {
            return null;
        }

        $imageUrl = html_entity_decode($imageUrl, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($imageUrl);
        $host = strtolower((string) ($parts['host'] ?? ''));
        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        $allowedHost = $host === 'tiktokcdn.com'
            || str_ends_with($host, '.tiktokcdn.com')
            || str_ends_with($host, '.tiktokcdn-us.com')
            || str_ends_with($host, '.tiktokcdn-eu.com')
            || str_ends_with($host, '.byteimg.com')
            || str_ends_with($host, '.ibytedtos.com');

        if ($scheme !== 'https' || !$allowedHost) {
            return null;
        }

        try {
            $imgResponse = Http::timeout(15)->get($imageUrl);
        } catch (\Throwable $e) {
            report($e);
            return null;
        }

        if (!$imgResponse->successful()) {
            return null;
        }

        $contentType = strtolower(trim(explode(';', (string) $imgResponse->header('Content-Type'))[0]));
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        if (!isset($extensions[$contentType])) {
            $detected = @getimagesizefromstring($imgResponse->body());
            $mime = is_array($detected) ? (string) ($detected['mime'] ?? '') : '';
            if (!isset($extensions[$mime])) {
                return null;
            }
            $contentType = $mime;
        }

        $body = $imgResponse->body();
        if ($body === '' || strlen($body) > 10485760) {
            return null;
        }

        $name = $videoId ?: substr(hash('sha256', $videoUrl), 0, 24);
        $path = 'uploads/tiktok/auto-'.$name.'.'.$extensions[$contentType];
        Storage::disk('public')->put($path, $body);

        $caption = $this->extractMetaContent($html, 'og:description')
            ?: $this->extractMetaContent($html, 'description')
            ?: $this->extractMetaContent($html, 'og:title');
        $caption = is_string($caption) ? trim(html_entity_decode($caption, ENT_QUOTES | ENT_HTML5, 'UTF-8')) : null;
        if ($caption === '' || strcasecmp($caption, 'TikTok') === 0) {
            $caption = null;
        }

        return [
            'path' => $path,
            'caption' => $caption ? mb_substr($caption, 0, 255) : null,
        ];
    }

    private function extractMetaContent(string $html, string $property): ?string
    {
        $pattern = '/<meta[^>]+(?:property|name)=["\']'.preg_quote($property, '/').'["\'][^>]+content=["\']([^"\']+)["\'][^>]*>/i';
        if (preg_match($pattern, $html, $m)) {
            return $m[1];
        }
        // Atribut content bisa muncul sebelum property/name.
        $pattern2 = '/<meta[^>]+content=["\']([^"\']+)["\'][^>]+(?:property|name)=["\']'.preg_quote($property, '/').'["\'][^>]*>/i';
        if (preg_match($pattern2, $html, $m)) {
            return $m[1];
        }

        return null;
    }
}
