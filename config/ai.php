<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AI Information Engine
    |--------------------------------------------------------------------------
    |
    | Modul AI ini 100% lokal (tanpa LLM/API eksternal). Seluruh jawaban
    | disusun dari data website yang sudah di-index ke tabel ai_*.
    |
    */

    'mode' => env('AI_MODE', 'normal'), // strict | normal | debug

    // Simpan riwayat percakapan di database (privasi).
    'log_conversation' => env('AI_LOG_CONVERSATION', false),

    // Gunakan ChatbotService lama sebagai cadangan ketika AI Engine tidak
    // menemukan jawaban yang memenuhi ambang relevansi.
    'legacy_fallback' => env('AI_LEGACY_FALLBACK', true),

    // Jumlah jawaban yang ditampilkan pada respons.
    'max_results' => env('AI_MAX_RESULTS', 5),

    // Ambang confidence minimum agar jawaban dianggap valid (0..1).
    'min_confidence' => env('AI_MIN_CONFIDENCE', 0.35),

    // Ambang minimum skor relevansi sebelum jawaban ditampilkan (0..1).
    'min_relevance' => env('AI_MIN_RELEVANCE', 0.18),

    // Bobot relevance. Total akan dinormalisasi otomatis oleh AIConfig.
    'weights' => [
        'keyword' => 0.35,
        'title' => 0.25,
        'category' => 0.15,
        'content' => 0.25,
    ],

    // Parameter BM25.
    'bm25_k1' => env('AI_BM25_K1', 1.5),
    'bm25_b' => env('AI_BM25_B', 0.75),

    // Cache TTL (detik) untuk hasil pencarian yang identik.
    'cache_ttl' => env('AI_CACHE_TTL', 600),

    // Nama sekolah untuk jawaban pembuka / fallback.
    'school_name' => env('AI_SCHOOL_NAME', 'SMK Amaliah 1 & 2 Ciawi'),

    // Identitas sekolah (dipakai sebagai sumber knowledge statis).
    'identity' => [
        'name' => 'SMK Amaliah 1 & 2 Ciawi',
        'short_name' => 'SMK Amaliah Ciawi',
        'address' => 'Jl. Raya Tol Jagorawi No.1, Ciawi, Kec. Ciawi, Kabupaten Bogor, Jawa Barat 16720',
        'phone' => '0856-1922-827 / 0856-4901-1449',
        'whatsapp' => '6285649011449',
        'email' => 'smkamaliahciawi@gmail.com',
        'instagram' => '@smkamaliah',
        'youtube' => 'SMK Amaliah Ciawi',
        'website' => 'https://smkamaliah.sch.id',
    ],

    // Jumlah turn terakhir yang diingat untuk resolusi pertanyaan lanjutan.
    'memory_turns' => env('AI_MEMORY_TURNS', 4),

    // Masa berlaku pointer percakapan (menit) untuk menjawab pertanyaan lanjutan.
    'memory_ttl_minutes' => env('AI_MEMORY_TTL_MINUTES', 15),

    // Sumber knowledge tambahan di luar database.
    'sources' => [
        // Folder atau berkas .txt/.md lokal (opsional).
        'document_path' => env('AI_DOCUMENT_PATH'),
    ],

    // Batas jumlah karakter pertanyaan sebelum dipotong.
    'max_question_length' => 500,

];