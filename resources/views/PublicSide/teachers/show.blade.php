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
        @if ($hasImages)
            <div x-data="{ activeSlide: 1, totalSlides: {{ $mainImages->count() }} }">
                <div class="ts-hero">
                    @foreach ($mainImages as $image)
                        <div x-show="activeSlide === {{ $loop->iteration }}"
                            x-transition:enter="transition ease-out duration-1000"
                            x-transition:leave="transition ease-in duration-1000" class="absolute inset-0">
                            <img src="{{ Storage::url($image->path) }}" alt="{{ $image->description ?? $image->filename }}"
                                class="w-full h-full object-cover">
                        </div>
                    @endforeach
                    <div class="absolute inset-0" style="background: rgba(40,40,41,.55)"></div>
                </div>
            </div>
        @else
            <div class="ts-hero"></div>
        @endif

        {{-- Breadcrumb --}}
        <div class="breadcrumb-bar">
            <div class="page-shell ts-crumb">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2 md:space-x-3 text-lg">
                        <li class="inline-flex items-center">
                            <a href="/"
                                class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                {{ __('Beranda') }}
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-white text-xs"></i>
                                <a href="{{ route('public.teachers.index') }}"
                                    class="ml-2 font-medium text-gray-300 hover:text-white md:ml-3 transition-colors">
                                    {{ __('Guru & Staf') }}
                                </a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-white text-xs"></i>
                                <span class="ml-2 font-medium md:ml-3 crumb-current">{{ $teacher->name }}</span>
                            </div>
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
                            <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ __('Foto') }} {{ $teacher->name }}"
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
                        <p class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-[#3f8600] bg-[#eefde2] border border-[#63cd00]/25 rounded-full px-4 py-1.5">
                            <i class="fas fa-chalkboard-teacher text-xs"></i>
                            {{ $teacher->position }}
                        </p>
                    @endif

                    @if (!empty($teacher->subject))
                        <p class="mt-6 text-base sm:text-lg text-gray-600 leading-relaxed max-w-prose">
                            Mengampu mata pelajaran <strong class="text-gray-900">{{ $teacher->subject }}</strong> di
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
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">{{ __('Nama Lengkap') }}</p>
                                    <p class="text-sm font-semibold text-gray-800 break-words">{{ $teacher->name }}</p>
                                </div>
                            </div>
                        @endif

                        @if (!empty($teacher->position))
                            <div class="ts-info-card">
                                <div class="ts-info-card__icon"><i class="fas fa-briefcase"></i></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">{{ __('Jabatan') }}</p>
                                    <p class="text-sm font-semibold text-gray-800 break-words">{{ $teacher->position }}</p>
                                </div>
                            </div>
                        @endif

                        @if (!empty($teacher->subject))
                            <div class="ts-info-card">
                                <div class="ts-info-card__icon"><i class="fas fa-book-open"></i></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">{{ __('Mata Pelajaran') }}</p>
                                    <p class="text-sm font-semibold text-gray-800 break-words">{{ $teacher->subject }}</p>
                                </div>
                            </div>
                        @endif

                        @if (!empty($teacher->school))
                            <div class="ts-info-card {{ $isStaff ? '--staff' : '' }}">
                                <div class="ts-info-card__icon"><i class="fas {{ $isStaff ? 'fa-users-gear' : 'fa-school' }}"></i></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">{{ __('Sekolah') }}</p>
                                    <p class="text-sm font-semibold text-gray-800 break-words">{{ $schoolBadge }}</p>
                                </div>
                            </div>
                        @endif

                        @if (!empty($teacher->category))
                            <div class="ts-info-card">
                                <div class="ts-info-card__icon"><i class="fas fa-tags"></i></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">{{ __('Kategori') }}</p>
                                    <p class="text-sm font-semibold text-gray-800 break-words">{{ $teacher->category }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <a href="{{ route('public.teachers.index') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg"
                            style="background-color: #63cd00; box-shadow: 0 6px 16px -6px rgba(99,205,0,.5);">
                            <i class="fas fa-arrow-left text-xs"></i>
                            {{ __('Kembali ke Daftar Guru') }}
                        </a>
                        <a href="#guruLainnya"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-[#282829] bg-gray-100 border border-gray-200 transition-all duration-200 hover:bg-gray-200">
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