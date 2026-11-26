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
                    Fasilitas &amp; Prasarana
                </span>
                <h1 class="text-white text-2xl sm:text-4xl lg:text-5xl font-extrabold leading-tight tracking-tight">Fasilitas <span class="text-[#00875a]">Modern</span></h1>
                <p class="mt-2.5 sm:mt-4 max-w-2xl text-white/90 text-sm sm:text-base lg:text-lg leading-relaxed">
                    {{ $total }} fasilitas lengkap mendukung proses belajar mengajar berkualitas dan pengembangan potensi siswa.
                </p>

                <div class="mt-5 sm:mt-8 flex flex-wrap items-center gap-2.5 sm:gap-3">
                    <a href="#daftarFasilitas"
                        class="inline-flex items-center gap-2 bg-[#00875a] text-white font-semibold text-xs sm:text-sm lg:text-base px-5 sm:px-6 py-2.5 sm:py-3 rounded-full hover:bg-[#00704a] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-building"></i> Lihat Fasilitas
                    </a>
                    <a href="{{ route('public.about.index') }}"
                        class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white font-semibold text-xs sm:text-sm lg:text-base px-5 sm:px-6 py-2.5 sm:py-3 rounded-full hover:bg-white hover:text-[#1f1f20] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-circle-info"></i> Tentang Kami
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
                        <li class="flex items-center">
                            <i class="fa-solid fa-chevron-right text-white/30 text-xs"></i>
                            <span class="inline-flex items-center ml-1.5 sm:ml-2.5 font-medium text-[#00875a]">Fasilitas</span>
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
                        <i class="fa-solid fa-building text-[#00875a] group-hover:text-white transition-colors duration-300 text-base sm:text-lg"></i>
                    </div>
                    <div class="mt-2.5 sm:mt-3 text-2xl sm:text-3xl font-extrabold text-[#1c1c1d] tracking-tight">
                        {{ $total }}
                    </div>
                    <div class="mt-1 text-xs sm:text-sm font-semibold text-gray-700 leading-snug">Total Fasilitas</div>
                </div>
                @foreach ($types as $type)
                    <div class="group bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 text-center shadow-xs hover:border-[#00875a] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="mx-auto w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#e6f4ea] border border-[#00875a]/30 flex items-center justify-center shadow-xs group-hover:bg-[#00875a] group-hover:border-[#00875a] transition-colors duration-300">
                            <i class="fa-solid {{ $type === 'Lab' ? 'fa-flask' : ($type === 'Akademik' ? 'fa-book-open' : ($type === 'Olahraga' ? 'fa-futbol' : 'fa-building')) }} text-[#00875a] group-hover:text-white transition-colors duration-300 text-base sm:text-lg"></i>
                        </div>
                        <div class="mt-2.5 sm:mt-3 text-2xl sm:text-3xl font-extrabold text-[#1c1c1d] tracking-tight">
                            {{ $typeCounts[$type] ?? 0 }}
                        </div>
                        <div class="mt-1 text-xs sm:text-sm font-semibold text-gray-700 leading-snug">{{ $type }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================ DAFTAR FASILITAS ============================ --}}
    <section id="daftarFasilitas" class="bg-gray-50 py-12 sm:py-16 lg:py-20">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="max-w-2xl mb-8 sm:mb-10">
                <span class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-[#00875a] mb-2 sm:mb-3">
                    <span class="w-6 h-0.5 rounded-full bg-[#00875a]"></span>
                    Sarana &amp; Prasarana
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#1c1c1d] tracking-tight">Fasilitas Unggulan Kami</h2>
                <p class="mt-2 sm:mt-3 text-gray-600 text-sm sm:text-base lg:text-lg">Mendukung proses belajar mengajar modern dan pengembangan bakat siswa.</p>
            </div>
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
                                    <h3 class="text-lg font-bold text-[#1c1c1d] leading-snug line-clamp-2 group-hover:text-[#00875a] transition-colors duration-200">
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
                                    <div class="w-14 h-14 mx-auto rounded-full bg-white border border-gray-200 flex items-center justify-center text-[#00875a] text-xl">
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
    <section class="bg-[#1f1f20] py-16 sm:py-20">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white">Tertarik Bergabung?</h2>
            <p class="mt-4 max-w-2xl mx-auto text-white/80 text-lg">
                Daftar PPDB dan manfaatkan fasilitas modern untuk masa depan gemilang.
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('public.about.index') }}"
                    class="inline-flex items-center gap-2 bg-[#00875a] text-white font-semibold text-base px-8 py-4 rounded-full hover:bg-[#00704a] hover:-translate-y-0.5 transition-all duration-300 shadow-lg">
                    <i class="fa-solid fa-user-plus"></i> Info PPDB
                </a>
                <a href="https://wa.me/6285649011449" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white font-semibold text-base px-8 py-4 rounded-full hover:bg-white hover:text-[#1f1f20] hover:-translate-y-0.5 transition-all duration-300">
                    <i class="fa-brands fa-whatsapp"></i> Konsultasi WA
                </a>
            </div>
        </div>
    </section>

@endsection