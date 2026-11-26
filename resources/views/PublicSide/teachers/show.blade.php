@extends('layouts.public-app')

@section('title', $teacher->name . ' — Guru & Staf')
@section('description', 'Profil ' . $teacher->name . ', ' . $teacher->position . ' di SMK Amaliah 1 & 2 Ciawi-Bogor.')

@section('content')

    @include('PublicSide.teachers._styles')

    @php
        $hasImages = isset($mainImages) && $mainImages->isNotEmpty();
        $isStaff = ($teacher->school ?? '') === 'Staff';
        $schoolBadge = $teacher->school ?: 'Staff';
        $roleLabel = $isStaff ? 'Staf Kependidikan' : 'Tenaga Pendidik';

        $initial = !empty(trim($teacher->name)) ? strtoupper(mb_substr(trim($teacher->name), 0, 1)) : '?';
    @endphp

    <div class="page-section">

        {{-- Hero --}}
        <div class="ts-hero">
            @if ($hasImages)
                <img src="{{ img_url($mainImages->first()->path, 1600, 900) }}" width="1600" height="900"
                    alt="{{ $mainImages->first()->description ?? $mainImages->first()->filename }}"
                    class="w-full h-full object-cover">
            @endif
            <div class="absolute inset-0" style="background: rgba(40,40,41,.55)"></div>
        </div>

        {{-- Breadcrumb --}}
        <div class="breadcrumb-bar">
            <div class="page-shell ts-crumb overflow-x-auto scrollbar-hide py-2">
                <nav class="flex min-w-0" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center gap-1.5 sm:gap-2.5 text-xs sm:text-sm whitespace-nowrap">
                        <li class="inline-flex items-center">
                            <a href="/"
                                class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                {{ __('Beranda') }}
                            </a>
                        </li>
                        <li class="flex items-center gap-1.5 sm:gap-2.5">
                            <i class="fas fa-chevron-right text-white/40 text-[10px]"></i>
                            <a href="{{ route('public.teachers.index') }}"
                                class="font-medium text-gray-300 hover:text-white transition-colors">
                                {{ __('Guru & Staf') }}
                            </a>
                        </li>
                        <li class="flex items-center gap-1.5 sm:gap-2.5" aria-current="page">
                            <i class="fas fa-chevron-right text-white/40 text-[10px]"></i>
                            <span class="font-medium crumb-current truncate max-w-xs">{{ $teacher->name }}</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Profil Guru --}}
        <div class="page-shell">
            <div class="teacher-detail">

                {{-- Aside: Foto + ringkasan --}}
                <aside class="teacher-detail__aside">
                    <div class="teacher-photo-frame">
                        @if ($teacher->photo)
                            <img src="{{ img_url($teacher->photo, 640, 640) }}" width="640" height="640" alt="{{ __('Foto') }} {{ $teacher->name }}"
                                loading="lazy" onerror="this.remove()">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-9xl font-bold"
                                style="background: linear-gradient(145deg, #282829, #1f2937); color:#63cd00;">
                                {{ $initial }}
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        @if (!empty($teacher->school))
                            <span class="ts-badge {{ $isStaff ? '' : 'ts-badge--green' }}">
                                <i class="fas {{ $isStaff ? 'fa-users-gear' : 'fa-school' }}"></i>
                                {{ $schoolBadge }}
                            </span>
                        @endif
                        @if (!empty($teacher->category))
                            <span class="ts-badge"><i class="fas fa-tags"></i> {{ $teacher->category }}</span>
                        @endif
                        <span class="ts-badge"><i class="fas fa-id-badge"></i> {{ $roleLabel }}</span>
                    </div>

                    {{-- Ringkasan cepat --}}
                    <div class="mt-6 space-y-2.5">
                        @if (!empty($teacher->position))
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <i class="fas fa-briefcase text-[#63cd00] w-4 text-center"></i>
                                <span class="font-medium">{{ $teacher->position }}</span>
                            </div>
                        @endif
                        @if (!empty($teacher->subject))
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <i class="fas fa-book-open text-[#63cd00] w-4 text-center"></i>
                                <span class="font-medium">{{ $teacher->subject }}</span>
                            </div>
                        @endif
                    </div>
                </aside>

                {{-- Main: Detail --}}
                <main class="teacher-detail__main">
                    <span class="section-eyebrow">{{ $roleLabel }}</span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        {{ $teacher->name }}
                    </h1>

                    @if (!empty($teacher->position))
                        <p class="mt-3 inline-flex items-center gap-2 text-sm font-bold text-[#1f5c00] bg-emerald-50 border border-emerald-200 rounded-full px-4 py-1.5 shadow-xs">
                            <i class="fas fa-chalkboard-teacher text-xs"></i>
                            {{ $teacher->position }}
                        </p>
                    @endif

                    @if (!empty($teacher->subject))
                        <p class="mt-6 text-base sm:text-lg text-gray-700 leading-relaxed max-w-prose">
                            Mengampu mata pelajaran <strong class="text-gray-900 font-bold">{{ $teacher->subject }}</strong> di
                            {{ $teacher->school ?: 'SMK Amaliah' }}, berikut adalah profil tenaga pendidik yang aktif
                            mendampingi proses belajar mengajar di sekolah kami.
                        </p>
                    @endif

                    {{-- Info lengkap --}}
                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @if (!empty($teacher->name))
                            <div class="ts-info-card">
                                <div class="ts-info-card__icon"><i class="fas fa-user"></i></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">{{ __('Nama Lengkap') }}</p>
                                    <p class="text-sm font-bold text-gray-900 break-words">{{ $teacher->name }}</p>
                                </div>
                            </div>
                        @endif

                        @if (!empty($teacher->position))
                            <div class="ts-info-card">
                                <div class="ts-info-card__icon"><i class="fas fa-briefcase"></i></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">{{ __('Jabatan') }}</p>
                                    <p class="text-sm font-bold text-gray-900 break-words">{{ $teacher->position }}</p>
                                </div>
                            </div>
                        @endif

                        @if (!empty($teacher->subject))
                            <div class="ts-info-card">
                                <div class="ts-info-card__icon"><i class="fas fa-book-open"></i></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">{{ __('Mata Pelajaran') }}</p>
                                    <p class="text-sm font-bold text-gray-900 break-words">{{ $teacher->subject }}</p>
                                </div>
                            </div>
                        @endif

                        @if (!empty($teacher->school))
                            <div class="ts-info-card {{ $isStaff ? '--staff' : '' }}">
                                <div class="ts-info-card__icon"><i class="fas {{ $isStaff ? 'fa-users-gear' : 'fa-school' }}"></i></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">{{ __('Sekolah') }}</p>
                                    <p class="text-sm font-bold text-gray-900 break-words">{{ $schoolBadge }}</p>
                                </div>
                            </div>
                        @endif

                        @if (!empty($teacher->category))
                            <div class="ts-info-card">
                                <div class="ts-info-card__icon"><i class="fas fa-tags"></i></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">{{ __('Kategori') }}</p>
                                    <p class="text-sm font-bold text-gray-900 break-words">{{ $teacher->category }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <a href="{{ route('public.teachers.index') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-[#1c1c1d] hover:bg-[#63cd00] hover:text-[#1c1c1d] transition-all duration-200 shadow-sm">
                            <i class="fas fa-arrow-left text-xs"></i>
                            {{ __('Kembali ke Daftar Guru') }}
                        </a>
                        <a href="#guruLainnya"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-[#282829] bg-gray-100 border border-gray-200 transition-all duration-200 hover:bg-gray-200">
                            {{ __('Guru & Staf Lainnya') }}
                            <i class="fas fa-arrow-down text-xs"></i>
                        </a>
                    </div>
                </main>
            </div>

            {{-- Guru Lain --}}
            @if ($relatedTeachers->isNotEmpty())
                <div id="guruLainnya" class="mt-16 lg:mt-20 scroll-mt-24">
                    <div class="mb-8">
                        <h2 class="section-title">{{ __('Guru & Staf Lainnya') }}</h2>
                        <p class="section-lead">{{ __('Kenali juga tenaga pendidik lain dari sekolah kami.') }}</p>
                    </div>
                    <div class="teachers-grid">
                        @foreach ($relatedTeachers as $related)
                            @include('PublicSide.teachers.card', ['teacher' => $related])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection