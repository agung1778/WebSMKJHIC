@extends('layouts.public-app')

@section('content')

    @php
        $hasImages = isset($mainImages) && $mainImages->isNotEmpty();
    @endphp
    <div>
        <section class="relative max-w-screen">
            {{-- Slider Gambar Dinamis --}}
            @if($hasImages)
                <div x-data="{ activeSlide: 1, totalSlides: {{ $mainImages->count() }} }"
                    x-init="setInterval(() => { activeSlide = activeSlide % totalSlides + 1 }, 5000)">
                    <div class="relative w-full h-[300px] overflow-hidden">
                        @foreach($mainImages as $image)
                            <div x-show="activeSlide === {{ $loop->iteration }}"
                                x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-1000"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0">

                                <img src="{{ Storage::url($image->path) }}" alt="{{ $image->description ?? $image->filename }}"
                                    class="w-full h-full object-cover">
                            </div>
                        @endforeach

                    </div>
                </div>
            @else
                <div>
                    <div class="relative h-[300px] overflow-hidden bg-black">
                        {{-- Layar hitam sebagai fallback --}}
                    </div>
                </div>
            @endif
        </section>
        <div style="background-color: #2D2D2D;">
            <div class="max-w-screen-xl h-[70px] mx-auto px-4 sm:px-6 lg:px-8">
                {{-- Menggunakan h-full dan flex items-center untuk membuat konten di tengah vertikal --}}
                <div class="h-full flex items-center">
                    <nav class="flex" aria-label="Breadcrumb">
                        {{-- Text-lg untuk memperbesar teks --}}
                        <ol class="inline-flex items-center space-x-2 md:space-x-3 text-lg">
                            <li class="inline-flex items-center">
                                <a href="/"
                                    class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                    Home
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                                    <a href="{{ route('public.about.index') }}"
                                        class="ml-2 font-medium text-gray-300 hover:text-white md:ml-3 transition-colors">About</a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    {{-- Mengganti warna chevron untuk konsistensi --}}
                                    <i class="fas fa-chevron-right text-white text-xs"></i>
                                    <span class="ml-2 font-medium md:ml-3 text-[#ffffff]">Vision And Mission</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="bg-white py-16 sm:py-24">
            <div class="container mx-auto max-w-4xl px-6 lg:px-8">

                <div class="text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        Visi & Misi SMK Amaliah Ciawi
                    </h2>
                    <p class="mt-6 text-xl leading-8 text-gray-600">
                        "Menjadi Sekolah Menengah Kejuruan Berkualitas Yang Menyatu Dalam Tauhid"
                    </p>
                </div>

                <div class="my-12 border-t border-gray-200"></div>

                <div class="mt-12 space-y-10">

                    <div class="relative flex items-start">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center">
                            <i class="fas fa-hands-holding-circle text-3xl text-[#59E300]"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                Integrasi Nilai Tauhid
                            </h3>
                            <p class="mt-2 text-base leading-7 text-gray-600">
                                Mengintegrasikan nilai-nilai Tauhid pada setiap mata pelajaran untuk membentuk karakter yang
                                kuat.
                            </p>
                        </div>
                    </div>

                    <div class="relative flex items-start">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center">
                            <i class="fas fa-tools text-3xl text-[#59E300]"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                Orientasi Praktik
                            </h3>
                            <p class="mt-2 text-base leading-7 text-gray-600">
                                Fokus pada pembelajaran praktik dengan komposisi 70% praktik dan 30% teori untuk kesiapan
                                kerja.
                            </p>
                        </div>
                    </div>

                    <div class="relative flex items-start">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center">
                            <i class="fas fa-puzzle-piece text-3xl text-[#59E300]"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                Pembelajaran Menyenangkan & Aplikatif
                            </h3>
                            <p class="mt-2 text-base leading-7 text-gray-600">
                                Menciptakan proses belajar yang tidak hanya menyenangkan tetapi juga dapat diterapkan
                                langsung.
                            </p>
                        </div>
                    </div>

                    <div class="relative flex items-start">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center">
                            <i class="fas fa-clipboard-check text-3xl text-[#59E300]"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                Penilaian Berbasis Kompetensi
                            </h3>
                            <p class="mt-2 text-base leading-7 text-gray-600">
                                Setiap penilaian didasarkan pada ketuntasan kompetensi untuk memastikan standar kualitas
                                lulusan.
                            </p>
                        </div>
                    </div>

                    <div class="relative flex items-start">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center">
                            <i class="fas fa-user-graduate text-3xl text-[#59E300]"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                Membekali Lulusan yang Terampil
                            </h3>
                            <p class="mt-2 text-base leading-7 text-gray-600">
                                Memberikan bekal keterampilan yang bermanfaat dan relevan bagi masyarakat dan industri.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection