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
        ? ($stats['yearMin'] === $stats['yearMax'] ? (string) $stats['yearMin'] : $stats['yearMin'] . ' – ' . $stats['yearMax'])
        : 'Sedang berjalan';
    $hasGallery = $total > 0;
@endphp

{{-- Aturan CSS khusus per kategori (disusun dari data) untuk filter radio --}}
@if ($categories->isNotEmpty())
    <style>
        @foreach ($categories as $category)
            @php $slug = \Illuminate\Support\Str::slug($category); @endphp
            #acr-{{ $slug }}:checked ~ .ac-tabs label[for="acr-{{ $slug }}"] {
                background: var(--ac-green); color: #ffffff;
                box-shadow: 0 4px 12px -2px rgba(99, 205, 0, 0.45);
            }
            #acr-{{ $slug }}:checked ~ .ac-tabs label[for="acr-{{ $slug }}"] .ac-count { background: rgba(255, 255, 255, 0.25); color: #ffffff; }
            #acr-{{ $slug }}:checked ~ .ac-grid .ac-card[data-g~="{{ $slug }}"] { display: flex; }
            #acr-{{ $slug }}:checked ~ .ac-empty[data-e="{{ $slug }}"] { display: flex; }
        @endforeach
    </style>
@endif

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
                    Galeri Prestasi
                </span>
                <h1 class="text-white text-3xl lg:text-5xl font-bold leading-tight">Prestasi &amp; Pencapaian Siswa</h1>
                <p class="mt-4 max-w-2xl text-white/85 text-base lg:text-lg leading-relaxed">
                    {{ $total }} pencapaian membanggakan para siswa SMK Amaliah 1 &amp; 2 di berbagai kompetisi —
                    dari tingkat kabupaten hingga nasional.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="#daftarPrestasi"
                        class="inline-flex items-center gap-2 bg-[#63cd00] text-[#282829] font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-trophy"></i> Lihat Prestasi
                    </a>
                    <a href="#tentangPrestasi"
                        class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-white hover:text-[#282829] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-book-open"></i> Tentang Prestasi
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
                            <span class="inline-flex items-center ml-2 md:ml-3 font-medium text-[#63cd00]">Prestasi</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ============================ STATISTIK ============================ --}}
    <section class="bg-white">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 -mt-8 relative z-20">
                <div class="bg-[#282829] rounded-2xl p-5 lg:p-6 shadow-xl flex items-center gap-4">
                    <span class="w-11 h-11 lg:w-12 lg:h-12 rounded-xl bg-[#63cd00]/15 flex items-center justify-center text-[#63cd00] text-lg lg:text-xl flex-shrink-0">
                        <i class="fa-solid fa-trophy"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-2xl lg:text-3xl font-extrabold text-white leading-none">{{ $total }}</p>
                        <p class="text-xs lg:text-sm text-white/60 mt-1 truncate">Total Prestasi</p>
                    </div>
                </div>
                <div class="bg-[#282829] rounded-2xl p-5 lg:p-6 shadow-xl flex items-center gap-4">
                    <span class="w-11 h-11 lg:w-12 lg:h-12 rounded-xl bg-[#63cd00]/15 flex items-center justify-center text-[#63cd00] text-lg lg:text-xl flex-shrink-0">
                        <i class="fa-solid fa-layer-group"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-2xl lg:text-3xl font-extrabold text-white leading-none">{{ $stats['categoriesCount'] ?? 0 }}</p>
                        <p class="text-xs lg:text-sm text-white/60 mt-1 truncate">Kategori Raihan</p>
                    </div>
                </div>
                <div class="bg-[#282829] rounded-2xl p-5 lg:p-6 shadow-xl flex items-center gap-4">
                    <span class="w-11 h-11 lg:w-12 lg:h-12 rounded-xl bg-[#63cd00]/15 flex items-center justify-center text-[#63cd00] text-lg lg:text-xl flex-shrink-0">
                        <i class="fa-solid fa-ranking-star"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-2xl lg:text-3xl font-extrabold text-white leading-none">{{ $stats['levelsCount'] ?? 0 }}</p>
                        <p class="text-xs lg:text-sm text-white/60 mt-1 truncate">Tingkat Kompetisi</p>
                    </div>
                </div>
                <div class="bg-[#282829] rounded-2xl p-5 lg:p-6 shadow-xl flex items-center gap-4">
                    <span class="w-11 h-11 lg:w-12 lg:h-12 rounded-xl bg-[#63cd00]/15 flex items-center justify-center text-[#63cd00] text-lg lg:text-xl flex-shrink-0">
                        <i class="fa-solid fa-calendar-check"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-2xl lg:text-3xl font-extrabold text-white leading-none truncate">{{ $yearLabel }}</p>
                        <p class="text-xs lg:text-sm text-white/60 mt-1 truncate">Tahun Aktif</p>
                    </div>
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
                                        <img src="{{ asset('storage/' . $achievement->image) }}" alt="{{ $achievement->title }}" loading="lazy">
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
                        Sejarah &amp; Semangat
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