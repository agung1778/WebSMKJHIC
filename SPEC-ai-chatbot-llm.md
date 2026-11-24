# SPEC: AI Chatbot Berbasis LLM Gratis (Google Gemini API) dengan RAG

## 1. Objective
Membangun dan mengintegrasikan AI Chatbot cerdas untuk website SMK Amaliah (WebSMKJHIC) menggunakan LLM gratis (Google Gemini API - model `gemini-1.5-flash` / `gemini-2.0-flash`) dengan pendekatan RAG (Retrieval-Augmented Generation).

### Mengapa ini dibuat?
Sistem chatbot sebelumnya menggunakan pencarian kata kunci dan ekstraksi teks statis (regex/template) yang kaku, sering kali gagal memahami pertanyaan percakapan natural pengguna, dan menghasilkan jawaban yang kurang fleksibel.

### Target Solusi
1. Chatbot memahami pertanyaan pengguna secara fleksibel dan luwes dalam Bahasa Indonesia yang ramah, sopan, dan representatif bagi sekolah.
2. Menggunakan RAG: Konteks akurat diambil dari database internal website (Jurusan, SPMB, Fasilitas, Guru, Berita, Prestasi, dll.), lalu disintesis oleh LLM sehingga jawaban 100% akurat sesuai data sekolah dan terhindar dari halusinasi.
3. Resilient / Zero-Downtime: Jika kuota Gemini habis atau koneksi internet API bermasalah/API key belum diatur, sistem otomatis melakukan graceful fallback ke engine lokal yang sudah ada tanpa melempar error 500 ke pengunjung website.
4. UI Contract Compatibility: Tetap mematuhi response JSON yang diharapkan Alpine.js frontend (`{ reply, answered, intent, sources, engine }`).

---

## 2. Commands
- Run Dev: `php artisan serve` & `npm run dev`
- Run Tests: `php artisan test --filter=Chatbot`
- Test LLM Client: `php artisan tinker`

---

## 3. Project Structure & Changes
- `config/ai.php`: Tambahkan konfigurasi LLM (driver, Gemini API key, model target, timeout, temperature, max_tokens, prompt instructions).
- `app/AI/LLM/GeminiClient.php` (baru): Service / Client HTTP berbasis Laravel `Illuminate\Support\Facades\Http` untuk memanggil Google Gemini REST API (endpoint `generateContent`).
- `app/AI/Core/AnswerGenerator.php`: Integrasikan opsi generate jawaban via LLM jika Gemini aktif dan konteks/pertanyaan tersedia, dengan fallback ke extractive generator.
- `app/Http/Controllers/PublicPage/AIChatbotController.php`: Pastikan response contract selalu menyertakan atribut `intent`, `sources`, `engine: 'gemini' | 'ai-engine' | 'legacy'`.
- `.env.example` & `.env`: Dokumentasikan environment variables `GEMINI_API_KEY`, `GEMINI_MODEL`, `AI_LLM_ENABLED`.
- `tests/Feature/GeminiChatbotTest.php` (baru): Unit/Feature test untuk integrasi Gemini client (dengan `Http::fake()`) dan flow RAG.

---

## 4. Edge Cases & Resilience
1. **API Key Kosong / Belum Diisi:** Sistem otomatis mendeteksi ketiadaan API key dan langsung menggunakan engine lokal tanpa crash.
2. **Rate Limit / Timeout (429 / 504 / Connection Exception):** Request dibatasi timeout (misal 5-8 detik). Jika timeout atau 429 dari Gemini, fallback otomatis ke jawaban lokal dan log peringatan (warning) tanpa mengganggu user.
3. **Pertanyaan di Luar Topik Sekolah (Out of Domain):** LLM diinstruksikan melalui System Prompt agar dengan santun mengarahkan user kembali ke topik seputar SMK Amaliah (jurusan, pendaftaran SPMB, fasilitas, kegiatan, dll.).
4. **Prompt Injection / Jailbreak:** System prompt dilengkapi pengaman untuk tetap berpegang teguh pada persona AI Asisten Sekolah SMK Amaliah dan tidak membocorkan prompt internal atau menjalankan instruksi jahat.

---

## 5. Acceptance Criteria
- [ ] User dapat bertanya dengan bahasa santai / natural dan menerima jawaban yang relevan, ramah, dan informatif.
- [ ] Data faktual sekolah (alamat, jurusan, nama kepala sekolah, alur pendaftaran, dll.) bersumber langsung dari data website.
- [ ] Response JSON dari endpoint `/public/ai/ask` konsisten memiliki kunci `reply`, `answered`, `intent`, `sources`, dan `engine`.
- [ ] Ketika `Http::fake()` mengembalikan response sukses dari Gemini, output jawaban berasal dari LLM (`engine: gemini`).
- [ ] Ketika `Http::fake()` melempar timeout / error, chatbot tetap merespons via fallback lokal (`engine: ai-engine` atau `legacy`).
- [ ] Seluruh test `php artisan test --filter=Chatbot` dan test baru berjalan sukses (Green).

---

## 6. Non-Requirements
- Tidak memerlukan database berbayar / vector database eksternal (memanfaatkan indeks dan database website yang sudah ada secara efisien).
- Tidak memerlukan pembayaran / kartu kredit (menggunakan Google Gemini API Free Tier).
