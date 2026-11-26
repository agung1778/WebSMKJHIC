@extends('layouts.public-app')

@section('title', $extracurricular->name . ' | Ekstrakurikuler SMK Amaliah 1 & 2')
@section('description', \Illuminate\Support\Str::limit(strip_tags($extracurricular->description), 160) ?? 'Kegiatan ekstrakurikuler SMK Amaliah 1 & 2 Ciawi-Bogor.')

@include('PublicSide.extracurricular._styles')

@php
    $readMinutes = $extracurricular->description
        ? max(1, (int) ceil(str_word_count(strip_tags($extracurricular->description)) / 200))
        : 1;
    $typeClass = $extracurricular->type === 'Wajib'
        ? 'ec-chip--type-wajib'
        : 'ec-chip--type-pilihan';
@endphp

@section('content')

    {{-- ============================ HERO ============================ --}}
    <section class="relative bg-[#1f1f20]">
        <div class="relative min-h-[300px] sm:min-h-[360px] lg:h-[440px] overflow-hidden flex flex-col justify-end">
            @if ($extracurricular->image)
                <img src="{{ img_url($extracurricular->image, 1600, 900) }}" width="1600" height="900" alt="{{ $extracurricular->name }}"
                    class="absolute inset-0 w-full h-full object-cover">
            @else
                <div class="ec-thumb-fallback ec-thumb-fallback--big"><i class="fa-solid fa-futbol"></i></div>
            @endif

            <div class="absolute inset-0"
                style="background: linear-gradient(100deg, rgba(20,20,22,0.94) 0%, rgba(20,20,22,0.82) 50%, rgba(20,20,22,0.58) 100%)"></div>

            <div class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 w-full">
                <span class="ec-chip ec-chip--green w-fit mb-3 sm:mb-4 text-xs shadow-md"><i class="fa-solid fa-futbol"></i> Ekstrakurikuler</span>
                <h1 class="text-white text-2xl sm:text-3xl lg:text-5xl font-extrabold leading-tight max-w-4xl tracking-tight">
                    {{ $extracurricular->name }}
                </h1>
                <div class="mt-4 sm:mt-5 flex flex-wrap items-center gap-x-4 sm:gap-x-6 gap-y-2 text-xs sm:text-sm text-white/90">
                    <span class="ec-chip {{ $typeClass }} text-xs">{{ $extracurricular->type }}</span>
                    <span class="inline-flex items-center gap-1.5 sm:gap-2">
                        <i class="fa-solid fa-user-tie text-[#00875a]"></i>
                        {{ $extracurricular->coach }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 sm:gap-2">
                        <i class="fa-regular fa-clock text-[#00875a]"></i>
                        {{ $readMinutes }} menit baca
                    </span>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <div style="background-color:#1c1c1d; border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div class="max-w-screen-xl min-h-12 sm:min-h-14 mx-auto px-4 sm:px-6 lg:px-8 flex items-center py-2.5 sm:py-3 overflow-x-auto scrollbar-hide">
                <nav aria-label="Breadcrumb" class="w-full">
                    <ol class="flex items-center space-x-2 md:space-x-3 text-xs sm:text-sm whitespace-nowrap">
                        <li class="inline-flex items-center flex-shrink-0">
                            <a href="/" class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                <i class="fa-solid fa-house text-xs mr-1.5 opacity-70"></i> Home
                            </a>
                        </li>
                        <li class="inline-flex items-center flex-shrink-0">
                            <i class="fa-solid fa-chevron-right text-white/30 text-xs"></i>
                            <a href="{{ route('public.extracurricular.index') }}"
                                class="inline-flex items-center ml-2 md:ml-3 font-medium text-gray-300 hover:text-white transition-colors">
                                Ekstrakurikuler
                            </a>
                        </li>
                        <li class="inline-flex items-center min-w-0">
                            <i class="fa-solid fa-chevron-right text-white/30 text-xs flex-shrink-0"></i>
                            <span class="ml-2 md:ml-3 font-medium text-[#00875a] truncate max-w-[50vw] sm:max-w-[45vw]" title="{{ $extracurricular->name }}">
                                {{ $extracurricular->name }}
                            </span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ============================ KONTEN ============================ --}}
    <section class="bg-gray-50/60 py-8 sm:py-12 lg:py-16">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-x-12 gap-y-10">

                {{-- Kolom kiri (2/3): artikel --}}
                <div class="lg:col-span-2 min-w-0">
                    <a href="{{ route('public.extracurricular.index') }}"
                        class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-gray-600 hover:text-[#00875a] bg-white border border-gray-200 hover:border-[#00875a]/30 px-3.5 py-2 rounded-xl transition-all duration-200 mb-6 shadow-sm">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Kembali ke Daftar Ekstrakurikuler
                    </a>

                    {{-- Gambar besar (jika ada) --}}
                    @if ($extracurricular->image)
                        <figure class="mb-8">
                            <img src="{{ img_url($extracurricular->image, 900, 520) }}" width="900" height="520" alt="{{ $extracurricular->name }}"
                                class="w-full rounded-2xl shadow-md object-cover border border-gray-100">
                            @if ($extracurricular->publisher)
                                <figcaption class="mt-2 text-xs sm:text-sm text-gray-500 flex items-center gap-1.5">
                                    <i class="fa-solid fa-camera text-[#00875a]"></i>
                                    Dokumentasi {{ $extracurricular->publisher }}
                                </figcaption>
                            @endif
                        </figure>
                    @endif

                    {{-- Deskripsi kegiatan --}}
                    <article class="ec-article bg-white border border-[#eef0f3] rounded-2xl p-6 sm:p-10 shadow-sm">
                        @if (trim(strip_tags($extracurricular->description ?? '')) !== '')
                            {!! \App\Support\HtmlSanitizer::clean($extracurricular->description) !!}
                        @else
                            <p class="text-gray-500 text-center py-8">
                                Deskripsi detail kegiatan ini belum tersedia.
                            </p>
                        @endif
                    </article>

                    {{-- Info tambahan: Kontak --}}
                    @if ($extracurricular->contact)
                        <div class="mt-8 bg-white border border-[#e5e7eb] rounded-2xl p-5 sm:p-6 flex flex-col sm:flex-row items-center gap-4 shadow-sm">
                            <span class="flex-shrink-0 w-12 h-12 rounded-xl bg-[#d97706]/15 flex items-center justify-center text-[#d97706] text-xl">
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <div class="text-center sm:text-left">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Hubungi Pelatih</p>
                                <p class="text-base sm:text-lg font-semibold text-gray-800">{{ $extracurricular->contact }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Bagikan --}}
                    <footer class="mt-8 bg-[#1f1f20] rounded-2xl p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-md">
                        <div>
                            <p class="font-bold text-white text-base">Bagikan kegiatan ini</p>
                            <p class="text-xs sm:text-sm text-white/70 mt-0.5">Bantu sebarkan informasi ke teman & keluarga.</p>
                        </div>
                        <div class="flex items-center gap-2.5 sm:gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-xl bg-white/10 text-white flex items-center justify-center hover:bg-[#1877F2] hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke Facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($extracurricular->name) }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-xl bg-white/10 text-white flex items-center justify-center hover:bg-black hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke X (Twitter)">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($extracurricular->name . ' - ' . url()->current()) }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-xl bg-white/10 text-white flex items-center justify-center hover:bg-[#25D366] hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            <a href="{{ url()->current() }}" onclick="navigator.clipboard?.writeText(this.href); alert('Tautan disalin!'); return false;"
                                class="w-10 h-10 rounded-xl bg-white/10 text-white flex items-center justify-center hover:bg-[#00875a] hover:text-white hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Salin tautan">
                                <i class="fa-solid fa-link"></i>
                            </a>
                        </div>
                    </footer>
                </div>

                {{-- Kolom kanan (1/3): sidebar --}}
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-[160px] space-y-6">

                        {{-- Info kegiatan --}}
                        <div class="ec-side">
                            <h3 class="ec-side__head"><i class="fa-solid fa-circle-info"></i> Info Kegiatan</h3>
                            <div class="ec-side__body space-y-4 text-sm">
                                <div class="ec-side__row">
                                    <span class="ec-side__icon"><i class="fa-solid fa-tag"></i></span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tipe</p>
                                        <p class="font-semibold text-gray-800 truncate">{{ $extracurricular->type }}</p>
                                    </div>
                                </div>
                                <div class="ec-side__row">
                                    <span class="ec-side__icon"><i class="fa-solid fa-user-tie"></i></span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Pelatih</p>
                                        <p class="font-semibold text-gray-800">{{ $extracurricular->coach }}</p>
                                    </div>
                                </div>
                                @if ($extracurricular->contact)
                                    <div class="ec-side__row">
                                        <span class="ec-side__icon"><i class="fa-solid fa-phone"></i></span>
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Kontak</p>
                                            <p class="font-semibold text-gray-800">{{ $extracurricular->contact }}</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="ec-side__row">
                                    <span class="ec-side__icon"><i class="fa-solid fa-user-pen"></i></span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Penanggung Jawab</p>
                                        <p class="font-semibold text-gray-800">{{ $extracurricular->publisher }}</p>
                                    </div>
                                </div>
                                <div class="ec-side__row">
                                    <span class="ec-side__icon"><i class="fa-regular fa-clock"></i></span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Waktu Baca</p>
                                        <p class="font-semibold text-gray-800">{{ $readMinutes }} menit</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kegiatan lainnya --}}
                        <div class="ec-side">
                            <h3 class="ec-side__head"><i class="fa-solid fa-futbol"></i> Kegiatan Lainnya</h3>
                            <div class="ec-side__body">
                                @forelse ($suggestedExtracurriculars as $item)
                                    <a href="{{ route('public.extracurricular.show', $item->id) }}"
                                        class="ec-rel" aria-label="{{ $item->name }}">
                                        <div class="ec-rel__thumb">
                                            @if ($item->image)
                                                <img src="{{ img_url($item->image, 400, 400) }}" width="400" height="400" alt="{{ $item->name }}" loading="lazy">
                                            @else
                                                <i class="fa-solid fa-futbol"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 leading-snug line-clamp-2 hover:text-[#00875a] transition-colors duration-200">
                                                {{ $item->name }}
                                            </p>
                                            <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                                                <span class="ec-chip ec-chip--green text-[10px] py-0.5 px-2">
                                                    <i class="fa-solid fa-user-tie"></i>
                                                    {{ $item->coach }}
                                                </span>
                                                <span class="ec-chip {{ $item->type === 'Wajib' ? 'ec-chip--type-wajib' : 'ec-chip--type-pilihan' }} text-[10px] py-0.5 px-2">
                                                    {{ $item->type }}
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-sm text-gray-500 py-4 text-center">Tidak ada kegiatan lain untuk ditampilkan.</p>
                                @endforelse
                            </div>

                            <div class="p-4 pt-0">
                                <a href="{{ route('public.extracurricular.index') }}"
                                    class="w-full block text-center py-2.5 sm:py-3 rounded-xl text-xs sm:text-sm font-semibold text-white transition-all duration-200 hover:bg-[#00875a] bg-[#1f1f20] shadow-sm">
                                    Lihat Semua Kegiatan
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>

                        {{-- Info kegiatan --}}
                        <div class="ec-side">
                            <h3 class="ec-side__head"><i class="fa-solid fa-circle-info"></i> Info Kegiatan</h3>
                            <div class="ec-side__body space-y-4 text-sm">
                                <div class="ec-side__row">
                                    <span class="ec-side__icon"><i class="fa-solid fa-tag"></i></span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tipe</p>
                                        <p class="font-semibold text-gray-800 truncate">{{ $extracurricular->type }}</p>
                                    </div>
                                </div>
                                <div class="ec-side__row">
                                    <span class="ec-side__icon"><i class="fa-solid fa-user-tie"></i></span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Pelatih</p>
                                        <p class="font-semibold text-gray-800">{{ $extracurricular->coach }}</p>
                                    </div>
                                </div>
                                @if ($extracurricular->contact)
                                    <div class="ec-side__row">
                                        <span class="ec-side__icon"><i class="fa-solid fa-phone"></i></span>
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Kontak</p>
                                            <p class="font-semibold text-gray-800">{{ $extracurricular->contact }}</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="ec-side__row">
                                    <span class="ec-side__icon"><i class="fa-solid fa-user-pen"></i></span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Penanggung Jawab</p>
                                        <p class="font-semibold text-gray-800">{{ $extracurricular->publisher }}</p>
                                    </div>
                                </div>
                                <div class="ec-side__row">
                                    <span class="ec-side__icon"><i class="fa-regular fa-clock"></i></span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Waktu Baca</p>
                                        <p class="font-semibold text-gray-800">{{ $readMinutes }} menit</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kegiatan lainnya --}}
                        <div class="ec-side">
                            <h3 class="ec-side__head"><i class="fa-solid fa-futbol"></i> Kegiatan Lainnya</h3>
                            <div class="ec-side__body">
                                @forelse ($suggestedExtracurriculars as $item)
                                    <a href="{{ route('public.extracurricular.show', $item->id) }}"
                                        class="ec-rel" aria-label="{{ $item->name }}">
                                        <div class="ec-rel__thumb">
                                            @if ($item->image)
                                                <img src="{{ img_url($item->image, 400, 400) }}" width="400" height="400" alt="{{ $item->name }}" loading="lazy">
                                            @else
                                                <i class="fa-solid fa-futbol"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 leading-snug line-clamp-2 hover:text-[#63cd00] transition-colors duration-200">
                                                {{ $item->name }}
                                            </p>
                                            <span class="ec-chip ec-chip--green mt-1.5 inline-flex">
                                                <i class="fa-solid fa-user-tie"></i>
                                                {{ $item->coach }}
                                            </span>
                                            <span class="ec-chip {{ $item->type === 'Wajib' ? 'ec-chip--type-wajib' : 'ec-chip--type-pilihan' }} mt-1.5 inline-flex">
                                                {{ $item->type }}
                                            </span>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-sm text-gray-500 py-4 text-center">Tidak ada kegiatan lain untuk ditampilkan.</p>
                                @endforelse
                            </div>

                            <div class="p-4 pt-0">
                                <a href="{{ route('public.extracurricular.index') }}"
                                    class="w-full block text-center py-3 rounded-xl text-sm font-semibold text-white transition-colors duration-200 hover:opacity-90 bg-[#282829]">
                                    Lihat Semua Kegiatan
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </section>

@endsection