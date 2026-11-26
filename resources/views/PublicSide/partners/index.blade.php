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
                            <img src="{{ img_url($image->path, 1600, 900) }}" width="1600" height="900"
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
                style="background: linear-gradient(100deg, rgba(20,20,22,0.92) 0%, rgba(20,20,22,0.78) 50%, rgba(20,20,22,0.55) 100%)"></div>

            <div class="relative z-10 h-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center">
                <span
                    class="inline-flex items-center gap-2 w-fit text-[11px] sm:text-xs font-bold tracking-wider uppercase text-white bg-black/60 backdrop-blur border border-white/20 rounded-full px-3.5 sm:px-4 py-1 sm:py-1.5 mb-2.5 sm:mb-4 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-[#63cd00] shadow-sm shadow-[#63cd00]/80"></span>
                    Bermitra dengan Industri — SMK Amaliah 1 & 2
                </span>
                <h1 class="text-white text-2xl sm:text-3xl lg:text-5xl font-extrabold leading-tight tracking-tight drop-shadow-sm">Industry Partners</h1>
                <p class="mt-2.5 sm:mt-4 max-w-2xl text-gray-100 text-sm sm:text-base lg:text-lg leading-relaxed font-normal">
                    Lebih dari {{ $totalPartners }} mitra industri terkemuka menjalin kerja sama strategis dengan SMK Amaliah 1 & 2 untuk program Praktik Kerja Lapangan (PKL), sertifikasi kompetensi, dan penyaluran lulusan siap kerja.
                </p>

                <div class="mt-5 sm:mt-8 flex flex-wrap items-center gap-2.5 sm:gap-3">
                    <a href="#daftarMitra"
                        class="inline-flex items-center gap-2 bg-[#63cd00] text-[#1c1c1d] font-bold text-xs sm:text-sm lg:text-base px-5 sm:px-6 py-2.5 sm:py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-md">
                        <i class="fas fa-handshake"></i> Jelajahi Mitra
                    </a>
                    <a href="https://wa.me/6285649011449" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 bg-white text-[#1c1c1d] font-bold text-xs sm:text-sm lg:text-base px-5 sm:px-6 py-2.5 sm:py-3 rounded-full hover:bg-gray-100 hover:-translate-y-0.5 transition-all duration-300 shadow-md">
                        <i class="fab fa-whatsapp text-[#25D366]"></i> Ajukan Kerja Sama
                    </a>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <div style="background-color:#2D2D2D;">
            <div class="max-w-screen-xl min-h-12 py-2 mx-auto px-4 sm:px-6 lg:px-8 flex items-center overflow-x-auto scrollbar-hide">
                <nav aria-label="Breadcrumb">
                    <ol class="inline-flex items-center gap-1.5 sm:gap-2.5 text-xs sm:text-sm whitespace-nowrap">
                        <li class="flex items-center">
                            <a href="/" class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                Home
                            </a>
                        </li>
                        <li class="flex items-center gap-1.5 sm:gap-2.5">
                            <i class="fas fa-chevron-right text-white/40 text-[10px]"></i>
                            <span class="inline-flex items-center font-medium text-[#63cd00]">Industry Partners</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ============================ STATISTIK ============================ --}}
    <section class="bg-white">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                @php
                    $stats = [
                        ['icon' => 'fas fa-handshake', 'value' => $totalPartners . '+', 'label' => 'Mitra Industri Aktif'],
                        ['icon' => 'fas fa-graduation-cap', 'value' => '100%', 'label' => 'Kesiapan Kerja Lulusan'],
                        ['icon' => 'fas fa-briefcase', 'value' => '5 Jurusan', 'label' => 'Dukungan Program PKL'],
                        ['icon' => 'fas fa-calendar-alt', 'value' => $oldestDate ? \Carbon\Carbon::parse($oldestDate)->year : '2008', 'label' => 'Tahun Mulai Kemitraan'],
                    ];
                @endphp
                @foreach($stats as $stat)
                    <div
                        class="fade-in-section flex items-center gap-3 sm:gap-4 bg-gray-50 border border-gray-200 rounded-2xl p-4 sm:p-6 hover:border-[#63cd00] hover:shadow-md transition-all duration-300">
                        <div
                            class="flex-shrink-0 w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-[#63cd00]/10 text-[#63cd00] flex items-center justify-center">
                            <i class="{{ $stat['icon'] }} text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xl sm:text-2xl lg:text-3xl font-extrabold leading-none text-[#282829]">{{ $stat['value'] }}</p>
                            <p class="mt-1 text-[11px] sm:text-xs lg:text-sm font-medium text-gray-600 leading-tight line-clamp-2">{{ $stat['label'] }}</p>
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
            <div class="mt-8 sm:mt-10 flex flex-col lg:flex-row lg:items-center gap-3 sm:gap-4">
                <div class="relative flex-1 lg:max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                        <i class="fas fa-search text-slate-400 text-xs sm:text-sm"></i>
                    </span>
                    <input type="search" id="partnerSearchInput" placeholder="Cari nama mitra..."
                        class="w-full pl-10 sm:pl-11 pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-full text-xs sm:text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#63cd00] transition">
                </div>
                <div id="sectorFilters" class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-hide flex-nowrap sm:flex-wrap">
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
                        class="partner-card group bg-white rounded-2xl border border-gray-200 p-5 sm:p-6 flex flex-col text-center shadow-sm sm:shadow-md transition-all duration-300 hover:border-[#63cd00] hover:shadow-xl hover:-translate-y-1 h-full">

                        {{-- Logo / Inisial --}}
                        <div
                            class="relative h-28 w-full bg-gray-50 rounded-xl flex items-center justify-center p-4 mb-4 border border-gray-200 overflow-hidden transition-colors duration-300 group-hover:bg-[#63cd00]/5">
                            @if($partner->logo)
                                <img src="{{ img_url($partner->logo, 160, 90) }}" width="160" height="90" loading="lazy" alt="Logo {{ $partner->name }}"
                                    class="max-h-20 w-auto object-contain">
                            @else
                                @php
                                    $initials = collect(explode(' ', $partner->name))->take(2)->map(fn($w) => strtoupper(mb_substr($w, 0, 1)))->implode('');
                                @endphp
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-[#63cd00]/15 text-[#3E9B00] font-extrabold text-base flex items-center justify-center shadow-xs mb-1">
                                        {{ $initials ?: 'MI' }}
                                    </div>
                                    <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Mitra Industri</span>
                                </div>
                            @endif
                            @if($partner->sector)
                                <span
                                    class="absolute top-2 right-2 inline-flex items-center gap-1 bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10.5px] font-bold tracking-wide px-2.5 py-1 rounded-full shadow-2xs">
                                    {{ Str::limit($partner->sector, 22) }}
                                </span>
                            @endif
                        </div>

                        {{-- Nama (Tinggi seragam untuk menjaga baris lurus) --}}
                        <h3 class="text-base sm:text-lg font-bold text-[#1c1c1d] group-hover:text-[#2e7000] leading-snug mb-1 min-h-[2.8em] flex items-center justify-center transition-colors">{{ $partner->name }}</h3>

                        {{-- Meta: kota & tahun --}}
                        <div class="flex flex-wrap items-center justify-center gap-x-3.5 gap-y-1 text-xs text-gray-600 font-medium min-h-[1.5em]">
                            @if($partner->city)
                                <span class="inline-flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-[#2e7000]"></i> {{ $partner->city }}</span>
                            @endif
                            @if($partner->partnership_date)
                                <span class="inline-flex items-center gap-1.5"><i class="fas fa-calendar-alt text-[#2e7000]"></i> {{ \Carbon\Carbon::parse($partner->partnership_date)->translatedFormat('M Y') }}</span>
                            @endif
                        </div>

                        {{-- Deskripsi singkat --}}
                        <p class="text-xs sm:text-sm text-gray-600 flex-grow mt-3 mb-5 leading-relaxed line-clamp-3 font-normal">
                            {{ Str::limit(strip_tags($partner->description), 110) }}
                        </p>

                        {{-- Aksi --}}
                        <div class="w-full mt-auto pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                            <a href="{{ route('public.partners.show', $partner) }}"
                                class="flex-1 inline-flex items-center justify-center gap-2 bg-[#1c1c1d] hover:bg-[#63cd00] hover:text-[#1c1c1d] text-white text-xs font-bold px-4 py-2.5 rounded-full transition-all duration-300 shadow-xs">
                                Lihat Detail <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                            @if($partner->company_contact)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $partner->company_contact) }}" target="_blank" rel="noopener" title="Kontak: {{ $partner->company_contact }}"
                                    class="h-9 w-9 flex-shrink-0 inline-flex items-center justify-center bg-gray-100 border border-gray-200 hover:bg-[#63cd00]/15 hover:text-[#2e7000] hover:border-[#63cd00]/40 text-gray-600 rounded-full transition-colors duration-300">
                                    <i class="fas fa-phone-alt text-xs"></i>
                                </a>
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

            {{-- Tombol Muat Lebih Banyak --}}
            <div id="loadMoreContainer" class="text-center mt-10">
                <button type="button" id="loadMoreBtn"
                    class="inline-flex items-center gap-2 bg-white border-2 border-gray-200 hover:border-[#63cd00] text-[#282829] hover:text-[#63cd00] font-bold text-sm px-8 py-3.5 rounded-full shadow-sm hover:shadow-md transition-all duration-300">
                    <span>Tampilkan Lebih Banyak Mitra</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
            </div>

            {{-- CTA Kerja Sama --}}
            <div class="mt-16 bg-[#282829] rounded-3xl p-8 sm:p-12 text-center text-white relative overflow-hidden shadow-xl">
                <div class="absolute -top-12 -right-12 w-64 h-64 rounded-full bg-[#63cd00]/15 blur-2xl"></div>
                <div class="relative z-10 max-w-2xl mx-auto">
                    <h3 class="text-2xl sm:text-3xl font-bold">Tertarik Bermitra dengan SMK Amaliah 1 & 2?</h3>
                    <p class="mt-3 text-white/80 text-sm sm:text-base leading-relaxed">
                        Kami membuka pintu kolaborasi bagi instansi, BUMN, dan korporasi untuk program Praktik Kerja Lapangan (PKL), rekrutmen lulusan, serta penyelarasan kurikulum vokasi.
                    </p>
                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                        <a href="https://wa.me/6285649011449" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 bg-[#63cd00] text-[#282829] font-bold text-sm sm:text-base px-6 py-3 rounded-full hover:bg-[#59E300] transition-all shadow-md">
                            <i class="fab fa-whatsapp"></i> Hubungi Tim Kerjasama
                        </a>
                    </div>
                </div>
            </div>


        </div>
    </section>

    <style>
        .sector-pill {
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 9999px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #282829;
            cursor: pointer;
            transition: all 0.25s ease;
            line-height: 1.2;
            white-space: nowrap;
            flex-shrink: 0;
        }
        @media (min-width: 640px) {
            .sector-pill {
                padding: 9px 18px;
                font-size: 13px;
            }
        }
        .sector-pill:hover { background: #f3f4f6; }
        .sector-pill.active {
            background: #63cd00;
            border-color: #63cd00;
            color: #ffffff;
            box-shadow: 0 8px 18px rgba(99, 205, 0, 0.28);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var searchInput = document.getElementById('partnerSearchInput');
            var pills = Array.from(document.querySelectorAll('.sector-pill'));
            var cards = Array.from(document.querySelectorAll('.partner-card'));
            var noResults = document.getElementById('noResultsMessage');
            var loadMoreContainer = document.getElementById('loadMoreContainer');
            var loadMoreBtn = document.getElementById('loadMoreBtn');
            var state = { q: '', sector: '' };
            var PAGE_SIZE = 12;
            var visibleCount = PAGE_SIZE;

            function applyState() {
                var q = state.q;
                var hasFilter = Boolean(q || state.sector);
                var matchedCards = [];

                cards.forEach(function (card) {
                    var okName = !q || (card.dataset.name || '').indexOf(q) !== -1;
                    var okSector = !state.sector || (card.dataset.sector || '').indexOf(state.sector) !== -1;
                    var match = okName && okSector;
                    if (match) {
                        matchedCards.push(card);
                    } else {
                        card.classList.add('hidden');
                    }
                });

                if (hasFilter) {
                    // Jika sedang mencari/filter, tampilkan semua yang cocok
                    matchedCards.forEach(function (card) {
                        card.classList.remove('hidden');
                    });
                    if (loadMoreContainer) {
                        loadMoreContainer.classList.add('hidden');
                    }
                } else {
                    // Tampilkan secara bertahap
                    matchedCards.forEach(function (card, index) {
                        if (index < visibleCount) {
                            card.classList.remove('hidden');
                        } else {
                            card.classList.add('hidden');
                        }
                    });
                    if (loadMoreContainer) {
                        loadMoreContainer.classList.toggle('hidden', visibleCount >= matchedCards.length);
                    }
                }

                if (noResults) {
                    noResults.classList.toggle('hidden', matchedCards.length > 0);
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    state.q = searchInput.value.toLowerCase().trim();
                    visibleCount = PAGE_SIZE;
                    applyState();
                });
            }

            pills.forEach(function (pill) {
                pill.addEventListener('click', function () {
                    pills.forEach(function (p) { p.classList.remove('active'); });
                    pill.classList.add('active');
                    state.sector = pill.dataset.sector || '';
                    visibleCount = PAGE_SIZE;
                    applyState();
                });
            });

            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function () {
                    visibleCount += PAGE_SIZE;
                    applyState();
                });
            }

            applyState();
        });
    </script>

@endsection