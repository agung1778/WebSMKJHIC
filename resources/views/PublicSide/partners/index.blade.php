@extends('layouts.public-app')

@section('title', 'Industry Partners | SMK Amaliah 1 & 2')
@section('description', 'Mitra industri SMK Amaliah 1 & 2 Ciawi-Bogor: kerja sama strategis di bidang industri, bisnis, dan profesional untuk mendukung lulusan siap kerja.')

@php
    $amaliahGreen = '#63cd00';
    $amaliahDark = '#282829';
    $hasImages = isset($partnersImages) && $partnersImages->isNotEmpty();
    $slideCount = $hasImages ? $partnersImages->count() : 0;

    $totalPartners = $partners->count();
    $totalCities = $partners->pluck('city')->filter()->unique()->count();
    $totalSectors = $partners->pluck('sector')->filter()->unique()->count();
    $oldestDate = $partners->pluck('partnership_date')->filter()->min();
    $sectors = $partners->pluck('sector')->filter()->unique()->values()->sort();
@endphp

@section('content')

    {{-- ============================ HERO ============================ --}}
    <section class="relative bg-[#282829]">
        <div class="relative h-[360px] lg:h-[440px] overflow-hidden">
            @if($hasImages && $slideCount > 0)
                <div x-data="{ active: 0 }"
                    x-init="setInterval(() => { if ({{ $slideCount }} > 1) active = (active + 1) % {{ $slideCount }} }, 5000)">
                    @foreach($partnersImages as $image)
                        <div x-show="active === {{ $loop->index }}" x-cloak
                            x-transition:enter="transition ease-out duration-1000"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-1000"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="absolute inset-0">
                            <img src="{{ Storage::url($image->path) }}"
                                alt="{{ $image->description ?? $image->filename }}"
                                class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            @else
                <div class="absolute inset-0">
                    <div class="absolute -top-24 -right-16 w-96 h-96 rounded-full opacity-25"
                        style="background: radial-gradient(circle, #63cd00 0%, transparent 70%)"></div>
                    <div class="absolute bottom-0 left-0 w-full h-2/3"
                        style="background: radial-gradient(ellipse at bottom left, rgba(99,205,0,.18) 0%, transparent 60%)"></div>
                </div>
            @endif

            <div class="absolute inset-0"
                style="background: linear-gradient(100deg, rgba(40,40,41,.87) 0%, rgba(40,40,41,.55) 45%, rgba(40,40,41,.18) 100%)"></div>

            <div class="relative z-10 h-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center">
                <span
                    class="inline-flex items-center gap-2 w-fit text-[11px] lg:text-xs font-semibold tracking-widest uppercase text-[#d9ffb3] bg-white/10 backdrop-blur border border-white/15 rounded-full px-4 py-1.5 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#63cd00]"></span>
                    Bermitra dengan Industri — SMK Amaliah 1 &amp; 2
                </span>
                <h1 class="text-white text-3xl lg:text-5xl font-bold leading-tight">Industry Partners</h1>
                <p class="mt-4 max-w-2xl text-white/85 text-base lg:text-lg leading-relaxed">
                    {{ $totalPartners }} mitra industri yang tersebar di {{ $totalCities }} kota dan
                    {{ $totalSectors }} sektor usaha, menjalin kerja sama strategis agar lulusan
                    siap kerja dan relevan dengan kebutuhan pasar.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="#daftarMitra"
                        class="inline-flex items-center gap-2 bg-[#63cd00] text-[#282829] font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fas fa-handshake"></i> Jelajahi Mitra
                    </a>
                    <a href="https://wa.me/6285649011449" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-white hover:text-[#282829] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fas fa-handshake"></i> Ajukan Kerja Sama
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
                            <i class="fas fa-chevron-right text-white/40 text-xs"></i>
                            <span class="inline-flex items-center ml-0 md:ml-3 font-medium text-[#63cd00]">Industry Partners</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ============================ STATISTIK ============================ --}}
    <section class="bg-white">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @php
                    $stats = [
                        ['icon' => 'fas fa-handshake', 'value' => $totalPartners, 'label' => 'Mitra Industri'],
                        ['icon' => 'fas fa-map-marker-alt', 'value' => $totalCities, 'label' => 'Kota Terwakili'],
                        ['icon' => 'fas fa-briefcase', 'value' => $totalSectors, 'label' => 'Sektor Usaha'],
                        ['icon' => 'fas fa-calendar-alt', 'value' => $oldestDate ? \Carbon\Carbon::parse($oldestDate)->year : '-', 'label' => 'Tahun Kerja Sama'],
                    ];
                @endphp
                @foreach($stats as $stat)
                    <div
                        class="fade-in-section flex items-center gap-4 bg-gray-50 border border-gray-200 rounded-2xl p-5 sm:p-6 hover:border-[#63cd00] hover:shadow-md transition-all duration-300">
                        <div
                            class="flex-shrink-0 w-12 h-12 rounded-full bg-[#63cd00]/10 text-[#63cd00] flex items-center justify-center">
                            <i class="{{ $stat['icon'] }} text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-2xl lg:text-3xl font-extrabold leading-none text-[#282829]">{{ $stat['value'] }}</p>
                            <p class="mt-1 text-xs lg:text-sm font-medium text-gray-500 truncate">{{ $stat['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================ DAFTAR MITRA ============================ --}}
    <section id="daftarMitra" class="bg-gray-50 py-16 sm:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#2D2D2D] tracking-tight">Mitra Industri Terkemuka</h2>
                <p class="mt-4 text-lg text-slate-600">Kolaborasi kami bersama dunia usaha, industri, dan profesional untuk jembatan sekolah ke dunia kerja.</p>
                <div class="flex items-center justify-center gap-x-2 mx-auto mt-4">
                    <div class="w-20 h-1 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                    <div class="w-8 h-1 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                    <div class="w-4 h-1 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                </div>
            </div>

            {{-- Filter: Pencarian & Sektor --}}
            <div class="mt-10 flex flex-col lg:flex-row lg:items-center gap-4">
                <div class="relative flex-1 lg:max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                        <i class="fas fa-search text-slate-400"></i>
                    </span>
                    <input type="search" id="partnerSearchInput" placeholder="Cari nama mitra..."
                        class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-full text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#63cd00] transition">
                </div>
                <div id="sectorFilters" class="flex flex-wrap items-center gap-2">
                    <button type="button" data-sector="" class="sector-pill active">Semua ({{ $totalPartners }})</button>
                    @foreach($sectors as $sector)
                        <button type="button" data-sector="{{ strtolower($sector) }}" class="sector-pill">{{ $sector }}</button>
                    @endforeach
                </div>
            </div>

            {{-- GRID MITRA --}}
            <div id="partnersGrid"
                class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">

                @forelse($partners as $partner)
                    <div data-name="{{ strtolower($partner->name) }}"
                        data-sector="{{ strtolower($partner->sector ?? '') }}"
                        class="partner-card group bg-white rounded-2xl border border-gray-200 p-6 flex flex-col text-center shadow-md transition-all duration-300 hover:border-[#63cd00] hover:shadow-xl hover:-translate-y-1">

                        {{-- Logo --}}
                        <div
                            class="relative h-28 w-full bg-gray-50 rounded-xl flex items-center justify-center p-4 mb-5 border border-gray-200 overflow-hidden transition-colors duration-300 group-hover:bg-[#63cd00]/5">
                            @if($partner->logo)
                                <img src="{{ Storage::url($partner->logo) }}" alt="Logo {{ $partner->name }}"
                                    class="max-h-20 w-auto object-contain">
                            @else
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <i class="fas fa-building text-3xl mb-1"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Logo</span>
                                </div>
                            @endif
                            @if($partner->sector)
                                <span
                                    class="absolute top-2 right-2 inline-flex items-center gap-1 bg-[#63cd00]/10 text-[#3E9B00] border border-[#63cd00]/30 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                                    {{ Str::limit($partner->sector, 22) }}
                                </span>
                            @endif
                        </div>

                        {{-- Nama --}}
                        <h3 class="text-lg font-bold text-[#282829] leading-snug mb-1">{{ $partner->name }}</h3>

                        {{-- Meta: kota & tahun --}}
                        <div class="flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-xs text-gray-500">
                            @if($partner->city)
                                <span class="inline-flex items-center gap-1"><i class="fas fa-map-marker-alt" style="color: {{ $amaliahGreen }};"></i> {{ $partner->city }}</span>
                            @endif
                            @if($partner->partnership_date)
                                <span class="inline-flex items-center gap-1"><i class="fas fa-calendar-alt" style="color: {{ $amaliahGreen }};"></i> {{ \Carbon\Carbon::parse($partner->partnership_date)->translatedFormat('M Y') }}</span>
                            @endif
                        </div>

                        {{-- Deskripsi singkat --}}
                        <p class="text-sm text-gray-500 flex-grow mt-3 mb-5">
                            {{ Str::limit(strip_tags($partner->description), 90) }}
                        </p>

                        {{-- Aksi --}}
                        <div class="w-full mt-auto pt-5 border-t border-gray-100 flex items-center justify-between gap-3">
                            <a href="{{ route('public.partners.show', $partner) }}"
                                class="flex-1 inline-flex items-center justify-center gap-2 bg-[#282829] hover:bg-[#63cd00] text-white text-xs font-bold px-4 py-2.5 rounded-full transition-colors duration-300">
                                Lihat Detail <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                            @if($partner->company_contact)
                                <span title="Kontak: {{ $partner->company_contact }}"
                                    class="h-9 w-9 flex-shrink-0 inline-flex items-center justify-center bg-gray-100 border border-gray-200 hover:bg-[#63cd00]/10 hover:text-[#63cd00] hover:border-[#63cd00]/40 text-gray-500 rounded-full transition-colors duration-300">
                                    <i class="fas fa-phone-alt text-sm"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        class="sm:col-span-2 lg:col-span-3 bg-white border-2 border-dashed border-slate-300 rounded-2xl p-12 text-center">
                        <div class="text-4xl mb-3 text-slate-300"><i class="fas fa-building"></i></div>
                        <p class="text-slate-500 font-medium">Data mitra industri belum tersedia.</p>
                    </div>
                @endforelse

                {{-- Pesan jika tidak ada hasil filter --}}
                <div id="noResultsMessage"
                    class="hidden sm:col-span-2 lg:col-span-3 bg-white border-2 border-dashed border-slate-300 rounded-2xl p-12 text-center">
                    <div class="text-4xl mb-3 text-slate-300"><i class="fas fa-search"></i></div>
                    <p class="text-slate-500 font-medium">Mitra yang Anda cari tidak ditemukan.</p>
                </div>
            </div>


        </div>
    </section>

    <style>
        .sector-pill {
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 9999px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #282829;
            cursor: pointer;
            transition: all 0.25s ease;
            line-height: 1.2;
        }
        .sector-pill:hover { background: #f3f4f6; }
        .sector-pill.active {
            background: #63cd00;
            border-color: #63cd00;
            color: #ffffff;
            box-shadow: 0 8px 18px rgba(99, 205, 0, 0.28);
        }
        .hidden { display: none !important; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var searchInput = document.getElementById('partnerSearchInput');
            var pills = Array.from(document.querySelectorAll('.sector-pill'));
            var cards = Array.from(document.querySelectorAll('.partner-card'));
            var noResults = document.getElementById('noResultsMessage');
            var state = { q: '', sector: '' };

            function applyState() {
                var q = state.q;
                var visible = 0;
                cards.forEach(function (card) {
                    var okName = !q || (card.dataset.name || '').indexOf(q) !== -1;
                    var okSector = !state.sector || (card.dataset.sector || '').indexOf(state.sector) !== -1;
                    var match = okName && okSector;
                    visible += match ? 1 : 0;
                    card.classList.toggle('hidden', !match);
                });
                noResults.classList.toggle('hidden', visible > 0);
            }

            if (searchInput) {
                searchInput.addEventListener('keyup', function () {
                    state.q = searchInput.value.toLowerCase().trim();
                    applyState();
                });
            }

            pills.forEach(function (pill) {
                pill.addEventListener('click', function () {
                    pills.forEach(function (p) { p.classList.remove('active'); });
                    pill.classList.add('active');
                    state.sector = pill.dataset.sector;
                    applyState();
                });
            });

            applyState();
        });
    </script>

@endsection