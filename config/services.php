<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'instagram' => [
        'api_version' => env('INSTAGRAM_GRAPH_API_VERSION', 'v23.0'),
        'account_id' => env('INSTAGRAM_BUSINESS_ACCOUNT_ID'),
        'access_token' => env('INSTAGRAM_ACCESS_TOKEN'),
        'profile_url' => env('INSTAGRAM_PROFILE_URL', 'https://www.instagram.com/smkamaliah'),

        // Postingan publik yang dipakai sebagai fallback bila belum ada feed tersinkron.
        'post_urls' => array_values(array_filter(array_map('trim', explode(',', env(
            'INSTAGRAM_POST_URLS',
            ''
        ))))),

        // Meta Graph API. Token long-lived disimpan lokal agar bisa di-refresh otomatis.
        'api_base' => rtrim((string) env('INSTAGRAM_API_BASE', 'https://graph.facebook.com'), '/'),
        'app_id' => env('INSTAGRAM_APP_ID'),
        'app_secret' => env('INSTAGRAM_APP_SECRET'),
        'redirect_uri' => env('INSTAGRAM_REDIRECT_URI'),
        'token_path' => env('INSTAGRAM_TOKEN_PATH', 'private/instagram-oauth.json'),
        'sync_limit' => max(1, min(20, (int) env('INSTAGRAM_SYNC_LIMIT', 9))),
    ],

    'tiktok' => [
        'username' => env('TIKTOK_USERNAME', 'smk.amaliah'),
        'profile_url' => env('TIKTOK_PROFILE_URL', 'https://www.tiktok.com/@smk.amaliah'),
        'video_ids' => array_values(array_filter(array_map('trim', explode(',', env(
            'TIKTOK_VIDEO_IDS',
            '7701197482784541973,7700908599970647316,7698562646173355272'
        ))))),

        // Display API v2 (scope: video.list). Token disimpan lokal agar bisa di-refresh otomatis.
        'api_base' => rtrim((string) env('TIKTOK_API_BASE', 'https://open.tiktokapis.com'), '/'),
        'client_key' => env('TIKTOK_CLIENT_KEY'),
        'client_secret' => env('TIKTOK_CLIENT_SECRET'),
        'redirect_uri' => env('TIKTOK_REDIRECT_URI'),
        'access_token' => env('TIKTOK_ACCESS_TOKEN'),
        'refresh_token' => env('TIKTOK_REFRESH_TOKEN'),
        'open_id' => env('TIKTOK_OPEN_ID'),
        'token_path' => env('TIKTOK_TOKEN_PATH', 'private/tiktok-oauth.json'),
        'sync_limit' => max(1, min(20, (int) env('TIKTOK_SYNC_LIMIT', 9))),
    ],

];
