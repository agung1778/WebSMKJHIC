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
        $hasImages = isset($partnersImages) && $partnersImages->isNotEmpty();
    @endphp

    <body>
        <section class="relative max-w-screen">
            {{-- Slider Gambar Dinamis --}}
            @if($hasImages)
                <div x-data="{ activeSlide: 1, totalSlides: {{ $partnersImages->count() }} }"
                    x-init="setInterval(() => { activeSlide = activeSlide % totalSlides + 1 }, 5000)">
                    <div class="relative w-full h-[300px] overflow-hidden">
                        @foreach($partnersImages as $image)
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
                                    <a href="{{ route('public.partners.index') }}"
                                        class="ml-2 font-medium text-white hover:text-white md:ml-3 transition-colors">Industry
                                        Partners</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        {{-- ========================================================== --}}
        {{-- BAGIAN MITRA INDUSTRI --}}
        {{-- ========================================================== --}}
        <section class="bg-[#333333] py-16 sm:py-24">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- KEPALA BAGIAN (JUDUL) --}}
                <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16">
                
                    {{-- Judul Utama --}}
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-white tracking-tight">
                        Bermitra dengan Industri Terkemuka
                    </h2>
                    <p class="mt-4 text-lg text-gray-300">
                        Kami menjalin kerja sama strategis untuk memastikan lulusan siap kerja dan relevan dengan kebutuhan
                        pasar.
                    </p>
                </div>

                {{-- GRID DAFTAR MITRA --}}
                {{-- Ganti grid-cols-4 dengan 5 atau 6 jika logo lebih banyak dan kecil --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 lg:gap-8">

                    {{-- Diasumsikan Anda melakukan loop dari data $partners yang dikirim dari controller --}}
                    @forelse($partners as $partner)
                        <div
                            class="group bg-white/5 border border-white/10 rounded-xl p-6 flex flex-col text-center transition-all duration-300 hover:border-white/20 hover:-translate-y-2 hover:shadow-2xl">

                            {{-- Wadah Logo --}}
                            <div
                                class="h-28 w-full bg-gray-100 rounded-lg flex items-center justify-center p-4 mb-5 shadow-inner">

                                {{-- Ubah $partner->logo_path menjadi $partner->logo --}}
                                @if ($partner->logo)

                                    {{-- Ubah juga di sini --}}
                                    <img src="{{ Storage::url($partner->logo) }}" alt="Logo {{ $partner->name }}"
                                        class="max-h-20 w-auto object-contain">

                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded-md">
                                        <span class="text-sm font-semibold text-gray-500">Logo</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Nama Mitra --}}
                            <h3 class="text-lg font-bold text-white mb-2 leading-tight">
                                {{ $partner->name }}
                            </h3>

                            {{-- Deskripsi Singkat (flex-grow mendorong area aksi ke bawah) --}}
                            <p class="text-sm text-gray-300 flex-grow mb-4">
                                {{ Str::limit($partner->description, 60) }}
                            </p>

                            {{-- ========================================================== --}}
                            {{-- AREA AKSI BARU (Tombol Detail & Link Website) --}}
                            {{-- ========================================================== --}}
                            <div
                                class="w-full mt-auto pt-5 border-t border-white/10 flex items-center justify-center space-x-4">

                                {{-- Tombol Lihat Detail (Aksi Utama) --}}
                                {{-- Pastikan route 'public.partners.show' sudah ada dan menerima parameter partner --}}
                                <a href="{{ route('public.partners.show', $partner) }}"
                                    class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 text-white text-xs font-bold px-4 py-2 rounded-full transition-colors duration-300">
                                    Lihat Detail
                                </a>

                                {{-- Link Website (jika ada, sekarang menjadi ikon) --}}
                                @if ($partner->website)
                                    <a href="{{ $partner->website }}" target="_blank" rel="noopener noreferrer"
                                        title="Kunjungi Situs Web"
                                        class="h-9 w-9 flex items-center justify-center bg-white/10 hover:bg-white/20 text-white rounded-full transition-colors duration-300">
                                        <i class="fas fa-globe text-sm"></i>
                                    </a>
                                @endif
                            </div>

                        </div>
                    @empty
                        <div
                            class="col-span-2 md:col-span-3 lg:col-span-4 bg-white/5 border border-dashed border-white/10 rounded-xl p-12 text-center">
                            <p class="text-gray-400">Data mitra industri belum tersedia.</p>
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