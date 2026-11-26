@extends('layouts.public-app')

@section('title', 'Major Competency | SMK Amaliah 1 & 2')
@section('description', 'Program Keahlian SMK Amaliah 1 & 2 Ciawi-Bogor: Rekayasa Perangkat Lunak, Animasi, Teknik Komputer & Jaringan, Desain Komunikasi Visual, dan program bisnis & pariwisata.')

@php
    $amaliahGreen = '#63cd00';
    $amaliahDark = '#282829';
    $hasHeroImages = isset($majorsImages) && $majorsImages->isNotEmpty();
    $slideCount = $hasHeroImages ? $majorsImages->count() : 0;
    $totalMajors = $majors->count();
    $amaliah1Count = $majors->where('tag', 'SMK Amaliah 1')->count();
    $amaliah2Count = $majors->where('tag', 'SMK Amaliah 2')->count();
@endphp

@section('content')

    {{-- ============================ HERO ============================ --}}
    <section class="relative bg-[#282829]">
        <div class="relative h-[360px] lg:h-[440px] overflow-hidden">
            @if($hasHeroImages && $slideCount > 0)
                <div x-data="{ active: 0 }"
                    x-init="setInterval(() => { if ({{ $slideCount }} > 1) active = (active + 1) % {{ $slideCount }} }, 5000)">
                    @foreach($majorsImages as $image)
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
                    Program Keahlian — SMK Amaliah 1 & 2
                </span>
                <h1 class="text-white text-2xl sm:text-3xl lg:text-5xl font-extrabold leading-tight tracking-tight drop-shadow-sm">Major Competency</h1>
                <p class="mt-2.5 sm:mt-4 max-w-2xl text-gray-100 text-sm sm:text-base lg:text-lg leading-relaxed font-normal">
                    {{ $totalMajors }} program keahlian unggulan yang dirancang link & match dengan kebutuhan dunia
                    industri, tersebar di {{ $amaliah1Count }} program SMK Amaliah 1 dan {{ $amaliah2Count }} program SMK
                    Amaliah 2.
                </p>

                <div class="mt-5 sm:mt-8 flex flex-wrap items-center gap-2.5 sm:gap-3">
                    <a href="#daftarJurusan"
                        class="inline-flex items-center gap-2 bg-[#63cd00] text-[#1c1c1d] font-bold text-xs sm:text-sm lg:text-base px-5 sm:px-6 py-2.5 sm:py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-md">
                        <i class="fa-solid fa-sitemap"></i> Lihat Jurusan
                    </a>
                    <a href="https://ppdb.smkamaliah.sch.id/login" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 bg-white/15 backdrop-blur border border-white/25 text-white font-bold text-xs sm:text-sm lg:text-base px-5 sm:px-6 py-2.5 sm:py-3 rounded-full hover:bg-white hover:text-[#1c1c1d] hover:-translate-y-0.5 transition-all duration-300 shadow-md">
                        <i class="fa-solid fa-rocket"></i> Info SPMB
                    </a>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <div style="background-color:#2D2D2D;">
            <div class="max-w-screen-xl min-h-12 py-2 mx-auto px-4 sm:px-6 lg:px-8 flex items-center overflow-x-auto scrollbar-hide">
                <nav aria-label="Breadcrumb" class="min-w-0">
                    <ol class="inline-flex items-center gap-1.5 sm:gap-2.5 text-xs sm:text-sm whitespace-nowrap">
                        <li class="flex items-center">
                            <a href="/" class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                Home
                            </a>
                        </li>
                        <li class="flex items-center gap-1.5 sm:gap-2.5">
                            <i class="fa-solid fa-chevron-right text-white/40 text-[10px]"></i>
                            <span class="inline-flex items-center font-medium text-[#63cd00]">Major Competency</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ============================ FOKUS SEKOLAH ============================ --}}
    <section class="bg-white">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                @php
                    $schoolFocus = [
                        ['tag' => 'SMK Amaliah 1', 'icon' => 'fa-code', 'title' => 'SMK Amaliah 1 (IT & Kreatif)', 'text' => 'Berfokus pada dunia IT (Teknologi Informasi): pemrograman, jaringan, dan multimedia (PPLG, TJKT, ANIMASI, DKV).', 'count' => $amaliah1Count],
                        ['tag' => 'SMK Amaliah 2', 'icon' => 'fa-briefcase', 'title' => 'SMK Amaliah 2 (Bisnis & Pariwisata)', 'text' => 'Berfokus pada manajemen bisnis, pemasaran digital, dan pariwisata untuk sektor jasa dan industri kreatif (MP, AKL, LPS, BR, DPB).', 'count' => $amaliah2Count],
                    ];
                @endphp
                @foreach($schoolFocus as $f)
                    <div
                        class="fade-in-section group relative overflow-hidden bg-white border border-gray-200 rounded-2xl p-5 sm:p-7 shadow-xs hover:border-[#63cd00] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-xl bg-[#eefde2] border border-emerald-200 flex items-center justify-center shadow-xs group-hover:bg-[#63cd00] group-hover:border-[#63cd00] transition-colors duration-300">
                                <i class="fa-solid {{ $f['icon'] }} text-lg text-[#2e7000] group-hover:text-[#1c1c1d] transition-colors duration-300"></i>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-[#1c1c1d] leading-tight">{{ $f['title'] }}</h3>
                                <p class="mt-2 text-sm text-gray-700 leading-relaxed font-normal">{{ $f['text'] }}</p>
                                <p class="mt-3 inline-flex items-center gap-1.5 text-xs font-bold text-[#2e7000]">
                                    <i class="fa-solid fa-layer-group"></i> {{ $f['count'] }} Program Keahlian
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================ DAFTAR JURUSAN ============================ --}}
    <section id="daftarJurusan" class="bg-gray-50 py-12 sm:py-20 lg:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-[#2e7000] mb-2">
                    <span class="w-6 h-0.5 rounded-full bg-[#2e7000]"></span>
                    Pilihan Masa Depan
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#1c1c1d] tracking-tight">Program Keahlian</h2>
                <p class="mt-3 text-sm sm:text-base lg:text-lg text-gray-700 leading-relaxed font-normal">Pilih program keahlian sesuai minat, bakat, dan rencana karier Anda.</p>
                <div class="flex items-center justify-center gap-x-2 mx-auto mt-4">
                    <div class="w-16 h-1 rounded-full bg-[#63cd00]"></div>
                    <div class="w-6 h-1 rounded-full bg-[#63cd00]/60"></div>
                    <div class="w-3 h-1 rounded-full bg-[#63cd00]/30"></div>
                </div>
            </div>

            {{-- Tabs Nav --}}
            <div x-data="{ activeTab: 'all' }" class="mt-8 sm:mt-12">
                <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3">
                    <button @click="activeTab = 'all'" :class="{
                                'bg-[#1c1c1d] text-white shadow-md font-bold': activeTab === 'all',
                                'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200 font-semibold': activeTab !== 'all'
                            }"
                        class="px-5 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm rounded-full transition-all duration-200">
                        Semua ({{ $totalMajors }})
                    </button>
                    <button @click="activeTab = 'SMK Amaliah 1'" :class="{
                                'bg-[#1c1c1d] text-white shadow-md font-bold': activeTab === 'SMK Amaliah 1',
                                'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200 font-semibold': activeTab !== 'SMK Amaliah 1'
                            }"
                        class="px-5 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm rounded-full transition-all duration-200">
                        SMK Amaliah 1 ({{ $amaliah1Count }})
                    </button>
                    <button @click="activeTab = 'SMK Amaliah 2'" :class="{
                                'bg-[#1c1c1d] text-white shadow-md font-bold': activeTab === 'SMK Amaliah 2',
                                'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200 font-semibold': activeTab !== 'SMK Amaliah 2'
                            }"
                        class="px-5 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm rounded-full transition-all duration-200">
                        SMK Amaliah 2 ({{ $amaliah2Count }})
                    </button>
                </div>

                <div class="mt-8 sm:mt-12 grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                    @forelse($majors as $major)
                        @php
                            $advantages = $major->advantage ? array_filter(explode("\n", $major->advantage)) : [];
                            $limitedAdvantages = array_slice($advantages, 0, 3);
                        @endphp
                        <div x-show="activeTab === 'all' || activeTab === '{{ $major->tag }}'"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="fade-in-section bg-white rounded-2xl border border-gray-200/90 shadow-sm transition-all duration-300 group overflow-hidden flex flex-col hover:shadow-xl hover:border-[#63cd00] hover:-translate-y-1">

                            {{-- Gambar Utama --}}
                            <a href="{{ route('public.majors.show', $major) }}" class="block h-48 sm:h-56 lg:h-60 relative overflow-hidden bg-gray-100">
                                @if($major->image)
                                    <img src="{{ img_url($major->image, 900, 520) }}" width="900" height="520" loading="lazy" alt="Gambar {{ $major->name }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.05]">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-graduation-cap text-3xl"></i>
                                    </div>
                                @endif
                                <span
                                    class="absolute top-3 right-3 inline-flex items-center gap-1.5 bg-black/80 backdrop-blur border border-white/20 text-white text-[10px] sm:text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full shadow-sm">
                                    {{ $major->tag ?? 'Program Keahlian' }}
                                </span>
                            </a>

                            <div class="p-5 sm:p-6 flex flex-col relative flex-grow">
                                {{-- Logo --}}
                                <div class="absolute -top-10 sm:-top-12 left-5 sm:left-6 bg-white p-2 sm:p-2.5 rounded-2xl shadow-lg border border-gray-100">
                                    @if($major->logo)
                                        <img src="{{ img_url($major->logo, 160, 90) }}" width="160" height="90" loading="lazy" alt="Logo {{ $major->abbreviation ?? $major->name }}"
                                            class="h-12 w-12 sm:h-14 sm:w-14 object-contain">
                                    @else
                                        <div class="h-12 w-12 sm:h-14 sm:w-14 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-building"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Header --}}
                                <div class="pt-6 sm:pt-8 flex items-center gap-3">
                                    <div>
                                        <h3 class="text-xl font-extrabold text-[#1c1c1d] leading-tight">
                                            {{ $major->abbreviation ?? $major->name }}
                                        </h3>
                                        <p class="text-sm font-semibold text-gray-700 mt-1 leading-snug">{{ $major->name }}</p>
                                    </div>
                                </div>

                                {{-- Keunggulan --}}
                                <div class="mt-4 flex-grow border-t border-gray-100 pt-4">
                                    <h4 class="font-bold text-[#1c1c1d] text-sm">Skills Unggulan</h4>
                                    <ul class="mt-2.5 text-sm text-gray-700 leading-relaxed space-y-2">
                                        @forelse($limitedAdvantages as $advantage)
                                            <li class="flex items-start">
                                                <i class="fa-solid fa-circle-check text-xs mt-1 mr-2 flex-shrink-0 text-[#2e7000]"></i>
                                                <span class="font-medium">{{ trim($advantage) }}</span>
                                            </li>
                                        @empty
                                            <li class="text-gray-400 italic">Keunggulan belum diinput.</li>
                                        @endforelse
                                    </ul>
                                </div>

                                {{-- Footer --}}
                                <div class="mt-6 flex items-center gap-4">
                                    <a href="{{ route('public.majors.show', $major) }}"
                                        class="inline-flex items-center gap-2 justify-center bg-[#1c1c1d] hover:bg-[#63cd00] hover:text-[#1c1c1d] text-white text-sm font-bold px-6 py-2.5 rounded-full transition-all duration-300 w-full shadow-xs">
                                        Selengkapnya <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 text-center py-12">
                            <p class="text-gray-500 font-medium">Belum ada jurusan yang ditambahkan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

@endsection