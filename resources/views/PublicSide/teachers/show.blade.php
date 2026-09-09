@extends('layouts.public-app')

@section('title', $teacher->name . ' — Guru & Staf')
@section('description', 'Profil ' . $teacher->name . ', ' . $teacher->position . ' di SMK Amaliah 1 & 2 Ciawi-Bogor.')

@section('content')

    @include('PublicSide.teachers._styles')

    @php
        $hasImages = isset($mainImages) && $mainImages->isNotEmpty();
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
                            <a href="/" class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
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
                            <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ __('Foto') }} {{ $teacher->name }}">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center text-8xl font-bold text-slate-300 bg-slate-100">
                                {{ strtoupper(mb_substr(trim($teacher->name), 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        @if (!empty($teacher->school))
                            <span class="ts-badge ts-badge--green">
                                <i class="fas fa-school"></i>
                                {{ $teacher->school }}
                            </span>
                        @endif
                        @if (!empty($teacher->position))
                            <span class="ts-badge"><i class="fas fa-chalkboard-teacher"></i> {{ $teacher->position }}</span>
                        @endif
                    </div>
                </aside>

                {{-- Main: Detail --}}
                <main class="teacher-detail__main">
                    <span class="section-eyebrow">{{ $teacher->position }}</span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        {{ $teacher->name }}
                    </h1>

                    @if (!empty($teacher->subject))
                        <p class="mt-4 text-base sm:text-lg text-gray-600 leading-relaxed">
                            {{ $teacher->subject }}
                        </p>
                    @endif

                    <div class="mt-8 ts-info-row">
                        @if (!empty($teacher->name))
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">{{ __('Nama Lengkap') }}</p>
                                <p class="text-sm font-medium text-gray-800">{{ $teacher->name }}</p>
                            </div>
                        @endif
                        @if (!empty($teacher->position))
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">{{ __('Posisi') }}</p>
                                <p class="text-sm font-medium text-gray-800">{{ $teacher->position }}</p>
                            </div>
                        @endif
                        @if (!empty($teacher->subject))
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">{{ __('Mata Pelajaran') }}</p>
                                <p class="text-sm font-medium text-gray-800">{{ $teacher->subject }}</p>
                            </div>
                        @endif
                        @if (!empty($teacher->school))
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">{{ __('Kategori') }}</p>
                                <p class="text-sm font-medium text-gray-800">{{ $teacher->school }}</p>
                            </div>
                        @endif
                        @if (!empty($teacher->category))
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">{{ __('Kategori') }}</p>
                                <p class="text-sm font-medium text-gray-800">{{ $teacher->category }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('public.teachers.index') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg"
                            style="background-color: #63cd00; box-shadow: 0 6px 16px -6px rgba(99,205,0,.5);">
                            <i class="fas fa-arrow-left text-xs"></i>
                            {{ __('Kembali ke Daftar Guru') }}
                        </a>
                    </div>
                </main>
            </div>

            {{-- Guru Lain --}}
            @if ($relatedTeachers->isNotEmpty())
                <div class="mt-16 lg:mt-20">
                    <div class="mb-8">
                        <h2 class="section-title">{{ __('Guru Lainnya') }}</h2>
                        <p class="section-lead">{{ __('Kenali juga pendidik lain dari sekolah kami.') }}</p>
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
