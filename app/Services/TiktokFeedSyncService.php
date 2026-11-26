<?php

namespace App\Services;

use App\Models\TiktokPost;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class TiktokFeedSyncService
{
    /** Field Video Object yang didukung endpoint /v2/video/list/ (Display API v2). */
    private const VIDEO_FIELDS = 'id,create_time,cover_image_url,video_description,title,share_url';

    private const MAX_IMAGE_BYTES = 10485760;

    /** Kode error TikTok yang menandakan token perlu di-refresh. */
    private const AUTH_ERROR_CODES = ['10001', '10002', '10404', '10502'];

    public function isConfigured(): bool
    {
        if ((string) config('services.tiktok.access_token') !== '') {
            return true;
        }

        if ((string) config('services.tiktok.refresh_token') !== '') {
            return true;
        }

        $stored = $this->readTokenFile();

        return !empty($stored['access_token']) || !empty($stored['refresh_token']);
    }

    /**
     * Ambil video TikTok terbaru lalu simpan ke tabel tiktok_posts.
     * Mengembalikan jumlah video yang berhasil disinkronkan.
     */
    public function sync(): int
    {
        [$accessToken, $refreshToken] = $this->resolveCredentials();
        $limit = (int) config('services.tiktok.sync_limit', 9);

        $response = $this->requestVideos($accessToken, $limit);

        if ($this->isAuthError($response) && $refreshToken !== '') {
            // Token kedaluwarsa di tengah jalan: refresh lalu coba sekali lagi.
            $tokens = $this->refreshAccessToken($refreshToken);
            $response = $this->requestVideos($tokens['access_token'], $limit);
        }

        if (!$response->successful()) {
            throw new RuntimeException('TikTok API mengembalikan HTTP '.$response->status().'.');
        }

        $payload = $response->json();
        if (!is_array($payload)) {
            throw new RuntimeException('Respons TikTok API tidak valid.');
        }

        $error = $payload['error'] ?? [];
        $errorCode = (string) ($error['code'] ?? '');
        if ($errorCode !== '' && $errorCode !== 'ok') {
            throw new RuntimeException(
                'TikTok API: '.((string) ($error['message'] ?? 'gagal')).' ['.$errorCode.']'
            );
        }

        $videos = $payload['data']['videos'] ?? [];
        if (!is_array($videos)) {
            throw new RuntimeException('Daftar video TikTok tidak valid.');
        }

        $synced = 0;

        foreach ($videos as $video) {
            if (!is_array($video)) {
                continue;
            }

            $videoId = (string) ($video['id'] ?? '');
            if ($videoId === '' || !ctype_digit($videoId)) {
                continue;
            }

            $existing = TiktokPost::where('video_id', $videoId)->first();
            $coverPath = $this->downloadCover($video['cover_image_url'] ?? null, $videoId, $existing);

            if (!$coverPath) {
                continue;
            }

            $post = $existing ?? new TiktokPost();
            $post->video_id = $videoId;
            $post->caption = $this->caption($video);
            $post->video_url = $this->videoUrl($video, $videoId);
            $post->path = $coverPath;
            $post->is_active = $existing ? (bool) $existing->is_active : true;

            if (!$existing && !empty($video['create_time']) && ctype_digit((string) $video['create_time'])) {
                try {
                    $post->created_at = Carbon::createFromTimestampUTC((int) $video['create_time']);
                } catch (Throwable) {
                    // Biarkan timestamp bawaan Eloquent bila nilai waktu tidak valid.
                }
            }

            $post->save();
            $synced++;
        }

        $this->trimSyncedPosts($limit);

        return $synced;
    }

    /**
     * Kembalikan [access_token, refresh_token] yang siap dipakai.
     *
     * @return array{0: string, 1: string}
     */
    private function resolveCredentials(): array
    {
        $stored = $this->readTokenFile();
        $envAccess = trim((string) config('services.tiktok.access_token'));
        $envRefresh = trim((string) config('services.tiktok.refresh_token'));

        $refreshToken = (string) ($stored['refresh_token'] ?? '') ?: $envRefresh;

        $storedAccess = (string) ($stored['access_token'] ?? '');
        $expiresAt = (int) ($stored['expires_at'] ?? 0);

        if ($storedAccess !== '' && $expiresAt > time() + 60) {
            return [$storedAccess, $refreshToken];
        }

        // Token dari .env dipakai lebih dulu; refresh hanya bila benar-benar perlu.
        if ($envAccess !== '') {
            return [$envAccess, $refreshToken];
        }

        if ($refreshToken !== '') {
            $tokens = $this->refreshAccessToken($refreshToken);

            return [$tokens['access_token'], $tokens['refresh_token']];
        }

        throw new RuntimeException('TikTok Display API belum dikonfigurasi.');
    }

    private function requestVideos(string $accessToken, int $limit): \Illuminate\Http\Client\Response
    {
        $base = (string) config('services.tiktok.api_base', 'https://open.tiktokapis.com');
        $url = $base.'/v2/video/list/?'.http_build_query(['fields' => self::VIDEO_FIELDS]);

        return Http::timeout(20)
            ->withToken($accessToken)
            ->acceptJson()
            ->post($url, ['max_count' => max(1, min(20, $limit))]);
    }

    private function isAuthError(\Illuminate\Http\Client\Response $response): bool
    {
        if (in_array($response->status(), [401, 403], true)) {
            return true;
        }

        $code = (string) $response->json('error.code', '');

        return in_array($code, self::AUTH_ERROR_CODES, true);
    }

    /** URL untuk authorize akun TikTok (dipakai command tiktok:authorize). */
    public function authorizationUrl(string $state): string
    {
        $clientKey = trim((string) config('services.tiktok.client_key'));
        if ($clientKey === '') {
            throw new RuntimeException('TIKTOK_CLIENT_KEY belum diisi.');
        }

        return 'https://www.tiktok.com/v2/auth/authorize/?'.http_build_query([
            'client_key' => $clientKey,
            'redirect_uri' => $this->redirectUri(),
            'state' => $state,
            'scope' => 'user.info.basic,video.list',
            'response_type' => 'code',
        ]);
    }

    /** Tukar authorization code menjadi token, lalu simpan ke storage lokal. */
    public function exchangeAuthorizationCode(string $code): array
    {
        $clientKey = trim((string) config('services.tiktok.client_key'));
        $clientSecret = trim((string) config('services.tiktok.client_secret'));

        if ($clientKey === '' || $clientSecret === '') {
            throw new RuntimeException('TIKTOK_CLIENT_KEY dan TIKTOK_CLIENT_SECRET wajib diisi.');
        }

        $base = (string) config('services.tiktok.api_base', 'https://open.tiktokapis.com');

        $response = Http::timeout(20)->asForm()->post($base.'/v2/oauth/token/', [
            'client_key' => $clientKey,
            'client_secret' => $clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectUri(),
        ]);

        if (!$response->successful()) {
            throw new RuntimeException('Gagal menukar kode otorisasi TikTok (HTTP '.$response->status().').');
        }

        $data = $response->json('data');
        if (!is_array($data) || empty($data['access_token'])) {
            throw new RuntimeException('Respons token TikTok tidak valid. Periksa kode otorisasi dan redirect URI.');
        }

        return $this->storeTokenData($data, '');
    }

    /**
     * @return array{access_token: string, refresh_token: string}
     */
    private function refreshAccessToken(string $refreshToken): array
    {
        $clientKey = trim((string) config('services.tiktok.client_key'));
        $clientSecret = trim((string) config('services.tiktok.client_secret'));

        if ($clientKey === '' || $clientSecret === '') {
            throw new RuntimeException('TIKTOK_CLIENT_KEY dan TIKTOK_CLIENT_SECRET wajib diisi untuk refresh token TikTok.');
        }

        $base = (string) config('services.tiktok.api_base', 'https://open.tiktokapis.com');

        $response = Http::timeout(20)->asForm()->post($base.'/v2/oauth/token/', [
            'client_key' => $clientKey,
            'client_secret' => $clientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]);

        if (!$response->successful()) {
            throw new RuntimeException('Gagal me-refresh token TikTok (HTTP '.$response->status().').');
        }

        $data = $response->json('data');
        if (!is_array($data) || empty($data['access_token'])) {
            throw new RuntimeException('Respons refresh token TikTok tidak valid.');
        }

        return $this->storeTokenData($data, $refreshToken);
    }

    /**
     * @return array{access_token: string, refresh_token: string, open_id: string}
     */
    private function storeTokenData(array $data, string $fallbackRefreshToken): array
    {
        $accessToken = (string) $data['access_token'];
        $newRefreshToken = (string) ($data['refresh_token'] ?? '') ?: $fallbackRefreshToken;
        $expiresIn = max(60, (int) ($data['expires_in'] ?? 86400));
        $refreshExpiresIn = max(0, (int) ($data['refresh_expires_in'] ?? 31536000));

        $openId = (string) ($data['open_id'] ?? config('services.tiktok.open_id') ?? '');

        $this->writeTokenFile([
            'access_token' => $accessToken,
            'refresh_token' => $newRefreshToken,
            'expires_at' => time() + $expiresIn - 300,
            'refresh_expires_at' => $refreshExpiresIn > 0 ? time() + $refreshExpiresIn : 0,
            'open_id' => $openId,
            'updated_at' => time(),
        ]);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $newRefreshToken,
            'open_id' => $openId,
        ];
    }

    private function redirectUri(): string
    {
        $configured = trim((string) config('services.tiktok.redirect_uri'));

        return $configured !== ''
            ? $configured
            : rtrim((string) config('app.url'), '/').'/';
    }

    /**
     * Cover TikTok hanya berlaku ~6 jam, jadi selalu unduh ulang.
     * Bila unduhan gagal, cover lama yang sudah tersimpan tetap dipakai.
     */
    private function downloadCover(mixed $url, string $videoId, ?TiktokPost $existing): ?string
    {
        $fallback = $existing && Storage::disk('public')->exists($existing->path)
            ? $existing->path
            : null;

        $url = is_string($url) ? $url : '';
        if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
            return $fallback;
        }

        $parts = parse_url($url);
        $host = strtolower((string) ($parts['host'] ?? ''));
        $isTiktokCdn = $host === 'tiktokcdn.com'
            || str_ends_with($host, '.tiktokcdn.com')
            || str_ends_with($host, '.tiktokcdn-us.com')
            || str_ends_with($host, '.byteimg.com');

        if (($parts['scheme'] ?? '') !== 'https' || !$isTiktokCdn) {
            return $fallback;
        }

        $response = Http::timeout(15)->get($url);
        if (!$response->successful()) {
            return $fallback;
        }

        $contentType = strtolower(trim(explode(';', (string) $response->header('Content-Type'))[0]));
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($extensions[$contentType])) {
            return $fallback;
        }

        $image = $response->body();
        if ($image === '' || strlen($image) > self::MAX_IMAGE_BYTES) {
            return $fallback;
        }

        $path = 'uploads/tiktok/synced/'.hash('sha256', $videoId).'.'.$extensions[$contentType];
        Storage::disk('public')->put($path, $image);

        return $path;
    }

    private function caption(array $video): ?string
    {
        $caption = trim((string) ($video['video_description'] ?? ''));
        if ($caption === '') {
            $caption = trim((string) ($video['title'] ?? ''));
        }

        return $caption === '' ? null : mb_substr($caption, 0, 255);
    }

    private function videoUrl(array $video, string $videoId): string
    {
        $shareUrl = (string) ($video['share_url'] ?? '');

        if (!filter_var($shareUrl, FILTER_VALIDATE_URL)) {
            $shareUrl = rtrim((string) config('services.tiktok.profile_url'), '/').'/video/'.$videoId;
        }

        return mb_substr($shareUrl, 0, 255);
    }

    /** Hapus video hasil sync yang sudah melebihi kuota; video input manual tidak disentuh. */
    private function trimSyncedPosts(int $limit): void
    {
        TiktokPost::where('path', 'like', 'uploads/tiktok/synced/%')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->skip($limit)
            ->each(function (TiktokPost $post) {
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
        return trim((string) config('services.tiktok.token_path', 'private/tiktok-oauth.json'));
    }
}
