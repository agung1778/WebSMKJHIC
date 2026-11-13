<?php

namespace App\Services;

use App\Models\InstaPost;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class InstagramFeedSyncService
{
    private const MEDIA_LIMIT = 15;
    private const MAX_IMAGE_BYTES = 10485760;

    public function sync(): int
    {
        $accountId = (string) config('services.instagram.account_id');
        $accessToken = (string) config('services.instagram.access_token');
        $apiVersion = (string) config('services.instagram.api_version');

        if ($accountId === '' || $accessToken === '') {
            throw new RuntimeException('Instagram Graph API belum dikonfigurasi.');
        }

        if (!ctype_digit($accountId) || !preg_match('/^v\d+\.\d+$/D', $apiVersion)) {
            throw new RuntimeException('Konfigurasi Instagram Graph API tidak valid.');
        }

        $response = Http::timeout(20)
            ->withToken($accessToken)
            ->get("https://graph.facebook.com/{$apiVersion}/{$accountId}/media", [
                'fields' => 'id,caption,media_type,media_url,thumbnail_url,permalink,timestamp',
                'limit' => self::MEDIA_LIMIT,
            ])
            ->throw();

        $mediaItems = $response->json('data', []);
        if (!is_array($mediaItems)) {
            throw new RuntimeException('Respons Instagram Graph API tidak valid.');
        }

        $syncedCount = 0;

        foreach (array_reverse(array_slice($mediaItems, 0, self::MEDIA_LIMIT)) as $media) {
            if (!is_array($media) || empty($media['id'])) {
                continue;
            }

            $mediaId = (string) $media['id'];
            $existingPost = InstaPost::where('instagram_media_id', $mediaId)->first();
            $imageUrl = ($media['media_type'] ?? '') === 'VIDEO'
                ? ($media['thumbnail_url'] ?? null)
                : ($media['media_url'] ?? null);

            $imagePath = $existingPost && Storage::disk('public')->exists($existingPost->path)
                ? $existingPost->path
                : $this->downloadImage($imageUrl, $mediaId);

            if (!$imagePath) {
                continue;
            }

            $post = $existingPost ?? new InstaPost();
            $oldPath = $post->path;
            $post->instagram_media_id = $mediaId;
            $post->caption = empty($media['caption']) ? null : Str::limit((string) $media['caption'], 255);
            $post->path = $imagePath;
            $post->post_url = $media['permalink'] ?? null;
            $post->is_active = $existingPost ? $existingPost->is_active : true;
            if (!$existingPost && !empty($media['timestamp'])) {
                try {
                    $post->created_at = Carbon::parse($media['timestamp']);
                } catch (\Throwable) {
                }
            }
            $post->save();

            if ($oldPath && $oldPath !== $imagePath && str_starts_with($oldPath, 'uploads/instagram/')) {
                Storage::disk('public')->delete($oldPath);
            }

            $syncedCount++;
        }

        InstaPost::whereNotNull('instagram_media_id')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->skip(self::MEDIA_LIMIT)
            ->each(function (InstaPost $post) {
                Storage::disk('public')->delete($post->path);
                $post->delete();
            });

        return $syncedCount;
    }

    private function downloadImage(?string $url, string $mediaId): ?string
    {
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');
        $isInstagramMediaHost = str_ends_with($host, '.cdninstagram.com')
            || str_ends_with($host, '.fbcdn.net');

        if (($parts['scheme'] ?? '') !== 'https' || !$isInstagramMediaHost) {
            return null;
        }

        $response = Http::timeout(15)->get($url);
        if (!$response->successful()) {
            return null;
        }

        $contentType = strtolower(trim(explode(';', (string) $response->header('Content-Type'))[0]));
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($extensions[$contentType])) {
            return null;
        }

        $image = $response->body();
        if ($image === '' || strlen($image) > self::MAX_IMAGE_BYTES) {
            return null;
        }

        $path = 'uploads/instagram/' . hash('sha256', $mediaId) . '.' . $extensions[$contentType];
        Storage::disk('public')->put($path, $image);

        return $path;
    }
}