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
                    Program Keahlian — SMK Amaliah 1 &amp; 2
                </span>
                <h1 class="text-white text-3xl lg:text-5xl font-bold leading-tight">Major Competency</h1>
                <p class="mt-4 max-w-2xl text-white/85 text-base lg:text-lg leading-relaxed">
                    {{ $totalMajors }} program keahlian unggulan yang dirancang link &amp; match dengan kebutuhan dunia
                    industri, tersebar di {{ $amaliah1Count }} program SMK Amaliah 1 dan {{ $amaliah2Count }} program SMK
                    Amaliah 2.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="#daftarJurusan"
                        class="inline-flex items-center gap-2 bg-[#63cd00] text-[#282829] font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-sitemap"></i> Lihat Jurusan
                    </a>
                    <a href="https://ppdb.smkamaliah.sch.id/login" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-white hover:text-[#282829] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-rocket"></i> Info SPMB
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
                            <span class="inline-flex items-center ml-0 md:ml-3 font-medium text-[#63cd00]">Major Competency</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ============================ FOKUS SEKOLAH ============================ --}}
    <section class="bg-white">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @php
                    $schoolFocus = [
                        ['tag' => 'SMK Amaliah 1', 'icon' => 'fa-code', 'title' => 'SMK Amaliah 1 (IT & Kreatif)', 'text' => 'Berfokus pada dunia IT (Teknologi Informasi): pemrograman, jaringan, dan multimedia. (PPLG, TJKT, ANIMASI, DKV)', 'count' => $amaliah1Count],
                        ['tag' => 'SMK Amaliah 2', 'icon' => 'fa-briefcase', 'title' => 'SMK Amaliah 2 (Bisnis & Pariwisata)', 'text' => 'Berfokus pada manajemen bisnis, pemasaran digital, dan pariwisata untuk sektor jasa dan industri kreatif. (MP, AKL, LPS, BR, DPB)', 'count' => $amaliah2Count],
                    ];
                @endphp
                @foreach($schoolFocus as $f)
                    <div
                        class="fade-in-section group relative overflow-hidden bg-gray-50 border border-gray-200 rounded-2xl p-7 hover:border-[#63cd00] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-full bg-white border border-gray-200 flex items-center justify-center shadow-sm group-hover:bg-[#63cd00] group-hover:border-[#63cd00] transition-colors duration-300">
                                <i class="fa-solid {{ $f['icon'] }} text-lg text-[#63cd00] group-hover:text-white transition-colors duration-300"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#282829] leading-tight">{{ $f['title'] }}</h3>
                                <p class="mt-2 text-sm text-gray-500 leading-relaxed">{{ $f['text'] }}</p>
                                <p class="mt-3 inline-flex items-center gap-1.5 text-xs font-semibold text-[#63cd00]">
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
    <section id="daftarJurusan" class="bg-gray-50 py-16 sm:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#2D2D2D] tracking-tight">Program Keahlian</h2>
                <p class="mt-4 text-lg text-slate-600">Pilih program keahlian sesuai minat dan bakat Anda.</p>
                <div class="flex items-center justify-center gap-x-2 mx-auto mt-4">
                    <div class="w-20 h-1 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                    <div class="w-8 h-1 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                    <div class="w-4 h-1 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                </div>
            </div>

            {{-- Tabs Nav --}}
            <div x-data="{ activeTab: 'all' }">
                <div class="flex flex-wrap justify-center items-center gap-2 mt-10">
                    <button @click="activeTab = 'all'" :class="{
                                'bg-[#63cd00] text-white shadow-lg': activeTab === 'all',
                                'bg-white text-[#282829] hover:bg-gray-200 border border-gray-300': activeTab !== 'all'
                            }"
                        class="px-6 py-2.5 text-sm font-semibold rounded-full transition-all duration-300">
                        Semua ({{ $totalMajors }})
                    </button>
                    <button @click="activeTab = 'SMK Amaliah 1'" :class="{
                                'bg-[#63cd00] text-white shadow-lg': activeTab === 'SMK Amaliah 1',
                                'bg-white text-[#282829] hover:bg-gray-200 border border-gray-300': activeTab !== 'SMK Amaliah 1'
                            }"
                        class="px-6 py-2.5 text-sm font-semibold rounded-full transition-all duration-300">
                        SMK Amaliah 1 ({{ $amaliah1Count }})
                    </button>
                    <button @click="activeTab = 'SMK Amaliah 2'" :class="{
                                'bg-[#63cd00] text-white shadow-lg': activeTab === 'SMK Amaliah 2',
                                'bg-white text-[#282829] hover:bg-gray-200 border border-gray-300': activeTab !== 'SMK Amaliah 2'
                            }"
                        class="px-6 py-2.5 text-sm font-semibold rounded-full transition-all duration-300">
                        SMK Amaliah 2 ({{ $amaliah2Count }})
                    </button>
                </div>

                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
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
                            class="fade-in-section bg-white rounded-2xl shadow-md transition-all duration-300 group overflow-hidden flex flex-col hover:shadow-xl hover:-translate-y-1">

                            {{-- Gambar Utama --}}
                            <a href="{{ route('public.majors.show', $major) }}" class="block h-52 lg:h-60 relative overflow-hidden">
                                @if($major->image)
                                    <img src="{{ Storage::url($major->image) }}" alt="Gambar {{ $major->name }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.05]">
                                @else
                                    <div class="w-full h-full bg-gray-300"></div>
                                @endif
                                <span
                                    class="absolute top-3 right-3 inline-flex items-center gap-1.5 bg-[#282829]/85 backdrop-blur text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full">
                                    {{ $major->tag ?? 'Program Keahlian' }}
                                </span>
                            </a>

                            <div class="p-6 flex flex-col relative flex-grow">
                                {{-- Logo --}}
                                <div class="absolute -top-12 left-6 bg-white p-2.5 rounded-2xl shadow-xl border border-gray-100">
                                    @if($major->logo)
                                        <img src="{{ Storage::url($major->logo) }}" alt="Logo {{ $major->abbreviation ?? $major->name }}"
                                            class="h-14 w-14 object-contain">
                                    @else
                                        <div class="h-14 w-14 bg-gray-200 rounded-xl flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-building"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Header --}}
                                <div class="pt-8 flex items-center gap-3">
                                    <div>
                                        <h3 class="text-xl font-bold leading-tight" style="color: {{ $amaliahDark }};">
                                            {{ $major->abbreviation ?? $major->name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-0.5">{{ $major->name }}</p>
                                    </div>
                                </div>

                                {{-- Keunggulan --}}
                                <div class="mt-4 flex-grow border-t border-gray-100 pt-4">
                                    <h4 class="font-medium text-gray-700 text-sm">Skills Unggulan</h4>
                                    <ul class="mt-2 text-sm text-gray-600 leading-relaxed space-y-2">
                                        @forelse($limitedAdvantages as $advantage)
                                            <li class="flex items-start">
                                                <i class="fa-solid fa-check-circle text-xs mt-1 mr-2 flex-shrink-0"
                                                    style="color: {{ $amaliahGreen }};"></i>
                                                <span>{{ trim($advantage) }}</span>
                                            </li>
                                        @empty
                                            <li class="text-gray-400 italic">Keunggulan belum diinput.</li>
                                        @endforelse
                                    </ul>
                                </div>

                                {{-- Footer --}}
                                <div class="mt-6 flex items-center gap-4">
                                    <a href="{{ route('public.majors.show', $major) }}"
                                        class="inline-flex items-center gap-2 justify-center bg-[#282829] hover:bg-[#63cd00] text-white text-sm font-semibold px-6 py-2.5 rounded-full transition-colors duration-300 w-full">
                                        Selengkapnya <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 text-center py-12">
                            <p class="text-gray-500">Belum ada jurusan yang ditambahkan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

@endsection