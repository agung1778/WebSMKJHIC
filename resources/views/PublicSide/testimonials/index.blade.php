@extends('layouts.public-app')

@section('title', 'Testimoni Alumni | SMK Amaliah 1 & 2')
@section('description', 'Kisah sukses, pengalaman berharga, dan inspirasi dari para alumni SMK Amaliah 1 & 2 Ciawi-Bogor yang telah berkarier di berbagai industri terkemuka.')

@php
    $amaliahGreen = '#63cd00';
    $amaliahDark = '#282829';
    $hasImages = isset($majorsImages) && $majorsImages->isNotEmpty();
    $slideCount = $hasImages ? $majorsImages->count() : 0;
    $total = $testimonials->count();
@endphp

@section('content')

    {{-- ============================ HERO ============================ --}}
    <section class="relative bg-[#282829]">
        <div class="relative h-[320px] lg:h-[420px] overflow-hidden">
            @if($hasImages && $slideCount > 0)
                <div x-data="{ active: 0 }"
                    x-init="setInterval(() => { if ({{ $slideCount }} > 1) active = (active + 1) % {{ $slideCount }} }, 5000)">
                    @foreach($majorsImages as $image)
                        <div x-show="active === {{ $loop->index }}" x-cloak
                            x-transition:enter="transition ease-out duration-1000"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-1000"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="absolute inset-0">
                            <img src="{{ img_url($image->path, 1600, 900) }}" width="1600" height="900" loading="lazy"
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
                    Cerita Alumni & Jejak Langkah
                </span>
                <h1 class="text-white text-2xl sm:text-3xl lg:text-5xl font-extrabold leading-tight tracking-tight drop-shadow-sm">Testimoni Alumni</h1>
                <p class="mt-2.5 sm:mt-4 max-w-2xl text-gray-100 text-sm sm:text-base lg:text-lg leading-relaxed font-normal">
                    Dengarkan cerita langsung dari para alumni SMK Amaliah 1 & 2 mengenai pengalaman belajar, pembentukan karakter, dan kesiapan berkarier di dunia industri.
                </p>

                <div class="mt-5 sm:mt-8 flex flex-wrap items-center gap-2.5 sm:gap-3">
                    <a href="#daftarTestimoni"
                        class="inline-flex items-center gap-2 bg-[#63cd00] text-[#1c1c1d] font-bold text-xs sm:text-sm lg:text-base px-5 sm:px-6 py-2.5 sm:py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-md">
                        <i class="fas fa-comment-dots"></i> Baca Testimoni
                    </a>
                    <a href="{{ route('public.about.index') }}"
                        class="inline-flex items-center gap-2 bg-white text-[#1c1c1d] font-bold text-xs sm:text-sm lg:text-base px-5 sm:px-6 py-2.5 sm:py-3 rounded-full hover:bg-gray-100 hover:-translate-y-0.5 transition-all duration-300 shadow-md">
                        <i class="fas fa-school text-[#449e00]"></i> Profil Sekolah
                    </a>
                </div>
            </div>
        </div>

        {{-- Breadcrumb Responsif --}}
        <div style="background-color: #2D2D2D;">
            <div class="max-w-screen-xl min-h-12 py-2 mx-auto px-4 sm:px-6 lg:px-8 flex items-center overflow-x-auto scrollbar-hide">
                <nav aria-label="Breadcrumb" class="min-w-0">
                    <ol class="inline-flex items-center gap-1.5 sm:gap-2.5 text-xs sm:text-sm whitespace-nowrap">
                        <li class="flex items-center">
                            <a href="/" class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                Home
                            </a>
                        </li>
                        <li class="flex items-center gap-1.5 sm:gap-2.5">
                            <i class="fas fa-chevron-right text-white/40 text-[10px]"></i>
                            <span class="inline-flex items-center font-medium text-[#63cd00]">Testimoni</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ============================ DAFTAR TESTIMONI ============================ --}}
    <section id="daftarTestimoni" class="bg-gray-50 py-10 sm:py-16 lg:py-20">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header Section --}}
            <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-12">
                <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase mb-2">Suara Alumni</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#282829]">Apa Kata Para Lulusan Kami?</h2>
                <div class="flex items-center justify-center gap-x-2 mx-auto mt-3">
                    <div class="w-16 h-1 rounded-full bg-[#63cd00]"></div>
                    <div class="w-6 h-1 rounded-full bg-[#63cd00]"></div>
                    <div class="w-2.5 h-1 rounded-full bg-[#63cd00]"></div>
                </div>
            </div>

            {{-- Container Grid Testimoni --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                @forelse ($testimonials as $testimonial)
                    <div
                        class="group bg-white border border-gray-200 rounded-2xl p-5 sm:p-7 flex flex-col justify-between shadow-xs hover:border-[#63cd00] hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                        {{-- Bagian Atas: Kutipan + Deskripsi --}}
                        <div>
                            <div class="flex items-center justify-between mb-3 sm:mb-4">
                                <i class="fas fa-quote-left text-2xl sm:text-3xl text-[#63cd00]/30 group-hover:text-[#63cd00] transition-colors"></i>
                                <span class="text-[11px] sm:text-xs text-gray-400 font-medium">
                                    {{ $testimonial->created_at ? $testimonial->created_at->translatedFormat('d M Y') : '' }}
                                </span>
                            </div>

                            <p class="text-gray-700 text-sm sm:text-base leading-relaxed italic">
                                "{{ $testimonial->description }}"
                            </p>
                        </div>

                        {{-- Bagian Bawah: Profil Alumni --}}
                        <div class="mt-5 sm:mt-6 pt-4 border-t border-gray-100 flex items-center gap-3.5 sm:gap-4">
                            {{-- Foto Avatar --}}
                            <div class="flex-shrink-0">
                                @if($testimonial->photo)
                                    <img src="{{ img_url($testimonial->photo, 200, 200) }}" width="200" height="200" loading="lazy"
                                        alt="Foto {{ $testimonial->name }}"
                                        class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover border-2 border-[#63cd00]/30 shadow-xs">
                                @else
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-[#63cd00]/15 text-[#3E9B00] font-bold text-base sm:text-lg flex items-center justify-center border-2 border-[#63cd00]/30">
                                        {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            {{-- Nama & Info Jurusan --}}
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm sm:text-base font-bold text-[#282829] truncate leading-tight">
                                    {{ $testimonial->name }}
                                </h3>
                                <p class="text-xs sm:text-sm font-semibold text-[#63cd00] mt-0.5 truncate">
                                    Alumni {{ $testimonial->major->name ?? 'SMK Amaliah' }}
                                    @if($testimonial->alumni_year)
                                        <span class="text-gray-400 font-normal">({{ $testimonial->alumni_year }})</span>
                                    @endif
                                </p>
                            </div>

                            <a href="{{ route('public.testimonials.show', $testimonial) }}"
                                class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold text-gray-400 hover:text-[#63cd00] transition-colors flex-shrink-0">
                                Detail <i class="fas fa-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="w-full text-center py-12 md:col-span-2 bg-white border-2 border-dashed border-gray-200 rounded-2xl p-8">
                        <i class="fas fa-comment-slash text-4xl text-gray-300 mb-3 block"></i>
                        <p class="text-gray-500 font-medium">Belum ada testimoni untuk ditampilkan.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </section>

@endsection
