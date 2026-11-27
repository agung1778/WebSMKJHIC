<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan · SMK Amaliah 1 & 2 Ciawi</title>
    <link rel="icon" type="image/webp" href="{{ asset('assets/logo/am.webp') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#63cd00',
                        'brand-dark': '#4fb800',
                        dark: '#282829',
                    }
                }
            }
        };
    </script>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #0b1509 0%, #152410 40%, #0d1a0c 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen px-4 py-10 text-gray-800 antialiased relative overflow-hidden">
    {{-- Glow Background Accents --}}
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#63cd00]/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-xl p-6 sm:p-10 bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 text-center relative overflow-hidden z-10">
        {{-- Top Accent Bar --}}
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-emerald-400 via-[#63cd00] to-emerald-500"></div>

        {{-- Logo Sekolah & Error Code Badge --}}
        <div class="flex flex-col items-center justify-center mb-6">
            <div class="w-20 h-20 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center p-2.5 shadow-sm mb-4">
                <img src="{{ asset('assets/logo/am.webp') }}" alt="SMK Amaliah" class="w-full h-full object-contain">
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold tracking-wider">
                <span class="w-2 h-2 rounded-full bg-[#63cd00] animate-pulse"></span>
                KODE ERROR 404
            </div>
        </div>

        {{-- Big Title --}}
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight mb-3">
            Halaman Tidak Ditemukan
        </h1>

        <p class="text-sm sm:text-base text-gray-600 leading-relaxed mb-6">
            Mohon maaf, halaman yang Anda tuju mungkin sudah dipindahkan, dihapus, atau tautan yang Anda masukkan salah.
        </p>

        {{-- Tautan Cepat Populer --}}
        <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100 text-left mb-6">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">
                <i class="fa-solid fa-compass text-emerald-600 mr-1.5"></i> Mungkin Anda Mencari:
            </p>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <a href="{{ url('/') }}" class="flex items-center gap-2 p-2 rounded-xl bg-white border border-gray-200/70 hover:border-emerald-300 hover:text-emerald-700 transition">
                    <i class="fa-solid fa-house text-[#63cd00]"></i> Beranda Utama
                </a>
                <a href="{{ url('/majors') }}" class="flex items-center gap-2 p-2 rounded-xl bg-white border border-gray-200/70 hover:border-emerald-300 hover:text-emerald-700 transition">
                    <i class="fa-solid fa-graduation-cap text-[#63cd00]"></i> Program Kejuruan
                </a>
                <a href="{{ url('/teachers') }}" class="flex items-center gap-2 p-2 rounded-xl bg-white border border-gray-200/70 hover:border-emerald-300 hover:text-emerald-700 transition">
                    <i class="fa-solid fa-chalkboard-user text-[#63cd00]"></i> Guru & Staf
                </a>
                <a href="{{ url('/infospmb') }}" class="flex items-center gap-2 p-2 rounded-xl bg-white border border-gray-200/70 hover:border-emerald-300 hover:text-emerald-700 transition">
                    <i class="fa-solid fa-user-plus text-[#63cd00]"></i> Pendaftaran SPMB
                </a>
            </div>
        </div>

        {{-- Tombol Aksi Utama --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url('/') }}" 
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#63cd00] hover:bg-[#4fb800] text-white font-semibold rounded-xl text-sm transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#63cd00]/50">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
            </a>
            <button onclick="window.history.length > 1 ? window.history.back() : window.location.href='/'"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-all duration-200 focus:outline-none cursor-pointer">
                Halaman Sebelumnya
            </button>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 text-xs text-gray-400">
            &copy; {{ date('Y') }} SMK Amaliah 1 & 2 Ciawi. Hak Cipta Dilindungi.
        </div>
    </div>
</body>
</html>
