<?php
/**
 * ==========================================================================
 * STANDALONE AI CHATBOT BACKEND (PHP Zero-Dependency)
 * Menerima request POST JSON dari widget frontend, memanggil Gemini API
 * dengan grounding konteks dari knowledge.json, dan mengembalikan jawaban JSON.
 * ==========================================================================
 */

header('Content-Type: application/json; charset=utf-8');

// Load konfigurasi
$config = require __DIR__ . '/config.php';

// Atur CORS agar widget bisa dipanggil lintas domain jika perlu
header('Access-Control-Allow-Origin: ' . ($config['allowed_origin'] ?? '*'));
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

// Baca raw POST body JSON
$inputRaw = file_get_contents('php://input');
$data = json_decode($inputRaw, true);

$message = trim($data['message'] ?? '');
$history = is_array($data['history'] ?? null) ? $data['history'] : [];

if ($message === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Pesan tidak boleh kosong']);
    exit;
}

$apiKey = $config['gemini_api_key'] ?? '';
if (empty($apiKey) || $apiKey === 'MASUKKAN_API_KEY_GEMINI_ANDA_DI_SINI') {
    echo json_encode([
        'success' => false,
        'reply' => '⚠️ API Key Gemini belum diatur di file `api/config.php`. Silakan masukkan API Key Gemini Anda agar chatbot dapat menjawab!'
    ]);
    exit;
}

// 1. Baca Konteks dari knowledge.json
$contextText = '';
if (!empty($config['knowledge_file']) && file_exists($config['knowledge_file'])) {
    $knowledgeData = json_decode(file_get_contents($config['knowledge_file']), true);
    if (!empty($knowledgeData)) {
        $contextText = json_encode($knowledgeData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

// 2. Susun System Instruction (Persona & Aturan Jawab)
$brandName = $config['brand_name'] ?? 'Website Kami';
$botName = $config['bot_name'] ?? 'AI Assistant';

$systemInstruction = "Anda adalah {$botName}, asisten cerdas dan ramah untuk {$brandName}.\n"
    . "Tugas Anda adalah melayani dan menjawab pertanyaan calon pelanggan/pengunjung website dengan sopan, antusias, dan jelas dalam Bahasa Indonesia.\n\n"
    . "Aturan Utama:\n"
    . "1. Jawablah secara akurat, padat, dan ringkas. Gunakan format markdown rapi (seperti poin/bullet atau teks tebal) jika menjelaskan beberapa hal.\n"
    . "2. Utamakan informasi resmi yang tercantum di bagian KONTEKS DATA WEBSITE di bawah.\n"
    . "3. Jangan membuat-buat fakta (halusinasi) seperti harga atau nomor telepon jika tidak tertera di data.\n"
    . "4. Jika informasi tidak ditemukan di konteks, katakan dengan ramah bahwa info tersebut belum tercantum dan sarankan mereka menghubungi admin/customer support.\n";

if ($contextText !== '') {
    $systemInstruction .= "\n--- KONTEKS DATA WEBSITE (SUMBER FAKTA UTAMA) ---\n"
        . $contextText
        . "\n--- AKHIR KONTEKS ---";
}

// 3. Susun Contents (Riwayat percakapan & pesan terakhir)
$contents = [];
$recentHistory = array_slice($history, -6);
foreach ($recentHistory as $turn) {
    $role = ($turn['role'] ?? 'user') === 'user' ? 'user' : 'model';
    $text = trim($turn['message'] ?? '');
    if ($text !== '') {
        $contents[] = [
            'role' => $role,
            'parts' => [
                ['text' => $text]
            ]
        ];
    }
}

$contents[] = [
    'role' => 'user',
    'parts' => [
        ['text' => $message]
    ]
];

// 4. Bangun Payload untuk Google Gemini REST API
$model = urlencode($config['gemini_model'] ?? 'gemini-1.5-flash');
$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

$payload = [
    'systemInstruction' => [
        'parts' => [
            ['text' => $systemInstruction]
        ]
    ],
    'contents' => $contents,
    'generationConfig' => [
        'temperature' => (float) ($config['temperature'] ?? 0.4),
        'maxOutputTokens' => (int) ($config['max_tokens'] ?? 800),
    ]
];

// 5. Eksekusi HTTP POST Request via cURL
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json'
    ],
    CURLOPT_TIMEOUT        => (int) ($config['timeout'] ?? 10),
    CURLOPT_SSL_VERIFYPEER => true
]);

$responseBody = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($responseBody === false || !empty($curlError)) {
    http_response_code(502);
    echo json_encode([
        'success' => false,
        'reply' => 'Maaf, gagal terhubung ke server AI. Silakan periksa koneksi internet server Anda.',
        'error' => $curlError
    ]);
    exit;
}

$jsonRes = json_decode($responseBody, true);

if ($httpCode >= 200 && $httpCode < 300) {
    $replyText = $jsonRes['candidates'][0]['content']['parts'][0]['text'] ?? null;
    if (!empty($replyText)) {
        echo json_encode([
            'success' => true,
            'reply' => trim($replyText)
        ]);
        exit;
    }
}

// Tangani kasus error dari Google API (misal kuota habis atau model salah)
$errorMessage = $jsonRes['error']['message'] ?? 'Terjadi kesalahan saat memproses jawaban.';
echo json_encode([
    'success' => false,
    'reply' => 'Maaf, asisten AI sedang sibuk atau kuota harian tercapai. Silakan coba beberapa saat lagi.',
    'debug' => $errorMessage
]);
