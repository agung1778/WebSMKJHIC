@extends('layouts.public-app')

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
        <section class="bg-gray-50 py-16 sm:py-24">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- KEPALA BAGIAN (JUDUL) --}}
                <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">

                    {{-- Judul Utama dengan sentuhan gradien untuk tampilan modern --}}
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-[#2D2D2D] tracking-tight">
                        Bermitra dengan Industri Terkemuka
                    </h2>
                    <p class="mt-4 text-lg text-slate-600">
                        Kami menjalin kerja sama strategis untuk memastikan lulusan siap kerja dan relevan dengan kebutuhan
                        pasar.
                    </p>
                </div>

                {{-- GRID DAFTAR MITRA --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">

                    {{-- Loop untuk setiap kartu mitra --}}
                    @forelse($partners as $partner)
                        <div
                            class="group bg-white border border-slate-200 rounded-xl p-6 flex flex-col text-center transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-2">

                            {{-- Wadah Logo --}}
                            <div
                                class="h-28 w-full bg-slate-100 rounded-lg flex items-center justify-center p-4 mb-5 border border-slate-200">

                                @if ($partner->logo)
                                    <img src="{{ Storage::url($partner->logo) }}" alt="Logo {{ $partner->name }}"
                                        class="max-h-20 w-auto object-contain">
                                @else
                                    <div class="w-full h-full bg-slate-200 flex items-center justify-center rounded-md">
                                        <span class="text-sm font-semibold text-slate-500">Logo</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Nama Mitra --}}
                            <h3 class="text-lg font-bold text-slate-800 mb-2 leading-tight">
                                {{ $partner->name }}
                            </h3>

                            {{-- Deskripsi Singkat --}}
                            <p class="text-sm text-slate-500 flex-grow mb-4">
                                {{ Str::limit($partner->description, 70) }}
                            </p>

                            {{-- AREA AKSI (Tombol) --}}
                            <div
                                class="w-full mt-auto pt-5 border-t border-slate-200/80 flex items-center justify-center space-x-3">

                                {{-- Tombol Lihat Detail (Aksi Utama) --}}
                                <a href="{{ route('public.partners.show', $partner) }}"
                                    class="inline-flex items-center justify-center bg-[#2D2D2D] hover:bg-[#3C3C3C] text-white text-xs font-bold px-4 py-2.5 rounded-full transition-colors duration-300">
                                    Lihat Detail
                                </a>

                                {{-- Link Website (Ikon) --}}
                                @if ($partner->website)
                                    <a href="{{ $partner->website }}" target="_blank" rel="noopener noreferrer"
                                        title="Kunjungi Situs Web"
                                        class="h-9 w-9 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-full transition-colors duration-300">
                                        <i class="fas fa-globe text-sm"></i>
                                    </a>
                                @endif
                            </div>

                        </div>
                    @empty
                        {{-- Tampilan jika tidak ada data mitra --}}
                        <div
                            class="sm:col-span-2 lg:col-span-3 xl:col-span-4 bg-white border-2 border-dashed border-slate-300 rounded-xl p-12 text-center">
                            <p class="text-slate-500">Data mitra industri belum tersedia.</p>
                        </div>
                    @endforelse

                </div>
            </div>
        </section>

    </body>

    </html>


@endsection