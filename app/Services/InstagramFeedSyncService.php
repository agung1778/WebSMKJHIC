<?php

namespace App\Services;

use App\Models\InstaPost;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class InstagramFeedSyncService
{
    private const MEDIA_FIELDS = 'id,caption,media_type,media_url,thumbnail_url,permalink,timestamp';

    private const MAX_IMAGE_BYTES = 10485760;

    /** Kode error Graph API yang menandakan token perlu di-refresh. */
    private const AUTH_ERROR_CODES = [190, 400, 401];

    public function isConfigured(): bool
    {
        if ((string) config('services.instagram.access_token') !== '') {
            return true;
        }

        $stored = $this->readTokenFile();

        return !empty($stored['access_token']);
    }

    /**
     * Ambil media Instagram terbaru lalu simpan ke tabel insta_posts.
     * Mengembalikan jumlah postingan yang berhasil disinkronkan.
     */
    public function sync(): int
    {
        [$accessToken, $accountId] = $this->resolveCredentials();
        $limit = (int) config('services.instagram.sync_limit', 9);

        $response = $this->requestMedia($accessToken, $accountId, $limit);

        if ($this->isAuthError($response)) {
            // Token kedaluwarsa di tengah jalan: refresh lalu coba sekali lagi.
            $tokens = $this->refreshAccessToken($accessToken);
            $response = $this->requestMedia($tokens['access_token'], $accountId, $limit);
        }

        if (!$response->successful()) {
            throw new RuntimeException('Instagram Graph API mengembalikan HTTP '.$response->status().'.');
        }

        $error = $response->json('error');
        if (is_array($error)) {
            $code = (string) ($error['code'] ?? '');
            if ($code !== '') {
                throw new RuntimeException(
                    'Instagram Graph API: '.((string) ($error['message'] ?? 'gagal')).' ['.$code.']'
                );
            }
        }

        $mediaItems = $response->json('data', []);
        if (!is_array($mediaItems)) {
            throw new RuntimeException('Daftar media Instagram tidak valid.');
        }

        $synced = 0;

        foreach (array_slice($mediaItems, 0, $limit) as $media) {
            if (!is_array($media) || empty($media['id'])) {
                continue;
            }

            $mediaId = (string) $media['id'];
            $permalink = is_string($media['permalink'] ?? null) ? $media['permalink'] : null;
            $existing = $this->findExisting($mediaId, $permalink);
            $imageUrl = $this->imageUrl($media);

            $imagePath = $existing && Storage::disk('public')->exists($existing->path)
                ? $existing->path
                : $this->downloadImage($imageUrl, $mediaId);

            if (!$imagePath) {
                continue;
            }

            $post = $existing ?? new InstaPost();
            $oldPath = $post->path;
            $post->instagram_media_id = $mediaId;
            $post->caption = $this->caption($media);
            $post->post_url = $permalink;
            $post->path = $imagePath;
            $post->is_active = $existing ? (bool) $existing->is_active : true;

            if (!$existing && !empty($media['timestamp'])) {
                try {
                    $post->created_at = Carbon::parse($media['timestamp']);
                } catch (Throwable) {
                    // Biarkan timestamp bawaan Eloquent bila nilai waktu tidak valid.
                }
            }

            $post->save();

            if ($oldPath && $oldPath !== $imagePath && str_starts_with($oldPath, 'uploads/instagram/')) {
                Storage::disk('public')->delete($oldPath);
            }

            $synced++;
        }

        $this->trimSyncedPosts($limit);

        return $synced;
    }

    /**
     * Kembalikan [access_token, account_id] yang siap dipakai.
     *
     * @return array{0: string, 1: string}
     */
    private function resolveCredentials(): array
    {
        $stored = $this->readTokenFile();
        $envAccess = trim((string) config('services.instagram.access_token'));
        $envAccount = trim((string) config('services.instagram.account_id'));

        $storedAccess = (string) ($stored['access_token'] ?? '');
        $expiresAt = (int) ($stored['expires_at'] ?? 0);

        if ($storedAccess !== '' && $expiresAt > time() + 60) {
            return [$storedAccess, (string) ($stored['account_id'] ?? '') ?: $envAccount];
        }

        // Token dari .env dipakai lebih dulu; refresh hanya bila benar-benar perlu.
        if ($envAccess !== '') {
            return [$envAccess, $envAccount];
        }

        if ($storedAccess !== '') {
            $tokens = $this->refreshAccessToken($storedAccess);

            return [$tokens['access_token'], $tokens['account_id'] ?: $envAccount];
        }

        throw new RuntimeException('Instagram Graph API belum dikonfigurasi.');
    }

    private function requestMedia(string $accessToken, string $accountId, int $limit): Response
    {
        $apiVersion = trim((string) config('services.instagram.api_version', 'v23.0'));

        if (!ctype_digit($accountId) || !preg_match('/^v\d+\.\d+$/D', $apiVersion)) {
            throw new RuntimeException('Konfigurasi Instagram Graph API tidak valid.');
        }

        return Http::timeout(20)
            ->withToken($accessToken)
            ->acceptJson()
            ->get($this->graphUrl($accountId.'/media'), [
                'fields' => self::MEDIA_FIELDS,
                'limit' => max(1, min(20, $limit)),
            ]);
    }

    private function isAuthError(Response $response): bool
    {
        if (in_array($response->status(), [401, 403], true)) {
            return true;
        }

        $code = (string) $response->json('error.code', '');

        return in_array($code, array_map('strval', self::AUTH_ERROR_CODES), true);
    }

    /** URL untuk authorize akun Instagram (dipakai command instagram:authorize). */
    public function authorizationUrl(string $state): string
    {
        $appId = trim((string) config('services.instagram.app_id'));
        if ($appId === '') {
            throw new RuntimeException('INSTAGRAM_APP_ID belum diisi.');
        }

        return 'https://www.facebook.com/'.$this->apiVersion().'/dialog/oauth/?'.http_build_query([
            'client_id' => $appId,
            'redirect_uri' => $this->redirectUri(),
            'state' => $state,
            'scope' => 'instagram_basic,pages_show_list',
            'response_type' => 'code',
        ]);
    }

    /**
     * Tukar authorization code menjadi long-lived token, lalu simpan ke storage lokal.
     * Account ID diambil otomatis dari Facebook Page yang terhubung ke akun Instagram.
     *
     * @return array{access_token: string, account_id: string, username: string}
     */
    public function exchangeAuthorizationCode(string $code): array
    {
        $appId = trim((string) config('services.instagram.app_id'));
        $appSecret = trim((string) config('services.instagram.app_secret'));

        if ($appId === '' || $appSecret === '') {
            throw new RuntimeException('INSTAGRAM_APP_ID dan INSTAGRAM_APP_SECRET wajib diisi.');
        }

        // 1. Kode otorisasi -> short-lived token.
        $response = Http::timeout(20)->get($this->graphUrl('oauth/access_token'), [
            'client_id' => $appId,
            'client_secret' => $appSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUri(),
        ]);

        $shortLived = (string) ($response->json('access_token') ?? '');
        if (!$response->successful() || $shortLived === '') {
            throw new RuntimeException('Gagal menukar kode otorisasi Instagram (HTTP '.$response->status().').');
        }

        // 2. Short-lived -> long-lived token (~60 hari).
        $response = Http::timeout(20)->get($this->graphUrl('oauth/access_token'), [
            'grant_type' => 'fb_exchange_token',
            'client_id' => $appId,
            'client_secret' => $appSecret,
            'fb_exchange_token' => $shortLived,
        ]);

        $longLived = (string) ($response->json('access_token') ?? '');
        if (!$response->successful() || $longLived === '') {
            throw new RuntimeException('Gagal membuat long-lived token Instagram (HTTP '.$response->status().').');
        }

        $data = $response->json();
        $data = is_array($data) ? $data : [];

        // 3. Cari Instagram Business Account dari Page yang terhubung.
        $accountId = trim((string) config('services.instagram.account_id'));
        $username = '';

        $pages = Http::timeout(20)->get($this->graphUrl('me/accounts'), [
            'fields' => 'id,name,instagram_business_account{id,username}',
            'limit' => 100,
            'access_token' => $longLived,
        ]);

        if ($pages->successful()) {
            foreach ((array) $pages->json('data', []) as $page) {
                $instagram = is_array($page) ? ($page['instagram_business_account'] ?? null) : null;
                if (is_array($instagram) && !empty($instagram['id'])) {
                    $accountId = (string) $instagram['id'];
                    $username = (string) ($instagram['username'] ?? '');
                    break;
                }
            }
        }

        if ($accountId === '') {
            throw new RuntimeException(
                'Instagram Business Account tidak ditemukan. Hubungkan akun ke Facebook Page atau isi INSTAGRAM_BUSINESS_ACCOUNT_ID.'
            );
        }

        $data['account_id'] = $accountId;
        $tokens = $this->storeTokenData($data, $accountId, $longLived);
        $tokens['username'] = $username;

        return $tokens;
    }

    /** Perpanjang long-lived token Instagram tanpa perlu login ulang. */
    private function refreshAccessToken(string $accessToken): array
    {
        $response = Http::timeout(20)->get($this->graphUrl('oauth/access_token'), [
            'grant_type' => 'ig_refresh_token',
            'access_token' => $accessToken,
        ]);

        if (!$response->successful()) {
            throw new RuntimeException('Gagal me-refresh token Instagram (HTTP '.$response->status().').');
        }

        $data = $response->json();
        if (!is_array($data) || empty($data['access_token'])) {
            throw new RuntimeException('Respons refresh token Instagram tidak valid.');
        }

        $stored = $this->readTokenFile();
        $accountId = (string) ($stored['account_id'] ?? '') ?: (string) config('services.instagram.account_id', '');

        return $this->storeTokenData($data, $accountId, $accessToken);
    }

    /**
     * @return array{access_token: string, account_id: string}
     */
    private function storeTokenData(array $data, string $fallbackAccountId = '', string $fallbackToken = ''): array
    {
        $accessToken = (string) ($data['access_token'] ?? '') ?: $fallbackToken;
        if ($accessToken === '') {
            throw new RuntimeException('Token Instagram tidak valid.');
        }

        $accountId = (string) ($data['account_id'] ?? '') ?: $fallbackAccountId;
        $expiresIn = max(60, (int) ($data['expires_in'] ?? 5184000));

        $this->writeTokenFile([
            'access_token' => $accessToken,
            'account_id' => $accountId,
            'expires_at' => time() + $expiresIn - 300,
            'updated_at' => time(),
        ]);

        return ['access_token' => $accessToken, 'account_id' => $accountId];
    }

    private function redirectUri(): string
    {
        $configured = trim((string) config('services.instagram.redirect_uri'));

        return $configured !== ''
            ? $configured
            : rtrim((string) config('app.url'), '/').'/';
    }

    private function apiVersion(): string
    {
        return trim((string) config('services.instagram.api_version', 'v23.0'));
    }

    private function graphUrl(string $path): string
    {
        $base = rtrim((string) config('services.instagram.api_base', 'https://graph.facebook.com'), '/');

        return $base.'/'.$this->apiVersion().'/'.ltrim($path, '/');
    }

    /** URL gambar media: video/reels memakai thumbnail. */
    private function imageUrl(array $media): ?string
    {
        $type = strtoupper((string) ($media['media_type'] ?? ''));
        $mediaUrl = is_string($media['media_url'] ?? null) ? $media['media_url'] : null;
        $thumbnail = is_string($media['thumbnail_url'] ?? null) ? $media['thumbnail_url'] : null;

        if (in_array($type, ['VIDEO', 'REELS'], true)) {
            return $thumbnail ?: $mediaUrl;
        }

        return $mediaUrl;
    }

    /**
     * Samakan postingan hasil sinkron dengan postingan manual yang sudah memakai
     * ID numerik Graph API, shortcode dari link, atau URL persis yang sama.
     */
    private function findExisting(string $mediaId, ?string $permalink): ?InstaPost
    {
        $post = InstaPost::where('instagram_media_id', $mediaId)->first();
        if ($post) {
            return $post;
        }

        if ($permalink) {
            $post = InstaPost::where('post_url', $permalink)->first();
            if ($post) {
                return $post;
            }

            if (preg_match('~/(?:p|reel|reels|tv)/([A-Za-z0-9_-]+)~i', $permalink, $matches)) {
                $shortcode = $matches[1];
                $post = InstaPost::where('instagram_media_id', $shortcode)->first();
                if ($post) {
                    return $post;
                }
            }
        }

        return null;
    }

    /**
     * URL media Instagram hanya berlaku sementara, jadi selalu unduh ulang.
     * Bila unduhan gagal, gambar lama yang sudah tersimpan tetap dipakai.
     */
    private function downloadImage(?string $url, string $mediaId): ?string
    {
        $url = is_string($url) ? $url : '';
        if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($url);
        $host = strtolower((string) ($parts['host'] ?? ''));
        $isInstagramCdn = str_ends_with($host, '.cdninstagram.com')
            || str_ends_with($host, '.fbcdn.net')
            || str_ends_with($host, '.instagram.com');

        if (($parts['scheme'] ?? '') !== 'https' || !$isInstagramCdn) {
            return null;
        }

        try {
            $response = Http::timeout(15)->get($url);
        } catch (Throwable $e) {
            report($e);

            return null;
        }

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

        $path = 'uploads/instagram/synced/'.hash('sha256', $mediaId).'.'.$extensions[$contentType];
        Storage::disk('public')->put($path, $image);

        return $path;
    }

    private function caption(array $media): ?string
    {
        $caption = trim((string) ($media['caption'] ?? ''));

        return $caption === '' ? null : mb_substr($caption, 0, 255);
    }

    /** Hapus hanya media hasil sync yang melebihi kuota; input manual tidak disentuh. */
    private function trimSyncedPosts(int $limit): void
    {
        InstaPost::where('path', 'like', 'uploads/instagram/synced/%')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->skip($limit)
            ->each(function (InstaPost $post) {
                Storage::disk('public')->delete($post->path);
                $post->delete();
            });
    }

    private function readTokenFile(): array
    {
        $disk = Storage::disk('local');
        $path = $this->tokenPath();

        if (!$disk->exists($path)) {
            return [];
        }

        $data = json_decode((string) $disk->get($path), true);

        return is_array($data) ? $data : [];
    }

    private function writeTokenFile(array $data): void
    {
        Storage::disk('local')->put(
            $this->tokenPath(),
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    private function tokenPath(): string
    {
        return trim((string) config('services.instagram.token_path', 'private/instagram-oauth.json'));
    }
}
