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
    <section class="relative bg-[#1f1f20]">
        <div class="relative min-h-[300px] sm:min-h-[360px] lg:h-[420px] overflow-hidden flex flex-col justify-end">
            @if ($facility->image)
                <img src="{{ img_url($facility->image, 1600, 900) }}" width="1600" height="900" alt="{{ $facility->name }}"
                    class="absolute inset-0 w-full h-full object-cover">
            @else
                <div class="fc-thumb-fallback fc-thumb-fallback--big"><i class="fa-solid fa-building"></i></div>
            @endif

            <div class="absolute inset-0"
                style="background: linear-gradient(100deg, rgba(20,20,22,0.94) 0%, rgba(20,20,22,0.82) 48%, rgba(20,20,22,0.55) 100%)"></div>

            <div class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 flex flex-col justify-end w-full">
                <span class="fc-chip fc-chip--green w-fit mb-3"><i class="fa-solid fa-building"></i> Fasilitas</span>
                <h1 class="text-white text-2xl sm:text-4xl lg:text-5xl font-extrabold leading-tight max-w-4xl tracking-tight">
                    {{ $facility->name }}
                </h1>
                <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs sm:text-sm text-white/90">
                    <span class="fc-chip" style="position: static;">
                        <i class="fa-solid fa-tag"></i>
                        {{ $facility->type }}
                    </span>
                    @if ($facility->publisher)
                        <span class="inline-flex items-center gap-2">
                            <i class="fa-solid fa-user-pen text-[#00875a]"></i>
                            {{ $facility->publisher }}
                        </span>
                    @endif
                    <span class="inline-flex items-center gap-2">
                        <i class="fa-regular fa-clock text-[#00875a]"></i>
                        {{ $readMinutes }} menit baca
                    </span>
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
                        <li class="flex items-center">
                            <i class="fa-solid fa-chevron-right text-white/30 text-xs"></i>
                            <a href="{{ route('public.facilities.index') }}"
                                class="inline-flex items-center ml-1.5 sm:ml-2.5 font-medium text-gray-300 hover:text-white transition-colors">
                                Fasilitas
                            </a>
                        </li>
                        <li class="flex items-center min-w-0">
                            <i class="fa-solid fa-chevron-right text-white/30 text-xs flex-shrink-0"></i>
                            <span class="ml-1.5 sm:ml-2.5 font-medium text-[#00875a] truncate max-w-[40vw] sm:max-w-[55vw]" title="{{ $facility->name }}">
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
            <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-x-12 gap-y-10 items-start">

                {{-- Kolom kiri (2/3): artikel --}}
                <div class="lg:col-span-2 min-w-0">
                    <a href="{{ route('public.facilities.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#00875a] transition-colors duration-200 mb-6">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Kembali ke Daftar Fasilitas
                    </a>

                    {{-- Gambar besar (jika ada) --}}
                    @if ($facility->image)
                        <figure class="mb-8">
                            <img src="{{ img_url($facility->image, 900, 520) }}" width="900" height="520" alt="{{ $facility->name }}"
                                class="w-full h-auto aspect-video rounded-2xl shadow-md object-cover">
                            @if ($facility->publisher)
                                <figcaption class="mt-2 text-sm text-gray-500">
                                    <i class="fa-solid fa-camera mr-1 text-[#00875a]"></i>
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
                    <footer class="mt-8 bg-[#1f1f20] rounded-2xl p-6 flex flex-wrap items-center justify-between gap-4">
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
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-[#00875a] hover:text-white hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Salin tautan">
                                <i class="fa-solid fa-link"></i>
                            </a>
                        </div>
                    </footer>
                </div>

                {{-- Kolom kanan (1/3): sidebar --}}
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-[160px] space-y-6">

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
                                                <img src="{{ img_url($item->image, 400, 400) }}" width="400" height="400" alt="{{ $item->name }}" loading="lazy">
                                            @else
                                                <i class="fa-solid fa-building"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 leading-snug line-clamp-2 hover:text-[#00875a] transition-colors duration-200">
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
                                    class="w-full block text-center py-3 rounded-xl text-sm font-semibold text-white transition-colors duration-200 hover:bg-[#00875a] bg-[#1c1c1d]">
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