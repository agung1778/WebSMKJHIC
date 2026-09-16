@extends('layouts.public-app')

@section('title', 'Ekstrakurikuler | SMK Amaliah 1 & 2')
@section('description', 'Kegiatan ekstrakurikuler SMK Amaliah 1 & 2 Ciawi-Bogor: Pramuka, ACC, dan lainnya. Kembangkan bakat dan minat di luar jam pelajaran.')

@include('PublicSide.extracurricular._styles')

@php
    $hasImages = isset($mainImages) && $mainImages->isNotEmpty();
    $heroImage = $hasImages ? $mainImages->first() : null;
    $total = $extracurriculars->count();
    $wajibCount = $extracurriculars->where('type', 'Wajib')->count();
    $pilihanCount = $extracurriculars->where('type', 'Pilihan')->count();
@endphp

@section('content')

    {{-- ============================ HERO ============================ --}}
    <section class="relative bg-[#282829]">
        <div class="relative h-[320px] lg:h-[420px] overflow-hidden">
            @if ($heroImage)
                <img src="{{ Storage::url($heroImage->path) }}"
                    alt="{{ $heroImage->description ?? $heroImage->filename }}"
                    class="w-full h-full object-cover">
            @else
                <div class="absolute inset-0">
                    <div class="absolute -top-24 -right-16 w-96 h-96 rounded-full opacity-25"
                        style="background: radial-gradient(circle, #63cd00 0%, transparent 70%)"></div>
                    <div class="absolute bottom-0 left-0 w-full h-2/3"
                        style="background: radial-gradient(ellipse at bottom left, rgba(99,205,0,.18) 0%, transparent 60%)"></div>
                </div>
            @endif

            <div class="absolute inset-0"
                style="background: linear-gradient(100deg, rgba(40,40,41,.88) 0%, rgba(40,40,41,.55) 45%, rgba(40,40,41,.15) 100%)"></div>

            <div class="relative z-10 h-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center">
                <span
                    class="inline-flex items-center gap-2 w-fit text-[11px] lg:text-xs font-semibold tracking-widest uppercase text-[#d9ffb3] bg-white/10 backdrop-blur border border-white/15 rounded-full px-4 py-1.5 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#63cd00]"></span>
                    Ekstrakurikuler
                </span>
                <h1 class="text-white text-3xl lg:text-5xl font-bold leading-tight">Kegiatan <span class="text-[#63cd00]">Ekstrakurikuler</span></h1>
                <p class="mt-4 max-w-2xl text-white/85 text-base lg:text-lg leading-relaxed">
                    {{ $total }} kegiatan untuk mengembangkan bakat, minat, dan karakter siswa di luar jam pelajaran.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="#daftarEkstrakurikuler"
                        class="inline-flex items-center gap-2 bg-[#63cd00] text-[#282829] font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-futbol"></i> Lihat Kegiatan
                    </a>
                    <a href="{{ route('public.about.index') }}"
                        class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-white hover:text-[#282829] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-circle-info"></i> Tentang Kami
                    </a>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <div style="background-color:#2D2D2D;">
            <div class="max-w-screen-xl min-h-14 mx-auto px-4 sm:px-6 lg:px-8 flex items-center py-3">
                <nav aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2 md:space-x-3 text-sm">
                        <li class="flex items-center">
                            <a href="/" class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                Home
                            </a>
                        </li>
                        <li class="flex items-center">
                            <i class="fa-solid fa-chevron-right text-white/40 text-xs"></i>
                            <span class="inline-flex items-center ml-2 md:ml-3 font-medium text-[#63cd00]">Ekstrakurikuler</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ============================ STATISTIK ============================ --}}
    <section class="bg-white">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 -mt-8 relative z-20">
                <div class="bg-[#282829] rounded-2xl p-5 lg:p-6 shadow-xl flex items-center gap-4">
                    <span class="w-11 h-11 lg:w-12 lg:h-12 rounded-xl bg-[#63cd00]/15 flex items-center justify-center text-[#63cd00] text-lg lg:text-xl flex-shrink-0">
                        <i class="fa-solid fa-futbol"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-2xl lg:text-3xl font-extrabold text-white leading-none">{{ $total }}</p>
                        <p class="text-xs lg:text-sm text-white/60 mt-1 truncate">Total Kegiatan</p>
                    </div>
                </div>
                <div class="bg-[#282829] rounded-2xl p-5 lg:p-6 shadow-xl flex items-center gap-4">
                    <span class="w-11 h-11 lg:w-12 lg:h-12 rounded-xl bg-[#eab308]/15 flex items-center justify-center text-[#eab308] text-lg lg:text-xl flex-shrink-0">
                        <i class="fa-solid fa-shield-halved"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-2xl lg:text-3xl font-extrabold text-white leading-none">{{ $wajibCount }}</p>
                        <p class="text-xs lg:text-sm text-white/60 mt-1 truncate">Wajib</p>
                    </div>
                </div>
                <div class="bg-[#282829] rounded-2xl p-5 lg:p-6 shadow-xl flex items-center gap-4">
                    <span class="w-11 h-11 lg:w-12 lg:h-12 rounded-xl bg-[#0ea5e9]/15 flex items-center justify-center text-[#0ea5e9] text-lg lg:text-xl flex-shrink-0">
                        <i class="fa-solid fa-list-check"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-2xl lg:text-3xl font-extrabold text-white leading-none">{{ $pilihanCount }}</p>
                        <p class="text-xs lg:text-sm text-white/60 mt-1 truncate">Pilihan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================ DAFTAR EKSTRAKURIKULER ============================ --}}
    <section id="daftarEkstrakurikuler" class="bg-gray-50 py-16 sm:py-20">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="max-w-2xl mb-10">
                <span class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-[#63cd00] mb-3">
                    <span class="w-6 h-0.5 rounded-full bg-[#63cd00]"></span>
                    Kegiatan Ekstrakurikuler
                </span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#282829] tracking-tight">Kembangkan Bakat & Minat</h2>
                <p class="mt-3 text-gray-600 text-lg">Berbagai pilihan kegiatan yang didampingi pembina berpengalaman.</p>
            </div>

            @if ($total > 0)
                <div class="ec-grid">
                    @foreach ($extracurriculars as $item)
                        <a href="{{ route('public.extracurricular.show', $item->id) }}"
                            class="ec-card group"
                            aria-label="{{ $item->name }}">
                            <div class="ec-card__thumb">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" loading="lazy">
                                @else
                                    <div class="ec-thumb-fallback"><i class="fa-solid fa-futbol"></i></div>
                                @endif
                                <span class="ec-chip absolute top-3 left-3 z-10">
                                    <i class="fa-solid fa-user-tie"></i>
                                    {{ $item->coach }}
                                </span>
                                @if ($item->type === 'Wajib')
                                    <span class="ec-chip ec-chip--type-wajib absolute top-3 right-3 z-10">
                                        <i class="fa-solid fa-shield-halved"></i>
                                        Wajib
                                    </span>
                                @else
                                    <span class="ec-chip ec-chip--type-pilihan absolute top-3 right-3 z-10">
                                        <i class="fa-solid fa-list-check"></i>
                                        Pilihan
                                    </span>
                                @endif
                            </div>

                            <div class="p-5 flex flex-col flex-1">
                                <h3 class="text-lg font-bold text-[#282829] leading-snug line-clamp-2 group-hover:text-[#63cd00] transition-colors duration-200">
                                    {{ $item->name }}
                                </h3>
                                <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 mt-2 flex-grow">
                                    {{ strip_tags($item->description) }}
                                </p>
                                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                    <span class="ec-card__hint">
                                        Selengkapnya
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </span>
                                    @if ($item->contact)
                                        <span class="ec-meta"><i class="fa-solid fa-phone"></i> {{ $item->contact }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="border-2 border-dashed border-gray-300 rounded-2xl p-14 text-center bg-white">
                    <div class="w-14 h-14 mx-auto rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-xl mb-3">
                        <i class="fa-solid fa-futbol"></i>
                    </div>
                    <p class="text-gray-600 font-semibold">Belum ada kegiatan yang dipublikasikan.</p>
                    <p class="text-sm text-gray-500 mt-1">Data ekstrakurikuler akan segera hadir.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ============================ CTA SECTION ============================ --}}
    <section class="bg-[#282829] py-16 sm:py-20">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white">Tertarik Bergabung?</h2>
            <p class="mt-4 max-w-2xl mx-auto text-white/80 text-lg">
                Daftar PPDB dan jadilah bagian dari komunitas SMK Amaliah yang aktif berprestasi.
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('public.about.index') }}"
                    class="inline-flex items-center gap-2 bg-[#63cd00] text-[#282829] font-semibold text-base px-8 py-4 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-lg">
                    <i class="fa-solid fa-user-plus"></i> Info PPDB
                </a>
                <a href="https://wa.me/6285649011449" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white font-semibold text-base px-8 py-4 rounded-full hover:bg-white hover:text-[#282829] hover:-translate-y-0.5 transition-all duration-300">
                    <i class="fa-brands fa-whatsapp"></i> Konsultasi WA
                </a>
            </div>
        </div>
    </section>

@endsection