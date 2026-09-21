@extends('layouts.public-app')

@section('title', 'Berita & Informasi | SMK Amaliah 1 & 2')
@section('description', 'Berita terbaru seputar SMK Amaliah 1 & 2 Ciawi-Bogor: kegiatan, prestasi, informasi akademik, dan pengumuman resmi sekolah.')

@include('PublicSide.news._styles')

@php
    $total = $news->total();
    $heroImage = isset($newsImages) && $newsImages->isNotEmpty() ? $newsImages->first() : null;
    $featured = $news->onFirstPage() ? $news->first() : null;
    $rest = $featured ? $news->slice(1) : $news;
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
                    Media &amp; Informasi
                </span>
                <h1 class="text-white text-3xl lg:text-5xl font-bold leading-tight">Berita &amp; Informasi Terbaru</h1>
                <p class="mt-4 max-w-2xl text-white/85 text-base lg:text-lg leading-relaxed">
                    Ikuti {{ $total }} berita seputar kegiatan, prestasi, dan pengumuman resmi dari
                    SMK Amaliah 1 &amp; 2 Ciawi-Bogor.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="#daftarBerita"
                        class="inline-flex items-center gap-2 bg-[#63cd00] text-[#282829] font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-newspaper"></i> Lihat Berita
                    </a>
                    <a href="{{ route('public.achievement.index') }}"
                        class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-white hover:text-[#282829] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-trophy"></i> Prestasi Siswa
                    </a>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <div style="background-color:#2D2D2D;">
            <div class="max-w-screen-xl h-14 mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                <nav aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2 md:space-x-3 text-sm">
                        <li class="flex items-center">
                            <a href="/" class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                Home
                            </a>
                        </li>
                        <li class="flex items-center">
                            <i class="fa-solid fa-chevron-right text-white/40 text-xs"></i>
                            <span class="inline-flex items-center ml-2 md:ml-3 font-medium text-[#63cd00]">Berita</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ============================ DAFTAR BERITA ============================ --}}
    <section id="daftarBerita" class="bg-white py-16 sm:py-20">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Judul --}}
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-[#63cd00] mb-3">
                    <span class="w-6 h-0.5 rounded-full bg-[#63cd00]"></span>
                    Kabar Sekolah
                </span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#282829] tracking-tight">Berita Terbaru</h2>
                <p class="mt-3 text-gray-600 text-lg">Jelajahi kegiatan dan informasi terkini dari lingkungan SMK Amaliah.</p>
            </div>

            {{-- Berita unggulan (paling baru, hanya di halaman 1) --}}
            @if ($featured)
                <a href="{{ route('public.news.show', $featured) }}"
                    class="group mt-10 block bg-white border border-gray-200 rounded-2xl overflow-hidden transition-all duration-300 hover:border-[#63cd00] hover:shadow-xl">
                    <div class="grid md:grid-cols-2">
                        <div class="relative overflow-hidden aspect-[16/10] md:aspect-auto md:h-full nw-card__thumb">
                            @if ($featured->image)
                                <img src="{{ asset('storage/' . $featured->image) }}" alt="{{ $featured->title }}" loading="lazy" class="w-full h-full object-cover">
                            @else
                                <div class="nw-thumb-fallback nw-thumb-fallback--big"><i class="fa-solid fa-newspaper"></i></div>
                            @endif
                            <span class="nw-chip absolute bottom-3 left-3 z-10">
                                <i class="fa-solid fa-star"></i> Berita Unggulan
                            </span>
                        </div>

                        <div class="p-6 lg:p-10 flex flex-col justify-center">
                            <div class="flex flex-wrap items-center gap-x-5 gap-y-2 mb-4">
                                <span class="nw-meta"><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($featured->date_published)->format('d F Y') }}</span>
                                <span class="nw-meta"><i class="fa-solid fa-user-pen"></i> {{ $featured->publisher }}</span>
                            </div>
                            <h3 class="text-2xl lg:text-3xl font-extrabold text-[#282829] leading-tight group-hover:text-[#63cd00] transition-colors duration-200">
                                {{ $featured->title }}
                            </h3>
                            <p class="mt-4 text-gray-600 leading-relaxed line-clamp-3">
                                {{ \Illuminate\Support\Str::limit(strip_tags($featured->description), 220) }}
                            </p>
                            <div class="mt-6 inline-flex items-center gap-2 text-[#63cd00] font-bold text-sm">
                                Baca Selengkapnya
                                <i class="fa-solid fa-arrow-right transition-transform duration-200 group-hover:translate-x-1"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @endif

            {{-- Grid berita --}}
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
                style="grid-auto-rows: 1fr; align-items: stretch;">
                @forelse ($rest as $item)
                    <a href="{{ route('public.news.show', $item) }}"
                        class="nw-card group" aria-label="{{ $item->title }}">
                        <div class="nw-card__thumb">
                            @if ($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" loading="lazy">
                            @else
                                <div class="nw-thumb-fallback"><i class="fa-solid fa-newspaper"></i></div>
                            @endif
                            <span class="nw-chip nw-chip--dark absolute bottom-3 left-3 z-10">
                                <i class="fa-regular fa-calendar"></i>
                                {{ \Carbon\Carbon::parse($item->date_published)->format('d M Y') }}
                            </span>
                        </div>

                        <div class="p-5 flex flex-col flex-1">
                            <p class="nw-meta mb-2"><i class="fa-solid fa-user-pen"></i> {{ $item->publisher }}</p>
                            <h3 class="text-lg font-bold text-[#282829] leading-snug line-clamp-2 group-hover:text-[#63cd00] transition-colors duration-200">
                                {{ $item->title }}
                            </h3>
                            <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 mt-2 flex-grow">
                                {{ strip_tags($item->description) }}
                            </p>
                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <span class="nw-card__hint inline-flex items-center gap-2">
                                    Baca Selengkapnya
                                    <i class="fa-solid fa-arrow-right text-xs transition-transform duration-200 group-hover:translate-x-1"></i>
                                </span>
                                <span class="nw-meta"><i class="fa-regular fa-clock"></i> {{ $item->updated_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full border-2 border-dashed border-gray-300 rounded-2xl p-14 text-center bg-gray-50">
                        <div class="w-14 h-14 mx-auto rounded-full bg-white border border-gray-200 flex items-center justify-center text-[#63cd00] text-xl mb-3">
                            <i class="fa-solid fa-newspaper"></i>
                        </div>
                        <p class="text-gray-600 font-semibold">Belum ada berita yang dipublikasikan.</p>
                        <p class="text-sm text-gray-500 mt-1">Nantikan informasi terbaru dari SMK Amaliah.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($news->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $news->links() }}
                </div>
            @endif
        </div>
    </section>

@endsection