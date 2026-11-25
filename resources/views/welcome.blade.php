@extends('layouts.public-app')
@section('description', 'Selamat datang di SMK Amaliah. Kami memiliki jurusan TKJ, RPL, DKV, dan Akuntansi.')
@section('title', 'SMK Amaliah 1 & 2')

@section('content')
    <style>
        .hero-banner-wrapper {
            position: relative;
            width: 100%;
            height: 420px;
            min-height: 380px;
        }

        @media (min-width: 640px) {
            .hero-banner-wrapper {
                height: 480px;
                min-height: 440px;
            }
        }

        @media (min-width: 1024px) {
            .hero-banner-wrapper {
                height: 550px;
                min-height: 500px;
            }
        }

        /* Aturan ini akan aktif jika lebar layar 768px atau kurang */
        @media (max-width: 768px) {
            .custom-none {
                display: none;
            }
        }

        /* Hero Floating News Card & Watch Video Button */
        .hero-floating-card-wrapper {
            position: absolute;
            bottom: 20px;
            left: 16px;
            z-index: 20;
            width: calc(100% - 32px);
            max-width: 440px;
        }
        @media (min-width: 640px) {
            .hero-floating-card-wrapper {
                bottom: 32px;
                left: 32px;
                width: 380px;
            }
        }
        @media (min-width: 1024px) {
            .hero-floating-card-wrapper {
                bottom: 44px;
                left: 44px;
                width: 440px;
            }
        }
        .hero-watch-video-btn {
            position: absolute;
            top: 16px;
            right: 16px;
            z-index: 20;
        }
        @media (min-width: 640px) {
            .hero-watch-video-btn {
                top: 24px;
                right: 24px;
            }
        }

        /* Major Competency Section Styles */
        .major-card {
            flex-shrink: 0;
            width: 220px;
            margin-right: 4px;
            background-color: #1e1e1f;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 0.875rem;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 8px -1px rgba(0, 0, 0, 0.35);
        }
        @media (min-width: 640px) {
            .major-card {
                width: 250px;
                padding: 1rem;
            }
        }
        .major-card:hover {
            background-color: #242426;
            border-color: rgba(99, 205, 0, 0.4);
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -4px rgba(0, 0, 0, 0.5);
        }
        .major-card-img-wrapper {
            position: relative;
            width: 100%;
            height: 115px;
            border-radius: 0.75rem;
            overflow: hidden;
            margin-bottom: 0.75rem;
            background-color: rgba(0, 0, 0, 0.2);
            flex-shrink: 0;
        }
        @media (min-width: 640px) {
            .major-card-img-wrapper {
                height: 130px;
                margin-bottom: 0.875rem;
            }
        }
        .major-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .major-card:hover .major-card-img {
            transform: scale(1.05);
        }

        @keyframes scrollVertical {
            0% { transform: translateY(0); }
            100% { transform: translateY(-50%); }
        }
        .animate-scroll-vertical {
            animation: scrollVertical 24s linear infinite;
        }
    </style>


<div class="font-['Poppins'] bg-gray-100 pt-3 sm:pt-6 lg:pt-8">
        @php
            $amaliahBlue = '#E0E7FF';
            $defaultBanners = collect([
                (object)[
                    'path' => 'assets/image/DroneView.jpg',
                    'description' => 'Kampus SMK Amaliah 1 & 2 Ciawi',
                    'filename' => 'DroneView.jpg',
                    'is_local_asset' => true,
                ],
                (object)[
                    'path' => 'assets/image/Classmeet.JPG',
                    'description' => 'Aktivitas & Kreativitas Siswa SMK Amaliah',
                    'filename' => 'Classmeet.JPG',
                    'is_local_asset' => true,
                ],
                (object)[
                    'path' => 'assets/image/17Agustus.JPG',
                    'description' => 'Semangat Prestasi SMK Amaliah 1 & 2',
                    'filename' => '17Agustus.JPG',
                    'is_local_asset' => true,
                ],
            ]);
            $bannerSlides = (isset($mainImages) && $mainImages->isNotEmpty()) ? $mainImages : $defaultBanners;
            $hasImages = $bannerSlides->isNotEmpty();
        @endphp
        <main class="mt-2 sm:mt-4">
            <section class="relative max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                <div x-data="{
                        showVideo: false,
                        activeSlide: 1,
                        totalSlides: {{ $bannerSlides->count() }},
                        activeNews: 1,
                        totalNews: {{ $latestNews->count() > 0 ? $latestNews->count() : 1 }}
                    }"
                    x-init="
                        setInterval(() => {
                            if (!showVideo) {
                                activeSlide = activeSlide % totalSlides + 1;
                                activeNews = activeNews % totalNews + 1;
                            }
                        }, 5000);
                    "
                    class="relative">

                    {{-- Main Hero Container --}}
                    <div class="relative hero-banner-wrapper overflow-hidden rounded-2xl sm:rounded-3xl bg-[#282829] shadow-2xl" style="min-height: 380px;">

                        {{-- Background Images (Slider with Fallback) --}}
                        <div x-show="!showVideo" class="w-full h-full relative" style="min-height: 380px;">
                            @foreach($bannerSlides as $loop => $slide)
                                <div x-show="activeSlide === {{ $loop->iteration }}"
                                    x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-105"
                                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-500"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                    class="absolute inset-0"
                                    @if(!$loop->first) style="display: none;" @endif>
                                    @if(!empty($slide->is_local_asset))
                                        <img src="{{ asset($slide->path) }}"
                                            alt="{{ $slide->description ?? 'SMK Amaliah 1 & 2' }}"
                                            class="w-full h-full object-cover"
                                            width="1600" height="900"
                                            @if($loop->first) fetchpriority="high" loading="eager" @else loading="lazy" @endif>
                                    @else
                                        <img src="{{ img_url($slide->path, 1600, 900) }}"
                                            alt="{{ $slide->description ?? $slide->filename }}"
                                            class="w-full h-full object-cover"
                                            width="1600" height="900"
                                            @if($loop->first) fetchpriority="high" loading="eager" @else loading="lazy" @endif>
                                    @endif
                                </div>
                            @endforeach

                            {{-- Floating News Card (Sesuai Desain Asli di Screenshot) --}}
                            @if(isset($latestNews) && $latestNews->isNotEmpty())
                                <div class="hero-floating-card-wrapper"
                                    x-show="!showVideo">

                                    <div class="relative w-full rounded-2xl bg-white/95 backdrop-blur-md shadow-2xl border border-white/60 min-h-[220px] sm:min-h-[200px] overflow-hidden">
                                        @foreach($latestNews as $loop => $news)
                                            <div x-show="activeNews === {{ $loop->iteration }}"
                                                x-transition:enter="transition-opacity ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                class="absolute inset-0 bg-white/95 backdrop-blur-md rounded-2xl p-5 sm:p-6 flex flex-col justify-between"
                                                @if(!$loop->first) style="display: none;" @endif>

                                                <div>
                                                    <h2 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 leading-snug line-clamp-2">
                                                        {{ $news->title }}
                                                    </h2>

                                                    @if(!empty(trim(strip_tags($news->description))) && trim(strip_tags($news->description)) !== '-')
                                                        <p class="text-xs sm:text-sm mt-2 text-gray-600 line-clamp-2 leading-relaxed">
                                                            {{ strip_tags($news->description) }}
                                                        </p>
                                                    @endif
                                                </div>

                                                <div class="mt-3 flex items-center justify-between pt-2 border-t border-gray-100">
                                                    <p class="text-[11px] sm:text-xs font-medium text-gray-500">
                                                        {{ \Carbon\Carbon::parse($news->date_published)->translatedFormat('d F Y') }}
                                                    </p>

                                                    <a href="{{ route('public.news.show', $news) }}"
                                                        class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-white bg-[#282829] hover:bg-black px-4 py-2 rounded-full transition-all duration-300 group shadow-md hover:scale-105">
                                                        <span>Selengkapnya</span>
                                                        <i class="fas fa-arrow-right text-xs transition-transform duration-300 group-hover:translate-x-1"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Tombol "Watch Video" di Pojok Kanan Atas --}}
                            <button @click="showVideo = true"
                                class="hero-watch-video-btn flex items-center gap-1.5 sm:gap-2 bg-black/60 backdrop-blur-md text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-full hover:bg-black/80 transition-all duration-300 text-xs sm:text-sm shadow-lg border border-white/10 cursor-pointer"
                                aria-label="Tonton video profil">
                                <svg xmlns="https://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-[#63cd00]" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                </svg>
                                <span class="font-semibold text-xs sm:text-sm">Watch Video</span>
                            </button>
                        </div>

                        {{-- Kontainer Iframe YouTube (Hanya tampil jika showVideo true) --}}
                        <div x-show="showVideo" x-cloak class="w-full h-full bg-black">
                            <iframe class="w-full h-full"
                                :src="showVideo ? 'https://www.youtube-nocookie.com/embed/STOhZZmY6Co?autoplay=1&mute=1&controls=0&loop=1&playlist=STOhZZmY6Co&rel=0&iv_load_policy=3&modestbranding=1' : ''"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                                loading="lazy">
                            </iframe>

                            {{-- Tombol "Close" untuk Video --}}
                            <button @click="showVideo = false" aria-label="Tutup video"
                                class="absolute top-4 right-4 sm:top-6 sm:right-6 z-20 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 bg-black/70 backdrop-blur-md text-white rounded-full hover:bg-black transition-all duration-300 shadow-lg border border-white/20 cursor-pointer">
                                <svg xmlns="https://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>
            </section>

            {{-- 4 Fitur Utama / Quick Action (Grid 2 Kolom di Handphone) --}}
            <section class="max-w-screen-xl mx-auto px-3 sm:px-6 lg:px-8 mt-5 sm:mt-10 mb-8 sm:mb-16">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-5">

                    @php
                        $fitur = [
                            [
                                'icon' => 'fa-file-lines',
                                'title' => 'SPMB Online',
                                'desc' => 'Daftar secara mudah melalui sistem online kami.',
                                'link' => 'https://ppdb.smkamaliah.sch.id/login',
                                'button_text' => 'Daftar',
                            ],
                            [
                                'icon' => 'fa-chart-simple',
                                'title' => 'E-Learning',
                                'desc' => 'Akses materi dan tugas belajar kapan saja.',
                                'link' => 'https://lms.smkamaliah.sch.id',
                                'button_text' => 'Belajar',
                            ],
                            [
                                'icon' => 'fa-vr-cardboard',
                                'title' => 'Virtual Tour',
                                'desc' => 'Jelajahi setiap sudut dan fasilitas sekolah.',
                                'link' => 'https://yourdisc710.itch.io/amaliah-tour',
                                'button_text' => 'Jelajah',
                            ],
                            [
                                'icon' => 'fa-building-columns',
                                'title' => 'Ujian Online',
                                'desc' => 'Ujian sekolah mudah dan aman via platform kami.',
                                'link' => 'https://play.google.com/store/apps/details?id=com.amexam',
                                'button_text' => 'Ujian',
                            ],
                        ];

                        $amaliahGreen = $amaliahGreen ?? '#63cd00';
                        $amaliahDark = $amaliahDark ?? '#282829';
                    @endphp

                    {{-- Loop untuk menampilkan setiap kartu fitur --}}
                    @foreach ($fitur as $item)
                        <a href="{{ $item['link'] }}"
                            class="group bg-white p-3 sm:p-5 rounded-xl sm:rounded-2xl flex flex-col items-start border-2 border-transparent hover:border-[#63cd00] transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">

                            {{-- Bagian Ikon --}}
                            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl mb-2 sm:mb-3 transition-transform duration-300 group-hover:scale-105" style="background-color: {{ $amaliahGreen }};">
                                <i class="fas {{ $item['icon'] }} text-base sm:text-2xl text-white"></i>
                            </div>

                            {{-- Bagian Teks --}}
                            <h2 class="text-xs sm:text-base font-bold mb-1 line-clamp-1" style="color: {{ $amaliahDark }};">{{ $item['title'] }}</h2>
                            <p class="text-[10px] sm:text-xs text-gray-500 mb-2 sm:mb-3 flex-grow line-clamp-2 leading-relaxed">{{ $item['desc'] }}</p>

                            {{-- Tombol Link dengan teks dinamis --}}
                            <div class="text-[11px] sm:text-xs font-semibold flex items-center justify-between w-full mt-auto pt-1 border-t border-gray-100" style="color: {{ $amaliahGreen }};">
                                <span class="truncate">{{ $item['button_text'] }}</span>
                                <i class="fas fa-chevron-right text-[9px] sm:text-xs transition-transform group-hover:translate-x-1"></i>
                            </div>
                        </a>
                    @endforeach

                </div>
            </section>

            {{-- Bagian Header Judul --}}
            <section class="text-center px-4 sm:px-6 lg:px-8 mb-6 sm:mb-12 fade-in-section">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800">We Have Intelligent Solution For Your Education</h2>
                <div class="flex items-center justify-center gap-x-2 mx-auto mt-3 sm:mt-4">
                    <div class="w-16 sm:w-20 h-1 sm:h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                    <div class="w-3 sm:w-4 h-1 sm:h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                    <div class="w-3 sm:w-4 h-1 sm:h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                </div>
            </section>

            {{-- Bagian Konten Utama (Deskripsi, Tombol, dan Grid Gambar) --}}
            <section class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 mb-12 sm:mb-20 fade-in-section">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                    {{-- Kolom Kiri: Teks dan Tombol --}}
                    <div class="text-gray-600">
                        <p class="text-sm sm:text-base leading-relaxed">
                            SMK Amaliah 1 & 2 merupakan bentuk sekolah kejuruan yang dibawah naungan Yayasan Pusat Studi
                            Pengembangan Islam Amaliyah Indonesia (YPSPIAI) dengan mengutamakan kualitas, Profesionalitas
                            dan Pelayanan Prima dan dibawah pengawasan Universitas Djuanda (UNIDA) berdiri pada tahun 2008.
                        </p>

                        {{-- Wadah untuk Tombol --}}
                        <div class="flex flex-row flex-wrap sm:flex-nowrap items-center gap-3 mt-6 sm:mt-8">
                            <a href="https://ppdb.smkamaliah.sch.id/"
                                class="group inline-flex items-center justify-between text-white pl-4 sm:pl-6 pr-2 py-1.5 sm:py-2 rounded-lg font-semibold shadow-md transition-all duration-300 hover:shadow-lg hover:opacity-90 text-xs sm:text-sm"
                                style="background-color: {{ $amaliahGreen }};">
                                <span class="mr-3 sm:mr-4">Info PPDB</span>
                                <span class="bg-white rounded-full h-7 w-7 sm:h-8 sm:w-8 flex items-center justify-center transition-transform duration-300 group-hover:translate-x-1">
                                    <i class="fas fa-arrow-right text-xs" style="color: {{ $amaliahGreen }};"></i>
                                </span>
                            </a>

                            <a href="{{ route('public.about.index') }}"
                                class="group inline-flex items-center justify-between text-white pl-4 sm:pl-6 pr-2 py-1.5 sm:py-2 rounded-lg font-semibold shadow-md transition-all duration-300 hover:shadow-lg hover:opacity-90 text-xs sm:text-sm"
                                style="background-color: {{ $amaliahGreen }};">
                                <span class="mr-3 sm:mr-4">Tentang Kami</span>
                                <span class="bg-white rounded-full h-7 w-7 sm:h-8 sm:w-8 flex items-center justify-center transition-transform duration-300 group-hover:translate-x-1">
                                    <i class="fas fa-arrow-right text-xs" style="color: {{ $amaliahGreen }};"></i>
                                </span>
                            </a>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Grid Gambar Dinamis dari Database (Dengan Fallback Cantik) --}}
                    @php
                        $fallbackGrid = [
                            'assets/image/Classmeet.JPG',
                            'assets/image/DroneView.jpg',
                            'assets/image/17Agustus.JPG',
                            'assets/image/Classmeet.JPG',
                            'assets/image/DroneView.jpg',
                        ];
                    @endphp
                    <div class="grid grid-cols-3 grid-rows-3 gap-2 sm:gap-4 h-56 sm:h-80 lg:h-96">

                        {{-- Gambar 1 (Slot Paling Kiri, Tinggi) --}}
                        @php $img1 = isset($gridImages[0]) && $gridImages[0]->path ? img_url($gridImages[0]->path, 900, 900) : asset($fallbackGrid[0]); @endphp
                        <img src="{{ $img1 }}" alt="Aktivitas SMK Amaliah 1" loading="lazy" width="900" height="900"
                            class="w-full h-full object-cover rounded-lg sm:rounded-xl row-span-2 shadow-sm">

                        {{-- Gambar 2 (Slot Kanan Atas, Besar) --}}
                        @php $img2 = isset($gridImages[1]) && $gridImages[1]->path ? img_url($gridImages[1]->path, 900, 900) : asset($fallbackGrid[1]); @endphp
                        <img src="{{ $img2 }}" alt="Aktivitas SMK Amaliah 2" loading="lazy" width="900" height="900"
                            class="w-full h-full object-cover rounded-lg sm:rounded-xl col-span-2 row-span-2 shadow-sm">

                        {{-- Gambar 3 (Slot Kiri Bawah) --}}
                        @php $img3 = isset($gridImages[2]) && $gridImages[2]->path ? img_url($gridImages[2]->path, 900, 900) : asset($fallbackGrid[2]); @endphp
                        <img src="{{ $img3 }}" alt="Aktivitas SMK Amaliah 3" loading="lazy" width="900" height="900"
                            class="w-full h-full object-cover rounded-lg sm:rounded-xl shadow-sm">

                        {{-- Gambar 4 (Slot Tengah Bawah) --}}
                        @php $img4 = isset($gridImages[3]) && $gridImages[3]->path ? img_url($gridImages[3]->path, 900, 900) : asset($fallbackGrid[3]); @endphp
                        <img src="{{ $img4 }}" alt="Aktivitas SMK Amaliah 4" loading="lazy" width="900" height="900"
                            class="w-full h-full object-cover rounded-lg sm:rounded-xl shadow-sm">

                        {{-- Gambar 5 (Slot Kanan Bawah) --}}
                        @php $img5 = isset($gridImages[4]) && $gridImages[4]->path ? img_url($gridImages[4]->path, 900, 900) : asset($fallbackGrid[4]); @endphp
                        <img src="{{ $img5 }}" alt="Aktivitas SMK Amaliah 5" loading="lazy" width="900" height="900"
                            class="w-full h-full object-cover rounded-lg sm:rounded-xl shadow-sm">

                    </div>
                </div>
            </section>
            @php
                $amaliahDark = '#282829';

                $statsNumberStudents = ($studentsCount && $studentsCount > 0) ? $studentsCount : '1200';
                $statsNumberTeachers = ($teachersCount && $teachersCount > 0) ? $teachersCount : '60';
                $statsNumberFacilities = ($facilitiesCount && $facilitiesCount > 0) ? $facilitiesCount : '25';

                $stats = [
                    ['icon' => 'fa-users', 'number' => $statsNumberStudents . ' +', 'label' => 'Peserta Didik'],
                    ['icon' => 'fa-rocket', 'number' => $statsNumberTeachers . ' +', 'label' => 'Tenaga Pendidik'],
                    ['icon' => 'fa-star', 'number' => $statsNumberFacilities . ' +', 'label' => 'Fasilitas Unggulan'],
                    ['icon' => 'fa-award', 'number' => 'A+', 'label' => 'Akreditasi'],
                ];
            @endphp
            {{-- SECTION STATS BAR (Grid 2x2 di Handphone, Flex di Desktop) --}}
            <section class="py-8 sm:py-12 lg:py-16 fade-in-section stats-counter-section"
                style="background-color: {{ $amaliahDark }};">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-2 lg:flex lg:flex-row items-center justify-center lg:justify-around gap-4 sm:gap-6 lg:gap-12">

                        @foreach ($stats as $idx => $stat)
                            <div class="flex items-center gap-2.5 sm:gap-4 justify-start">
                                <div class="bg-gray-200/90 flex items-center justify-center h-12 w-12 sm:h-16 sm:w-16 lg:h-20 lg:w-20 rounded-xl sm:rounded-2xl flex-shrink-0 shadow-sm">
                                    <i class="fas {{ $stat['icon'] }} text-lg sm:text-2xl lg:text-3xl" style="color: {{ $amaliahDark }};"></i>
                                </div>
                                <div class="text-white min-w-0">
                                    <p class="text-xl sm:text-3xl lg:text-4xl font-bold whitespace-nowrap count-up"
                                        data-target-value="{{ $stat['number'] }}">{{ $stat['number'] }}</p>
                                    <p class="text-[11px] sm:text-sm text-gray-300 leading-tight">{{ $stat['label'] }}</p>
                                </div>
                            </div>
                            @if(!$loop->last)
                                {{-- PEMISAH DESKTOP --}}
                                <div class="hidden lg:block w-px h-14 bg-white/20"></div>
                            @endif
                        @endforeach

                    </div>
                </div>
            </section>

            <script>
                document.addEventListener("DOMContentLoaded", function () {

                    /**
                     * Fungsi untuk menganimasikan angka dari 0 ke target
                     * @param {HTMLElement} el Elemen <p> yang berisi angka
                     * @param {number} duration Durasi animasi dalam milidetik
                     */
                    function animateCountUp(el, duration = 2000) {
                        const finalString = el.dataset.targetValue;

                        // Ekstrak akhiran non-angka (seperti "+", "K", "jt")
                        const suffix = finalString.match(/[^0-9.-]+$/)?.[0] || '';

                        // Ekstrak angka murni dari string (menghapus format, koma, dll)
                        const targetValue = parseInt(finalString.replace(/[^0-9.-]/g, ''), 10);

                        // Keamanan jika data-target-value tidak valid
                        if (isNaN(targetValue)) {
                            el.textContent = finalString; // Tampilkan teks asli jika bukan angka
                            console.warn("Invalid number for count-up:", finalString, el);
                            return;
                        }

                        el.textContent = '0' + suffix;

                        let startTime = null;

                        // Fungsi 'step' yang dipanggil oleh requestAnimationFrame
                        const step = (timestamp) => {
                            if (!startTime) {
                                startTime = timestamp;
                            }

                            const progress = timestamp - startTime;
                            const percentage = Math.min(progress / duration, 1);

                            // Hitung angka saat ini
                            const currentValue = Math.floor(percentage * targetValue);

                            // Format angka dengan pemisah ribuan (misal: 1.000) dan tambahkan akhiran
                            el.textContent = currentValue.toLocaleString('id-ID') + suffix;

                            // Lanjutkan animasi jika belum selesai
                            if (percentage < 1) {
                                requestAnimationFrame(step);
                            } else {
                                // Selesai: pastikan angka final dan formatnya akurat
                                el.textContent = targetValue.toLocaleString('id-ID') + suffix;
                            }
                        };

                        // Mulai animasi
                        requestAnimationFrame(step);
                    }

                    // --- Logika IntersectionObserver ---

                    // Pilih section yang akan memicu animasi
                    const counterSection = document.querySelector('.stats-counter-section');

                    if (counterSection) {
                        const options = {
                            root: null,
                            threshold: 0.1 // Memicu saat 10% section terlihat
                        };

                        const callback = (entries, observer) => {
                            entries.forEach(entry => {
                                // Jika section masuk ke viewport
                                if (entry.isIntersecting) {

                                    // 1. Temukan semua elemen .count-up di dalam section itu
                                    const counters = entry.target.querySelectorAll('.count-up');

                                    // 2. Jalankan animasi untuk setiap elemen
                                    counters.forEach(counter => {
                                        animateCountUp(counter, 2000); // Durasi 2 detik
                                    });

                                    // 3. Berhenti mengamati section ini agar animasi tidak berulang
                                    observer.unobserve(entry.target);
                                }
                            });
                        };

                        // Buat dan jalankan observer
                        const observer = new IntersectionObserver(callback, options);
                        observer.observe(counterSection);
                    }
                });
            </script>
            <section class="bg-white py-8 sm:py-16 lg:py-20 fade-in-section">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center">

                        {{-- Kolom Kiri: Teks & Tombol --}}
                        <div class="text-left">
                            <h2 class="text-2xl sm:text-4xl md:text-5xl font-bold" style="color: {{ $amaliahDark }};">
                                Here Is Our<br>Industry Partner
                            </h2>

                            <div class="flex items-center gap-x-2 mt-3 sm:mt-4">
                                <div class="w-16 sm:w-20 h-1 sm:h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                                <div class="w-3 sm:w-4 h-1 sm:h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                                <div class="w-3 sm:w-4 h-1 sm:h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                            </div>

                            <p class="mt-4 sm:mt-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                                We cooperate with industry leaders to provide students with real-world experience through
                                internships, industrial visits, training, and career opportunities after graduation.
                            </p>

                            <a href="{{ route('public.partners.index') }}"
                                class="group mt-6 sm:mt-8 inline-flex items-center text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-lg font-semibold shadow-md group/button transition-opacity duration-300 hover:opacity-90 text-sm sm:text-base"
                                style="background-color: {{ $amaliahGreen }};">
                                <span class="mr-3 sm:mr-4">Selengkapnya</span>
                                <div class="bg-white rounded-full p-1.5 sm:p-2 flex items-center justify-center transition-transform duration-300 group-hover:translate-x-1">
                                    <i class="fas fa-arrow-right text-xs sm:text-base" style="color: {{ $amaliahGreen }};"></i>
                                </div>
                            </a>
                        </div>

                        {{-- Kolom Kanan: Grid Logo Mitra dengan Animasi Scroll --}}
                        <div class="group relative h-64 sm:h-80 lg:h-[28rem] overflow-hidden">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 sm:gap-x-8 gap-y-4 sm:gap-y-8 animate-scroll-vertical group-hover:[animation-play-state:paused]">

                                {{-- Loop data dari controller (DUPLIKASI 1) --}}
                                @forelse ($partners as $partner)
                                    <div class="text-center">
                                        <div class="bg-white h-16 sm:h-24 w-full rounded-xl mb-2 sm:mb-3 flex items-center justify-center p-2.5 sm:p-3 border border-gray-200 shadow-sm hover:border-[#63cd00] transition-colors">
                                            @if (!empty($partner->logo))
                                                <img src="{{ img_url($partner->logo, 160, 90) }}" alt="Logo {{ $partner->name }}" loading="lazy" width="160" height="90"
                                                    class="max-h-full max-w-full object-contain">
                                            @else
                                                <span class="text-[10px] sm:text-xs font-bold text-gray-700 line-clamp-2 uppercase tracking-tight text-center px-1">{{ $partner->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-2 md:col-span-4 text-center">
                                        <p class="text-gray-500 text-xs sm:text-sm">Belum ada mitra yang ditambahkan.</p>
                                    </div>
                                @endforelse

                                {{-- Loop data dari controller (DUPLIKASI 2 - Untuk Efek Mulus) --}}
                                @forelse ($partners as $partner)
                                    <div class="text-center">
                                        <div class="bg-white h-16 sm:h-24 w-full rounded-xl mb-2 sm:mb-3 flex items-center justify-center p-2.5 sm:p-3 border border-gray-200 shadow-sm hover:border-[#63cd00] transition-colors">
                                            @if (!empty($partner->logo))
                                                <img src="{{ img_url($partner->logo, 160, 90) }}" alt="Logo {{ $partner->name }}" loading="lazy" width="160" height="90"
                                                    class="max-h-full max-w-full object-contain">
                                            @else
                                                <span class="text-[10px] sm:text-xs font-bold text-gray-700 line-clamp-2 uppercase tracking-tight text-center px-1">{{ $partner->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                            {{-- Efek fade di bagian bawah --}}
                            <div class="absolute bottom-0 left-0 w-full h-16 sm:h-24 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
                        </div>

                    </div>
                </div>
            </section>

            {{-- ============================ PIMPINAN SEKOLAH (PERSIS SEPERTI GAMBAR DENGAN RESPONSIF & FLUID ANIMATION) ============================ --}}
            @php
                $leaderImg = function ($leader, $fallback) {
                    if ($leader && $leader->image) {
                        return str_starts_with($leader->image, 'uploads/')
                            ? asset('storage/' . $leader->image)
                            : (str_starts_with($leader->image, 'http') ? $leader->image : asset($leader->image));
                    }
                    return asset($fallback);
                };

                $leader1 = $leaders->where('school', 'Amaliah 1')->first();
                $leader2 = $leaders->where('school', 'Amaliah 2')->first();

                $profiles = [
                    [
                        'tab' => 'amaliah1',
                        'name' => $leader1->name ?? 'Isma Tri Pamungkas, S.Pd., Gr',
                        'position' => $leader1->position ?? 'Kepala Sekolah SMK Amaliah 1',
                        'quote' => !empty($leader1->quote) ? $leader1->quote : 'Pendidikan vokasi sejati bukan hanya tentang mencetak tangan yang terampil dan otak yang cerdas, melainkan juga menempa hati yang berakhlak mulia serta berpegang teguh pada nilai-nilai tauhid',
                        'image' => $leaderImg($leader1, 'assets/image/kepsek1.webp'),
                        'facebook' => $leader1->facebook_url ?: 'https://www.facebook.com/',
                        'instagram' => $leader1->instagram_url ?: 'https://www.instagram.com/',
                        'linkedin' => $leader1->linkedin_url ?: 'https://id.linkedin.com/',
                    ],
                    [
                        'tab' => 'amaliah2',
                        'name' => $leader2->name ?? 'Dr. Gugun Gunadi, S.Pd.I., M.Pd.',
                        'position' => $leader2->position ?? 'Kepala Sekolah SMK Amaliah 2',
                        'quote' => !empty($leader2->quote) ? $leader2->quote : 'Sekolah adalah rumah untuk tumbuh — di sini kami membimbing peserta didik tidak hanya menguasai kompetensi kerja, tetapi juga membentuk akhlak dan sikap profesional.',
                        'image' => $leaderImg($leader2, 'assets/image/kepsek2.webp'),
                        'facebook' => $leader2->facebook_url ?: 'https://www.facebook.com/',
                        'instagram' => $leader2->instagram_url ?: 'https://www.instagram.com/',
                        'linkedin' => $leader2->linkedin_url ?: 'https://id.linkedin.com/',
                    ],
                ];
            @endphp

            @php
                $hasBothLeaders = $leaders->where('school', 'Amaliah 1')->isNotEmpty()
                    && $leaders->where('school', 'Amaliah 2')->isNotEmpty();
            @endphp
            <section class="bg-[#282829] py-16 sm:py-20 overflow-hidden fade-in-section">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8"
                    x-data="{
                        activeTab: 'amaliah1',
                        autoRotate: {{ $hasBothLeaders ? 'true' : 'false' }},
                        timer: null,
                        init() {
                            if (this.autoRotate) {
                                this.timer = setInterval(() => {
                                    if (this.autoRotate) {
                                        this.activeTab = this.activeTab === 'amaliah1' ? 'amaliah2' : 'amaliah1';
                                    }
                                }, 5000);
                            }
                        },
                        setTab(tab) {
                            this.activeTab = tab;
                            if (this.timer) {
                                clearInterval(this.timer);
                                this.timer = null;
                            }
                        }
                    }"
                    @mouseenter="autoRotate = false"
                    @mouseleave="autoRotate = true"
                    @touchstart.passive="autoRotate = false"
                    @touchend.passive="setTimeout(() => autoRotate = true, 3000)">

                    {{-- Bagian Header: Judul dan Tombol Tab (Sesuai Gambar Asli) --}}
                    <div class="text-center mb-6 sm:mb-12">
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight">
                            Get To Know Our School Leaders
                        </h2>
                        <div class="flex justify-center items-center space-x-2 mt-5 sm:mt-8">
                            <button type="button"
                                @click="setTab('amaliah1')"
                                :class="activeTab === 'amaliah1' ? 'bg-[#63cd00] text-white shadow-lg' : 'bg-white text-[#282829] hover:bg-gray-200'"
                                class="px-4 sm:px-5 py-2 text-xs sm:text-sm font-semibold rounded-full transition-all duration-300 cursor-pointer"
                                aria-label="SMK Amaliah 1">
                                SMK Amaliah 1
                            </button>
                            <button type="button"
                                @click="setTab('amaliah2')"
                                :class="activeTab === 'amaliah2' ? 'bg-[#63cd00] text-white shadow-lg' : 'bg-white text-[#282829] hover:bg-gray-200'"
                                class="px-4 sm:px-5 py-2 text-xs sm:text-sm font-semibold rounded-full transition-all duration-300 cursor-pointer"
                                aria-label="SMK Amaliah 2">
                                SMK Amaliah 2
                            </button>
                        </div>
                    </div>

                    {{-- Konten: Fluid Stacking Grid dengan Tata Letak Responsif --}}
                    <div class="relative grid grid-cols-1 grid-rows-1 items-center mt-8 sm:mt-12 lg:mt-16 min-h-[460px] sm:min-h-[380px] lg:min-h-[320px]">
                        @foreach ($profiles as $profile)
                            <div x-show="activeTab === '{{ $profile['tab'] }}'"
                                x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 transform translate-x-4"
                                x-transition:enter-end="opacity-100 transform translate-x-0"
                                x-transition:leave="transition ease-in duration-300"
                                x-transition:leave-start="opacity-100 transform translate-x-0"
                                x-transition:leave-end="opacity-0 transform -translate-x-4"
                                class="col-start-1 row-start-1 w-full grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-12 items-center">

                                {{-- FOTO: Pada Mobile Tampil di Bagian Atas Nama (order-1), Pada Desktop di Kolom Kanan (lg:order-2) --}}
                                <div class="order-1 lg:order-2 flex justify-center items-center">
                                    <div class="w-44 h-44 sm:w-64 sm:h-64 lg:w-80 lg:h-80 rounded-full shadow-2xl overflow-hidden flex items-center justify-center bg-transparent">
                                        <img src="{{ $profile['image'] }}"
                                            alt="{{ $profile['name'] }}"
                                            class="w-full h-full object-cover rounded-full"
                                            loading="lazy">
                                    </div>
                                </div>

                                {{-- TEKS: Pada Mobile di Bawah Foto (order-2, text-center), Pada Desktop di Kolom Kiri (lg:order-1, lg:text-left) --}}
                                <div class="order-2 lg:order-1 text-center lg:text-left">
                                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white tracking-normal">
                                        {{ $profile['name'] }}
                                    </h3>
                                    <p class="text-sm sm:text-base text-[#63cd00] mt-1 mb-3 sm:mb-4 font-semibold">
                                        {{ $profile['position'] }}
                                    </p>
                                    @if (!empty($profile['quote']))
                                        <blockquote class="text-xs sm:text-base text-gray-300 italic max-w-lg mx-auto lg:mx-0 leading-relaxed font-normal">
                                            "{!! nl2br(e(trim($profile['quote'], ' "“”'))) !!}"
                                        </blockquote>
                                    @endif
                                    <div class="flex items-center space-x-3 mt-5 sm:mt-6 justify-center lg:justify-start">
                                        <a href="{{ $profile['facebook'] }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-700 hover:bg-gray-600 text-white transition-colors">
                                            <i class="fab fa-facebook-f text-sm"></i>
                                        </a>
                                        <a href="{{ $profile['instagram'] }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-700 hover:bg-gray-600 text-white transition-colors">
                                            <i class="fab fa-instagram text-sm"></i>
                                        </a>
                                        <a href="{{ $profile['linkedin'] }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-700 hover:bg-gray-600 text-white transition-colors">
                                            <i class="fab fa-linkedin-in text-sm"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>
            </section>

            <section class="py-6 sm:py-10 lg:py-14 fade-in-section overflow-hidden" style="background-color: {{ $amaliahDark }};">
                <div x-data="{
                        timer: null,
                        isPaused: false,
                        init() {
                            this.startAutoScroll();
                        },
                        startAutoScroll() {
                            this.stopAutoScroll();
                            this.timer = setInterval(() => {
                                if (!this.isPaused) {
                                    this.scrollSlider('next');
                                }
                            }, 3500);
                        },
                        stopAutoScroll() {
                            if (this.timer) {
                                clearInterval(this.timer);
                                this.timer = null;
                            }
                        },
                        pause() {
                            this.isPaused = true;
                        },
                        resume() {
                            this.isPaused = false;
                        },
                        scrollSlider(direction) {
                            const slider = this.$refs.slider;
                            if (!slider) return;
                            const firstItem = slider.querySelector('.slider-item');
                            const step = firstItem ? firstItem.offsetWidth + 24 : 250;
                            const maxScrollLeft = slider.scrollWidth - slider.clientWidth;

                            if (direction === 'next') {
                                if (slider.scrollLeft >= maxScrollLeft - 10) {
                                    slider.scrollTo({ left: 0, behavior: 'smooth' });
                                } else {
                                    slider.scrollBy({ left: step, behavior: 'smooth' });
                                }
                            } else {
                                if (slider.scrollLeft <= 10) {
                                    slider.scrollTo({ left: maxScrollLeft, behavior: 'smooth' });
                                } else {
                                    slider.scrollBy({ left: -step, behavior: 'smooth' });
                                }
                            }
                        }
                    }"
                    @mouseenter="pause()"
                    @mouseleave="resume()"
                    @touchstart.passive="pause()"
                    @touchend.passive="setTimeout(() => resume(), 2500)"
                    class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 relative">

                    {{-- Dekorasi Titik --}}
                    <div class="absolute top-6 left-8 md:left-12 flex items-center space-x-2 custom-none">
                        <div class="w-2.5 h-2.5 bg-gray-600 rounded-full"></div>
                        <div class="w-2.5 h-2.5 bg-gray-600 rounded-full"></div>
                        <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                    </div>

                    {{-- Header Section --}}
                    <div class="text-center">
                        <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-white">Major Competency</h2>
                        <p class="mt-1 text-xs sm:text-sm text-gray-400">Program Keahlian Di SMK Amaliah 1 &amp; 2</p>
                        <div class="w-16 sm:w-20 h-px bg-gray-600 mx-auto mt-2.5 sm:mt-3"></div>
                    </div>

                    {{-- Layout Grid Utama (Statis Kiri, Scroll Kanan) --}}
                    <div class="mt-4 sm:mt-8 grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-6">

                        {{-- KOLOM 1: KONTEN STATIS (TIDAK IKUT SCROLL) --}}
                        <div class="lg:col-span-1 hidden lg:flex flex-col justify-between">
                            {{-- Gambar Grid Jurusan Dinamis --}}
                            <div class="flex flex-col space-y-3">
                                {{-- Gambar 1 --}}
                                @php
                                    $leftImg1 = (isset($majorGridImages[0]) && $majorGridImages[0]->path)
                                        ? img_url($majorGridImages[0]->path, 900, 375)
                                        : asset('assets/image/Classmeet.JPG');
                                @endphp
                                <img src="{{ $leftImg1 }}" loading="lazy" width="900" height="375"
                                    alt="{{ $majorGridImages[0]->description ?? 'Aktivitas Jurusan SMK Amaliah' }}"
                                    class="h-32 sm:h-36 w-full rounded-xl object-cover border border-white/10 shadow-sm">

                                {{-- Gambar 2 --}}
                                @php
                                    $leftImg2 = (isset($majorGridImages[1]) && $majorGridImages[1]->path)
                                        ? img_url($majorGridImages[1]->path, 900, 375)
                                        : asset('assets/image/17Agustus.JPG');
                                @endphp
                                <img src="{{ $leftImg2 }}" loading="lazy" width="900" height="375"
                                    alt="{{ $majorGridImages[1]->description ?? 'Prestasi Jurusan SMK Amaliah' }}"
                                    class="h-32 sm:h-36 w-full rounded-xl object-cover border border-white/10 shadow-sm">
                            </div>
                            {{-- Tombol Navigasi Statis --}}
                            <div class="mt-4 flex items-center space-x-2.5">
                                <button @click="scrollSlider('prev')"
                                    class="bg-[#1e1e1f] hover:bg-[#63cd00] text-white hover:text-black border border-white/10 w-9 h-9 rounded-lg flex items-center justify-center transition-all duration-300 shadow-sm hover:scale-105 active:scale-95 cursor-pointer"
                                    aria-label="Sebelumnya">
                                    <i class="fas fa-chevron-left text-xs"></i>
                                </button>
                                <button @click="scrollSlider('next')"
                                    class="bg-[#1e1e1f] hover:bg-[#63cd00] text-white hover:text-black border border-white/10 w-9 h-9 rounded-lg flex items-center justify-center transition-all duration-300 shadow-sm hover:scale-105 active:scale-95 cursor-pointer"
                                    aria-label="Selanjutnya">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </button>
                            </div>
                        </div>

                        @php
                            use Illuminate\Support\Str;
                        @endphp

                        {{-- KOLOM 2: KONTEN SLIDER (BISA DI-SCROLL & AUTO SCROLL) --}}
                        <div x-ref="slider"
                            class="lg:col-span-3 flex gap-5 sm:gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth scrollbar-hide py-2 pb-6 -mx-4 pl-4 pr-8 sm:mx-0 sm:px-0 sm:pr-2">

                            @foreach ($majors as $major)
                                <div class="major-card slider-item group snap-start">

                                    @php
                                        $majorDefaultImages = [
                                            'assets/image/Classmeet.JPG',
                                            'assets/image/DroneView.jpg',
                                            'assets/image/17Agustus.JPG',
                                        ];
                                        $majorImg = $major->image ? img_url($major->image, 700, 375) : asset($majorDefaultImages[$loop->index % count($majorDefaultImages)]);
                                    @endphp
                                    <div class="major-card-img-wrapper">
                                        <img src="{{ $majorImg }}" alt="Gambar Jurusan {{ $major->name }}" loading="lazy" width="700" height="375"
                                            class="major-card-img">
                                        @if($major->tag)
                                            <span class="absolute top-2 right-2 bg-black/70 backdrop-blur-md text-[9px] font-semibold text-[#63cd00] px-2 py-0.5 rounded-full border border-white/10">
                                                {{ $major->tag }}
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-sm sm:text-base font-bold text-white group-hover:text-[#63cd00] transition-colors line-clamp-1">
                                        {{ $major->name }}
                                    </h3>

                                    <p class="text-[11px] sm:text-xs text-gray-300/80 leading-relaxed mt-1 flex-grow line-clamp-2">
                                        {{ Str::limit(strip_tags($major->description), 85) }}
                                    </p>

                                    <a href="{{ route('public.majors.show', $major) }}"
                                        class="mt-3 pt-2.5 border-t border-white/10 flex items-center justify-between group/btn text-[11px] sm:text-xs">
                                        <span class="font-semibold text-white group-hover:text-[#63cd00] transition-colors">
                                            Selengkapnya
                                        </span>
                                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-white/10 group-hover:bg-[#63cd00] flex items-center justify-center transition-all duration-300 text-white group-hover:text-black">
                                            <i class="fas fa-arrow-right text-[10px] transition-transform duration-300 group-hover/btn:-rotate-45"></i>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                            {{-- Spacer Margin Kanan untuk Mobile & Desktop agar kartu terakhir tidak menempel ke border --}}
                            <div class="flex-shrink-0 w-6 sm:w-8 pointer-events-none" aria-hidden="true"></div>
                        </div>

                        {{-- Tombol Navigasi (Hanya tampil di mobile) --}}
                        <div class="lg:hidden mt-2 flex items-center justify-center space-x-2.5">
                            <button @click="scrollSlider('prev')"
                                class="bg-[#1e1e1f] hover:bg-[#63cd00] text-white hover:text-black border border-white/10 w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 shadow-sm active:scale-95"
                                aria-label="Geser ke kiri">
                                <i class="fas fa-chevron-left text-[11px]"></i>
                            </button>
                            <button @click="scrollSlider('next')"
                                class="bg-[#1e1e1f] hover:bg-[#63cd00] text-white hover:text-black border border-white/10 w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 shadow-sm active:scale-95"
                                aria-label="Geser ke kanan">
                                <i class="fas fa-chevron-right text-[11px]"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </section>
                        <section class="bg-gray-50 py-8 sm:py-16 lg:py-20 fade-in-section">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                    {{-- Header Section --}}
                    <div class="text-center">
                        <div class="flex space-x-2 justify-center mb-3 sm:mb-4">
                            <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                            <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                            <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                        </div>

                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold" style="color: {{ $amaliahDark }};">
                            Baca Berita Terbaru Kami
                        </h2>
                        <p class="mt-1.5 sm:mt-2 text-xs sm:text-base text-gray-500">
                            Read our latest news, and know about smk amaliah
                        </p>
                    </div>

                    <div class="mt-6 sm:mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                        @php
                            $newsFallbackImage = asset('assets/image/DroneView.jpg');
                        @endphp
                        @forelse ($latestNews as $newsItem)
                            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 flex flex-row sm:flex-col border border-gray-100">
                                <a href="{{ route('public.news.show', $newsItem->id) }}" class="flex flex-row sm:flex-col w-full">
                                    @php
                                        $newsCover = $newsItem->image ? img_url($newsItem->image, 900, 520) : $newsFallbackImage;
                                    @endphp
                                    <img src="{{ $newsCover }}" alt="{{ $newsItem->title }}" loading="lazy" width="900" height="520"
                                        onerror="this.onerror=null;this.src='{{ $newsFallbackImage }}';"
                                        class="w-24 h-24 sm:w-full sm:h-48 object-cover flex-shrink-0">

                                    <div class="p-3 sm:p-5 flex flex-col justify-between flex-grow min-w-0">
                                        <p class="text-gray-800 font-semibold text-xs sm:text-base leading-snug line-clamp-2 sm:line-clamp-3">
                                            {{ $newsItem->title }}
                                        </p>
                                        <p class="mt-2 text-[10px] sm:text-xs text-gray-400">
                                            <i class="fa-regular fa-calendar-days mr-1 text-[9px]"></i>
                                            {{ \Carbon\Carbon::parse($newsItem->date_published)->translatedFormat('d F Y') }}
                                        </p>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="col-span-1 sm:col-span-2 lg:col-span-4 text-center py-8">
                                <p class="text-gray-500 text-xs sm:text-sm">Belum ada berita untuk ditampilkan.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Tombol "Lihat Lebih Banyak" --}}
                    <div class="text-center mt-6 sm:mt-10">
                        <a href="{{ route('public.news.index') }}"
                            class="inline-block bg-white border border-gray-200 rounded-full px-5 sm:px-6 py-2.5 sm:py-3.5 text-xs sm:text-sm font-semibold shadow-xs hover:shadow-md hover:border-gray-300 transition-all duration-300">
                            <span class="text-gray-600">Mau Baca Lebih Banyak?</span>
                            <span class="ml-1 font-bold" style="color: {{ $amaliahGreen }};">Ayo Kesini</span>
                            <i class="fas fa-chevron-right ml-1.5 text-[10px] sm:text-xs" style="color: {{ $amaliahGreen }};"></i>
                        </a>
                    </div>
                </div>
            </section>

                        <section class="bg-[#ffffff] py-8 sm:py-14 mt-0 sm:mt-[-40px] fade-in-section">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                    <!-- Judul dan Deskripsi Section -->
                    <div class="max-w-3xl mx-auto text-center">
                        <h2 class="text-2xl font-bold text-gray-900 sm:text-4xl">
                            Discover Our Story
                        </h2>
                        <p class="mt-2 sm:mt-4 text-xs sm:text-base text-gray-600 leading-relaxed">
                            Watch the video below to get a glimpse into our values, mission, and the people behind our success.
                        </p>
                    </div>

                    <!-- Kontainer Video Responsif 16:9 -->
                    <div class="mt-6 sm:mt-10 max-w-4xl mx-auto">
                        <div class="relative w-full rounded-xl sm:rounded-2xl overflow-hidden shadow-xl" style="padding-top: 56.25%;">
                            <iframe class="absolute top-0 left-0 w-full h-full border-0"
                                src="https://www.youtube-nocookie.com/embed/STOhZZmY6Co?si=34QAmdyIwXbAXs-7&amp;controls=0"
                                loading="lazy" title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                            </iframe>
                        </div>
                    </div>

                </div>
            </section>
                        <section class="py-8 sm:py-16 lg:py-20 fade-in-section overflow-hidden" style="background-color: {{ $amaliahDark }};">
                {{-- Container Utama --}}
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 relative">

                    {{-- Dekorasi Titik --}}
                    <div class="absolute top-8 left-8 md:left-12 flex items-center space-x-2 custom-none">
                        <div class="w-3 h-3 bg-gray-600 rounded-full"></div>
                        <div class="w-3 h-3 bg-gray-600 rounded-full"></div>
                        <div class="w-3 h-3 bg-white rounded-full"></div>
                    </div>

                    {{-- Header Section --}}
                    <div class="text-center">
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white">Fasilitas</h2>
                        <p class="mt-1.5 sm:mt-2 text-xs sm:text-base text-gray-400">Get To Know About Our Facilities</p>
                        <div class="w-20 sm:w-24 h-px bg-gray-600 mx-auto mt-3 sm:mt-4"></div>
                    </div>

                    {{-- Galeri Gambar Mozaik Dinamis Responsif (Grid 2 Kolom di Handphone) --}}
                    @php
                        $facilityFallbacks = [
                            'assets/image/Classmeet.JPG',
                            'assets/image/DroneView.jpg',
                            'assets/image/17Agustus.JPG',
                            'assets/image/Classmeet.JPG',
                            'assets/image/DroneView.jpg',
                        ];
                        $resolveFacilityImg = function ($facility, $fallbackPath) {
                            if ($facility && !empty($facility->image)) {
                                $path = $facility->image;
                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path) || file_exists(public_path($path))) {
                                    return img_url($path, 1000, 625);
                                }
                            }
                            return asset($fallbackPath);
                        };
                    @endphp
                    <div class="mt-6 sm:mt-10 w-full grid grid-cols-2 md:grid-cols-4 md:grid-rows-2 gap-2 sm:gap-4 h-auto md:h-[32rem]">

                        {{-- Gambar 1 (Hero di mobile, tinggi di desktop) --}}
                        <div class="col-span-2 sm:col-span-1 md:row-span-2 h-44 sm:h-56 md:h-full rounded-xl overflow-hidden shadow-sm">
                            @php
                                $f1 = $resolveFacilityImg($facilities[0] ?? null, $facilityFallbacks[0]);
                            @endphp
                            <img src="{{ $f1 }}" alt="{{ $facilities[0]->name ?? 'Fasilitas 1' }}" loading="lazy" width="1000" height="625"
                                onerror="this.onerror=null;this.src='{{ asset($facilityFallbacks[0]) }}';"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>

                        {{-- Gambar 2 --}}
                        <div class="col-span-1 md:row-span-1 h-32 sm:h-48 md:h-full rounded-xl overflow-hidden shadow-sm">
                            @php
                                $f2 = $resolveFacilityImg($facilities[1] ?? null, $facilityFallbacks[1]);
                            @endphp
                            <img src="{{ $f2 }}" alt="{{ $facilities[1]->name ?? 'Fasilitas 2' }}" loading="lazy" width="1000" height="625"
                                onerror="this.onerror=null;this.src='{{ asset($facilityFallbacks[1]) }}';"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>

                        {{-- Gambar 3 --}}
                        <div class="col-span-1 md:col-span-2 md:row-span-1 h-32 sm:h-48 md:h-full rounded-xl overflow-hidden shadow-sm">
                            @php
                                $f3 = $resolveFacilityImg($facilities[2] ?? null, $facilityFallbacks[2]);
                            @endphp
                            <img src="{{ $f3 }}" alt="{{ $facilities[2]->name ?? 'Fasilitas 3' }}" loading="lazy" width="1000" height="625"
                                onerror="this.onerror=null;this.src='{{ asset($facilityFallbacks[2]) }}';"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>

                        {{-- Gambar 4 --}}
                        <div class="col-span-1 md:row-span-1 h-32 sm:h-48 md:h-full rounded-xl overflow-hidden shadow-sm">
                            @php
                                $f4 = $resolveFacilityImg($facilities[3] ?? null, $facilityFallbacks[3]);
                            @endphp
                            <img src="{{ $f4 }}" alt="{{ $facilities[3]->name ?? 'Fasilitas 4' }}" loading="lazy" width="1000" height="625"
                                onerror="this.onerror=null;this.src='{{ asset($facilityFallbacks[3]) }}';"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>

                        {{-- Gambar 5 --}}
                        <div class="col-span-1 md:col-span-2 md:row-span-1 h-32 sm:h-48 md:h-full rounded-xl overflow-hidden shadow-sm">
                            @php
                                $f5 = $resolveFacilityImg($facilities[4] ?? null, $facilityFallbacks[4]);
                            @endphp
                            <img src="{{ $f5 }}" alt="{{ $facilities[4]->name ?? 'Fasilitas 5' }}" loading="lazy" width="1000" height="625"
                                onerror="this.onerror=null;this.src='{{ asset($facilityFallbacks[4]) }}';"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>
                    </div>

                    {{-- Tombol Selengkapnya --}}
                    <div class="text-right mt-4 sm:mt-6">
                        <a href="{{ route('public.facilities.index') }}" class="inline-flex items-center group text-xs sm:text-sm">
                            <span class="font-semibold text-white mr-2 sm:mr-3">Selengkapnya</span>
                            <div class="bg-gray-200 rounded-full p-1.5 sm:p-2 group-hover:bg-gray-300 transition-transform duration-300 group-hover:translate-x-1">
                                <i class="fas fa-arrow-right text-gray-800 text-xs"></i>
                            </div>
                        </a>
                    </div>

                </div>
            </section>
            {{-- CSS Tambahan untuk menyembunyikan scrollbar --}}
            <style>
                .scrollbar-hide::-webkit-scrollbar {
                    display: none;
                }

                .scrollbar-hide {
                    -ms-overflow-style: none;
                    scrollbar-width: none;
                }
            </style>

                        <section class="bg-gray-50 py-8 sm:py-16 lg:py-20 fade-in-section">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                    {{-- Header Section --}}
                    <div class="text-center">
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold" style="color: {{ $amaliahDark }};">Testimoni</h2>
                        <div class="flex items-center justify-center gap-x-2 mx-auto mt-3 sm:mt-4">
                            <div class="w-16 sm:w-20 h-1 sm:h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                            <div class="w-3 sm:w-4 h-1 sm:h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                            <div class="w-3 sm:w-4 h-1 sm:h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                        </div>
                    </div>

                    {{-- Slider Testimoni: Satu Kartu + Swipe Mobile + Otomatis (Alpine.js) --}}
                    <div class="mx-auto max-w-3xl mt-6 sm:mt-10" x-data="{
                            index: 0,
                            total: {{ $testimonials->count() }},
                            timer: null,
                            touchStartX: 0,
                            touchEndX: 0,
                            init() {
                                this.startTimer();
                            },
                            destroy() {
                                this.stopTimer();
                            },
                            startTimer() {
                                if (this.total > 1 && !this.timer) {
                                    this.timer = setInterval(() => this.next(), 6000);
                                }
                            },
                            stopTimer() {
                                if (this.timer) {
                                    clearInterval(this.timer);
                                    this.timer = null;
                                }
                            },
                            next() {
                                if (this.total <= 1) return;
                                this.index = (this.index + 1) % this.total;
                            },
                            prev() {
                                if (this.total <= 1) return;
                                this.index = (this.index - 1 + this.total) % this.total;
                            },
                            go(i) {
                                this.index = i;
                            },
                            handleTouchStart(e) {
                                this.stopTimer();
                                this.touchStartX = e.changedTouches[0].screenX;
                            },
                            handleTouchEnd(e) {
                                this.touchEndX = e.changedTouches[0].screenX;
                                const diff = this.touchStartX - this.touchEndX;
                                if (Math.abs(diff) > 40) {
                                    if (diff > 0) {
                                        this.next();
                                    } else {
                                        this.prev();
                                    }
                                }
                                setTimeout(() => this.startTimer(), 3500);
                            }
                        }"
                        @mouseenter="stopTimer()"
                        @mouseleave="startTimer()"
                        @touchstart.passive="handleTouchStart($event)"
                        @touchend.passive="handleTouchEnd($event)">

                        <div x-cloak class="relative h-[250px] sm:h-[280px]">
                            @forelse ($testimonials as $i => $t)
                                <div x-show="index === {{ $i }}"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-3"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="bg-white border border-gray-200 rounded-xl sm:rounded-2xl shadow-sm px-6 py-5 sm:px-12 sm:py-7 text-center h-[250px] sm:h-[280px] flex flex-col justify-between select-none">
                                    <div class="ts-quote text-[#63cd00] text-2xl sm:text-3xl flex-shrink-0"><i class="fas fa-quote-left"></i></div>
                                    <div class="flex-1 flex items-center justify-center my-auto px-1 sm:px-4">
                                        <p class="text-gray-700 text-xs sm:text-base leading-relaxed italic line-clamp-4">"{{ $t->description }}"</p>
                                    </div>
                                    <div class="flex items-center justify-center gap-2.5 sm:gap-4 flex-shrink-0 pt-1">
                                        @if ($t->photo)
                                            <img src="{{ img_url($t->photo, 96, 96) }}" alt="{{ $t->name }}" loading="lazy" width="96" height="96"
                                                class="w-10 h-10 sm:w-14 sm:h-14 rounded-full object-cover shadow-sm flex-shrink-0">
                                        @else
                                            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full flex items-center justify-center text-white font-bold text-xs sm:text-sm flex-shrink-0"
                                                style="background-color: {{ $amaliahGreen }}">{{ collect(explode(' ', $t->name))->take(2)->map(fn($w) => strtoupper(mb_substr($w, 0, 1)))->implode('') }}</div>
                                        @endif
                                        <div class="text-left min-w-0">
                                            <p class="font-bold text-gray-900 text-xs sm:text-base leading-tight truncate">{{ $t->name }}</p>
                                            <p class="text-[10px] sm:text-xs truncate" style="color: {{ $amaliahGreen }};">
                                                Alumni {{ $t->major->name ?? 'N/A' }} · {{ $t->alumni_year }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white border border-gray-200 rounded-xl sm:rounded-2xl shadow-sm p-6 text-center text-gray-400 text-xs sm:text-sm h-[250px] sm:h-[280px] flex items-center justify-center">
                                    Belum ada testimoni.
                                </div>
                            @endforelse

                            {{-- Tombol Panah Kiri / Kanan --}}
                            @if ($testimonials->count() > 1)
                                <button type="button" @click="prev()" aria-label="Testimoni Sebelumnya"
                                    class="absolute -left-2 sm:-left-6 top-1/2 -translate-y-1/2 w-8 h-8 sm:w-11 sm:h-11 rounded-full bg-white border border-gray-200 text-gray-700 flex items-center justify-center hover:bg-gray-50 shadow-md transition z-10 text-xs sm:text-base">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button type="button" @click="next()" aria-label="Testimoni Selanjutnya"
                                    class="absolute -right-2 sm:-right-6 top-1/2 -translate-y-1/2 w-8 h-8 sm:w-11 sm:h-11 rounded-full bg-white border border-gray-200 text-gray-700 flex items-center justify-center hover:bg-gray-50 shadow-md transition z-10 text-xs sm:text-base">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            @endif
                        </div>

                        {{-- Indikator Titik --}}
                        @if ($testimonials->count() > 1)
                            <div class="flex items-center justify-center gap-1.5 sm:gap-2 mt-4 sm:mt-6">
                                @foreach ($testimonials as $i => $t)
                                    <button type="button" @click="go({{ $i }})"
                                        :class="index === {{ $i }} ? 'w-5 sm:w-6 bg-[#63cd00]' : 'w-2 bg-gray-300 hover:bg-gray-400'"
                                        class="h-1.5 sm:h-2 rounded-full transition-all duration-300"
                                        aria-label="Pilih testimoni {{ $i + 1 }}"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </section>
            {{-- ================================================================= --}}
            {{-- SECTION INSTAGRAM (GRID ALA INSTAGRAM) --}}
            {{-- ================================================================= --}}
            <section class="social-feed-section bg-white py-8 sm:py-16 lg:py-20 fade-in-section overflow-hidden">

                <div class="social-feed-container max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="social-feed-grid grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                        <div class="min-w-0">
                            <div class="text-center mb-6 sm:mb-10">
                                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold" style="color: {{ $amaliahDark }};">
                                    Instagram Terbaru
                                </h2>
                                <div class="flex items-center justify-center gap-x-2 mx-auto mt-4">
                                    <div class="w-20 h-1 rounded-full" style="background-color: {{ $amaliahDark }};"></div>
                                    <div class="w-8 h-1 rounded-full" style="background-color: {{ $amaliahDark }};"></div>
                                    <div class="w-4 h-1 rounded-full" style="background-color: {{ $amaliahDark }};"></div>
                                </div>
                            </div>

                            <div class="ig-card mx-auto">

                                <div class="ig-head">
                                    <a class="ig-avatar" href="{{ config('services.instagram.profile_url') }}" target="_blank" rel="noopener" aria-label="Profil Instagram SMK Amaliah">
                                        <img src="{{ asset('assets/logo/am.webp') }}" alt="SMK Amaliah" loading="lazy">
                                    </a>
                                    <div class="ig-user">
                                        <span class="ig-username">@smkamaliah</span>
                                        <span class="ig-sub">SMK Amaliah 1 &amp; 2 · Ciawi-Bogor</span>
                                    </div>
                                    <a class="ig-follow" href="{{ config('services.instagram.profile_url') }}" target="_blank" rel="noopener">Follow</a>
                                </div>

                                <div class="ig-grid">
                                    @forelse ($instaPosts as $post)
                                        <a href="{{ $post->post_url ?: '#' }}" target="{{ $post->post_url ? '_blank' : '_self' }}"
                                            class="ig-item" title="{{ $post->caption ?? 'Postingan Instagram' }}">
                                            <img src="{{ img_url($post->path, 420, 420) }}" alt="{{ $post->caption ?? 'Instagram' }}" loading="lazy" width="420" height="420">
                                            @if ($post->post_url)
                                                <span class="ig-stats">
                                                    <span class="ig-stat"><i class="fa-regular fa-heart"></i></span>
                                                    <span class="ig-stat"><i class="fa-regular fa-comment"></i></span>
                                                </span>
                                            @endif
                                        </a>
                                    @empty
                                        <div class="ig-empty">Belum ada postingan Instagram.</div>
                                    @endforelse
                                                                </div>
                                <a class="tiktok-profile-link" href="{{ config('services.instagram.profile_url') }}" target="_blank" rel="noopener">
                                    Lihat semua postingan di Instagram <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </div>
                        </div>

                        <div class="min-w-0 mt-8 lg:mt-0">
                            <div class="text-center mb-6 sm:mb-10">
                                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold" style="color: {{ $amaliahDark }};">
                                    TikTok Terbaru
                                </h2>
                                <div class="flex items-center justify-center gap-x-2 mx-auto mt-4">
                                    <div class="w-20 h-1 rounded-full" style="background-color: {{ $amaliahDark }};"></div>
                                    <div class="w-8 h-1 rounded-full" style="background-color: {{ $amaliahDark }};"></div>
                                    <div class="w-4 h-1 rounded-full" style="background-color: {{ $amaliahDark }};"></div>
                                </div>
                            </div>
                            <div class="tiktok-card">
                                <div class="tiktok-head">
                                    <a class="tiktok-avatar" href="{{ config('services.tiktok.profile_url') }}" target="_blank" rel="noopener" aria-label="Profil TikTok SMK Amaliah">
                                        <img src="{{ asset('assets/logo/am.webp') }}" alt="SMK Amaliah" loading="lazy">
                                    </a>
                                    <div class="tiktok-account">
                                        <strong>{{ '@' . config('services.tiktok.username') }}</strong>
                                        <span>SMK Amaliah 1 &amp; 2 · Ciawi-Bogor</span>
                                    </div>
                                    <a class="tiktok-follow" href="{{ config('services.tiktok.profile_url') }}" target="_blank" rel="noopener">TikTok</a>
                                </div>
                                @php
                                    $tiktokFallbackCovers = [
                                        'assets/image/DroneView.jpg',
                                        'assets/image/Classmeet.JPG',
                                        'assets/image/17Agustus.JPG',
                                    ];
                                @endphp
                                <div class="tiktok-grid">
                                    @if(isset($tiktokPosts) && $tiktokPosts->count() > 0)
                                        @foreach ($tiktokPosts as $post)
                                            @php
                                                $videoUrl = $post->video_url ?: (
                                                    $post->video_id 
                                                        ? rtrim(config('services.tiktok.profile_url'), '/') . '/video/' . $post->video_id 
                                                        : config('services.tiktok.profile_url')
                                                );
                                                $tiktokCover = $post->path;
                                            @endphp
                                            <a class="tiktok-video-link" href="{{ $videoUrl }}" target="_blank" rel="noopener"
                                                aria-label="Tonton video TikTok: {{ $post->caption ?? 'Video ' . $loop->iteration }}"
                                                title="{{ $post->caption ?? 'Tonton video TikTok' }}">
                                                <img src="{{ img_url($tiktokCover, 500, 500) }}"
                                                    alt="{{ $post->caption ?? 'Cover Video TikTok' }}" loading="lazy" width="500" height="500">
                                                <span class="tiktok-play"><i class="fas fa-play"></i></span>
                                                <span class="tiktok-video-label">{{ Str::limit($post->caption ?? ('Tonton video ' . $loop->iteration), 35) }}</span>
                                            </a>
                                        @endforeach
                                    @else
                                        @forelse (config('services.tiktok.video_ids', []) as $videoId)
                                            @php
                                                $videoUrl = rtrim(config('services.tiktok.profile_url'), '/') . '/video/' . $videoId;
                                                $gridImage = $gridImages[$loop->index] ?? null;
                                                $tiktokCover = $gridImage && $gridImage->path
                                                    ? $gridImage->path
                                                    : $tiktokFallbackCovers[$loop->index % count($tiktokFallbackCovers)];
                                            @endphp
                                            <a class="tiktok-video-link" href="{{ $videoUrl }}" target="_blank" rel="noopener"
                                                aria-label="Tonton video TikTok {{ $loop->iteration }}">
                                                <img src="{{ img_url($tiktokCover, 500, 500) }}"
                                                    alt="" loading="lazy" width="500" height="500">
                                                <span class="tiktok-play"><i class="fas fa-play"></i></span>
                                                <span class="tiktok-video-label">Tonton video {{ $loop->iteration }}</span>
                                            </a>
                                        @empty
                                            <p class="tiktok-empty">
                                                Belum ada video yang dipilih.
                                                <a href="{{ config('services.tiktok.profile_url') }}" target="_blank" rel="noopener">Lihat profil TikTok</a>
                                            </p>
                                        @endforelse
                                    @endif
                                </div>
                                <a class="tiktok-profile-link" href="{{ config('services.tiktok.profile_url') }}" target="_blank" rel="noopener">
                                    Lihat semua video di TikTok <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .social-feed-container,
                    .social-feed-grid {
                        width: 100%;
                        min-width: 0;
                    }

                    .social-feed-grid > * {
                        min-width: 0;
                    }

                    .ig-card,
                    .tiktok-card {
                        width: 100%;
                        height: 100%;
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                        background: #fff;
                        border: 1px solid #dbdbdb;
                        border-radius: 8px;
                        overflow: hidden;
                    }

                    .ig-head,
                    .tiktok-head {
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        padding: 14px 16px;
                        border-bottom: 1px solid #dbdbdb;
                    }

                    .tiktok-avatar {
                        flex: 0 0 46px;
                        width: 46px;
                        height: 46px;
                        padding: 2px;
                        border-radius: 50%;
                        background: #171717;
                    }

                    .tiktok-avatar img {
                        display: block;
                        width: 100%;
                        height: 100%;
                        border: 2px solid #fff;
                        border-radius: 50%;
                        object-fit: cover;
                    }

                    .tiktok-account {
                        display: flex;
                        flex: 1;
                        flex-direction: column;
                        gap: 4px;
                        min-width: 0;
                    }

                    .tiktok-account strong {
                        font-size: 14px;
                    }

                    .tiktok-account span {
                        color: #6b7280;
                        font-size: 12px;
                    }

                    .tiktok-follow {
                        display: inline-flex;
                        flex: 0 0 auto;
                        align-items: center;
                        justify-content: center;
                        min-height: 36px;
                        padding: 6px 16px;
                        border-radius: 6px;
                        background: #171717;
                        color: #fff;
                        font-size: 13px;
                        font-weight: 600;
                        text-decoration: none;
                    }

                    .tiktok-grid {
                        display: grid;
                        grid-template-columns: repeat(3, minmax(0, 1fr));
                        gap: 3px;
                        background: #fff;
                    }

                    .tiktok-video-link {
                        position: relative;
                        display: block;
                        min-width: 0;
                        overflow: hidden;
                        aspect-ratio: 1 / 1;
                        background: #171717;
                    }

                    .tiktok-video-link img {
                        display: block;
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        transition: transform .25s ease, opacity .25s ease;
                    }

                    .tiktok-video-link:hover img {
                        transform: scale(1.04);
                        opacity: .8;
                    }

                    .tiktok-video-fallback {
                        display: grid;
                        place-items: center;
                        width: 100%;
                        height: 100%;
                        color: #fff;
                        font-size: 30px;
                    }

                    .tiktok-play {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        display: grid;
                        place-items: center;
                        width: 40px;
                        height: 40px;
                        border: 1px solid rgba(255, 255, 255, .8);
                        border-radius: 50%;
                        background: rgba(0, 0, 0, .55);
                        color: #fff;
                        font-size: 13px;
                        transform: translate(-50%, -50%);
                    }

                    @media (max-width: 640px) {
                        .tiktok-play {
                            width: 26px;
                            height: 26px;
                            font-size: 9px;
                        }

                        .tiktok-video-label {
                            padding: 12px 4px 4px;
                            font-size: 10px;
                        }
                    }

                    .tiktok-video-label {
                        position: absolute;
                        right: 0;
                        bottom: 0;
                        left: 0;
                        padding: 18px 8px 7px;
                        background: linear-gradient(transparent, rgba(0, 0, 0, .72));
                        color: #fff;
                        font-size: 11px;
                    }

                    .tiktok-video-link:focus-visible,
                    .tiktok-profile-link:focus-visible {
                        outline: 3px solid #63cd00;
                        outline-offset: 3px;
                    }

                    .tiktok-profile-link {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        gap: 8px;
                        min-height: 44px;
                        padding: 10px 16px;
                        margin: 12px;
                        border: 1px solid #d1d5db;
                        border-radius: 8px;
                        color: #282829;
                        font-size: 13px;
                        font-weight: 600;
                        text-decoration: none;
                    }

                    .tiktok-empty {
                        padding: 24px;
                        border: 1px solid #e5e7eb;
                        border-radius: 8px;
                        color: #6b7280;
                        text-align: center;
                    }

                    @media (max-width: 1023px) {
                        .tiktok-card {
                            max-width: calc(100vw - 2rem);
                        }
                    }

                    .ig-avatar {
                        flex-shrink: 0;
                        width: 46px;
                        height: 46px;
                        border-radius: 50%;
                        padding: 2px;
                        background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
                    }

                    .ig-avatar img {
                        width: 100%;
                        height: 100%;
                        border-radius: 50%;
                        object-fit: cover;
                        display: block;
                        border: 2px solid #fff;
                        background: #fff;
                    }

                    .ig-user {
                        flex: 1;
                        min-width: 0;
                        display: flex;
                        flex-direction: column;
                    }

                    .ig-username {
                        font-weight: 700;
                        font-size: 15px;
                        color: #262626;
                        line-height: 1.2;
                    }

                    .ig-sub {
                        font-size: 12px;
                        color: #8e8e8e;
                        line-height: 1.4;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    }

                    .ig-follow {
                        flex-shrink: 0;
                        background: #0095f6;
                        color: #fff;
                        font-size: 13px;
                        font-weight: 600;
                        padding: 5px 18px;
                        border-radius: 6px;
                        transition: background-color .2s;
                    }

                    .ig-follow:hover {
                        background: #1877f2;
                    }

                    
                    @media (max-width: 640px) {
                        .ig-item:nth-child(n+7) {
                            display: none !important;
                        }
                        .tiktok-video-link:nth-child(n+7) {
                            display: none !important;
                        }
                    }
                    .ig-grid {
                        display: grid;
                        grid-template-columns: repeat(3, 1fr);
                        gap: 0;
                        background: #fff;
                    }

                    .ig-item {
                        position: relative;
                        display: block;
                        aspect-ratio: 1 / 1;
                        overflow: hidden;
                        background: #f0f0f0;
                        border: 3px solid #fff;
                        box-sizing: border-box;
                    }

                    .ig-item img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        display: block;
                        transition: transform .25s ease, opacity .25s ease;
                    }

                    .ig-item:hover img {
                        transform: scale(1.04);
                        opacity: .85;
                    }

                    .ig-stats {
                        position: absolute;
                        left: 10px;
                        bottom: 10px;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        color: #fff;
                        font-size: 15px;
                        opacity: 0;
                        transition: opacity .2s;
                        text-shadow: 0 1px 3px rgba(0, 0, 0, .5);
                        z-index: 2;
                    }

                    .ig-item:hover .ig-stats {
                        opacity: 1;
                    }

                    .ig-stat {
                        display: inline-flex;
                    }

                    .ig-empty {
                        grid-column: 1 / -1;
                        text-align: center;
                        padding: 48px 16px;
                        color: #8e8e8e;
                        font-size: 14px;
                    }

                    @media (max-width: 480px) {
                        .ig-avatar,
                        .tiktok-avatar {
                            width: 40px;
                            height: 40px;
                        }

                        .ig-sub,
                        .tiktok-account span {
                            display: none;
                        }
                    }
                </style>
            </section>
            @php
                // Definisikan warna utama
                $amaliahGreen = '#63cd00';
                $amaliahDark = '#282829';

                // Definisikan informasi kontak
                $alamat = 'Jl. Raya Jl. Tol Jagorawi No.1, Ciawi, Kec. Ciawi, Kabupaten Bogor, Jawa Barat 16720';
                $email = 'smkamaliahciawi@gmail.com';
                $phone = '0856-1922-827 / 0856-4901-1449';
            @endphp

                        <section class="bg-gray-50 py-8 sm:py-16 lg:py-20 fade-in-section">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                    {{-- Tombol Virtual Tour di Atas (Responsif) --}}
                    <div class="text-center mb-6 sm:mb-10">
                        <a href="https://yourdisc710.itch.io/amaliah-tour"
                            class="inline-flex items-center bg-white border border-gray-300 rounded-full px-4 sm:px-8 py-2.5 sm:py-4 text-xs sm:text-base font-semibold shadow-sm hover:shadow-md hover:border-gray-400 transition-all duration-300 group max-w-full">
                            <span class="text-gray-800">Mau Lihat SMK Amaliah?</span>
                            <span class="ml-1.5 sm:ml-2 font-bold" style="color: {{ $amaliahGreen }};">Virtual Tour!</span>
                            <div class="ml-2 sm:ml-4 bg-white rounded-full p-1 sm:p-2 flex items-center justify-center border border-gray-300 group-hover:border-gray-400 transition-all flex-shrink-0">
                                <i class="fas fa-chevron-right text-[10px] sm:text-sm" style="color: {{ $amaliahGreen }};"></i>
                            </div>
                        </a>
                    </div>

                    {{-- Container Utama untuk Peta dan Info Responsif --}}
                    <div class="relative w-full rounded-xl sm:rounded-2xl overflow-hidden shadow-xl h-[240px] sm:h-[380px] md:h-[480px]">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.945187355167!2d106.8462900750414!3d-6.653716393341009!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c8eec16c788f%3A0x4680dbde73e8b763!2sSMK%20Amaliah%201%20dan%202%20Ciawi!5e0!3m2!1sid!2sid!4v1759652507072!5m2!1sid!2sid"
                            class="w-full h-full border-0" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade" title="Lokasi SMK Amaliah 1 dan 2 Ciawi"></iframe>
                    </div>
                </div>
            </section>
        </main>
    </div>
@endsection
