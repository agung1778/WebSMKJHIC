@extends('layouts.public-app')

@section('title', 'Fasilitas | SMK Amaliah 1 & 2')
@section('description', 'Fasilitas modern SMK Amaliah 1 & 2 Ciawi-Bogor: laboratorium, ruang kelas, sarana olahraga, dan prasarana pendukung kegiatan belajar mengajar.')

@include('PublicSide.facilities._styles')

@php
    $hasImages = isset($facilityImages) && $facilityImages->isNotEmpty();
    $heroImage = $hasImages ? $facilityImages->first() : null;
    $total = $facilities->count();
    $types = $facilities->pluck('type')->unique()->values();
    $typeCounts = $types->mapWithKeys(fn ($t) => [$t => $facilities->where('type', $t)->count()]);
@endphp

{{-- Aturan CSS khusus per tipe (data-driven) untuk filter radio --}}
@if ($types->isNotEmpty())
    <style>
        @foreach ($types as $type)
            @php $slug = \Illuminate\Support\Str::slug($type); @endphp
            #fcr-{{ $slug }}:checked ~ .fc-tabs label[for="fcr-{{ $slug }}"] {
                background: var(--fc-green); color: #ffffff;
                box-shadow: 0 4px 12px -2px rgba(99, 205, 0, 0.45);
            }
            #fcr-{{ $slug }}:checked ~ .fc-tabs label[for="fcr-{{ $slug }}"] .fc-count { background: rgba(255, 255, 255, 0.25); color: #ffffff; }
            #fcr-{{ $slug }}:checked ~ .fc-grid .fc-card[data-g~="{{ $slug }}"] { display: flex; }
            #fcr-{{ $slug }}:checked ~ .fc-empty[data-e="{{ $slug }}"] { display: flex; }
        @endforeach
    </style>
@endif

@section('content')

    {{-- ============================ HERO ============================ --}}
    <section class="relative bg-[#282829]">
        <div class="relative h-[320px] lg:h-[420px] overflow-hidden">
            @if ($heroImage)
                <img src="{{ img_url($heroImage->path, 1600, 900) }}" width="1600" height="900"
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
                    Fasilitas & Prasarana
                </span>
                <h1 class="text-white text-3xl lg:text-5xl font-bold leading-tight">Fasilitas <span class="text-[#63cd00]">Modern</span></h1>
                <p class="mt-4 max-w-2xl text-white/85 text-base lg:text-lg leading-relaxed">
                    {{ $total }} fasilitas lengkap mendukung proses belajar mengajar berkualitas dan pengembangan bakat siswa.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="#daftarFasilitas"
                        class="inline-flex items-center gap-2 bg-[#63cd00] text-[#282829] font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-building"></i> Lihat Fasilitas
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
                            <span class="inline-flex items-center ml-2 md:ml-3 font-medium text-[#63cd00]">Fasilitas</span>
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
                        <i class="fa-solid fa-building"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-2xl lg:text-3xl font-extrabold text-white leading-none">{{ $total }}</p>
                        <p class="text-xs lg:text-sm text-white/60 mt-1 truncate">Total Fasilitas</p>
                    </div>
                </div>
                @foreach ($types as $type)
                    <div class="bg-[#282829] rounded-2xl p-5 lg:p-6 shadow-xl flex items-center gap-4">
                        <span class="w-11 h-11 lg:w-12 lg:h-12 rounded-xl bg-[#63cd00]/15 flex items-center justify-center text-[#63cd00] text-lg lg:text-xl flex-shrink-0">
                            <i class="fa-solid {{ $type === 'Lab' ? 'fa-flask' : ($type === 'Akademik' ? 'fa-book-open' : ($type === 'Olahraga' ? 'fa-futbol' : 'fa-building')) }}"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-2xl lg:text-3xl font-extrabold text-white leading-none">{{ $typeCounts[$type] ?? 0 }}</p>
                            <p class="text-xs lg:text-sm text-white/60 mt-1 truncate">{{ $type }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================ DAFTAR FASILITAS ============================ --}}
    <section id="daftarFasilitas" class="bg-gray-50 py-16 sm:py-20">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="max-w-2xl mb-10">
                <span class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-[#63cd00] mb-3">
                    <span class="w-6 h-0.5 rounded-full bg-[#63cd00]"></span>
                    Sarana & Prasarana
                </span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#282829] tracking-tight">Fasilitas Unggulan Kami</h2>
                <p class="mt-3 text-gray-600 text-lg">Mendukung proses belajar mengajar modern dan pengembangan bakat siswa.</p>
            </div>

            @if ($total > 0)
                <div class="fc-filter">
                    {{-- Radio filter (tanpa JS) --}}
                    <input type="radio" name="fcr" id="fcr-all" class="fc-filter-input" checked>
                    @foreach ($types as $type)
                        <input type="radio" name="fcr" id="fcr-{{ \Illuminate\Support\Str::slug($type) }}" class="fc-filter-input">
                    @endforeach

                    {{-- Tab filter --}}
                    <div class="fc-tabs mb-8" role="tablist" aria-label="Filter tipe fasilitas">
                        <label for="fcr-all" class="fc-tab" role="tab">
                            <i class="fa-solid fa-layer-group"></i> Semua
                            <span class="fc-count">{{ $total }}</span>
                        </label>
                        @foreach ($types as $type)
                            <label for="fcr-{{ \Illuminate\Support\Str::slug($type) }}" class="fc-tab" role="tab">
                                <i class="fa-solid {{ $type === 'Lab' ? 'fa-flask' : ($type === 'Akademik' ? 'fa-book-open' : ($type === 'Olahraga' ? 'fa-futbol' : 'fa-building')) }}"></i> {{ $type }}
                                <span class="fc-count">{{ $typeCounts[$type] ?? 0 }}</span>
                            </label>
                        @endforeach
                    </div>

                    {{-- Grid kartu --}}
                    <div class="fc-grid">
                        @foreach ($facilities as $facility)
                            <a href="{{ route('public.facilities.show', $facility->id) }}"
                                class="fc-card group"
                                data-g="all {{ \Illuminate\Support\Str::slug($facility->type) }}"
                                aria-label="{{ $facility->name }}">
                                <div class="fc-card__thumb">
                                    @if ($facility->image)
                                        <img src="{{ img_url($facility->image, 900, 520) }}" width="900" height="520" alt="{{ $facility->name }}" loading="lazy">
                                    @else
                                        <div class="fc-thumb-fallback"><i class="fa-solid fa-building"></i></div>
                                    @endif
                                    <span class="fc-chip absolute top-3 left-3 z-10">
                                        <i class="fa-solid fa-tag"></i>
                                        {{ $facility->type }}
                                    </span>
                                </div>

                                <div class="p-5 flex flex-col flex-1">
                                    <h3 class="text-lg font-bold text-[#282829] leading-snug line-clamp-2 group-hover:text-[#63cd00] transition-colors duration-200">
                                        {{ $facility->name }}
                                    </h3>
                                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 mt-2 flex-grow">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($facility->description), 140) }}
                                    </p>
                                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                        <span class="fc-card__hint">
                                            Selengkapnya
                                            <i class="fa-solid fa-arrow-right text-xs"></i>
                                        </span>
                                        <span class="fc-meta"><i class="fa-solid fa-user-pen"></i> {{ $facility->publisher }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                        @foreach ($types as $type)
                            <div class="fc-empty" data-e="{{ \Illuminate\Support\Str::slug($type) }}">
                                <div class="fc-empty__inner">
                                    <div class="w-14 h-14 mx-auto rounded-full bg-white border border-gray-200 flex items-center justify-center text-[#63cd00] text-xl">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-semibold">Belum ada fasilitas tipe {{ $type }}.</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="border-2 border-dashed border-gray-300 rounded-2xl p-14 text-center bg-white">
                    <div class="w-14 h-14 mx-auto rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-xl mb-3">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <p class="text-gray-600 font-semibold">Belum ada fasilitas yang dipublikasikan.</p>
                    <p class="text-sm text-gray-500 mt-1">Data fasilitas akan segera hadir.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ============================ CTA SECTION ============================ --}}
    <section class="bg-[#282829] py-16 sm:py-20">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white">Tertarik Bergabung?</h2>
            <p class="mt-4 max-w-2xl mx-auto text-white/80 text-lg">
                Daftar PPDB dan manfaatkan fasilitas modern untuk masa depan gemilang.
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