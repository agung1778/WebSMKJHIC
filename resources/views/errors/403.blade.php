<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak · SMK Amaliah 1 & 2 Ciawi</title>
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
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-[#63cd00]/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-xl p-6 sm:p-10 bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 text-center relative overflow-hidden z-10">
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-amber-400 via-[#63cd00] to-amber-500"></div>

        <div class="flex flex-col items-center justify-center mb-6">
            <div class="w-20 h-20 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center p-2.5 shadow-sm mb-4">
                <i class="fa-solid fa-lock text-3xl text-amber-600"></i>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold tracking-wider">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                KODE ERROR 403
            </div>
        </div>

        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight mb-3">
            Akses Ditolak
        </h1>

        <p class="text-sm sm:text-base text-gray-600 leading-relaxed mb-6">
            Anda tidak memiliki izin akses untuk membuka halaman ini. Pastikan Anda telah masuk dengan akun yang memiliki hak akses yang sesuai.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url('/') }}" 
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#63cd00] hover:bg-[#4fb800] text-white font-semibold rounded-xl text-sm transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none">
                <i class="fa-solid fa-house"></i> Ke Beranda
            </a>
            <a href="{{ route('login') }}" 
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-900 hover:bg-black text-white font-semibold rounded-xl text-sm transition-all duration-200">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk Admin
            </a>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 text-xs text-gray-400">
            &copy; {{ date('Y') }} SMK Amaliah 1 & 2 Ciawi. Hak Cipta Dilindungi.
        </div>
    </div>
</body>
</html>
