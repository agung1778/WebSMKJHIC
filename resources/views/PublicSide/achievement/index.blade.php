@extends('layouts.public-app')

@section('title', 'Prestasi & Pencapaian | SMK Amaliah 1 & 2')
@section('description', 'Galeri prestasi siswa SMK Amaliah 1 & 2 Ciawi-Bogor: juara kompetisi akademik maupun non-akademik dari tingkat kabupaten hingga nasional.')

@include('PublicSide.achievement._styles')

@php
    $hasImages = isset($achievementImages) && $achievementImages->isNotEmpty();
    $heroImage = $hasImages ? $achievementImages->first() : null;
    $categories = $stats['categories'] ?? collect([]);
    $total = $stats['total'] ?? $achievements->total();
    $yearLabel = ($stats['yearMin'] ?? null) !== null
        ? ($stats['yearMin'] === $stats['yearMax'] ? (string) $stats['yearMin'] : $stats['yearMin'] . '–' . $stats['yearMax'])
        : date('Y');
    $hasGallery = $total > 0;
@endphp

{{-- Aturan CSS khusus per kategori (disusun dari data) untuk filter radio --}}
@if ($categories->isNotEmpty())
    <style>
        @foreach ($categories as $category)
            @php $slug = \Illuminate\Support\Str::slug($category); @endphp
            #acr-{{ $slug }}:checked ~ .ac-tabs label[for="acr-{{ $slug }}"] {
                background: #1c1c1d; color: #ffffff; border-color: #1c1c1d;
                box-shadow: 0 4px 12px -2px rgba(28, 28, 29, 0.35);
            }
            #acr-{{ $slug }}:checked ~ .ac-tabs label[for="acr-{{ $slug }}"] .ac-count { background: rgba(255, 255, 255, 0.25); color: #ffffff; }
            #acr-{{ $slug }}:checked ~ .ac-grid .ac-card[data-g~="{{ $slug }}"] { display: flex; }
            #acr-{{ $slug }}:checked ~ .ac-empty[data-e="{{ $slug }}"] { display: flex; }
        @endforeach
    </style>
@endif

@section('content')

    {{-- ============================ HERO ============================ --}}
    <section class="relative bg-[#1f1f20]">
        <div class="relative min-h-[300px] sm:min-h-[360px] lg:h-[420px] overflow-hidden flex flex-col justify-center">
            @if ($heroImage)
                <img src="{{ img_url($heroImage->path, 1600, 900) }}" width="1600" height="900"
                    alt="{{ $heroImage->description ?? $heroImage->filename }}"
                    class="absolute inset-0 w-full h-full object-cover">
            @else
                <div class="absolute inset-0">
                    <div class="absolute -top-24 -right-16 w-96 h-96 rounded-full opacity-20"
                        style="background: radial-gradient(circle, #00875a 0%, transparent 70%)"></div>
                    <div class="absolute bottom-0 left-0 w-full h-2/3"
                        style="background: radial-gradient(ellipse at bottom left, rgba(0,135,90,.22) 0%, transparent 60%)"></div>
                </div>
            @endif

            <div class="absolute inset-0"
                style="background: linear-gradient(100deg, rgba(20,20,22,0.94) 0%, rgba(20,20,22,0.82) 48%, rgba(20,20,22,0.55) 100%)"></div>

            <div class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 flex flex-col justify-center w-full">
                <span
                    class="inline-flex items-center gap-2 w-fit text-xs font-semibold tracking-wider uppercase text-white bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-3.5 py-1 mb-3 sm:mb-4 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-[#00875a]"></span>
                    Galeri Prestasi
                </span>
                <h1 class="text-white text-2xl sm:text-4xl lg:text-5xl font-extrabold leading-tight tracking-tight">Prestasi &amp; Pencapaian Siswa</h1>
                <p class="mt-2.5 sm:mt-4 max-w-2xl text-white/90 text-sm sm:text-base lg:text-lg leading-relaxed">
                    {{ $total }} pencapaian membanggakan para siswa SMK Amaliah 1 &amp; 2 di berbagai kompetisi —
                    dari tingkat kabupaten hingga nasional.
                </p>

                <div class="mt-5 sm:mt-8 flex flex-wrap items-center gap-2.5 sm:gap-3">
                    <a href="#daftarPrestasi"
                        class="inline-flex items-center gap-2 bg-[#00875a] text-white font-semibold text-xs sm:text-sm lg:text-base px-5 sm:px-6 py-2.5 sm:py-3 rounded-full hover:bg-[#00704a] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-trophy"></i> Lihat Prestasi
                    </a>
                    <a href="#tentangPrestasi"
                        class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white font-semibold text-xs sm:text-sm lg:text-base px-5 sm:px-6 py-2.5 sm:py-3 rounded-full hover:bg-white hover:text-[#1f1f20] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-book-open"></i> Tentang Prestasi
                    </a>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <div style="background-color:#1c1c1d; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div class="max-w-screen-xl min-h-12 py-2.5 mx-auto px-4 sm:px-6 lg:px-8 flex items-center overflow-x-auto scrollbar-hide">
                <nav aria-label="Breadcrumb" class="min-w-0">
                    <ol class="inline-flex items-center gap-1.5 sm:gap-2.5 text-xs sm:text-sm whitespace-nowrap">
                        <li class="flex items-center">
                            <a href="/" class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                <i class="fa-solid fa-house text-xs mr-1.5 opacity-70"></i> Home
                            </a>
                        </li>
                        <li class="flex items-center gap-1.5 sm:gap-2.5">
                            <i class="fa-solid fa-chevron-right text-white/30 text-[10px]"></i>
                            <span class="inline-flex items-center font-medium text-[#00875a]">Prestasi</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ============================ STATISTIK (SESUAI GAMBAR 1) ============================ --}}
    <section class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
                <div class="group bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 text-center shadow-xs hover:border-[#00875a] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="mx-auto w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#e6f4ea] border border-[#00875a]/30 flex items-center justify-center shadow-xs group-hover:bg-[#00875a] group-hover:border-[#00875a] transition-colors duration-300">
                        <i class="fa-solid fa-trophy text-[#00875a] group-hover:text-white transition-colors duration-300 text-base sm:text-lg"></i>
                    </div>
                    <div class="mt-2.5 sm:mt-3 text-2xl sm:text-3xl font-extrabold text-[#1c1c1d] tracking-tight">
                        {{ $total }}
                    </div>
                    <div class="mt-1 text-xs sm:text-sm font-semibold text-gray-700 leading-snug">Total Prestasi</div>
                </div>

                <div class="group bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 text-center shadow-xs hover:border-[#00875a] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="mx-auto w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#e6f4ea] border border-[#00875a]/30 flex items-center justify-center shadow-xs group-hover:bg-[#00875a] group-hover:border-[#00875a] transition-colors duration-300">
                        <i class="fa-solid fa-layer-group text-[#00875a] group-hover:text-white transition-colors duration-300 text-base sm:text-lg"></i>
                    </div>
                    <div class="mt-2.5 sm:mt-3 text-2xl sm:text-3xl font-extrabold text-[#1c1c1d] tracking-tight">
                        {{ $stats['categoriesCount'] ?? 0 }}
                    </div>
                    <div class="mt-1 text-xs sm:text-sm font-semibold text-gray-700 leading-snug">Kategori Raihan</div>
                </div>

                <div class="group bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 text-center shadow-xs hover:border-[#00875a] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="mx-auto w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#e6f4ea] border border-[#00875a]/30 flex items-center justify-center shadow-xs group-hover:bg-[#00875a] group-hover:border-[#00875a] transition-colors duration-300">
                        <i class="fa-solid fa-ranking-star text-[#00875a] group-hover:text-white transition-colors duration-300 text-base sm:text-lg"></i>
                    </div>
                    <div class="mt-2.5 sm:mt-3 text-2xl sm:text-3xl font-extrabold text-[#1c1c1d] tracking-tight">
                        {{ $stats['levelsCount'] ?? 0 }}
                    </div>
                    <div class="mt-1 text-xs sm:text-sm font-semibold text-gray-700 leading-snug">Tingkat Kompetisi</div>
                </div>

                <div class="group bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 text-center shadow-xs hover:border-[#00875a] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="mx-auto w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#e6f4ea] border border-[#00875a]/30 flex items-center justify-center shadow-xs group-hover:bg-[#00875a] group-hover:border-[#00875a] transition-colors duration-300">
                        <i class="fa-solid fa-calendar-check text-[#00875a] group-hover:text-white transition-colors duration-300 text-base sm:text-lg"></i>
                    </div>
                    <div class="mt-2.5 sm:mt-3 text-2xl sm:text-3xl font-extrabold text-[#1c1c1d] tracking-tight">
                        {{ $yearLabel }}
                    </div>
                    <div class="mt-1 text-xs sm:text-sm font-semibold text-gray-700 leading-snug">Tahun Aktif</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================ DAFTAR PRESTASI ============================ --}}
    <section id="daftarPrestasi" class="bg-gray-50 py-16 sm:py-20">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="max-w-2xl mb-10">
                <span class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-[#63cd00] mb-3">
                    <span class="w-6 h-0.5 rounded-full bg-[#63cd00]"></span>
                    Galeri Prestasi
                </span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#282829] tracking-tight">Kebanggaan SMK Amaliah</h2>
                <p class="mt-3 text-gray-600 text-lg">Koleksi kemenangan para siswa di kompetisi akademik maupun non-akademik.</p>
            </div>

            @if ($hasGallery)
                <div class="ac-filter">
                    {{-- Radio filter (radio + CSS murni, tanpa JavaScript) --}}
                    <input type="radio" name="acr" id="acr-all" class="ac-filter-input" checked>
                    @foreach ($categories as $category)
                        <input type="radio" name="acr" id="acr-{{ \Illuminate\Support\Str::slug($category) }}" class="ac-filter-input">
                    @endforeach

                    {{-- Tab filter --}}
                    <div class="ac-tabs mb-8" role="tablist" aria-label="Filter kategori prestasi">
                        <label for="acr-all" class="ac-tab" role="tab">
                            <i class="fa-solid fa-trophy"></i> Semua
                            <span class="ac-count">{{ $total }}</span>
                        </label>
                        @foreach ($categories as $category)
                            <label for="acr-{{ \Illuminate\Support\Str::slug($category) }}" class="ac-tab" role="tab">
                                <i class="fa-solid fa-medal"></i> {{ $category }}
                                <span class="ac-count">{{ $achievements->filter(fn ($a) => $a->category === $category)->count() }}</span>
                            </label>
                        @endforeach
                    </div>

                    {{-- Grid kartu --}}
                    <div class="ac-grid">
                        @forelse ($achievements as $achievement)
                            <a href="{{ route('public.achievement.show', $achievement->id) }}"
                                class="ac-card group"
                                data-g="all {{ \Illuminate\Support\Str::slug($achievement->category) }}"
                                aria-label="{{ $achievement->title }}">
                                <div class="ac-card__thumb">
                                    @if ($achievement->image)
                                        <img src="{{ img_url($achievement->image, 900, 520) }}" width="900" height="520" alt="{{ $achievement->title }}" loading="lazy">
                                    @else
                                        <div class="ac-thumb-fallback"><i class="fa-solid fa-trophy"></i></div>
                                    @endif
                                    <span class="ac-chip absolute top-3 left-3 z-10">
                                        <i class="fa-solid fa-map-location-dot"></i>
                                        {{ $achievement->level }}
                                    </span>
                                    <span class="ac-chip ac-chip--green absolute bottom-3 left-3 z-10">
                                        <i class="fa-solid fa-medal"></i>
                                        {{ $achievement->winner }}
                                    </span>
                                </div>

                                <div class="p-5 flex flex-col flex-1">
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mb-2">
                                        <span class="ac-meta"><i class="fa-solid fa-award"></i> {{ $achievement->category }}</span>
                                        <span class="ac-meta"><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($achievement->date)->format('d M Y') }}</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-[#282829] leading-snug line-clamp-2 group-hover:text-[#63cd00] transition-colors duration-200">
                                        {{ $achievement->title }}
                                    </h3>
                                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 mt-2 flex-grow">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($achievement->description), 140) }}
                                    </p>
                                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                        <span class="ac-card__hint">
                                            Lihat Detail
                                            <i class="fa-solid fa-arrow-right text-xs"></i>
                                        </span>
                                        <span class="ac-meta"><i class="fa-solid fa-user-pen"></i> {{ $achievement->publisher }}</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="ac-empty ac-empty--all">
                                <div class="ac-empty__inner">
                                    <div class="w-14 h-14 mx-auto rounded-full bg-white border border-gray-200 flex items-center justify-center text-[#63cd00] text-xl">
                                        <i class="fa-solid fa-award"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-semibold">Belum ada prestasi yang dipublikasikan.</p>
                                        <p class="text-sm text-gray-500 mt-1">Nantikan kabar kemenangan terbaru dari para siswa.</p>
                                    </div>
                                </div>
                            </div>
                        @endforelse

                        @foreach ($categories as $category)
                            <div class="ac-empty" data-e="{{ \Illuminate\Support\Str::slug($category) }}">
                                <div class="ac-empty__inner">
                                    <div class="w-14 h-14 mx-auto rounded-full bg-white border border-gray-200 flex items-center justify-center text-[#63cd00] text-xl">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-semibold">Belum ada prestasi kategori {{ $category }}.</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="border-2 border-dashed border-gray-300 rounded-2xl p-14 text-center bg-white">
                    <div class="w-14 h-14 mx-auto rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-xl mb-3">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <p class="text-gray-600 font-semibold">Belum ada prestasi yang ditambahkan.</p>
                    <p class="text-sm text-gray-500 mt-1">Data prestasi siswa akan segera hadir.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ============================ TENTANG PRESTASI ============================ --}}
    <section id="tentangPrestasi" class="bg-white py-16 sm:py-20">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-x-12 gap-10 items-center">
                <div class="lg:col-span-1">
                    <span class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-[#63cd00] mb-3">
                        <span class="w-6 h-0.5 rounded-full bg-[#63cd00]"></span>
                        Sejarah & Semangat
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-[#282829] tracking-tight">Berprestasi, Berkarakter, Bermartabat</h2>
                    <p class="mt-3 text-gray-600 text-lg leading-relaxed">
                        SMK Amaliah mendukung penuh bakat setiap siswa — buktinya sederet torehan prestasi dari masa ke masa.
                    </p>
                </div>

                <div class="lg:col-span-2">
                    @if ($achievementContent)
                        <article class="ac-article bg-gray-50 border border-[#eef0f3] rounded-2xl p-6 sm:p-10">
                            {!! \App\Support\HtmlSanitizer::clean($achievementContent->content) !!}
                        </article>
                    @else
                        <div class="bg-gray-50 border border-[#eef0f3] rounded-2xl p-10 text-center">
                            <div class="w-16 h-16 mx-auto rounded-full bg-white border border-gray-200 flex items-center justify-center text-[#63cd00] text-2xl mb-4">
                                <i class="fa-solid fa-book-open"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Konten Belum Tersedia</h3>
                            <p class="mt-2 text-sm text-gray-500 max-w-md mx-auto">
                                Narasi tentang sejarah dan perjalanan prestasi sekolah sedang disiapkan oleh tim kami.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection