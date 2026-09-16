@extends('layouts.public-app')

@section('title', 'Guru & Staf | SMK Amaliah 1 & 2')
@section('description', 'Kenali guru dan staf SMK Amaliah 1 & 2 Ciawi-Bogor: tenaga pendidik profesional dan berdedikasi di SMK Amaliah 1, SMK Amaliah 2, dan gabungan.')

@include('PublicSide.teachers._styles')

@php
    $amaliahGreen = '#63cd00';
    $amaliahDark = '#282829';
    $total = $teachers->count();

    $groupOf = function ($t) {
        return match ($t->school) {
            'Amaliah 1'    => 'amaliah1',
            'Amaliah 2'    => 'amaliah2',
            'Amaliah 1 & 2' => 'gabungan',
            default        => 'staff',
        };
    };

    $filterGroups = [
        ['key' => 'amaliah1', 'label' => 'SMK Amaliah 1',  'icon' => 'fa-school'],
        ['key' => 'amaliah2', 'label' => 'SMK Amaliah 2',  'icon' => 'fa-school'],
        ['key' => 'gabungan', 'label' => 'SMK Amaliah 1 & 2', 'icon' => 'fa-school-circle-check'],
        ['key' => 'staff',    'label' => 'Staff',           'icon' => 'fa-users-gear'],
    ];

    $statCards = [
        ['key' => 'amaliah1', 'label' => 'Guru SMK Amaliah 1',    'icon' => 'fa-school',             'fg' => '#3f8600', 'bg' => '#eefde2'],
        ['key' => 'amaliah2', 'label' => 'Guru SMK Amaliah 2',    'icon' => 'fa-school-flag',        'fg' => '#1d4ed8', 'bg' => '#e0e7ff'],
        ['key' => 'gabungan', 'label' => 'Guru 1 & 2 (Gabungan)', 'icon' => 'fa-school-circle-check', 'fg' => '#b45309', 'bg' => '#fef3c7'],
        ['key' => 'staff',    'label' => 'Staf / Kependidikan',   'icon' => 'fa-users-gear',          'fg' => '#282829', 'bg' => '#f1f5f9'],
    ];
@endphp

@section('content')

    {{-- ============================ HERO ============================ --}}
    <section class="relative bg-[#282829]">
        <div class="relative h-[320px] lg:h-[420px] overflow-hidden">
            @if($hasImages)
                <div x-data="{ active: 0 }"
                    x-init="setInterval(() => { if ({{ $mainImages->count() }} > 1) active = (active + 1) % {{ $mainImages->count() }} }, 5000)">
                    @foreach($mainImages as $image)
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
                style="background: linear-gradient(100deg, rgba(40,40,41,.88) 0%, rgba(40,40,41,.55) 45%, rgba(40,40,41,.15) 100%)"></div>

            <div class="relative z-10 h-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center">
                <span
                    class="inline-flex items-center gap-2 w-fit text-[11px] lg:text-xs font-semibold tracking-widest uppercase text-[#d9ffb3] bg-white/10 backdrop-blur border border-white/15 rounded-full px-4 py-1.5 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#63cd00]"></span>
                    Tenaga Pendidik &amp; Kependidikan
                </span>
                <h1 class="text-white text-3xl lg:text-5xl font-bold leading-tight">Guru &amp; Staf SMK Amaliah</h1>
                <p class="mt-4 max-w-2xl text-white/85 text-base lg:text-lg leading-relaxed">
                    Kenali {{ $total }} tenaga pendidik dan kependidikan yang berdedikasi, siap membimbing dan
                    mendampingi setiap siswa di SMK Amaliah 1 dan SMK Amaliah 2 Ciawi-Bogor.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="#daftarGuru"
                        class="inline-flex items-center gap-2 bg-[#63cd00] text-[#282829] font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-user-tie"></i> Lihat Daftar Guru &amp; Staf
                    </a>
                    <a href="{{ route('public.about.index') }}"
                        class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-white hover:text-[#282829] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-building-columns"></i> Profil Sekolah
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
                            <span class="inline-flex items-center ml-2 md:ml-3 font-medium text-[#63cd00]">Guru &amp; Staf</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ============================ STATISTIK KATEGORI ============================ --}}
    <section class="bg-gray-50">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($statCards as $card)
                    <div
                        class="group flex items-center gap-4 bg-white border border-gray-200 rounded-2xl px-5 py-4 shadow-sm hover:-translate-y-1 hover:shadow-lg hover:border-[#63cd00] transition-all duration-300">
                        <div
                            class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center text-lg transition-transform duration-300 group-hover:scale-110"
                            style="background:{{ $card['bg'] }};color:{{ $card['fg'] }}">
                            <i class="fa-solid {{ $card['icon'] }}"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-2xl font-extrabold text-[#282829] leading-none">{{ $counts[$card['key']] ?? 0 }}</p>
                            <p class="mt-1 text-xs font-semibold text-gray-500 truncate">{{ $card['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================ DAFTAR GURU & STAF ============================ --}}
    <section id="daftarGuru" class="bg-white py-16 sm:py-20">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Judul --}}
            <div class="max-w-2xl">
                <span class="section-eyebrow">Our Team</span>
                <h2 class="section-title">Kenali Mereka yang Mendampingi Putra-Putri Anda</h2>
                <p class="section-lead">
                    Pilih kategori untuk melihat tenaga pendidik dan kependidikan di sekolah masing-masing.
                </p>
            </div>

            {{-- Filter kategori (pill) --}}
            <div class="mt-8" x-data="{ active: 'all', counts: @json($counts) }">
                <div class="ts-tabs" role="tablist" aria-label="Filter kategori guru & staf">
                    <button type="button" class="ts-btn" role="tab" :class="{ 'is-active': active === 'all' }"
                        @click="active = 'all'">
                        <i class="fa-solid fa-users mr-1"></i> Semua
                        <span class="ml-1 opacity-70">({{ $total }})</span>
                    </button>
                    @foreach($filterGroups as $group)
                        <button type="button" class="ts-btn" role="tab" :class="{ 'is-active': active === '{{ $group['key'] }}' }"
                            @click="active = '{{ $group['key'] }}'">
                            <i class="fa-solid {{ $group['icon'] }} mr-1"></i> {{ $group['label'] }}
                            <span class="ml-1 opacity-70">({{ $counts[$group['key']] ?? 0 }})</span>
                        </button>
                    @endforeach
                </div>

                {{-- Pesan saat kategori belum ada isinya --}}
                <div x-show="active !== 'all' && counts[active] === 0"
                    class="empty-state mt-8">
                    <div class="w-14 h-14 rounded-full bg-white border border-gray-200 flex items-center justify-center text-[#63cd00] text-xl">
                        <i class="fa-solid fa-folder-open"></i>
                    </div>
                    <p class="font-semibold text-gray-700">Kategori ini belum memiliki data.</p>
                    <p class="text-sm text-gray-500">Sedang disiapkan oleh sekolah. Silakan pilih kategori lain.</p>
                </div>

                {{-- Grid kartu --}}
                <div class="teachers-grid mt-8">
                    @forelse ($teachers as $teacher)
                        @php $g = $groupOf($teacher); @endphp
                        <div class="h-full" x-show="active === 'all' || active === '{{ $g }}'"
                            x-transition.opacity.duration.250ms>
                            @include('PublicSide.teachers.card', ['teacher' => $teacher])
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="w-14 h-14 rounded-full bg-white border border-gray-200 flex items-center justify-center text-[#63cd00] text-xl">
                                <i class="fa-solid fa-school"></i>
                            </div>
                            <p class="font-semibold text-gray-700">Belum ada guru atau staf yang didaftarkan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

@endsection