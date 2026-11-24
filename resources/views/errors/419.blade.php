<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sesi Diperbarui · SMK Amaliah 1 &amp; 2</title>
    <link rel="icon" type="image/webp" href="{{ asset('assets/logo/am.webp') }}">
    <script src="https://cdn.tailwindcss.com"></script>
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
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen px-4 py-8 text-gray-800 antialiased">
    <div class="w-full max-w-lg p-6 sm:p-10 bg-white rounded-3xl shadow-xl border border-gray-100 text-center relative overflow-hidden">
        {{-- Aksen Dekorasi Atas --}}
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-emerald-400 via-[#63cd00] to-emerald-500"></div>

        {{-- Logo Sekolah --}}
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center p-2 shadow-sm">
                <img src="{{ asset('assets/logo/am.webp') }}" alt="SMK Amaliah" class="w-full h-full object-contain">
            </div>
        </div>

        {{-- Badge Status --}}
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold mb-4">
            <span class="w-2 h-2 rounded-full bg-[#63cd00] animate-pulse"></span>
            Keamanan Sesi Diperbarui
        </div>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight mb-3">
            Sesi Anda Telah Disegarkan
        </h1>

        <p class="text-sm sm:text-base text-gray-600 leading-relaxed mb-6">
            Untuk menjaga keamanan formulir dan data Anda, sesi browser telah diperbarui secara otomatis. Silakan muat ulang halaman untuk melanjutkan aktivitas Anda dengan aman.
        </p>

        {{-- Auto Countdown Indicator --}}
        <div class="p-3 bg-gray-50 rounded-xl border border-gray-200/70 text-xs text-gray-500 mb-6 flex items-center justify-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span>Memuat ulang otomatis dalam <strong id="countdown" class="text-gray-800">5</strong> detik...</span>
        </div>

        {{-- Tombol Aksi Responsif --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <button onclick="window.location.reload()" 
                class="w-full sm:w-auto px-6 py-3 bg-[#63cd00] hover:bg-[#4fb800] text-white font-semibold rounded-xl text-sm transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#63cd00]/50 cursor-pointer">
                Muat Ulang Halaman
            </button>
            <button onclick="window.history.length > 1 ? window.history.back() : window.location.href='/'"
                class="w-full sm:w-auto px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-all duration-200 focus:outline-none cursor-pointer">
                Kembali
            </button>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 text-xs text-gray-400">
            &copy; {{ date('Y') }} SMK Amaliah 1 &amp; 2 Ciawi. Hak Cipta Dilindungi.
        </div>
    </div>

    <script>
        var seconds = 5;
        var countdownEl = document.getElementById('countdown');
        var timer = setInterval(function() {
            seconds--;
            if (countdownEl) countdownEl.innerText = seconds;
            if (seconds <= 0) {
                clearInterval(timer);
                if (window.history.length > 1) {
                    window.location.reload();
                } else {
                    window.location.href = '/';
                }
            }
        }, 1000);
    </script>
</body>
</html>
