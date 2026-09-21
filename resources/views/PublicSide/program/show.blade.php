@extends('layouts.public-app')

@section('title', $program->name . ' | Program Unggulan SMK Amaliah 1 & 2')
@section('description', \Illuminate\Support\Str::limit(strip_tags($program->description), 160) ?? 'Program unggulan SMK Amaliah 1 & 2 Ciawi-Bogor.')

@include('PublicSide.program._styles')

@php
    $readMinutes = $program->description
        ? max(1, (int) ceil(str_word_count(strip_tags($program->description)) / 200))
        : 1;
@endphp

@section('content')

    {{-- ============================ HERO ============================ --}}
    <section class="relative bg-[#282829]">
        <div class="relative h-[340px] lg:h-[440px] overflow-hidden">
            @if ($program->image)
                <img src="{{ img_url($program->image, 1600, 900) }}" width="1600" height="900" alt="{{ $program->name }}"
                    class="w-full h-full object-cover">
            @else
                <div class="pg-thumb-fallback pg-thumb-fallback--big"><i class="fa-solid fa-book-open"></i></div>
            @endif

            <div class="absolute inset-0"
                style="background: linear-gradient(180deg, rgba(40,40,41,.35) 0%, rgba(40,40,41,.78) 100%)"></div>

            <div class="relative z-10 h-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-end pb-10">
                <span class="pg-chip pg-chip--green w-fit mb-4"><i class="fa-solid fa-graduation-cap"></i> Program Unggulan</span>
                <h1 class="text-white text-3xl lg:text-5xl font-extrabold leading-tight max-w-4xl">
                    {{ $program->name }}
                </h1>
                <div class="mt-5 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-white/85">
                    <span class="inline-flex items-center gap-2">
                        <i class="fa-solid fa-user-pen"></i>
                        {{ $program->publisher }}
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <i class="fa-regular fa-clock"></i>
                        {{ $readMinutes }} menit baca
                    </span>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <div style="background-color:#2D2D2D;">
            <div class="max-w-screen-xl min-h-14 mx-auto px-4 sm:px-6 lg:px-8 flex items-center py-3">
                <nav aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 md:space-x-3 text-sm">
                        <li class="inline-flex items-center flex-shrink-0">
                            <a href="/" class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                Home
                            </a>
                        </li>
                        <li class="inline-flex items-center flex-shrink-0">
                            <i class="fa-solid fa-chevron-right text-white/40 text-xs"></i>
                            <a href="{{ route('public.program.index') }}"
                                class="inline-flex items-center ml-2 md:ml-3 font-medium text-gray-300 hover:text-white transition-colors">
                                Program Unggulan
                            </a>
                        </li>
                        <li class="inline-flex items-center min-w-0">
                            <i class="fa-solid fa-chevron-right text-white/40 text-xs flex-shrink-0"></i>
                            <span class="ml-2 md:ml-3 font-medium text-[#63cd00] truncate max-w-[40vw] sm:max-w-[55vw]" title="{{ $program->name }}">
                                {{ $program->name }}
                            </span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ============================ KONTEN ============================ --}}
    <section class="bg-white">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-x-12 gap-y-10">

                {{-- Kolom kiri (2/3): artikel --}}
                <div class="lg:col-span-2 min-w-0">
                    <a href="{{ route('public.program.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#63cd00] transition-colors duration-200 mb-6">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Kembali ke Daftar Program
                    </a>

                    {{-- Gambar besar (jika ada) --}}
                    @if ($program->image)
                        <figure class="mb-8">
                            <img src="{{ img_url($program->image, 900, 520) }}" width="900" height="520" alt="{{ $program->name }}"
                                class="w-full rounded-2xl shadow-md object-cover">
                            @if ($program->publisher)
                                <figcaption class="mt-2 text-sm text-gray-500">
                                    <i class="fa-solid fa-camera mr-1 text-[#63cd00]"></i>
                                    Dokumentasi {{ $program->publisher }}
                                </figcaption>
                            @endif
                        </figure>
                    @endif

                    {{-- Deskripsi program --}}
                    <article class="pg-article bg-gray-50 border border-[#eef0f3] rounded-2xl p-6 sm:p-10">
                        @if (trim(strip_tags($program->description ?? '')) !== '')
                            {!! \App\Support\HtmlSanitizer::clean($program->description) !!}
                        @else
                            <p class="text-gray-500 text-center py-8">
                                Deskripsi detail program ini belum tersedia.
                            </p>
                        @endif
                    </article>

                    {{-- Keunggulan (jika ada data di field advantage - handled gracefully) --}}
                    @php
                        $advantages = $program->advantage ?? null;
                        $advantageList = $advantages ? array_filter(array_map('trim', explode("\n", $advantages))) : [];
                    @endphp
                    @if (!empty($advantageList))
                        <section class="mt-10">
                            <h2 class="text-2xl font-bold text-[#282829] mb-6">Keunggulan Program</h2>
                            <ul class="pg-advantages">
                                @foreach ($advantageList as $adv)
                                    <li>
                                        <span class="pg-adv-icon"><i class="fa-solid fa-check"></i></span>
                                        <span class="text-gray-700">{{ $adv }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    {{-- Koordinator (jika field ada di model) --}}
                    @if (isset($program->coordinator_name) && $program->coordinator_name)
                        <section class="mt-10">
                            <h2 class="text-2xl font-bold text-[#282829] mb-6">Koordinator Program</h2>
                            <div class="bg-gray-50 rounded-2xl p-6 flex flex-col sm:flex-row items-center gap-6 border border-gray-200">
                                @if (isset($program->coordinator_photo) && $program->coordinator_photo)
                                    <img src="{{ img_url($program->coordinator_photo, 400, 400) }}" width="400" height="400"
                                        alt="Foto {{ $program->coordinator_name }}"
                                        class="w-24 h-24 rounded-full object-cover shadow-md border-4 border-white flex-shrink-0">
                                @else
                                    <div class="w-24 h-24 rounded-full flex items-center justify-center bg-[#282829] text-[#63cd00] text-3xl font-bold flex-shrink-0">
                                        {{ strtoupper(substr($program->coordinator_name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">{{ $program->coordinator_name }}</h3>
                                    <p class="text-base text-gray-500">Koordinator Program</p>
                                </div>
                            </div>
                        </section>
                    @endif

                    {{-- Bagikan --}}
                    <footer class="mt-8 bg-[#282829] rounded-2xl p-6 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="font-bold text-white">Bagikan program ini</p>
                            <p class="text-sm text-white/60 mt-0.5">Bantu sebarkan informasi ke teman & keluarga.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-[#1877F2] hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke Facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($program->name) }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-black hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke X (Twitter)">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($program->name . ' - ' . url()->current()) }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-[#25D366] hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            <a href="{{ url()->current() }}" onclick="navigator.clipboard?.writeText(this.href); alert('Tautan disalin!'); return false;"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-[#63cd00] hover:text-[#282829] hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Salin tautan">
                                <i class="fa-solid fa-link"></i>
                            </a>
                        </div>
                    </footer>
                </div>

                {{-- Kolom kanan (1/3): sidebar --}}
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-8 space-y-6">

                        {{-- Info program --}}
                        <div class="pg-side">
                            <h3 class="pg-side__head"><i class="fa-solid fa-circle-info"></i> Info Program</h3>
                            <div class="pg-side__body space-y-4 text-sm">
                                <div class="pg-side__row">
                                    <span class="pg-side__icon"><i class="fa-solid fa-graduation-cap"></i></span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Jenis</p>
                                        <p class="font-semibold text-gray-800 truncate">Program Unggulan</p>
                                    </div>
                                </div>
                                <div class="pg-side__row">
                                    <span class="pg-side__icon"><i class="fa-solid fa-user-pen"></i></span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Penanggung Jawab</p>
                                        <p class="font-semibold text-gray-800">{{ $program->publisher }}</p>
                                    </div>
                                </div>
                                <div class="pg-side__row">
                                    <span class="pg-side__icon"><i class="fa-regular fa-clock"></i></span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Waktu Baca</p>
                                        <p class="font-semibold text-gray-800">{{ $readMinutes }} menit</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Program lainnya --}}
                        <div class="pg-side">
                            <h3 class="pg-side__head"><i class="fa-solid fa-book-open"></i> Program Lainnya</h3>
                            <div class="pg-side__body">
                                @forelse ($otherPrograms as $item)
                                    <a href="{{ route('public.program.show', $item->id) }}"
                                        class="pg-rel" aria-label="{{ $item->name }}">
                                        <div class="pg-rel__thumb">
                                            @if ($item->image)
                                                <img src="{{ img_url($item->image, 400, 400) }}" width="400" height="400" alt="{{ $item->name }}" loading="lazy">
                                            @else
                                                <i class="fa-solid fa-book-open"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 leading-snug line-clamp-2 hover:text-[#63cd00] transition-colors duration-200">
                                                {{ $item->name }}
                                            </p>
                                            <span class="pg-meta mt-1.5 inline-flex">
                                                <i class="fa-solid fa-user-pen"></i>
                                                {{ $item->publisher }}
                                            </span>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-sm text-gray-500 py-4 text-center">Tidak ada program lain untuk ditampilkan.</p>
                                @endforelse
                            </div>

                            <div class="p-4 pt-0">
                                <a href="{{ route('public.program.index') }}"
                                    class="w-full block text-center py-3 rounded-xl text-sm font-semibold text-white transition-colors duration-200 hover:opacity-90 bg-[#282829]">
                                    Lihat Semua Program
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </section>

@endsection