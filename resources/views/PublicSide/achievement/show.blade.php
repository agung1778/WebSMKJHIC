@extends('layouts.public-app')

@section('title', $achievement->title . ' | Prestasi SMK Amaliah 1 & 2')
@section('description', \Illuminate\Support\Str::limit(strip_tags($achievement->description), 160) ?? 'Prestasi resmi SMK Amaliah 1 & 2 Ciawi-Bogor.')

@include('PublicSide.achievement._styles')

@php
    $published = $achievement->date ? \Carbon\Carbon::parse($achievement->date) : null;
    $readMinutes = $achievement->description
        ? max(1, (int) ceil(str_word_count(strip_tags($achievement->description)) / 200))
        : 1;
@endphp

@section('content')

    {{-- ============================ HERO ============================ --}}
    <section class="relative bg-[#282829]">
        <div class="relative h-[340px] lg:h-[440px] overflow-hidden">
            @if ($achievement->image)
                <img src="{{ img_url($achievement->image, 1600, 900) }}" width="1600" height="900" alt="{{ $achievement->title }}"
                    class="w-full h-full object-cover">
            @else
                <div class="ac-thumb-fallback ac-thumb-fallback--big"><i class="fa-solid fa-trophy"></i></div>
            @endif

            <div class="absolute inset-0"
                style="background: linear-gradient(180deg, rgba(40,40,41,.35) 0%, rgba(40,40,41,.78) 100%)"></div>

            <div class="relative z-10 h-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-end pb-10">
                <span class="ac-chip ac-chip--green w-fit mb-4"><i class="fa-solid fa-medal"></i> Prestasi Siswa</span>
                <h1 class="text-white text-3xl lg:text-5xl font-extrabold leading-tight max-w-4xl">
                    {{ $achievement->title }}
                </h1>
                <div class="mt-5 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-white/85">
                    @if ($published)
                        <span class="inline-flex items-center gap-2">
                            <i class="fa-regular fa-calendar"></i>
                            {{ $published->format('d F Y') }}
                        </span>
                    @endif
                    <span class="inline-flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot"></i>
                        {{ $achievement->level }}
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <i class="fa-solid fa-layer-group"></i>
                        {{ $achievement->category ?? 'Prestasi' }}
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <i class="fa-regular fa-clock"></i>
                        {{ $readMinutes }} menit baca
                    </span>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <div style="background-color:#2D2D2D;">
            <div class="max-w-screen-xl min-h-14 mx-auto px-4 sm:px-6 lg:px-8 flex items-center py-3">
                <nav aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 md:space-x-3 text-sm">
                        <li class="inline-flex items-center flex-shrink-0">
                            <a href="/" class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                Home
                            </a>
                        </li>
                        <li class="inline-flex items-center flex-shrink-0">
                            <i class="fa-solid fa-chevron-right text-white/40 text-xs"></i>
                            <a href="{{ route('public.achievement.index') }}"
                                class="inline-flex items-center ml-2 md:ml-3 font-medium text-gray-300 hover:text-white transition-colors">
                                Prestasi
                            </a>
                        </li>
                        <li class="inline-flex items-center min-w-0">
                            <i class="fa-solid fa-chevron-right text-white/40 text-xs flex-shrink-0"></i>
                            <span class="ml-2 md:ml-3 font-medium text-[#63cd00] truncate max-w-[40vw] sm:max-w-[55vw]" title="{{ $achievement->title }}">
                                {{ $achievement->title }}
                            </span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ============================ KONTEN ============================ --}}
    <section class="bg-white">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-x-12 gap-y-10">

                {{-- Kolom kiri (2/3): artikel --}}
                <div class="lg:col-span-2 min-w-0">
                    <a href="{{ route('public.achievement.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#63cd00] transition-colors duration-200 mb-6">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Kembali ke Daftar Prestasi
                    </a>

                    {{-- Gambar besar (jika ada) --}}
                    @if ($achievement->image)
                        <figure class="mb-8">
                            <img src="{{ img_url($achievement->image, 900, 520) }}" width="900" height="520" alt="{{ $achievement->title }}"
                                class="w-full rounded-2xl shadow-md object-cover">
                            @if ($achievement->publisher)
                                <figcaption class="mt-2 text-sm text-gray-500">
                                    <i class="fa-solid fa-camera mr-1 text-[#63cd00]"></i>
                                    Dokumentasi {{ $achievement->publisher }}
                                </figcaption>
                            @endif
                        </figure>
                    @endif

                    {{-- Deskripsi prestasi --}}
                    <article class="ac-article bg-gray-50 border border-[#eef0f3] rounded-2xl p-6 sm:p-10">
                        @if (trim(strip_tags($achievement->description ?? '')) !== '')
                            {!! \App\Support\HtmlSanitizer::clean($achievement->description) !!}
                        @else
                            <p class="text-gray-500 text-center py-8">
                                Rincian lengkap prestasi ini belum tersedia. Hubungi admin untuk informasi lebih lanjut.
                            </p>
                        @endif
                    </article>

                    {{-- Bagikan --}}
                    <footer class="mt-8 bg-[#282829] rounded-2xl p-6 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="font-bold text-white">Bagikan prestasi ini</p>
                            <p class="text-sm text-white/60 mt-0.5">Bantu sebarkan kebanggaan untuk teman &amp; keluarga.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-[#1877F2] hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke Facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($achievement->title) }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-black hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke X (Twitter)">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($achievement->title . ' - ' . url()->current()) }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-[#25D366] hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            <a href="{{ url()->current() }}" onclick="navigator.clipboard?.writeText(this.href); alert('Tautan disalin!'); return false;"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-[#63cd00] hover:text-[#282829] hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Salin tautan">
                                <i class="fa-solid fa-link"></i>
                            </a>
                        </div>
                    </footer>
                </div>

                {{-- Kolom kanan (1/3): sidebar --}}
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-8 space-y-6">

                        {{-- Info prestasi --}}
                        <div class="ac-side">
                            <h3 class="ac-side__head"><i class="fa-solid fa-circle-info"></i> Info Prestasi</h3>
                            <div class="ac-side__body space-y-4 text-sm">
                                <div class="ac-side__row">
                                    <span class="ac-side__icon"><i class="fa-solid fa-layer-group"></i></span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Kategori</p>
                                        <p class="font-semibold text-gray-800 truncate">{{ $achievement->category ?? 'Umum' }}</p>
                                    </div>
                                </div>
                                <div class="ac-side__row">
                                    <span class="ac-side__icon"><i class="fa-solid fa-map-location-dot"></i></span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tingkat</p>
                                        <p class="font-semibold text-gray-800">{{ $achievement->level }}</p>
                                    </div>
                                </div>
                                <div class="ac-side__row">
                                    <span class="ac-side__icon"><i class="fa-solid fa-medal"></i></span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Pemenang</p>
                                        <p class="font-semibold text-gray-800 truncate">{{ $achievement->winner }}</p>
                                    </div>
                                </div>
                                @if ($published)
                                    <div class="ac-side__row">
                                        <span class="ac-side__icon"><i class="fa-regular fa-calendar"></i></span>
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tanggal</p>
                                            <p class="font-semibold text-gray-800">{{ $published->format('d F Y') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Prestasi lainnya --}}
                        <div class="ac-side">
                            <h3 class="ac-side__head"><i class="fa-solid fa-trophy"></i> Prestasi Lainnya</h3>
                            <div class="ac-side__body">
                                @forelse ($otherAchievements as $item)
                                    <a href="{{ route('public.achievement.show', $item->id) }}"
                                        class="ac-rel" aria-label="{{ $item->title }}">
                                        <div class="ac-rel__thumb">
                                            @if ($item->image)
                                                <img src="{{ img_url($item->image, 400, 400) }}" width="400" height="400" alt="{{ $item->title }}" loading="lazy">
                                            @else
                                                <i class="fa-solid fa-trophy"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 leading-snug line-clamp-2 hover:text-[#63cd00] transition-colors duration-200">
                                                {{ $item->title }}
                                            </p>
                                            <span class="ac-meta mt-1.5 inline-flex">
                                                <i class="fa-regular fa-calendar"></i>
                                                {{ $item->date ? \Carbon\Carbon::parse($item->date)->diffForHumans() : '—' }}
                                            </span>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-sm text-gray-500 py-4 text-center">Tidak ada prestasi lain untuk ditampilkan.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </section>

@endsection