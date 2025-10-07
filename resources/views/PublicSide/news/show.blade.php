@extends('layouts.public-navbar')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>@yield('title')</title>

        {{-- Link Extensions --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    @php
        $amaliahGreen = '#63cd00';
        $amaliahDark = '#282829';
        $amaliahBlue = '#E0E7FF';

        // Cek Variabel 
        $hasImages = isset($newsImages) && $newsImages->isNotEmpty();
    @endphp

    <body>


        {{-- ================================================================= --}}
        {{-- BAGIAN 1: HERO IMAGE (GAMBAR UTAMA BERITA) --}}
        {{-- ================================================================= --}}
        <header class="w-full h-80 lg:h-96 bg-gray-900 overflow-hidden">
            {{-- Mengambil langsung gambar utama berita ($news->image) sebagai Hero Image --}}
            @if ($news->image)
                <img src="{{ asset('storage/' . $news->image) }}" alt="Gambar Utama {{ $news->title }}"
                    class="w-full h-full object-cover opacity-80 transition-opacity duration-300 hover:opacity-100">
                {{-- Tambahan: opacity 80% dengan hover 100% untuk efek visual yang halus --}}
            @else
                {{-- Fallback jika gambar utama tidak tersedia --}}
                <div class="w-full h-full flex items-center justify-center bg-gray-800 text-white text-xl">
                    Gambar Berita Tidak Tersedia
                </div>
            @endif
        </header>
        <div style="background-color: #2D2D2D;">
            <div class="max-w-screen-xl h-[70px] mx-auto px-4 sm:px-6 lg:px-8">
                {{-- Menggunakan h-full dan flex items-center untuk membuat konten di tengah vertikal --}}
                <div class="h-full flex items-center">
                    <nav class="flex" aria-label="Breadcrumb">
                        {{-- Text-lg untuk memperbesar teks --}}
                        <ol class="inline-flex items-center space-x-2 md:space-x-3 text-lg">
                            {{-- 1. Home --}}
                            <li class="inline-flex items-center">
                                <a href="/"
                                    class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                    Home
                                </a>
                            </li>

                            {{-- 2. News (Diperbaiki agar mengarah ke index berita) --}}
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-white text-xs"></i>
                                    {{-- Menggunakan route('public.news.index') --}}
                                    <a href="{{ route('public.news.index') }}"
                                        class="ml-2 font-medium text-gray-300 hover:text-white md:ml-3 transition-colors">News</a>
                                </div>
                            </li>

                            {{-- 3. Judul Berita Saat Ini (Aktif) --}}
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-white text-xs"></i>
                                    {{-- Menggunakan Judul Berita dan warna aktif (hijau) --}}
                                    <span class="ml-2 font-medium md:ml-3 truncate max-w-xs" style="color: #ffffff;">
                                        {{ $news->title }}
                                    </span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-x-12">

                {{-- ========================================================== --}}
                {{-- KOLOM KIRI (2/3): KONTEN UTAMA ARTIKEL --}}
                {{-- ========================================================== --}}
                <div class="lg:col-span-2">
                    {{-- Tombol Kembali --}}
                    <a href="{{ route('public.news.index') }}"
                        class="text-gray-500 hover:text-gray-900 text-sm font-medium mb-8 inline-flex items-center transition-colors">
                        <i class="fas fa-arrow-left mr-2 text-xs"></i>
                        Kembali ke Daftar Berita
                    </a>

                    {{-- Header Artikel --}}
                    <header class="mb-8 border-b border-gray-200 pb-6">
                        <h1 class="text-3xl lg:text-4xl font-extrabold mb-4 text-gray-900 leading-tight">
                            {{ $news->title }}
                        </h1>

                        {{-- Metadata di bawah judul --}}
                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500">
                            <span class="inline-flex items-center">
                                <i class="far fa-calendar-alt mr-2 text-gray-400"></i>
                                Dipublikasikan: <strong
                                    class="ml-1 text-gray-700">{{ \Carbon\Carbon::parse($news->date_published)->format('d F Y') }}</strong>
                            </span>
                            <span class="inline-flex items-center">
                                <i class="far fa-user-circle mr-2 text-gray-400"></i>
                                Oleh: <strong class="ml-1 text-gray-700">{{ $news->publisher }}</strong>
                            </span>
                        </div>
                    </header>

                    {{-- Konten Utama Artikel & Galeri --}}
                    <div x-data="{ modalOpen: false, modalImage: '' }">
                        {{-- Isi Konten Artikel --}}
                        <article class="prose prose-lg max-w-none text-gray-800 leading-relaxed mb-12">
                            {!! nl2br(e($news->description)) !!}
                        </article>

                        {{-- BAGIAN GALERI MINI (THUMBNAILS) --}}
                        @if (isset($newsImages) && $newsImages->isNotEmpty())
                            <div class="pt-8 mt-12 border-t border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 mb-4">Galeri Foto</h3>
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    @foreach ($newsImages as $image)
                                        <div @click="modalImage = '{{ Storage::url($image->path) }}'; modalOpen = true"
                                            class="aspect-square overflow-hidden rounded-lg cursor-pointer group">
                                            <img src="{{ Storage::url($image->path) }}"
                                                alt="{{ $image->description ?? $image->filename }}"
                                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-300">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- MODAL PREVIEW GAMBAR (TETAP DI SINI) --}}
                        <div x-show="modalOpen"
                            class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-90 flex items-center justify-center"
                            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            @click.away="modalOpen = false" style="display: none;">
                            <div class="relative max-w-4xl w-full p-4 mx-auto">
                                <button @click="modalOpen = false"
                                    class="absolute -top-2 -right-2 m-4 text-white text-4xl hover:text-gray-300 transition-colors z-50">
                                    &times;
                                </button>
                                <img :src="modalImage"
                                    class="w-full h-auto max-h-[90vh] object-contain rounded-lg shadow-2xl">
                            </div>
                        </div>
                    </div>

                    {{-- BAGIAN FOOTER ARTIKEL (SHARING) --}}
                    <footer class="mt-12 pt-8 border-t border-gray-200">
                        <div class="flex items-center gap-4">
                            <h3 class="text-base font-semibold text-gray-700">Bagikan Artikel:</h3>
                            <div class="flex items-center space-x-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                                    target="_blank"
                                    class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all duration-200">
                                    <i class="fab fa-facebook-f text-lg"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($news->title) }}"
                                    target="_blank"
                                    class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-black hover:text-white transition-all duration-200">
                                    <i class="fab fa-twitter text-lg"></i>
                                </a>
                                <a href="https://api.whatsapp.com/send?text={{ urlencode($news->title . ' - ' . url()->current()) }}"
                                    target="_blank"
                                    class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-green-500 hover:text-white transition-all duration-200">
                                    <i class="fab fa-whatsapp text-lg"></i>
                                </a>
                            </div>
                        </div>
                    </footer>
                </div>

                {{-- ========================================================== --}}
                {{-- KOLOM KANAN (1/3): SUGGESTION SIDEBAR --}}
                {{-- ========================================================== --}}
                <aside class="lg:col-span-1 mt-12 lg:mt-0">
                    {{-- Wrapper untuk membuat 'sticky' --}}
                    <div class="lg:sticky lg:top-8">
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-5 pb-4 border-b">Baca Juga</h3>
                            <ul class="space-y-4">
                                @php
                                    // Pastikan variabel $randomNews ada dari controller Anda
                                    $suggestedNews = $randomNews ?? collect([]);
                                @endphp

                                @forelse ($suggestedNews->take(4) as $item)
                                    <li>
                                        {{-- PERBAIKAN: Menggunakan $item->id bukan $item->slug --}}
                                        <a href="{{ route('public.news.show', $item->id) }}" class="group block">
                                            <p
                                                class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors duration-200 leading-snug">
                                                {{ $item->title }}
                                            </p>
                                            <span
                                                class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($item->date_published)->diffForHumans() }}</span>
                                        </a>
                                    </li>
                                @empty
                                    <li class="text-sm text-gray-500">Tidak ada berita lain untuk ditampilkan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
        </div>

        <footer style="background-color: {{ $amaliahDark }};">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

                {{-- Konten Utama Footer (Multi-kolom) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">

                    {{-- Kolom 1: Logo, Deskripsi, dan Sosial Media --}}
                    <div class="space-y-6">
                        {{-- 1. Struktur Branding yang lebih rapi --}}
                        <a href="/" class="flex items-center gap-3">
                            <img src="{{ asset('assets/logo/amaliah_white.png') }}" alt="Logo SMK Amaliah" class="h-10">
                            <div>
                                <span class="text-white font-semibold text-lg leading-tight">SMK Amaliah 1 & 2</span>
                                <span class="block text-gray-400 text-xs">Ciawi - Bogor</span>
                            </div>
                        </a>

                        <p class="text-gray-400 text-sm leading-relaxed">
                            Berkomitmen untuk mencetak lulusan yang kompeten, berakhlak mulia, dan siap bersaing di
                            dunia industri global.
                        </p>

                        {{-- 2. Ikon Sosial Media dengan efek hover modern --}}
                        <div class="flex items-center space-x-3">
                            <a href="#" target="_blank"
                                class="group w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white">
                                <i
                                    class="fab fa-youtube text-gray-400 text-xl group-hover:text-red-600 transition-colors"></i>
                            </a>
                            <a href="#" target="_blank"
                                class="group w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white">
                                <i
                                    class="fab fa-instagram text-gray-400 text-xl group-hover:text-pink-600 transition-colors"></i>
                            </a>
                            <a href="#" target="_blank"
                                class="group w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white">
                                <i
                                    class="fab fa-facebook-f text-gray-400 text-xl group-hover:text-blue-600 transition-colors"></i>
                            </a>
                            <a href="#" target="_blank"
                                class="group w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white">
                                <i class="fab fa-tiktok text-gray-400 text-xl group-hover:text-black transition-colors"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Kolom 2: Link Navigasi Cepat --}}
                    <div>
                        <h4 class="font-semibold text-white tracking-wider uppercase">Jelajahi</h4>
                        <ul class="mt-4 space-y-3 text-sm">
                            {{-- 3. Efek hover yang lebih interaktif --}}
                            <li><a href="#"
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Beranda</a>
                            </li>
                            <li><a href="#"
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Tentang
                                    Kami</a></li>
                            <li><a href="#"
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Berita</a>
                            </li>
                            <li><a href="#"
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Jurusan</a>
                            </li>
                        </ul>
                    </div>

                    {{-- Kolom 3: Link Informasi --}}
                    <div>
                        <h4 class="font-semibold text-white tracking-wider uppercase">Informasi</h4>
                        <ul class="mt-4 space-y-3 text-sm">
                            <li><a href="#"
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Info
                                    PPDB</a></li>
                            <li><a href="#"
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Fasilitas</a>
                            </li>
                            <li><a href="#"
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Virtual
                                    Tour</a></li>
                            <li><a href="#"
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Kontak</a>
                            </li>
                        </ul>
                    </div>

                    {{-- Kolom 4: Informasi Kontak --}}
                    <div>
                        <h4 class="font-semibold text-white tracking-wider uppercase">Hubungi Kami</h4>
                        <div class="mt-4 flex flex-col gap-4 text-sm">
                            <div class="flex items-start gap-3 text-gray-400">
                                <i class="fas fa-map-marker-alt w-4 h-4 mt-1 flex-shrink-0"></i>
                                <span>{{ $alamat ?? 'Jl. Raya Veteran III, Banjarwaru, Ciawi, Kab. Bogor, Jawa Barat 16720' }}</span>
                            </div>
                            <div class="flex items-start gap-3 text-gray-400">
                                <i class="fas fa-envelope w-4 h-4 mt-1 flex-shrink-0"></i>
                                <a href="mailto:{{ $email ?? 'info@smkamaliah.sch.id' }}"
                                    class="hover:text-white transition">{{
                                    $email ?? 'info@smkamaliah.sch.id' }}</a>
                            </div>
                            <div class="flex items-start gap-3 text-gray-400">
                                <i class="fas fa-phone-alt w-4 h-4 mt-1 flex-shrink-0"></i>
                                <a href="tel:{{ $phone ?? '+622518241416' }}"
                                    class="hover:text-white transition">{{ $phone ?? '(0251) 8241416' }}</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Bagian Copyright di Bawah --}}
            {{-- 4. Pemisah visual dan struktur copyright yang lebih profesional --}}
            <div class="border-t border-gray-800">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4">
                        <p class="text-sm text-gray-500">
                            &copy; {{ date('Y') }} Tim IT SMK Amaliah. All Rights Reserved.
                        </p>
                        <div class="flex space-x-6 text-sm text-gray-500">
                            <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                            <a href="#" class="hover:text-white transition">Syarat & Ketentuan</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>



    </body>

    </html>



@endsection