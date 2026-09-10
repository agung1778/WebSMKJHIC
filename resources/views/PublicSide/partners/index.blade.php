@extends('layouts.public-app')

@section('title', 'Industry Partners | SMK Amaliah 1 & 2')
@section('description', '112+ mitra industri SMK Amaliah 1 & 2 dari berbagai sektor dan wilayah. Lihat kolaborasi link & match kami bersama dunia usaha dan dunia industri.')

@php
    $yearNow = (int) now()->year;
    $yearStart = $startYear ? (int) $startYear : $yearNow;
    $yearEnd = max($yearStart, $yearNow);
    $hasHeroImages = isset($partnersImages) && $partnersImages->isNotEmpty();
    $slideCount = $hasHeroImages ? $partnersImages->count() : 0;
@endphp

@section('content')

    {{-- ============================ HERO ============================ --}}
    <section class="relative bg-[#282829]">
        <div class="relative h-[360px] lg:h-[440px] overflow-hidden">
            @if($hasHeroImages && $slideCount > 0)
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
                    Dunia Usaha & Dunia Industri — Kemitraan
                </span>
                <h1 class="text-white text-3xl lg:text-5xl font-bold leading-tight">Bermitra dengan Industri Terkemuka</h1>
                <p class="mt-4 max-w-2xl text-white/85 text-base lg:text-lg leading-relaxed">
                    Lebih dari {{ $totalPartners }} mitra bisnis dan industri dari {{ $sectorCount }} sektor mendukung
                    pembelajaran link & match, PKL, dan penyerapan lulusan SMK Amaliah 1 &amp; 2 sejak {{ $yearStart }}.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="#daftarMitra"
                        class="inline-flex items-center gap-2 bg-[#63cd00] text-[#282829] font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-handshake"></i> Lihat Mitra Kami
                    </a>
                    <a href="https://wa.me/6285649011449?text=Halo%2C%20saya%20ingin%20menjadi%20mitra%20industri%20SMK%20Amaliah."
                        target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-white hover:text-[#282829] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-rocket"></i> Jadilah Mitra
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
            @php
                $stats = [
                    ['value' => $totalPartners, 'suffix' => '+', 'label' => 'Mitra Aktif', 'icon' => 'fa-handshake'],
                    ['value' => $sectorCount, 'suffix' => '', 'label' => 'Sektor Industri', 'icon' => 'fa-building'],
                    ['value' => $cityCount, 'suffix' => '+', 'label' => 'Kota & Wilayah', 'icon' => 'fa-location-dot'],
                    ['value' => $yearStart . '–' . $yearEnd, 'suffix' => '', 'label' => 'Tahun Kemitraan', 'icon' => 'fa-calendar-check'],
                ];
            @endphp
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($stats as $item)
                    <div
                        class="fade-in-section group bg-gray-50 border border-gray-200 rounded-2xl p-5 text-center hover:border-[#63cd00] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div
                            class="mx-auto w-11 h-11 rounded-full bg-white border border-gray-200 flex items-center justify-center shadow-sm group-hover:bg-[#63cd00] group-hover:border-[#63cd00] transition-colors duration-300">
                            <i class="fa-solid {{ $item['icon'] }} text-[#63cd00] group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <div class="mt-3 text-xl lg:text-2xl font-bold text-[#282829]">
                            {{ $item['value'] }}<span class="text-[#63cd00]">{{ $item['suffix'] }}</span>
                        </div>
                        <div class="mt-1 text-[11px] lg:text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $item['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================ DAFTAR MITRA ============================ --}}
    <section id="daftarMitra" class="bg-gray-50 py-16 sm:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-3xl mx-auto mb-10 lg:mb-12">
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#2D2D2D] tracking-tight">Daftar Mitra Industri</h2>
                <p class="mt-4 text-lg text-slate-600">
                    Cari mitra berdasarkan nama atau filter berdasarkan sektor industri.
                </p>
            </div>

            {{-- Pencarian --}}
            <div class="mb-6 max-w-2xl mx-auto relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="search" id="partnerSearchInput" placeholder="Cari nama atau kota mitra..."
                    class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-2xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#63cd00] bg-white shadow-sm">
            </div>

            {{-- Filter Sektor (chips) --}}
            <div id="sectorChips"
                class="flex flex-wrap items-center justify-center gap-2 mb-10 max-w-4xl mx-auto">
                <button data-sector="all"
                    class="sector-chip active px-4 py-2 rounded-full text-xs font-semibold border transition-all duration-300 bg-[#282829] text-white border-[#282829]">
                    Semua Sektor
                </button>
                @foreach($sectors as $sector)
                    <button data-sector="{{ strtolower($sector) }}"
                        class="sector-chip px-4 py-2 rounded-full text-xs font-semibold border border-gray-300 text-gray-700 bg-white hover:border-[#63cd00] hover:text-[#63cd00] transition-all duration-300">
                        {{ $sector }}
                    </button>
                @endforeach
            </div>

            {{-- Grid Kartu Mitra --}}
            <div id="partnersGrid"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">

                @forelse($partners as $partner)
                    @php
                        $partnerSectors = $partner->sector
                            ? collect(array_map('trim', explode(',', $partner->sector)))->filter()
                            : collect();
                        $year = $partner->partnership_date ? substr((string) $partner->partnership_date, 0, 4) : null;
                    @endphp
                    <div data-name="{{ strtolower($partner->name . ' ' . $partner->city) }}"
                        data-sectors="{{ $partnerSectors->map(fn($s) => strtolower($s))->implode(',') }}"
                        class="partner-card group bg-white border border-gray-200 rounded-2xl p-6 flex flex-col text-center transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-2">

                        {{-- Logo --}}
                        <div class="h-28 w-full bg-gray-50 rounded-xl flex items-center justify-center p-4 mb-5 border border-gray-100">
                            @if ($partner->logo)
                                <img src="{{ Storage::url($partner->logo) }}" alt="Logo {{ $partner->name }}"
                                    class="max-h-20 w-auto object-contain">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="text-sm font-semibold text-gray-400">
                                        <i class="fa-solid fa-building text-3xl"></i>
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Nama + Kota --}}
                        <h3 class="text-base font-bold text-gray-800 leading-tight">{{ $partner->name }}</h3>
                        @if($partner->city)
                            <p class="mt-1 inline-flex items-center justify-center gap-1.5 text-xs text-gray-500">
                                <i class="fa-solid fa-location-dot text-[#63cd00]"></i> {{ $partner->city }}
                            </p>
                        @endif

                        {{-- Badge Sektor --}}
                        @if($partnerSectors->isNotEmpty())
                            <div class="mt-3 flex flex-wrap justify-center gap-1.5">
                                @foreach($partnerSectors as $s)
                                    <span
                                        class="px-2.5 py-1 rounded-full bg-[#63cd00]/10 text-[#4a9e00] text-[10px] font-semibold border border-[#63cd00]/20">
                                        {{ $s }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        {{-- Deskripsi --}}
                        <p class="text-sm text-gray-500 flex-grow mt-3 mb-4">
                            {{ Str::limit($partner->description, 90) }}
                        </p>

                        {{-- Aksi --}}
                        <div class="w-full mt-auto pt-5 border-t border-gray-200/80 flex items-center justify-between gap-3">
                            <span class="inline-flex items-center gap-1.5 text-[11px] text-gray-400">
                                @if($year)
                                    <i class="fa-solid fa-calendar-check text-[#63cd00]"></i> {{ $year }}
                                @endif
                            </span>
                            <a href="{{ route('public.partners.show', $partner) }}"
                                class="inline-flex items-center gap-2 bg-[#282829] hover:bg-[#63cd00] text-white text-xs font-bold px-4 py-2.5 rounded-full transition-colors duration-300">
                                Lihat Detail <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="sm:col-span-2 lg:col-span-3 xl:col-span-4 bg-white border-2 border-dashed border-gray-300 rounded-2xl p-12 text-center">
                        <p class="text-gray-500">Data mitra industri belum tersedia.</p>
                    </div>
                @endforelse

                <div id="noResultsMessage"
                    class="hidden sm:col-span-2 lg:col-span-3 xl:col-span-4 bg-white border-2 border-dashed border-gray-300 rounded-2xl p-12 text-center">
                    <i class="fa-solid fa-building-circle-exclamation text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Mitra yang Anda cari tidak ditemukan. Coba kata kunci atau sektor lain.</p>
                </div>
            </div>

            {{-- Load More --}}
            <div id="loadMoreContainer" class="text-center mt-12">
                <button id="loadMoreBtn"
                    class="bg-white hover:bg-gray-100 text-gray-700 font-bold py-3 px-8 rounded-full border border-gray-300 transition-colors duration-300 shadow-sm">
                    Tampilkan Lebih Banyak
                </button>
            </div>

        </div>
    </section>

    {{-- ============================ MENGAPA BERMITRA ============================ --}}
    <section class="bg-white py-16 sm:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#2D2D2D] tracking-tight">Kenapa Bermitra dengan Kami?</h2>
                <p class="mt-4 text-lg text-slate-600">
                    Kolaborasi yang saling menguntungkan antara industri dan sekolah menuju lulusan siap kerja.
                </p>
            </div>

            @php
                $benefits = [
                    ['icon' => 'fa-graduation-cap', 'title' => 'Link & Match Kurikulum', 'desc' => 'Kurikulum disusun bersama industri agar sesuai kebutuhan nyata di lapangan.'],
                    ['icon' => 'fa-briefcase', 'title' => 'Program PKL & Magang', 'desc' => 'Mitra menjadi tempat praktik kerja lapangan bagi ratusan siswa setiap tahun.'],
                    ['icon' => 'fa-user-check', 'title' => 'Rekrutmen Lulusan', 'desc' => 'Saluran rekrutmen langsung dari lulusan yang sudah teruji kompetensinya.'],
                    ['icon' => 'fa-trophy', 'title' => 'Sertifikasi & Uji Kompetensi', 'desc' => 'Asesmen kompetensi oleh praktisi industri untuk pengakuan keahlian siswa.'],
                ];
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($benefits as $b)
                    <div
                        class="fade-in-section group bg-gray-50 border border-gray-200 rounded-2xl p-7 text-center hover:border-[#63cd00] hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">
                        <div
                            class="mx-auto w-14 h-14 rounded-full bg-white border border-gray-200 flex items-center justify-center shadow-sm group-hover:bg-[#63cd00] group-hover:border-[#63cd00] transition-colors duration-300">
                            <i class="fa-solid {{ $b['icon'] }} text-xl text-[#63cd00] group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <h3 class="mt-5 text-base font-bold text-[#282829]">{{ $b['title'] }}</h3>
                        <p class="mt-2 text-sm text-gray-500 leading-relaxed">{{ $b['desc'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- CTA --}}
            <div class="fade-in-section relative overflow-hidden rounded-3xl"
                style="background: linear-gradient(120deg, #282829 0%, #3a3a3b 100%);">
                <div class="absolute -top-24 -right-16 w-80 h-80 rounded-full opacity-25"
                    style="background: radial-gradient(circle, #63cd00 0%, transparent 70%)"></div>
                <div class="relative z-10 px-8 py-14 lg:px-16 lg:py-20 text-center lg:text-left flex flex-col lg:flex-row items-center justify-between gap-8">
                    <div class="max-w-2xl">
                        <h3 class="text-2xl lg:text-3xl font-bold text-white leading-tight">Tertarik Menjadi Mitra Kami?</h3>
                        <p class="mt-3 text-white/80 text-base">
                            Bergabunglah bersama {{ $totalPartners }}+ mitra industri lainnya. Hubungi tim BKK SMK Amaliah untuk
                            memulai kerja sama.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 flex-shrink-0">
                        <a href="{{ route('public.teachers.index') }}"
                            class="inline-flex items-center justify-center gap-2 bg-[#63cd00] text-[#282829] font-semibold px-7 py-3.5 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                            <i class="fa-solid fa-people-group"></i> Temui Tim Kami
                        </a>
                        <a href="https://wa.me/6285649011449?text=Halo%2C%20saya%20ingin%20menjadi%20mitra%20industri%20SMK%20Amaliah."
                            target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-2 bg-white text-[#282829] font-semibold px-7 py-3.5 rounded-full hover:bg-gray-100 hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                            <i class="fa-brands fa-whatsapp"></i> Hubungi BKK
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('partnerSearchInput');
            const cards = Array.from(document.querySelectorAll('.partner-card'));
            const noResults = document.getElementById('noResultsMessage');
            const loadMoreBtn = document.getElementById('loadMoreBtn');
            const loadMoreContainer = document.getElementById('loadMoreContainer');
            const chips = Array.from(document.querySelectorAll('.sector-chip'));

            const itemsPerLoad = 12;
            let itemsShown = itemsPerLoad;
            let activeSector = 'all';

            function activeFilters() {
                const term = (searchInput.value || '').toLowerCase().trim();
                const filtering = term || activeSector !== 'all';
                let visibleCount = 0;

                cards.forEach(card => {
                    const name = card.dataset.name || '';
                    const cardSectors = (card.dataset.sectors || '').split(',');
                    const matchTerm = !term || name.includes(term);
                    const matchSector = activeSector === 'all' || cardSectors.includes(activeSector);
                    const visible = matchTerm && matchSector;

                    if (visible) {
                        visibleCount++;
                        card.style.display = '';
                        card.dataset.filterOrder = visibleCount;
                    } else {
                        card.style.display = 'none';
                    }
                });

                noResults.classList.toggle('hidden', visibleCount > 0);

                if (filtering) {
                    loadMoreContainer.style.display = 'none';
                } else {
                    itemsShown = itemsPerLoad;
                    updateLoadMore();
                }
            }

            function updateLoadMore() {
                let shown = 0;
                cards.forEach((card, index) => {
                    if (activeSector !== 'all' || (searchInput.value || '').trim()) return;
                    if (index < itemsShown) {
                        card.style.display = '';
                        shown++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                loadMoreContainer.style.display = shown >= cards.length ? 'none' : 'block';
            }

            function setActiveChip(sector) {
                chips.forEach(chip => {
                    const isActive = chip.dataset.sector === sector;
                    chip.classList.toggle('active', isActive);
                    if (isActive) {
                        chip.classList.add('bg-[#282829]', 'text-white', 'border-[#282829]');
                        chip.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                    } else {
                        chip.classList.remove('bg-[#282829]', 'text-white', 'border-[#282829]');
                        chip.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
                    }
                });
            }

            chips.forEach(chip => {
                chip.addEventListener('click', () => {
                    activeSector = chip.dataset.sector;
                    setActiveChip(activeSector);
                    activeFilters();
                });
            });

            searchInput.addEventListener('keyup', activeFilters);
            loadMoreBtn.addEventListener('click', () => {
                itemsShown += itemsPerLoad;
                updateLoadMore();
            });

            activeFilters();
        });
    </script>

@endsection