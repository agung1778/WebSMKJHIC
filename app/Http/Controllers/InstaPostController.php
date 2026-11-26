<?php

namespace App\Http\Controllers;

use App\Models\InstaPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'caption'    => 'nullable|string|max:2000',
            'post_url'   => 'nullable|url|max:500',
            'instagram_media_id' => 'nullable|string|max:100',
        ], [
            'image_file.image' => 'File harus berupa gambar.',
            'image_file.mimes' => 'Format yang diizinkan: jpeg, png, jpg, webp, avif.',
            'image_file.max'   => 'Ukuran file maksimal 5MB.',
            'post_url.url'     => 'URL postingan Instagram tidak valid.',
        ]);

        $postUrl = $request->post_url;
        $mediaId = trim((string) $request->instagram_media_id);
        if ($postUrl && $mediaId === '') {
            $mediaId = $this->extractShortcode($postUrl) ?? '';
        }

        $caption = $this->cleanCaption($request->caption);

        $path = null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('uploads/instagram', 'public');
        } elseif ($postUrl) {
            $resolved = $this->resolveCoverFromUrl($postUrl, $mediaId);
            $path = $resolved['path'] ?? null;
            if (blank($caption) && !blank($resolved['caption'] ?? null)) {
                $caption = $resolved['caption'];
            }
            if ($mediaId === '' && !blank($resolved['media_id'] ?? null)) {
                $mediaId = $resolved['media_id'];
            }
        }

        if (!$path) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Unggah foto secara manual, atau isi link postingan Instagram yang valid agar foto bisa diambil otomatis.');
        }

        InstaPost::create([
            'caption'   => $caption,
            'path'      => $path,
            'post_url'  => $postUrl,
            'instagram_media_id' => $mediaId !== '' ? $mediaId : null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->trimToLimit(9);

        return redirect()
            ->route('admin.insta-posts.index')
            ->with('success', 'Postingan Instagram berhasil ditambahkan.');
    }

    /**
     * Batasi jumlah data maksimal 9. Data lama otomatis dihapus permanen jika melebihi kuota.
     */
    protected function trimToLimit(int $limit = 9): void
    {
        $oldestFirst = InstaPost::orderBy('created_at')->orderBy('id')->get();

        if ($oldestFirst->count() > $limit) {
            $oldestFirst
                ->take($oldestFirst->count() - $limit)
                ->each(function (InstaPost $post) {
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
    public function update(Request $request, InstaPost $instaPost)
    {
        $request->validate([
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'caption'    => 'nullable|string|max:2000',
            'post_url'   => 'nullable|url|max:500',
            'instagram_media_id' => 'nullable|string|max:100',
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

        $postUrl = $request->post_url;
        $mediaId = $request->instagram_media_id ?: $instaPost->instagram_media_id;
        if ($postUrl && empty($request->instagram_media_id)) {
            $mediaId = $this->extractShortcode($postUrl) ?: $mediaId;
        }

        $instaPost->caption = $this->cleanCaption($request->caption);
        $instaPost->post_url = $postUrl;
        $instaPost->instagram_media_id = $mediaId ?: null;
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

    private function cleanCaption(mixed $caption): ?string
    {
        $caption = is_string($caption) ? trim($caption) : '';

        return $caption === '' ? null : mb_substr($caption, 0, 255);
    }

    private function extractShortcode(string $url): ?string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        return preg_match('~/(?:p|reel|reels|tv)/([A-Za-z0-9_-]+)~i', $url, $matches)
            ? $matches[1]
            : null;
    }

    /**
     * Ambil foto postingan Instagram dari link (OG image) bila admin tidak mengunggah manual.
     *
     * @return array{path: string, caption: ?string, media_id: ?string}|null
     */
    private function resolveCoverFromUrl(string $postUrl, string $mediaId = ''): ?array
    {
        if (!filter_var($postUrl, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'facebookexternalhit/1.1 (+https://smkamaliah.sch.id)',
                    'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                ])
                ->get($postUrl);
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
        $allowedHost = str_ends_with($host, '.cdninstagram.com')
            || str_ends_with($host, '.fbcdn.net')
            || str_ends_with($host, '.instagram.com');

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

        $name = $mediaId !== '' ? $mediaId : substr(hash('sha256', $postUrl), 0, 24);
        $path = 'uploads/instagram/auto-'.$name.'.'.$extensions[$contentType];
        Storage::disk('public')->put($path, $body);

        $caption = $this->extractMetaContent($html, 'og:description')
            ?: $this->extractMetaContent($html, 'description')
            ?: $this->extractMetaContent($html, 'og:title');
        $caption = is_string($caption)
            ? trim(html_entity_decode($caption, ENT_QUOTES | ENT_HTML5, 'UTF-8'))
            : null;
        $caption = $caption
            ? preg_replace('/\s*[\x{2022}\x{00B7}]\s*Instagram photos and videos.*$/iu', '', $caption)
            : null;
        if ($caption === '' || strcasecmp((string) $caption, 'Instagram') === 0) {
            $caption = null;
        }

        return [
            'path' => $path,
            'caption' => $caption ? mb_substr($caption, 0, 255) : null,
            'media_id' => $mediaId !== '' ? $mediaId : $this->extractShortcode($postUrl),
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
