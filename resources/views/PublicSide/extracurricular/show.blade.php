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
    <section class="relative bg-[#282829]">
        <div class="relative h-[340px] lg:h-[440px] overflow-hidden">
            @if ($extracurricular->image)
                <img src="{{ asset('storage/' . $extracurricular->image) }}" alt="{{ $extracurricular->name }}"
                    class="w-full h-full object-cover">
            @else
                <div class="ec-thumb-fallback ec-thumb-fallback--big"><i class="fa-solid fa-futbol"></i></div>
            @endif

            <div class="absolute inset-0"
                style="background: linear-gradient(180deg, rgba(40,40,41,.35) 0%, rgba(40,40,41,.78) 100%)"></div>

            <div class="relative z-10 h-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-end pb-10">
                <span class="ec-chip ec-chip--green w-fit mb-4"><i class="fa-solid fa-futbol"></i> Ekstrakurikuler</span>
                <h1 class="text-white text-3xl lg:text-5xl font-extrabold leading-tight max-w-4xl">
                    {{ $extracurricular->name }}
                </h1>
                <div class="mt-5 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-white/85">
                    <span class="ec-chip {{ $typeClass }}">{{ $extracurricular->type }}</span>
                    <span class="inline-flex items-center gap-2">
                        <i class="fa-solid fa-user-tie"></i>
                        {{ $extracurricular->coach }}
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
                            <a href="{{ route('public.extracurricular.index') }}"
                                class="inline-flex items-center ml-2 md:ml-3 font-medium text-gray-300 hover:text-white transition-colors">
                                Ekstrakurikuler
                            </a>
                        </li>
                        <li class="inline-flex items-center min-w-0">
                            <i class="fa-solid fa-chevron-right text-white/40 text-xs flex-shrink-0"></i>
                            <span class="ml-2 md:ml-3 font-medium text-[#63cd00] truncate max-w-[40vw] sm:max-w-[55vw]" title="{{ $extracurricular->name }}">
                                {{ $extracurricular->name }}
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
                    <a href="{{ route('public.extracurricular.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#63cd00] transition-colors duration-200 mb-6">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Kembali ke Daftar Ekstrakurikuler
                    </a>

                    {{-- Gambar besar (jika ada) --}}
                    @if ($extracurricular->image)
                        <figure class="mb-8">
                            <img src="{{ asset('storage/' . $extracurricular->image) }}" alt="{{ $extracurricular->name }}"
                                class="w-full rounded-2xl shadow-md object-cover">
                            @if ($extracurricular->publisher)
                                <figcaption class="mt-2 text-sm text-gray-500">
                                    <i class="fa-solid fa-camera mr-1 text-[#63cd00]"></i>
                                    Dokumentasi {{ $extracurricular->publisher }}
                                </figcaption>
                            @endif
                        </figure>
                    @endif

                    {{-- Deskripsi kegiatan --}}
                    <article class="ec-article bg-gray-50 border border-[#eef0f3] rounded-2xl p-6 sm:p-10">
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
                        <div class="mt-8 bg-white border border-[#e5e7eb] rounded-2xl p-6 flex flex-col sm:flex-row items-center gap-4">
                            <span class="flex-shrink-0 w-12 h-12 rounded-xl bg-[#eab308]/15 flex items-center justify-center text-[#eab308] text-xl">
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Hubungi Pelatih</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $extracurricular->contact }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Bagikan --}}
                    <footer class="mt-8 bg-[#282829] rounded-2xl p-6 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="font-bold text-white">Bagikan kegiatan ini</p>
                            <p class="text-sm text-white/60 mt-0.5">Bantu sebarkan informasi ke teman & keluarga.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-[#1877F2] hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke Facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($extracurricular->name) }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-black hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke X (Twitter)">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($extracurricular->name . ' - ' . url()->current()) }}" target="_blank" rel="noopener noreferrer"
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
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" loading="lazy">
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