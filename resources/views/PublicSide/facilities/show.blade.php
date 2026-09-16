@extends('layouts.public-app')

@section('title', $facility->name . ' | Fasilitas SMK Amaliah 1 & 2')
@section('description', \Illuminate\Support\Str::limit(strip_tags($facility->description), 160) ?? 'Fasilitas SMK Amaliah 1 & 2 Ciawi-Bogor.')

@include('PublicSide.facilities._styles')

@php
    $readMinutes = $facility->description
        ? max(1, (int) ceil(str_word_count(strip_tags($facility->description)) / 200))
        : 1;
@endphp

@section('content')

    {{-- ============================ HERO ============================ --}}
    <section class="relative bg-[#282829]">
        <div class="relative h-[340px] lg:h-[440px] overflow-hidden">
            @if ($facility->image)
                <img src="{{ asset('storage/' . $facility->image) }}" alt="{{ $facility->name }}"
                    class="w-full h-full object-cover">
            @else
                <div class="fc-thumb-fallback fc-thumb-fallback--big"><i class="fa-solid fa-building"></i></div>
            @endif

            <div class="absolute inset-0"
                style="background: linear-gradient(180deg, rgba(40,40,41,.35) 0%, rgba(40,40,41,.78) 100%)"></div>

            <div class="relative z-10 h-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-end pb-10">
                <span class="fc-chip fc-chip--green w-fit mb-4"><i class="fa-solid fa-building"></i> Fasilitas</span>
                <h1 class="text-white text-3xl lg:text-5xl font-extrabold leading-tight max-w-4xl">
                    {{ $facility->name }}
                </h1>
                <div class="mt-5 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-white/85">
                    <span class="fc-chip absolute top-3 left-3 z-10" style="position: static;">
                        <i class="fa-solid fa-tag"></i>
                        {{ $facility->type }}
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <i class="fa-solid fa-user-pen"></i>
                        {{ $facility->publisher }}
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
                            <a href="{{ route('public.facilities.index') }}"
                                class="inline-flex items-center ml-2 md:ml-3 font-medium text-gray-300 hover:text-white transition-colors">
                                Fasilitas
                            </a>
                        </li>
                        <li class="inline-flex items-center min-w-0">
                            <i class="fa-solid fa-chevron-right text-white/40 text-xs flex-shrink-0"></i>
                            <span class="ml-2 md:ml-3 font-medium text-[#63cd00] truncate max-w-[40vw] sm:max-w-[55vw]" title="{{ $facility->name }}">
                                {{ $facility->name }}
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
                    <a href="{{ route('public.facilities.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#63cd00] transition-colors duration-200 mb-6">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Kembali ke Daftar Fasilitas
                    </a>

                    {{-- Gambar besar (jika ada) --}}
                    @if ($facility->image)
                        <figure class="mb-8">
                            <img src="{{ asset('storage/' . $facility->image) }}" alt="{{ $facility->name }}"
                                class="w-full rounded-2xl shadow-md object-cover">
                            @if ($facility->publisher)
                                <figcaption class="mt-2 text-sm text-gray-500">
                                    <i class="fa-solid fa-camera mr-1 text-[#63cd00]"></i>
                                    Dokumentasi {{ $facility->publisher }}
                                </figcaption>
                            @endif
                        </figure>
                    @endif

                    {{-- Deskripsi fasilitas --}}
                    <article class="fc-article bg-gray-50 border border-[#eef0f3] rounded-2xl p-6 sm:p-10">
                        @if (trim(strip_tags($facility->description ?? '')) !== '')
                            {!! \App\Support\HtmlSanitizer::clean($facility->description) !!}
                        @else
                            <p class="text-gray-500 text-center py-8">
                                Deskripsi detail fasilitas ini belum tersedia.
                            </p>
                        @endif
                    </article>

                    {{-- Bagikan --}}
                    <footer class="mt-8 bg-[#282829] rounded-2xl p-6 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="font-bold text-white">Bagikan fasilitas ini</p>
                            <p class="text-sm text-white/60 mt-0.5">Bantu sebarkan informasi ke teman & keluarga.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-[#1877F2] hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke Facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($facility->name) }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-black hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke X (Twitter)">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($facility->name . ' - ' . url()->current()) }}" target="_blank" rel="noopener noreferrer"
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

                        {{-- Info fasilitas --}}
                        <div class="fc-side">
                            <h3 class="fc-side__head"><i class="fa-solid fa-circle-info"></i> Info Fasilitas</h3>
                            <div class="fc-side__body space-y-4 text-sm">
                                <div class="fc-side__row">
                                    <span class="fc-side__icon"><i class="fa-solid fa-tag"></i></span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tipe</p>
                                        <p class="font-semibold text-gray-800 truncate">{{ $facility->type }}</p>
                                    </div>
                                </div>
                                <div class="fc-side__row">
                                    <span class="fc-side__icon"><i class="fa-solid fa-user-pen"></i></span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Penanggung Jawab</p>
                                        <p class="font-semibold text-gray-800">{{ $facility->publisher }}</p>
                                    </div>
                                </div>
                                <div class="fc-side__row">
                                    <span class="fc-side__icon"><i class="fa-regular fa-clock"></i></span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Waktu Baca</p>
                                        <p class="font-semibold text-gray-800">{{ $readMinutes }} menit</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Fasilitas lainnya --}}
                        <div class="fc-side">
                            <h3 class="fc-side__head"><i class="fa-solid fa-building"></i> Fasilitas Lainnya</h3>
                            <div class="fc-side__body">
                                @forelse ($otherFacilities as $item)
                                    <a href="{{ route('public.facilities.show', $item->id) }}"
                                        class="fc-rel" aria-label="{{ $item->name }}">
                                        <div class="fc-rel__thumb">
                                            @if ($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" loading="lazy">
                                            @else
                                                <i class="fa-solid fa-building"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 leading-snug line-clamp-2 hover:text-[#63cd00] transition-colors duration-200">
                                                {{ $item->name }}
                                            </p>
                                            <span class="fc-chip fc-chip--green mt-1.5 inline-flex">
                                                <i class="fa-solid fa-tag"></i>
                                                {{ $item->type }}
                                            </span>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-sm text-gray-500 py-4 text-center">Tidak ada fasilitas lain untuk ditampilkan.</p>
                                @endforelse
                            </div>

                            <div class="p-4 pt-0">
                                <a href="{{ route('public.facilities.index') }}"
                                    class="w-full block text-center py-3 rounded-xl text-sm font-semibold text-white transition-colors duration-200 hover:opacity-90 bg-[#282829]">
                                    Lihat Semua Fasilitas
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </section>

@endsection