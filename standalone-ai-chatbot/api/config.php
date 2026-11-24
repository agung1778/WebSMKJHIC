<?php
/**
 * ==========================================================================
 * KONFIGURASI AI CHATBOT (STANDALONE)
 * Masukkan Google Gemini API Key Anda di sini.
 * Dapatkan API Key gratis di: https://aistudio.google.com/app/apikey
 * ==========================================================================
 */

return [
    // 1. Google Gemini API Key (Wajib diisi)
    'gemini_api_key' => getenv('GEMINI_API_KEY') ?: 'MASUKKAN_API_KEY_GEMINI_ANDA_DI_SINI',

    // 2. Model Gemini (gemini-1.5-flash gratis dan cepat)
    'gemini_model' => 'gemini-1.5-flash',

    // 3. Nama Brand / Website
    'bot_name' => 'AI Customer Assistant',
    'brand_name' => 'Website Kami',

    // 4. Pengaturan Respon
    'timeout' => 10,        // Detik
    'temperature' => 0.4,   // 0.0 = sangat kaku/faktual, 1.0 = sangat kreatif
    'max_tokens' => 800,

    // 5. File Konteks Data / Pengetahuan Website
    'knowledge_file' => __DIR__ . '/knowledge.json',

    // 6. CORS (Izinkan akses dari domain website Anda, atau '*' untuk semua domain)
    'allowed_origin' => '*',
];
