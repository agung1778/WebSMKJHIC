<?php

/**
 * Konfigurasi Custom AI Information Engine SMK Amaliah.
 *
 * SEMUA nilai di sini bisa diubah tanpa menyentuh kode engine.
 * Tidak ada satu pun konfigurasi yang menunjuk ke API AI eksternal —
 * mesin ini murni membaca data website sendiri.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Mode Operasi
    |--------------------------------------------------------------------------
    | strict : hanya menjawab bila confidence >= min_confidence.
    | normal : boleh memakai context percakapan untuk memahami follow-up.
    | debug  : jawaban+serta jejak pencarian internal dikembalikan.
    */
    'mode' => env('AI_MODE', 'normal'),

    /*
    |--------------------------------------------------------------------------
    | Nama & Identitas
    |--------------------------------------------------------------------------
    */
    'name' => env('AI_NAME', 'Tanya Amaliah'),
    'school' => env('AI_SCHOOL', 'SMK Amaliah 1 & 2 Ciawi'),

    /*
    |--------------------------------------------------------------------------
    | Bobot Relevansi (Relevance Engine)
    |--------------------------------------------------------------------------
    | Final score = keyword*w_keyword + title*w_title + category*w_category
    |              + content*w_content
    | Nilai harus berjumlah 1.0 agar confidence berada di rentang 0..1.
    */
    'weights' => [
        'keyword'  => 0.40,
        'title'    => 0.25,
        'category' => 0.15,
        'content'  => 0.20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Confidence Threshold
    |--------------------------------------------------------------------------
    | Di bawah min_confidence engine TIDAK akan memaksa jawaban.
    */
    'min_confidence' => (float) env('AI_MIN_CONFIDENCE', 0.55),
    'strong'         => (float) env('AI_STRONG_CONFIDENCE', 0.75),
    'very_strong'    => (float) env('AI_VERY_STRONG_CONFIDENCE', 0.90),

    /*
    |--------------------------------------------------------------------------
    | Pencarian
    |--------------------------------------------------------------------------
    | algorithm : bm25 | tfidf | hybrid
    | bm25 params: k1 & b (standar Robertson/Sparck-Jones).
    */
    'search' => [
        'algorithm'      => env('AI_SEARCH_ALGORITHM', 'hybrid'),
        'bm25_k1'        => 1.5,
        'bm25_b'         => 0.75,
        'min_token_len'  => 2,
        'max_candidates' => 200,   // batas kandidat sebelum scoring akhir
        'top_k'          => 10,
        'ngram_max'      => 3,
        'field_boost'    => [
            'title'    => 2.2,
            'category' => 1.5,
            'meta'     => 1.0,
            'body'     => 1.0,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Normalisasi & Typo Correction
    |--------------------------------------------------------------------------
    | min_similarity = 0.78 -> di atas ini koreksi typo dianggap yakin.
    */
    'typo' => [
        'enabled'        => true,
        'min_similarity' => 0.78,
        'max_distance'   => 3,
        'min_length'     => 4,
    ],

    /*
    |--------------------------------------------------------------------------
    | Query Expansion
    |--------------------------------------------------------------------------
    | synonyms_enabled memakai tabel ai_synonyms (bisa dikelola admin).
    */
    'query_expansion' => [
        'enabled'        => true,
        'max_variants'   => 6,
        'synonyms_enabled' => true,
        'morphological'  => true,   // stem + awalan/akhiran ringan
    ],

    /*
    |--------------------------------------------------------------------------
    | Conversation Memory
    |--------------------------------------------------------------------------
    | Dipakai untuk memahami pertanyaan lanjutan ("Kalau RPL?", "Berapa lama?").
    */
    'memory' => [
        'enabled'      => true,
        'ttl_minutes'  => 30,
        'max_turns'    => 8,
        'max_context'  => 6,
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging & Privacy
    |--------------------------------------------------------------------------
    | AI_LOG_CONVERSATION=false -> pesan user tidak disimpan sama sekali.
    | yang disimpan cukup search log metrik (tanpa isi pertanyaan
    | lengkap bila privacy_anonymized = true).
    */
    'logging' => [
        'conversation'     => filter_var(env('AI_LOG_CONVERSATION', false), FILTER_VALIDATE_BOOLEAN),
        'search'           => filter_var(env('AI_LOG_SEARCH', true), FILTER_VALIDATE_BOOLEAN),
        'privacy_anonymize' => filter_var(env('AI_LOG_ANONYMIZE', false), FILTER_VALIDATE_BOOLEAN),
        'retention_days'   => (int) env('AI_LOG_RETENTION_DAYS', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | API
    |--------------------------------------------------------------------------
    */
    'api' => [
        'rate_limit'   => (int) env('AI_API_RATE_LIMIT', 30),
        'max_message'  => 500,
        'cache_ttl'    => (int) env('AI_CACHE_TTL', 300),
    ],

    /*
    |--------------------------------------------------------------------------
    | Knowledge Sources
    |--------------------------------------------------------------------------
    | DatabaseConnector membaca tabel website. Daftar di bawah controlling
    | tabel mana yang boleh dibaca engine (whitelist = read-only).
    */
    'sources' => [
        'database' => [
            'enabled' => true,
            'tables'  => [
                'majors', 'teachers', 'facilities', 'news', 'writings',
                'extracurriculars', 'partners', 'testimonials', 'achievements',
                'school_programs', 'school_leaders', 'pkk_projects',
                'school_settings', 'spmb_settings', 'navigations',
            ],
        ],
        'website' => [
            'enabled' => true,
            'pages'   => [
                '/about', '/majors', '/news', '/facilities', '/programs',
                '/extracurriculars', '/teachers', '/help/faq', '/help/feedback',
                '/traffic', '/partners', '/achievements',
            ],
        ],
        'documents' => [
            'enabled'  => true,
            'paths'    => [base_path('ai_documents')],
            'formats'  => ['txt', 'md', 'json', 'csv'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Disclaimer (ditampilkan di UI chatbot)
    |--------------------------------------------------------------------------
    */
    'disclaimer' => 'AI Chatbot SMK Amaliah merupakan asisten informasi berbasis data website. '
        . 'Jawaban yang diberikan berdasarkan informasi yang tersedia pada sistem dan dapat berubah '
        . 'apabila data website diperbarui. Untuk informasi resmi yang bersifat penting atau berubah '
        . 'secara berkala, pengguna disarankan melakukan konfirmasi kepada pihak SMK Amaliah.',
];