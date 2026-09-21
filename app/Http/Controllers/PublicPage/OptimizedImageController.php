<?php

namespace App\Http\Controllers\PublicPage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class OptimizedImageController extends Controller
{
    public function show(Request $request, string $path)
    {
        $width = max(1, (int) $request->query('w', 1600));
        $height = max(0, (int) $request->query('h', 0));

        // Cegah path traversal
        if (str_contains($path, '..')) {
            abort(404);
        }

        $fullPath = Storage::disk('public')->path($path);

        if (!is_file($fullPath)) {
            // Path bisa merujuk ke public/ (mis. asset logo/kepsek), bukan storage/
            $publicFallback = public_path($path);

            if (is_file($publicFallback)) {
                $fullPath = $publicFallback;
            }
        }

        if (!is_file($fullPath)) {
            abort(404);
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // SVG dan file raster yang tidak mampu di-generate GD langsung disajikan apa adanya
        if ($ext === 'svg') {
            return $this->stream($fullPath, 'image/svg+xml', $path);
        }

        $cacheKey = md5($fullPath . filesize($fullPath)) . "_{$width}x{$height}.webp";
        $cacheDir = '_optimized/' . substr($cacheKey, 0, 2);
        $cachePath = $cacheDir . '/' . $cacheKey;

        if (!Storage::disk('public')->exists($cachePath)) {
            $source = @imagecreatefromstring((string) file_get_contents($fullPath));

            if ($source === false) {
                return $this->stream($fullPath, mime_content_type($fullPath) ?: 'application/octet-stream', $path);
            }

            $source = $this->fixOrientation($source, $fullPath);

            $srcW = imagesx($source);
            $srcH = imagesy($source);
            $targetWidth = min($width, $srcW);

            if ($height > 0) {
                $scale = min($targetWidth / $srcW, $height / $srcH);
                $dstW = max(1, (int) round($srcW * $scale));
                $dstH = max(1, (int) round($srcH * $scale));
            } else {
                $dstW = max(1, $targetWidth);
                $dstH = max(1, (int) round($srcH * $dstW / $srcW));
            }

            $dest = imagecreatetruecolor($dstW, $dstH);
            imagealphablending($dest, false);
            imagesavealpha($dest, true);

            imagecopyresampled($dest, $source, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);

            ob_start();
            imagewebp($dest, null, 78);
            $webp = (string) ob_get_clean();

            imagedestroy($source);
            imagedestroy($dest);

            Storage::disk('public')->put($cachePath, $webp);
        }

        $absolute = Storage::disk('public')->path($cachePath);

        return response((string) Storage::disk('public')->get($cachePath), 200, [
            'Content-Type' => 'image/webp',
            'Content-Length' => (string) filesize($absolute),
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    public static function url(string $path, ?int $width = null, ?int $height = null): string
    {
        if ($path === '') {
            return '';
        }

        $width = (int) $width;
        $height = (int) $height;

        if ($width < 1) {
            return Storage::disk('public')->url($path);
        }

        $segments = array_map('rawurlencode', explode('/', trim($path, '/')));
        $url = url('/img') . '/' . implode('/', $segments);

        $query = [];
        if ($width > 0) {
            $query[] = 'w=' . $width;
        }
        if ($height > 0) {
            $query[] = 'h=' . $height;
        }
        if ($query) {
            $url .= '?' . implode('&', $query);
        }

        return $url;
    }

    private function stream(string $file, string $mime, string $path)
    {
        return response((string) file_get_contents($file), 200, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    private function fixOrientation($image, string $fullPath)
    {
        if (!function_exists('exif_read_data') || strtolower(pathinfo($fullPath, PATHINFO_EXTENSION)) !== 'jpg') {
            return $image;
        }

        $exif = @exif_read_data($fullPath);
        $orientation = (int) ($exif['Orientation'] ?? 1);

        switch ($orientation) {
            case 3:
                $image = imagerotate($image, 180, 0);
                break;
            case 6:
                $image = imagerotate($image, -90, 0);
                break;
            case 8:
                $image = imagerotate($image, 90, 0);
                break;
        }

        return $image;
    }
}