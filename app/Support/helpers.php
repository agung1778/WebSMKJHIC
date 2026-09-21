<?php

use App\Http\Controllers\PublicPage\OptimizedImageController;

if (!function_exists('img_url')) {
    function img_url($path, $width = null, $height = null): string
    {
        if (empty($path)) {
            return '';
        }

        return OptimizedImageController::url((string) $path, $width, $height);
    }
}

if (!function_exists('img_attrs')) {
    function img_attrs($width, $height): string
    {
        $attrs = [];

        if ((int) $width > 0) {
            $attrs[] = 'width="' . (int) $width . '"';
        }

        if ((int) $height > 0) {
            $attrs[] = 'height="' . (int) $height . '"';
        }

        return implode(' ', $attrs);
    }
}