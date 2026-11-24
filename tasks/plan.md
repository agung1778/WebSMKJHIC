# Implementation Plan: AI Chatbot dengan Google Gemini LLM & RAG

## Objective
Mengintegrasikan Google Gemini API (model `gemini-1.5-flash` / `gemini-2.0-flash`) sebagai engine LLM gratis untuk chatbot website SMK Amaliah (WebSMKJHIC), dengan RAG untuk memastikan jawaban akurat berbasis data website, serta graceful fallback ke engine lokal.

## Slices & Tasks

### Task 1: Konfigurasi & Environment Setup
- Perbarui `config/ai.php` untuk menambahkan konfigurasi LLM (enabled, driver, API key, model, timeout, system instruction).
- Tambahkan contoh konfigurasi di `.env.example` dan `.env` (`GEMINI_API_KEY`, `GEMINI_MODEL`, `AI_LLM_ENABLED`).

### Task 2: Service Client Gemini (`App\AI\LLM\GeminiClient`)
- Buat class `GeminiClient` di namespace `App\AI\LLM`.
- Buat method `generateResponse(string $question, string $context, array $history = []): ?string`.
- Format request payload sesuai Google Gemini REST API (`generateContent`).
- Tangani error/exception (timeout, bad status, rate limit, quota exceeded) dengan return `null` dan logging agar pemanggil bisa melakukan fallback otomatis.

### Task 3: Integrasi dengan RAG & Engine Jawaban
- Update `App\AI\Core\AnswerGenerator` atau pipeline `AIEngine`:
  - Jika LLM diaktifkan dan ada API Key, gunakan konteks yang ditemukan oleh `SearchEngine` / `ContextBuilder` lalu kirim ke `GeminiClient`.
  - Jika Gemini berhasil menjawab, kembalikan jawaban dari Gemini dengan meta tag `engine: 'gemini'`.
  - Jika Gemini gagal atau tidak ada API Key, gunakan mekanisme fallback lokal yang sudah ada tanpa error.
- Perbaiki kontrak response di `AIChatbotController` agar kunci `intent` dan `sources` selalu tersedia di output JSON.

### Task 4: Pengujian & Validasi
- Buat test `tests/Feature/GeminiChatbotTest.php` menggunakan `Http::fake()`.
- Verifikasi kasus sukses (LLM aktif).
- Verifikasi kasus graceful fallback (ketika HTTP Gemini gagal/timeout/429).
- Jalankan test dan pastikan semua lulus (Green).
