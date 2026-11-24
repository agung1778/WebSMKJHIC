# 🤖 Standalone AI Chatbot (Plug & Play)

Paket AI Chatbot mandiri yang **100% independen** dan siap **di-copy-paste (copas)** ke website atau project apapun (Laravel, PHP Native, WordPress, CodeIgniter, HTML/JS biasa, React, dll.).

Menggunakan **Google Gemini API (Free Tier)** sehingga **gratis**, cepat, dan sangat fasih berbahasa Indonesia.

---

## 📁 Struktur Folder

```text
standalone-ai-chatbot/
├── widget/
│   ├── chatbot.css     # Tampilan floating bubble & jendela chat responsif
│   └── chatbot.js      # Script widget otomatis (Vanilla JS, tanpa dependensi)
├── api/
│   ├── chat.php        # Backend handler PHP murni (cURL ke Google Gemini)
│   ├── config.php      # Tempat memasukkan API Key Gemini & nama brand
│   └── knowledge.json  # Data / fakta website Anda agar jawaban 100% akurat
├── demo.html           # Contoh halaman demo siap pakai
└── README.md           # Panduan ini
```

---

## 🚀 Cara Copas ke Website Apapun (3 Langkah Mudah)

### Langkah 1: Copy Folder `standalone-ai-chatbot`
Salin folder `standalone-ai-chatbot` ini ke dalam direktori website/project baru Anda (misalnya ke dalam folder `public/` atau root website).

### Langkah 2: Masukkan API Key & Sesuaikan Data Website
1. Dapatkan API Key gratis di: [Google AI Studio](https://aistudio.google.com/app/apikey).
2. Buka file `api/config.php` dan masukkan API Key:
   ```php
   'gemini_api_key' => 'AIzaSy_API_KEY_GEMINI_ANDA_DI_SINI',
   'brand_name' => 'Nama Website Baru Anda',
   ```
3. Buka file `api/knowledge.json` dan ganti isinya dengan informasi website baru Anda (profil, layanan/produk, alamat, kontak WhatsApp, FAQs).

### Langkah 3: Pasang Widget di Tampilan Website
Cukup tambahkan kode berikut di halaman website Anda (tepat sebelum tag `</body>`):

```html
<!-- 1. CSS Widget -->
<link rel="stylesheet" href="/standalone-ai-chatbot/widget/chatbot.css">

<!-- 2. Konfigurasi (Opsional) -->
<script>
    window.AiChatbotConfig = {
        endpoint: '/standalone-ai-chatbot/api/chat.php', // Path ke file chat.php
        title: 'Customer Service AI',
        subtitle: 'Online • Siap membantu',
        welcomeMessage: 'Halo! Ada yang bisa kami bantu hari ini? 😊'
    };
</script>

<!-- 3. JS Widget -->
<script src="/standalone-ai-chatbot/widget/chatbot.js"></script>
```

Selesai! Tombol floating chat akan langsung muncul di pojok kanan bawah website Anda.

---

## ✨ Fitur Utama
- **Zero-Dependency Frontend**: Murni Vanilla JavaScript + CSS. Tidak perlu install npm, React, Vue, Tailwind, atau Alpine.js.
- **Zero-Dependency Backend**: PHP murni menggunakan native cURL. Berjalan di PHP versi 7.4 hingga 8.3+.
- **Anti-Halusinasi (RAG via `knowledge.json`)**: AI hanya menjawab fakta berdasarkan data yang Anda tuliskan di `knowledge.json`.
- **Formatting Rapi**: Mendukung format markdown (teks tebal, list poin, link).
- **Responsive**: Mendukung tampilan desktop melayang dan tampilan mobile full-screen otomatis.
- **CORS Ready**: Bisa dipasang di frontend yang domain/subdomain-nya berbeda dari backend API.
