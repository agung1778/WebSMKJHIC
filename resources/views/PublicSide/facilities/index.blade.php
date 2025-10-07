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
        <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    </head>
    @php
        $amaliahGreen = '#63cd00';
        $amaliahDark = '#282829';
        $amaliahBlue = '#E0E7FF';

        // Cek Variabel 
        $hasImages = isset($facilityImages) && $facilityImages->isNotEmpty();
    @endphp

    <body>
        <section class="relative max-w-screen">
            {{-- Slider Gambar Dinamis --}}
            @if($hasImages)
                <div x-data="{ activeSlide: 1, totalSlides: {{ $facilityImages->count() }} }"
                    x-init="setInterval(() => { activeSlide = activeSlide % totalSlides + 1 }, 5000)">
                    <div class="relative w-full h-[300px] overflow-hidden">
                        @foreach($facilityImages as $image)
                            <div x-show="activeSlide === {{ $loop->iteration }}"
                                x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-1000"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0">

                                <img src="{{ Storage::url($image->path) }}" alt="{{ $image->description ?? $image->filename }}"
                                    class="w-full h-full object-cover">
                            </div>
                        @endforeach

                    </div>
                </div>
            @else
                <div>
                    <div class="relative h-[300px] overflow-hidden bg-black">
                        {{-- Layar hitam sebagai fallback --}}
                    </div>
                </div>
            @endif
        </section>
        <div style="background-color: #2D2D2D;">
            <div class="max-w-screen-xl h-[70px] mx-auto px-4 sm:px-6 lg:px-8">
                {{-- Menggunakan h-full dan flex items-center untuk membuat konten di tengah vertikal --}}
                <div class="h-full flex items-center">
                    <nav class="flex" aria-label="Breadcrumb">
                        {{-- Text-lg untuk memperbesar teks --}}
                        <ol class="inline-flex items-center space-x-2 md:space-x-3 text-lg">
                            <li class="inline-flex items-center">
                                <a href="/"
                                    class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                    Home
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-white text-xs"></i>
                                    <a href="{{ route('public.facilities.index') }}"
                                        class="ml-2 font-medium text-white hover:text-white md:ml-3 transition-colors">Facilities</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <section class="bg-white py-16 sm:py-24">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- KEPALA BAGIAN (JUDUL DI KIRI, FILTER DI KANAN) --}}
                {{-- Margin bawah dikurangi dari mb-12 menjadi mb-10 --}}
                <div class="flex flex-col md:flex-row justify-between md:items-end gap-8 mb-10">

                    {{-- Kolom Kiri: Judul dan Deskripsi --}}
                    <div class="md:w-1/2">
                        {{-- Ukuran font judul diperkecil --}}
                        <h2 class="text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight mt-[-50px]">
                            Fasilitas Unggulan Kami
                        </h2>
                        {{-- Ukuran font deskripsi diperkecil --}}
                        <p class="mt-3 text-base text-gray-600">
                            Jelajahi beragam sarana dan prasarana modern yang kami sediakan untuk mendukung proses belajar.
                        </p>
                    </div>

                    {{-- Kolom Kanan: Tombol Filter --}}
                    <div class="flex-shrink-0">
                        <div class="flex flex-wrap items-center justify-start md:justify-end gap-3">
                            <a href="{{ route('public.facilities.index') }}"
                                class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors duration-200
                                  {{ !$currentType ? 'bg-[#59E300] text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Semua
                            </a>
                            @foreach($types as $type)
                                <a href="{{ route('public.facilities.index', ['type' => $type]) }}"
                                    class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors duration-200
                                          {{ $currentType == $type ? 'bg-[#59E300] text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    {{ $type }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>


                {{-- GRID DAFTAR FASILITAS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                    @forelse($facilities as $facility)
                        @php
                            // Logika untuk menentukan ikon berdasarkan tipe fasilitas
                            $iconClass = 'fa-star'; // Ikon default
                            if ($facility->type == 'Akademik') {
                                $iconClass = 'fa-book-open';
                            } elseif ($facility->type == 'Olahraga') {
                                $iconClass = 'fa-futbol';
                            } elseif ($facility->type == 'Umum') {
                                $iconClass = 'fa-building';
                            }
                        @endphp

                        <div
                            class="group bg-white border border-gray-200 rounded-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:-translate-y-2">

                            {{-- GAMBAR FASILITAS --}}
                            <div class="relative h-56 w-full">
                                @if ($facility->image)
                                    <img src="{{ Storage::url($facility->image) }}" alt="Gambar {{ $facility->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                            </div>

                                        {{-- KONTEN CARD --}}
                                        <div class="p-6 flex flex-col flex-grow">
                                            {{-- TIPE & IKON --}}
                                            <div class="flex items-center text-sm font-semibold text-blue-600 mb-2">
                                                <i class="fas {{ $iconClass }} mr-2 w-4 text-center"></i>
                                                <span>{{ $facility->type }}</span>
                                            </div>

                                            {{-- NAMA FASILITAS --}}
                                            <h3 class="text-xl font-bold text-gray-900 mb-2 leading-tight">
                                                {{ $facility->name }}
                                            </h3>

                                            {{-- DESKRIPSI SINGKAT --}}
                                            <p class="text-gray-600 text-sm flex-grow mb-6">
                                                {{ Str::limit($facility->description, 120) }}
                                            </p>

                                            {{-- TOMBOL AKSI --}}
                                            <div class="mt-auto">
                                                <a href="{{ route('public.facilities.show', $facility) }}"
                                                    class="inline-flex items-center font-semibold text-blue-600 group/link">
                                                    Baca Selengkapnya
                                                    <i
                                                        class="fas fa-arrow-right ml-2 text-xs transition-transform duration-300 group-hover/link:translate-x-1"></i>
                                                </a>
                                            </div>
                                        </div>
                        </div>
                    @empty
                        {{-- TAMPILAN JIKA TIDAK ADA FASILITAS --}}
                        <div
                            class="col-span-1 md:col-span-2 lg:col-span-3 border-2 border-dashed border-gray-300 rounded-lg p-12 text-center">
                            <p class="text-gray-500 font-medium">Fasilitas dengan kriteria ini tidak ditemukan.</p>
                        </div>
                    @endforelse

                </div>
            </div>
        </section>






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