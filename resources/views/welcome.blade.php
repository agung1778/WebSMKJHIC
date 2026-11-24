@extends('layouts.public-app')
@section('description', 'Selamat datang di SMK Amaliah. Kami memiliki jurusan TKJ, RPL, DKV, dan Akuntansi.')
@section('title', 'SMK Amaliah 1 & 2')

@section('content')
    <style>
        .hero-clip-path {
            clip-path: polygon(0 0, 100% 0, 100% calc(100% - 2rem), calc(100% - 2rem) 100%, 0 100%);
            will-change: transform;
        }

        @media (min-width: 768px) {
            .hero-clip-path {
                clip-path: polygon(0 0, 100% 0, 100% calc(100% - 3.5rem), calc(100% - 3.5rem) 100%, 0 100%);
            }
        }

        /* Aturan ini akan aktif jika lebar layar 768px atau kurang */
        @media (max-width: 768px) {
            .custom-none {
                display: none;
            }
        }
    </style>


<div class="font-['Poppins'] bg-gray-100">
        @php
            $amaliahBlue = '#E0E7FF';
            $hasImages = isset($mainImages) && $mainImages->isNotEmpty();
        @endphp
        <main style="margin-top: 10px;">
            <section class="relative max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 mt-4 ">
                @if($hasImages && $latestNews->isNotEmpty())
                    <div x-data="{
                                                                                        showVideo: false,
                                                                                        activeImageSlide: 1,
                                                                                        totalImageSlides: {{ $mainImages->count() }},
                                                                                        activeNewsSlide: 1,
                                                                                        totalNewsSlides: {{ $latestNews->count() }}
                                                                                     }"
                        {{--==========================================================--}} {{-- PERBAIKAN 1: Menggabungkan 2
                        interval menjadi 1 --}} {{--==========================================================--}} x-init="
                                                                                        let heroSliderInterval = setInterval(() => {
                                                                                            if (!showVideo) {
                                                                                                activeImageSlide = activeImageSlide % totalImageSlides + 1;
                                                                                                activeNewsSlide = activeNewsSlide % totalNewsSlides + 1;
                                                                                            }
                                                                                        }, 5000);
                                                                                     ">

                        <div class="relative h-[420px] sm:h-[480px] lg:h-[550px] overflow-hidden hero-clip-path rounded-2xl sm:rounded-3xl">

                            {{-- Kontainer Slider Gambar (Hanya tampil jika showVideo false) --}}
                            <div x-show="!showVideo" class="w-full h-full">

                                {{-- ========================================================== --}}
                                {{-- PERBAIKAN LCP: Tambahkan $loop --}}
                                {{-- ========================================================== --}}
                                @foreach($mainImages as $loop => $image)
                                    <div x-show="activeImageSlide === {{ $loop->iteration }}"
                                        {{--==========================================================--}} {{-- PERBAIKAN 2: Samakan
                                        durasi animasi menjadi 500ms --}}
                                        {{--==========================================================--}}
                                        x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-500"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        class="absolute inset-0">

<img src="{{ img_url($image->path, 1600, 900) }}"
                                            alt="{{ $image->description ?? $image->filename }}" class="w-full h-full object-cover"
                                            width="1600" height="900"
                                            {{--==========================================================--}} {{-- PERBAIKAN LCP:
                                            Tambahkan fetchpriority dan loading --}}
                                            {{--==========================================================--}} @if($loop->first)
                                            fetchpriority="high" @endif loading="eager">
                                    </div>
                                @endforeach

                                {{-- Tombol "Watch Video" di Pojok Kanan Atas --}}
                                <button @click="showVideo = true"
                                    class="absolute top-4 right-4 sm:top-6 sm:right-6 z-20 flex items-center gap-2 bg-black/50 backdrop-blur-sm text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-full hover:bg-black/70 transition-all duration-300 text-xs sm:text-sm">
                                    <svg xmlns="https://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="font-semibold">Watch Video</span>
                                </button>
                            </div>

                            {{-- Kontainer Iframe YouTube (Hanya tampil jika showVideo true) --}}
                            <div x-show="showVideo" x-cloak class="w-full h-full">
                                {{-- Iframe yang sudah dimodifikasi --}}
<iframe class="w-full h-full"
    :src="showVideo ? 'https://www.youtube-nocookie.com/embed/STOhZZmY6Co?autoplay=1&mute=1&controls=0&loop=1&playlist=STOhZZmY6Co&rel=0&iv_load_policy=3&modestbranding=1' : ''"
    title="YouTube video player" frameborder="0"
    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
    allowfullscreen
    loading="lazy">
</iframe>

                                {{-- Tombol "Close" untuk Video --}}
                                <button @click="showVideo = false" aria-label="Tutup video"
                                    class="absolute top-4 right-4 sm:top-6 sm:right-6 z-20 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 bg-black/50 backdrop-blur-sm text-white rounded-full hover:bg-black/70 transition-all duration-300">
                                    <svg xmlns="https://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Bagian bawah (kartu berita dan logo) --}}
                       <div class="absolute bottom-4 left-4 right-4 sm:bottom-8 sm:left-8 md:left-12 z-10 sm:w-auto sm:max-w-md md:!block"
        x-show="!showVideo"
        x-transition:enter="transition transform ease-in-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition transform ease-in-out duration-500"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-10">

        {{-- Kontainer Slider Kartu Berita --}}
        <div class="relative w-full h-auto min-h-[200px] sm:min-h-[250px] overflow-hidden hero-clip-path ">

            {{-- KODE BERITA ANDA (LENGKAP) --}}
            @foreach($latestNews as $news)
            <div x-show="activeNewsSlide === {{ $loop->iteration }}"
                x-transition:enter="transition transform ease-in-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-10"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition transform ease-in-out duration-500"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-10" class="absolute inset-0 w-full">

                <div
                    class="flex flex-col h-full bg-white/90 backdrop-blur-lg p-4 sm:p-6 rounded-2xl shadow-2xl border border-white/30">
                    <h1 class="text-base sm:text-xl font-bold text-gray-900 leading-tight line-clamp-2">
                        {{ $news->title }}
                    </h1>
                    <p class="text-xs sm:text-sm mt-1.5 sm:mt-2 text-gray-700 line-clamp-2 sm:line-clamp-3 flex-grow">
                        {{ strip_tags($news->description) }}
                    </p>
                    <p class="text-[11px] sm:text-xs font-medium text-gray-500 mt-2 sm:mt-4">
                        Diterbitkan
                        {{ \Carbon\Carbon::parse($news->date_published)->translatedFormat('d F Y') }}
                    </p>
                    <div class="mt-2.5 sm:mt-4">
                        <a href="{{ route('public.news.show', $news) }}"
                            class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-white bg-[#282829] px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-full hover:bg-black transition-all duration-300 group">
                            Selengkapnya
                            <i
                                class="fas fa-arrow-right transition-transform duration-300 group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
                    </div>
                @else
                    {{-- Fallback responsif jika tidak ada data slider di database --}}
                    <div class="relative h-[420px] sm:h-[480px] lg:h-[550px] overflow-hidden hero-clip-path rounded-2xl sm:rounded-3xl bg-[#282829]">
                        <img src="{{ asset('assets/image/DroneView.jpg') }}" alt="SMK Amaliah 1 & 2 Ciawi" class="w-full h-full object-cover opacity-60" width="1600" height="900" fetchpriority="high">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/45 to-black/25"></div>
                        <div class="absolute inset-0 flex flex-col justify-end sm:justify-center p-6 sm:p-12 lg:p-16 max-w-2xl text-white">
                            <span class="inline-flex items-center gap-2 w-fit text-[11px] sm:text-xs font-semibold tracking-wider uppercase text-[#d9ffb3] bg-white/10 backdrop-blur border border-white/20 rounded-full px-3.5 py-1 mb-3">
                                <span class="w-2 h-2 rounded-full bg-[#63cd00]"></span>
                                Tauhid Is Our Fundament
                            </span>
                            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold leading-tight tracking-tight">
                                SMK Amaliah 1 &amp; 2 Ciawi
                            </h1>
                            <p class="mt-2 sm:mt-4 text-xs sm:text-base text-gray-200 line-clamp-3 leading-relaxed">
                                Membina generasi berakhlak mulia, berkarakter tauhid, unggul secara kompetensi, dan siap bersaing di dunia industri global.
                            </p>
                            <div class="mt-4 sm:mt-6 flex flex-wrap gap-3">
                                <a href="https://ppdb.smkamaliah.sch.id/login" class="inline-flex items-center gap-2 bg-[#63cd00] hover:bg-[#52ad00] text-white text-xs sm:text-sm font-semibold px-5 py-2.5 rounded-full transition shadow-lg">
                                    <span>Info SPMB</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                                <a href="{{ route('public.majors.index') }}" class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur text-white text-xs sm:text-sm font-semibold px-5 py-2.5 rounded-full transition border border-white/30">
                                    <span>Program Keahlian</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </section>

            <section class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 mb-16">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    @php
                        // Data untuk setiap kartu fitur (ditambahkan 'link' dan 'button_text')
                        $fitur = [
                            [
                                'icon' => 'fa-file-lines',
                                'title' => 'SPMB Online',
                                'desc' => 'Ayo daftarkan dirimu di SMK Amaliah secara mudah melalui sistem online kami.',
                                'link' => 'https://ppdb.smkamaliah.sch.id/login', // Ganti dengan route atau URL PPDB Anda
                                'button_text' => 'Daftar Sekarang',
                            ],
                            [
                                'icon' => 'fa-chart-simple',
                                'title' => 'E-Learning',
                                'desc' => 'Akses materi, tugas, dan sumber belajar kapan saja melalui platform E-Learning terintegrasi.',
                                'link' => 'https://lms.smkamaliah.sch.id', // Ganti dengan URL E-Learning Anda
                                'button_text' => 'Mulai Belajar',
                            ],
                            [
                                'icon' => 'fa-vr-cardboard',
                                'title' => 'Virtual Tour',
                                'desc' => 'Jelajahi setiap sudut dan fasilitas sekolah kami secara virtual dari kenyamanan rumah Anda.',
                                'link' => 'https://yourdisc710.itch.io/amaliah-tour', // Ganti dengan route atau URL Virtual Tour
                                'button_text' => 'Jelajahi Sekarang',
                            ],
                            [
                                'icon' => 'fa-building-columns',
                                'title' => 'Ujian Online',
                                'desc' => 'Laksanakan berbagai ujian sekolah dengan mudah dan aman melalui platform ujian online kami.',
                                'link' => 'https://play.google.com/store/apps/details?id=com.amexam', // Ganti dengan URL Ujian Online
                                'button_text' => 'Masuk Ujian',
                            ],
                        ];

                        // Asumsi variabel $amaliahGreen dan $amaliahDark sudah ada
                        $amaliahGreen = $amaliahGreen ?? '#63cd00';
                        $amaliahDark = $amaliahDark ?? '#282829';
                    @endphp

                    {{-- Loop untuk menampilkan setiap kartu fitur --}}
                    @foreach ($fitur as $item)
                        {{-- Seluruh kartu sekarang adalah sebuah link --}}
                        <a href="{{ $item['link'] }}"
                            class=" border-[{{ $amaliahGreen }}] group bg-gray-50 p-6 rounded-2xl flex flex-col items-start border-2  hover:border-[{{ $amaliahGreen }}] hover:bg-white transition-all duration-300 shadow-sm hover:shadow-lg transform hover:-translate-y-1">

                            {{-- Bagian Ikon --}}
                            <div class="p-4 rounded-xl mb-4" style="background-color: {{ $amaliahGreen }};">
                                <i class="fas {{ $item['icon'] }} text-2xl text-white"></i>
                            </div>

                            {{-- Bagian Teks --}}
                            <h2 class="text-lg font-bold mb-2" style="color: {{ $amaliahDark }};">{{ $item['title'] }}</h2>
                            <p class="text-sm text-gray-600 mb-4 flex-grow">{{ $item['desc'] }}</p>

                            {{-- Tombol Link dengan teks dinamis --}}
                            <div class="text-sm font-semibold flex items-center mt-auto" style="color: {{ $amaliahGreen }};">
                                <span>{{ $item['button_text'] }}</span>
                                <i class="fas fa-chevron-right ml-2 text-xs transition-transform group-hover:translate-x-1"></i>
                            </div>
                        </a>
                    @endforeach

                </div>
            </section>

            {{-- Bagian Header Judul --}}
            <section class="text-center px-4 sm:px-6 lg:px-8 mb-16 fade-in-section">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">We Have Intelligent Solution For Your Education
                </h2>
                <div class="flex items-center justify-center gap-x-2 mx-auto mt-4">
                    <div class="w-20 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                    <div class="w-4 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                    <div class="w-4 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                </div>
            </section>

            {{-- Bagian Konten Utama (Deskripsi, Tombol, dan Grid Gambar) --}}
            <section class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 mb-24 fade-in-section">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    {{-- Kolom Kiri: Teks dan Tombol --}}
                    <div class="text-gray-600">
                        <p class="text-base leading-relaxed">
                            SMK Amaliah 1 & 2 merupakan bentuk sekolah kejuruan yang dibawah naungan Yayasan Pusat Studi
                            Pengembangan Islam Amaliyah Indonesia (YPSPIAI) dengan mengutamakan kualitas, Profesionalitas
                            dan Pelayanan Prima dan dibawah pengawasan Universitas Djuanda (UNIDA) berdiri pada tahun 2008.
                        </p>

                        {{-- Wadah untuk Tombol --}}
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4 mt-8">

                            <a href="https://ppdb.smkamaliah.sch.id/"
                                class="group inline-flex items-center justify-between text-white pl-6 pr-2 py-2 rounded-lg font-semibold shadow-lg transition-all duration-300 hover:shadow-xl hover:opacity-90"
                                style="background-color: {{ $amaliahGreen }};">

                                <span class="mr-4">Info PPDB</span>

                                <span
                                    class="bg-white rounded-full h-8 w-8 flex items-center justify-center transition-transform duration-300 group-hover/button:translate-x-1 ease-in-out group-hover:translate-x-1">
                                    <i class="fas fa-arrow-right text-sm " style="color: {{ $amaliahGreen }};"></i>
                                </span>
                            </a>

                            <a href="{{ route('public.about.index') }}"
                                class="group inline-flex items-center justify-between text-white pl-6 pr-2 py-2 rounded-lg font-semibold shadow-lg transition-all duration-300 hover:shadow-xl hover:opacity-90"
                                style="background-color: {{ $amaliahGreen }};">

                                <span class="mr-4">Selengkapnya</span>

                                <span
                                    class="bg-white rounded-full h-8 w-8 flex items-center justify-center transition-transform duration-300 group-hover/button:translate-x-1 ease-in-out group-hover:translate-x-1">
                                    <i class="fas fa-arrow-right text-sm    " style="color: {{ $amaliahGreen }};"></i>
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
                            'assets/image/kepsek1.webp',
                            'assets/image/kepsek2.webp',
                        ];
                    @endphp
                    <div class="grid grid-cols-3 grid-rows-3 gap-2 sm:gap-4 h-64 sm:h-80 lg:h-96">

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
                // Definisikan variabel warna di atas agar mudah diakses
                $amaliahDark = '#282829';

                // Data untuk bagian statistik, disesuaikan dengan referensi gambar
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
            {{-- SECTION STATS BAR --}}
            <section class="py-12 lg:py-16 fade-in-section stats-counter-section"
                style="background-color: {{ $amaliahDark }};">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col lg:flex-row items-center justify-center lg:justify-around gap-y-10 gap-x-6">

                        {{-- STATISTIK UTAMA (KIRI) --}}
                        <div class="flex items-center gap-x-5">
                            <div class="bg-gray-200 flex items-center justify-center h-20 w-20 rounded-2xl flex-shrink-0">
                                <i class="fas {{ $stats[0]['icon'] }} text-4xl" style="color: {{ $amaliahDark }};"></i>
                            </div>
                            <div class="text-white">
                                <p class="text-4xl font-bold whitespace-nowrap count-up"
                                    data-target-value="{{ $stats[0]['number'] }}">0</p>
                                <p class="text-base text-gray-300">{{ $stats[0]['label'] }}</p>
                            </div>
                        </div>

                        {{-- PEMISAH --}}
                        <div class="w-4/5 h-px bg-gray-600 lg:w-px lg:h-20"></div>

                        {{-- GRUP STATISTIK LAINNYA (KANAN) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-10 sm:gap-6 lg:gap-12">
                            @foreach (array_slice($stats, 1) as $stat)
                                <div class="flex items-center gap-x-4 justify-center sm:justify-start">
                                    <div
                                        class="bg-gray-200 flex items-center justify-center h-16 w-16 rounded-xl flex-shrink-0">
                                        <i class="fas {{ $stat['icon'] }} text-2xl" style="color: {{ $amaliahDark }};"></i>
                                    </div>
                                    <div class="text-white">
                                        <p class="text-3xl font-bold whitespace-nowrap count-up"
                                            data-target-value="{{ $stat['number'] }}">0</p>
                                        <p class="text-sm text-gray-300 whitespace-nowrap">{{ $stat['label'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

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
            <section class="bg-white py-16 sm:py-24 fade-in-section">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                        {{-- Kolom Kiri: Teks & Tombol (Tidak ada perubahan) --}}
                        <div class="text-left">
                            <h2 class="text-4xl md:text-5xl font-bold" style="color: {{ $amaliahDark }};">
                                Here Is Our<br>Industry Partner
                            </h2>

                            <div class="flex items-center gap-x-2 mt-4">
                                <div class="w-20 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                                <div class="w-4 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                                <div class="w-4 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                            </div>

                            <p class="mt-6 text-gray-600 leading-relaxed">
                                We cooperate with industry leaders to provide students with real-world experience through
                                internships, industrial visits, training, and career opportunities after graduation.
                            </p>

                            <a href="{{ route('public.partners.index') }}"
                                class="group mt-8 inline-flex items-center text-white px-6 py-3 rounded-lg font-semibold shadow-lg group/button transition-opacity duration-300 hover:opacity-90"
                                style="background-color: {{ $amaliahGreen }};">
                                <span class="mr-4 text-lg">Selengkapnya</span>
                                <div
                                    class="bg-white rounded-full p-2 flex items-center justify-center transition-transform duration-300 group-hover/button:translate-x-1 ease-in-out group-hover:translate-x-1">
                                    <i class="fas fa-arrow-right text-base " style="color: {{ $amaliahGreen }};"></i>
                                </div>
                            </a>
                        </div>

                        {{-- Kolom Kanan: Grid Logo Mitra dengan Animasi Scroll --}}
                        <div class="group relative h-[28rem] overflow-hidden">
                            {{-- Kontainer untuk item yang akan dianimasikan --}}
                            <div
                                class="grid grid-cols-2 md:grid-cols-4 gap-x-8 gap-y-10 animate-scroll-vertical group-hover:[animation-play-state:paused]">

                                {{-- Loop data dari controller (DUPLIKASI 1) --}}
                                @forelse ($partners as $partner)
                                    <div class="text-center">
                                        <div class="bg-white h-24 w-full rounded-lg mb-3 flex items-center justify-center p-4 
                                                                               border border-dashed border-gray-300">
<img src="{{ img_url($partner->logo, 160, 90) }}" alt="Logo {{ $partner->name }}" loading="lazy" width="160" height="90"
                                                class="max-h-full max-w-full object-contain">
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-2 md:col-span-4 text-center">
                                        <p class="text-gray-500">Belum ada mitra yang ditambahkan.</p>
                                    </div>
                                @endforelse

                                {{-- Loop data dari controller (DUPLIKASI 2 - Untuk Efek Mulus) --}}
                                @forelse ($partners as $partner)
                                    <div class="text-center">
                                        <div class="bg-white h-24 w-full rounded-lg mb-3 flex items-center justify-center p-4 
                                                                               border border-dashed border-gray-300">
<img src="{{ img_url($partner->logo, 160, 90) }}" alt="Logo {{ $partner->name }}" loading="lazy" width="160" height="90"
                                                class="max-h-full max-w-full object-contain">
                                        </div>
                                    </div>
                                @empty
                                    {{-- Tidak perlu pesan empty di duplikasi --}}
                                @endforelse
                            </div>
                            {{-- Efek fade di bagian bawah untuk transisi yang lebih halus --}}
                            <div
                                class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-white to-transparent pointer-events-none">
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            @php
                $hasBothLeaders = $leaders->where('school', 'Amaliah 1')->isNotEmpty()
                    && $leaders->where('school', 'Amaliah 2')->isNotEmpty();
            @endphp
            <section class="bg-[#282829] py-16 sm:py-24 overflow-hidden fade-in-section relative">
                <style>
                    .leader-nav-btn {
                        display: none;
                        position: absolute;
                        top: 50%;
                        transform: translateY(-50%);
                        width: 42px;
                        height: 42px;
                        border-radius: 9999px;
                        background: #1c1c1e;
                        border: 2px solid #63cd00;
                        color: #ffffff;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        cursor: pointer;
                        z-index: 30;
                        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
                        transition: all 0.25s ease;
                    }
                    .leader-nav-btn:hover {
                        background: #63cd00;
                        color: #ffffff;
                        transform: translateY(-50%) scale(1.1);
                        box-shadow: 0 0 20px rgba(99, 205, 0, 0.6);
                    }
                    .leader-nav-prev {
                        left: -8px;
                    }
                    .leader-nav-next {
                        right: -8px;
                    }
                    @media (min-width: 640px) {
                        .leader-nav-btn {
                            width: 50px;
                            height: 50px;
                        }
                        .leader-nav-prev {
                            left: -22px;
                        }
                        .leader-nav-next {
                            right: -22px;
                        }
                    }
                    @media (min-width: 1024px) {
                        .leader-nav-prev {
                            left: -28px;
                        }
                        .leader-nav-next {
                            right: -28px;
                        }
                    }
                    .leader-book-card {
                        width: 100%;
                        background: linear-gradient(165deg, #272729 0%, #1e1e20 60%, #18181a 100%);
                        border: 1px solid rgba(255, 255, 255, 0.12);
                        border-top: 3px solid #63cd00;
                        border-radius: 24px;
                        box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.7), 0 0 40px rgba(99, 205, 0, 0.08);
                        position: relative;
                        overflow: hidden;
                        text-align: center;
                    }
                </style>

                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8"
                    x-data="{ activeTab: 'amaliah1', autoRotate: {{ $hasBothLeaders ? 'true' : 'false' }} }"
                    x-init="if (autoRotate) setInterval(() => { if (autoRotate) activeTab = activeTab === 'amaliah1' ? 'amaliah2' : 'amaliah1' }, 7000)"
                    @mouseenter="autoRotate = false" @mouseleave="autoRotate = true">

                    {{-- Bagian Header: Judul dan Tombol Tab --}}
                    <div class="text-center mb-10 sm:mb-14">
                        <span class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-[#63cd00] bg-[#63cd00]/10 border border-[#63cd00]/25 rounded-full px-4 py-1.5 mb-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#63cd00]"></span>
                            Pimpinan Sekolah
                        </span>
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight">
                            Get To Know Our School Leaders
                        </h2>
                        <p class="mt-3 text-sm sm:text-base text-gray-400 max-w-xl mx-auto">
                            Mengenal lebih dekat pimpinan SMK Amaliah 1 &amp; 2 Ciawi yang berkomitmen membimbing generasi berakhlak mulia.
                        </p>

                        {{-- Tab Switcher dengan Tombol Panah Responsif Terintegrasi --}}
                        <div class="flex items-center justify-center gap-2 sm:gap-3 mt-6">
                            {{-- Panah Kiri Switcher --}}
                            <button @click="activeTab = 'amaliah1'"
                                :class="{ 'opacity-50 cursor-not-allowed': activeTab === 'amaliah1' }"
                                class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/10 hover:bg-[#63cd00] text-white flex items-center justify-center transition-all duration-300 shadow"
                                aria-label="Buka SMK Amaliah 1">
                                <i class="fa-solid fa-chevron-left text-xs sm:text-sm"></i>
                            </button>

                            {{-- Pilihan Tab Pill --}}
                            <div class="inline-flex p-1 sm:p-1.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-md shadow-inner">
                                <button @click="activeTab = 'amaliah1'"
                                    :class="{
                                        'bg-[#63cd00] text-white shadow-lg shadow-[#63cd00]/30 font-bold': activeTab === 'amaliah1',
                                        'text-gray-300 hover:text-white hover:bg-white/10 font-medium': activeTab !== 'amaliah1'
                                    }"
                                    class="px-4 sm:px-6 py-2 text-xs sm:text-sm rounded-full transition-all duration-300 flex items-center gap-2"
                                    aria-label="Lihat Kepala Sekolah SMK Amaliah 1">
                                    <i class="fa-solid fa-book-open text-xs"></i>
                                    <span>SMK Amaliah 1</span>
                                </button>
                                <button @click="activeTab = 'amaliah2'"
                                    :class="{
                                        'bg-[#63cd00] text-white shadow-lg shadow-[#63cd00]/30 font-bold': activeTab === 'amaliah2',
                                        'text-gray-300 hover:text-white hover:bg-white/10 font-medium': activeTab !== 'amaliah2'
                                    }"
                                    class="px-4 sm:px-6 py-2 text-xs sm:text-sm rounded-full transition-all duration-300 flex items-center gap-2"
                                    aria-label="Lihat Kepala Sekolah SMK Amaliah 2">
                                    <i class="fa-solid fa-book-open text-xs"></i>
                                    <span>SMK Amaliah 2</span>
                                </button>
                            </div>

                            {{-- Panah Kanan Switcher --}}
                            <button @click="activeTab = 'amaliah2'"
                                :class="{ 'opacity-50 cursor-not-allowed': activeTab === 'amaliah2' }"
                                class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/10 hover:bg-[#63cd00] text-white flex items-center justify-center transition-all duration-300 shadow"
                                aria-label="Buka SMK Amaliah 2">
                                <i class="fa-solid fa-chevron-right text-xs sm:text-sm"></i>
                            </button>
                        </div>
                    </div>

                    {{-- KONTEN UTAMA: Buku Interaktif (3D Book Flip Page Turn Transition) --}}
                    @php
                        $leader1 = $leaders->where('school', 'Amaliah 1')->first();
                        $leader2 = $leaders->where('school', 'Amaliah 2')->first();
                        $leaderImage = function ($leader, $fallback) {
                            if ($leader && $leader->image) {
                                return str_starts_with($leader->image, 'uploads/')
                                    ? asset('storage/' . $leader->image)
                                    : (str_starts_with($leader->image, 'http') ? $leader->image : asset($leader->image));
                            }
                            return asset($fallback);
                        };
                        $leaderProfiles = [
                            [
                                'tab' => 'amaliah1',
                                'leader' => $leader1,
                                'fallback' => 'assets/image/kepsek1.webp',
                                'alt' => 'Kepala Sekolah SMK Amaliah 1',
                                'school_title' => 'SMK AMALIAH 1',
                                'page_num' => '1 / 2'
                            ],
                            [
                                'tab' => 'amaliah2',
                                'leader' => $leader2,
                                'fallback' => 'assets/image/kepsek2.webp',
                                'alt' => 'Kepala Sekolah SMK Amaliah 2',
                                'school_title' => 'SMK AMALIAH 2',
                                'page_num' => '2 / 2'
                            ],
                        ];
                    @endphp

                    <div class="relative max-w-2xl mx-auto px-2 sm:px-4">

                        {{-- Tombol Panah Samping: Kiri (Previous) & Kanan (Next) Terpisah Sempurna --}}
                        <button type="button" @click="activeTab = 'amaliah1'"
                            :class="{ 'opacity-40 pointer-events-none': activeTab === 'amaliah1' }"
                            class="leader-nav-btn leader-nav-prev hidden sm:flex"
                            aria-label="Halaman pimpinan sebelumnya (SMK Amaliah 1)">
                            <i class="fa-solid fa-chevron-left text-sm"></i>
                        </button>
                        <button type="button" @click="activeTab = 'amaliah2'"
                            :class="{ 'opacity-40 pointer-events-none': activeTab === 'amaliah2' }"
                            class="leader-nav-btn leader-nav-next hidden sm:flex"
                            aria-label="Halaman pimpinan berikutnya (SMK Amaliah 2)">
                            <i class="fa-solid fa-chevron-right text-sm"></i>
                        </button>

                        {{-- Wadah 3D Book Container (Grid Stack) --}}
                        <div class="relative w-full" style="display: grid; grid-template-columns: 1fr; perspective: 1200px;">
                            @if ($leader1 || $leader2)
                                @foreach ($leaderProfiles as $i => $profile)
                                    @if ($profile['leader'])
                                        <div x-show="activeTab === '{{ $profile['tab'] }}'" x-cloak
                                            x-transition:enter="transition-all duration-600 ease-[cubic-bezier(0.16,1,0.3,1)]"
                                            x-transition:enter-start="opacity-0 [transform:rotateY(-35deg)_scale(0.96)] shadow-none"
                                            x-transition:enter-end="opacity-100 [transform:rotateY(0deg)_scale(1)] shadow-2xl"
                                            x-transition:leave="transition-all duration-400 ease-in absolute inset-0"
                                            x-transition:leave-start="opacity-100 [transform:rotateY(0deg)_scale(1)]"
                                            x-transition:leave-end="opacity-0 [transform:rotateY(35deg)_scale(0.96)] shadow-none"
                                            style="grid-row: 1; grid-column: 1; transform-origin: center center;"
                                            class="leader-book-card p-6 sm:p-9 lg:p-10">

                                            {{-- Aksen Dekorasi Cahaya --}}
                                            <div class="absolute -top-16 -right-16 w-52 h-52 bg-[#63cd00]/15 rounded-full blur-3xl pointer-events-none"></div>
                                            <div class="absolute -bottom-16 -left-16 w-52 h-52 bg-[#63cd00]/10 rounded-full blur-3xl pointer-events-none"></div>

                                            {{-- Header Kartu Halaman Buku --}}
                                            <div class="flex items-center justify-between mb-6 pb-3 border-b border-white/10 text-xs text-gray-400">
                                                <span class="inline-flex items-center gap-1.5 font-bold uppercase tracking-wider text-[#63cd00]">
                                                    <i class="fa-solid fa-bookmark"></i>
                                                    {{ $profile['school_title'] }}
                                                </span>
                                                <span class="bg-white/10 px-2.5 py-0.5 rounded-full text-[11px] font-semibold text-gray-300">
                                                    Halaman {{ $profile['page_num'] }}
                                                </span>
                                            </div>

                                            {{-- 1. FOTO LEADERS DI ATAS NAMA (Ukuran pas, rapi, dan terpusat) --}}
                                            <div class="flex justify-center mb-5">
                                                <div class="relative group">
                                                    {{-- Glow Cincin Hijau Emerald --}}
                                                    <div class="absolute -inset-1.5 bg-gradient-to-tr from-[#63cd00] to-[#8ef03a] rounded-full blur opacity-70 group-hover:opacity-100 transition duration-500"></div>

                                                    {{-- Lingkaran Foto Pimpinan --}}
                                                    <div class="relative w-32 h-32 sm:w-40 sm:h-40 rounded-full overflow-hidden border-4 border-[#1c1c1e] shadow-2xl bg-gray-800">
                                                        <img src="{{ img_url($leaderImage($profile['leader'], $profile['fallback']), 400, 400) }}"
                                                            loading="lazy"
                                                            width="400" height="400"
                                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                            alt="{{ $profile['alt'] }}">
                                                    </div>

                                                    {{-- Badge Topi Wisuda --}}
                                                    <div class="absolute bottom-1 right-1 sm:right-2 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-[#63cd00] text-white flex items-center justify-center shadow-lg border-2 border-[#1c1c1e]">
                                                        <i class="fa-solid fa-graduation-cap text-[10px] sm:text-xs"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- 2. NAMA LEADERS DI BAWAH FOTO --}}
                                            <h3 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-white tracking-tight">
                                                {{ $profile['leader']->name }}
                                            </h3>

                                            {{-- 3. JABATAN / POSISI --}}
                                            <div class="mt-2 inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#63cd00]/15 border border-[#63cd00]/30 text-[#63cd00] text-xs sm:text-sm font-semibold">
                                                <span class="w-2 h-2 rounded-full bg-[#63cd00] animate-pulse"></span>
                                                {{ $profile['leader']->position }} · {{ $profile['school_title'] }}
                                            </div>

                                            {{-- 4. KUTIPAN / SAMBUTAN --}}
                                            @if ($profile['leader']->quote)
                                                <div class="mt-6 bg-black/30 border border-white/5 rounded-2xl p-5 sm:p-6 relative max-w-lg mx-auto shadow-inner">
                                                    <i class="fas fa-quote-left text-xl text-[#63cd00]/30 absolute top-3 left-3"></i>
                                                    <blockquote class="text-xs sm:text-sm text-gray-200 italic leading-relaxed text-center px-4">
                                                        "{!! nl2br(e($profile['leader']->quote)) !!}"
                                                    </blockquote>
                                                    <i class="fas fa-quote-right text-xl text-[#63cd00]/30 absolute bottom-3 right-3"></i>
                                                </div>
                                            @endif

                                            {{-- 5. JEJARING SOSIAL --}}
                                            <div class="flex items-center space-x-3 mt-6 justify-center">
                                                <a href="{{ $profile['leader']->facebook_url ?: 'https://www.facebook.com/' }}"
                                                    target="_blank" rel="noopener noreferrer"
                                                    aria-label="Facebook {{ $profile['leader']->name }}"
                                                    class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-[#63cd00] hover:text-white text-gray-300 transition-all duration-300 shadow-sm hover:scale-110">
                                                    <i class="fab fa-facebook-f text-xs sm:text-sm"></i>
                                                </a>
                                                <a href="{{ $profile['leader']->instagram_url ?: 'https://www.instagram.com/' }}"
                                                    target="_blank" rel="noopener noreferrer"
                                                    aria-label="Instagram {{ $profile['leader']->name }}"
                                                    class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-[#63cd00] hover:text-white text-gray-300 transition-all duration-300 shadow-sm hover:scale-110">
                                                    <i class="fab fa-instagram text-xs sm:text-sm"></i>
                                                </a>
                                                <a href="{{ $profile['leader']->linkedin_url ?: 'https://id.linkedin.com/' }}"
                                                    target="_blank" rel="noopener noreferrer"
                                                    aria-label="LinkedIn {{ $profile['leader']->name }}"
                                                    class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-[#63cd00] hover:text-white text-gray-300 transition-all duration-300 shadow-sm hover:scale-110">
                                                    <i class="fab fa-linkedin-in text-xs sm:text-sm"></i>
                                                </a>
                                            </div>

                                            {{-- Footer Kartu Halaman Buku --}}
                                            <div class="mt-6 pt-3 border-t border-white/10 flex items-center justify-between text-[11px] sm:text-xs text-gray-400">
                                                <span class="text-gray-400">
                                                    {{ $profile['school_title'] }}
                                                </span>
                                                <button @click="activeTab = activeTab === 'amaliah1' ? 'amaliah2' : 'amaliah1'"
                                                    class="hover:text-white text-[#63cd00] font-semibold transition-colors flex items-center gap-1.5 cursor-pointer">
                                                    <span>{{ $profile['tab'] === 'amaliah1' ? 'Buka SMK Amaliah 2' : 'Buka SMK Amaliah 1' }}</span>
                                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                                </button>
                                            </div>

                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="w-full bg-[#1f1f21] border border-white/10 rounded-3xl p-8 text-center text-gray-400 italic">
                                    Belum ada data pemimpin sekolah untuk ditampilkan.
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </section>
            {{-- Tambahkan CSS untuk Animasi di bagian bawah file blade atau di file CSS utama --}}
            <style>
                @keyframes scroll-vertical {
                    from {
                        transform: translateY(0);
                    }

                    to {
                        transform: translateY(-50%);
                    }
                }

                .animate-scroll-vertical {
                    /* Sesuaikan durasi (misal: 60s) untuk mengatur kecepatan scroll */
                    animation: scroll-vertical 120s linear infinite;
                }
            </style>

            {{-- CSS Tambahan untuk menyembunyikan scrollbar --}}
            <section class="py-16 sm:py-24 fade-in-section" style="background-color: {{ $amaliahDark }};">
                <div x-data="{
                                                                                                                                                                                                                                                                                                                                                        scrollSlider(direction) {
                                                                                                                                                                                                                                                                                                                                                            const slider = this.$refs.slider;
                                                                                                                                                                                                                                                                                                                                                            const scrollAmount = slider.querySelector('.slider-item').offsetWidth + 32; // Lebar kartu + gap
                                                                                                                                                                                                                                                                                                                                                            slider.scrollBy({
                                                                                                                                                                                                                                                                                                                                                                left: direction === 'next' ? scrollAmount : -scrollAmount,
                                                                                                                                                                                                                                                                                                                                                                behavior: 'smooth'
                                                                                                                                                                                                                                                                                                                                                            });
                                                                                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                                                                                    }"
                    class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 relative">

                    {{-- Dekorasi Titik --}}
                    <div class="absolute top-8 left-8 md:left-12 flex items-center space-x-2 custom-none">
                        <div class="w-3 h-3 bg-gray-600 rounded-full"></div>
                        <div class="w-3 h-3 bg-gray-600 rounded-full"></div>
                        <div class="w-3 h-3 bg-white rounded-full"></div>
                    </div>

                    {{-- Header Section --}}
                    <div class="text-center">
                        <h2 class="text-3xl md:text-4xl font-bold text-white">Major Competency</h2>
                        <p class="mt-2 text-gray-400">Program Keahlian Di SMK Amaliah 1 & 2 </p>
                        <div class="w-24 h-px bg-gray-600 mx-auto mt-4"></div>
                    </div>

                    {{-- Layout Grid Utama (Statis Kiri, Scroll Kanan) --}}
                    <div class="mt-10 grid grid-cols-1 lg:grid-cols-4 gap-8">

                        {{-- KOLOM 1: KONTEN STATIS (TIDAK IKUT SCROLL) --}}
                        <div class="lg:col-span-1 hidden lg:flex flex-col justify-between">
                            {{-- Gambar Grid Jurusan Dinamis --}}
                            <div class="flex flex-col space-y-6">
                                {{-- Gambar 1 --}}
                                @if (isset($majorGridImages[0]) && $majorGridImages[0]->path)
<img src="{{ img_url($majorGridImages[0]->path, 900, 375) }}" loading="lazy" width="900" height="375"
                                        alt="{{ $majorGridImages[0]->description ?? 'Gambar Grid Jurusan 1' }}"
                                        class="h-48 w-full rounded-xl object-cover">
                                @else
                                    {{-- Fallback jika gambar tidak ada --}}
                                    <div class="bg-gray-700 h-48 w-full rounded-xl flex items-center justify-center">
                                        <i class="fas fa-image text-4xl text-gray-500"></i>
                                    </div>
                                @endif

                                {{-- Gambar 2 --}}
                                @if (isset($majorGridImages[1]) && $majorGridImages[1]->path)
<img src="{{ img_url($majorGridImages[1]->path, 900, 375) }}" loading="lazy" width="900" height="375"
                                        alt="{{ $majorGridImages[1]->description ?? 'Gambar Grid Jurusan 2' }}"
                                        class="h-48 w-full rounded-xl object-cover">
                                @else
                                    {{-- Fallback jika gambar tidak ada --}}
                                    <div class="bg-gray-700 h-48 w-full rounded-xl flex items-center justify-center">
                                        <i class="fas fa-image text-4xl text-gray-500"></i>
                                    </div>
                                @endif
                            </div>
                            {{-- Tombol Navigasi Statis --}}
                            <div class="mt-6 flex items-center space-x-4">
                                <button @click="scrollSlider('prev')"
                                    class="bg-white hover:bg-gray-200 text-gray-800 w-12 h-12 rounded-lg flex items-center justify-center transition-colors"
                                    id="majorbutton" role="presentation" aria-label="button">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button @click="scrollSlider('next')"
                                    class="bg-white hover:bg-gray-200 text-gray-800 w-12 h-12 rounded-lg flex items-center justify-center transition-colors"
                                    id="majorbutton" role="presentation" aria-label="button">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                        @php
                            use Illuminate\Support\Str;
                        @endphp

                        {{-- KOLOM 2: KONTEN SLIDER (BISA DI-SCROLL) --}}
                        <div x-ref="slider"
                            class="lg:col-span-3 flex space-x-5 sm:space-x-8 overflow-x-auto snap-x snap-mandatory scroll-smooth scrollbar-hide pb-4 -mx-4 px-4">

                            {{-- Loop dinamis untuk Kartu Jurusan dari database --}}
                            @foreach ($majors as $major)
                                <div class="flex-shrink-0 w-[270px] sm:w-80 snap-start flex flex-col slider-item">

                                    {{-- Menampilkan Gambar Jurusan dengan fallback --}}
                                    @php
                                        $majorDefaultImages = [
                                            'assets/image/Classmeet.JPG',
                                            'assets/image/DroneView.jpg',
                                            'assets/image/17Agustus.JPG',
                                        ];
                                        $majorImg = $major->image ? img_url($major->image, 700, 375) : asset($majorDefaultImages[$loop->index % count($majorDefaultImages)]);
                                    @endphp
                                    <img src="{{ $majorImg }}" alt="Gambar Jurusan {{ $major->name }}" loading="lazy" width="700" height="375"
                                        class="h-44 sm:h-48 w-full rounded-lg object-cover mb-4">

                                    {{-- Menggunakan properti objek, bukan array --}}
                                    <h3 class="text-xl font-bold text-white">{{ $major->name }}</h3>

                                    {{-- PERBAIKAN: Pembatasan Deskripsi menggunakan Str::limit() (max 120 karakter) --}}
                                    <p class="text-sm text-gray-400 mt-2 flex-grow">
                                        {{ Str::limit(strip_tags($major->description), 120) }}
                                    </p>


                                    <a href="{{ route('public.majors.show', $major) }}"
                                        class="inline-flex items-center group mt-4">

                                        <span
                                            class="text-sm font-semibold text-white mr-3 transition-colors duration-300 group-hover:text-gray-200">
                                            Selengkapnya
                                        </span>

                                        <div
                                            class="bg-gray-200 rounded-full p-2 transition-all duration-300 ease-in-out group-hover:bg-white group-hover:scale-110 group-hover:shadow-md">
                                            <i
                                                class="fas fa-arrow-right text-gray-800 text-sm transition-transform duration-300 ease-in-out group-hover:-rotate-45"></i>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        {{-- Tombol Navigasi (Hanya tampil di mobile) --}}
                        <div class="lg:hidden mt-6 flex items-center justify-center space-x-4">
                            <button @click="scrollSlider('prev')"
                                class="bg-white hover:bg-gray-200 text-gray-800 w-12 h-12 rounded-lg flex items-center justify-center transition-colors"
                                id="majorbutton" role="presentation" aria-label="button">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button @click="scrollSlider('next')"
                                class="bg-white hover:bg-gray-200 text-gray-800 w-12 h-12 rounded-lg flex items-center justify-center transition-colors"
                                id="majorbutton" role="presentation" aria-label="button">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </section>
            <section class="bg-gray-50 py-16 sm:py-24 fade-in-section">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                    {{-- Header Section --}}
                    <div class="text-center">
                        {{-- Dekorasi Titik Hijau --}}
                        <div class="flex space-x-2 justify-center md:justify-start mb-4">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                        </div>

                        <h2 class="text-3xl md:text-4xl font-bold" style="color: {{ $amaliahDark }};">
                            Baca Berita Terbaru Kami
                        </h2>
                        <p class="mt-2 text-gray-500">
                            Read our latest news, and know about smk amaliah
                        </p>
                    </div>


                    <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                        {{-- Menggunakan @forelse untuk menangani jika tidak ada berita --}}
                        @php
                            $newsFallbackImage = asset('assets/image/DroneView.jpg');
                        @endphp
                        @forelse ($latestNews as $newsItem)
                            <div
                                class="bg-white rounded-2xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col">
                                <a href="{{ route('public.news.show', $newsItem->id) }}" class="block flex-1 flex flex-col">

                                    {{-- GAMBAR: Mengambil dari storage dengan fallback anti-404 --}}
                                    @php
                                        $newsCover = $newsItem->image ? img_url($newsItem->image, 900, 520) : $newsFallbackImage;
                                    @endphp
                                    <img src="{{ $newsCover }}" alt="{{ $newsItem->title }}" loading="lazy" width="900" height="520"
                                        onerror="this.onerror=null;this.src='{{ $newsFallbackImage }}';"
                                        class="w-full h-48 sm:h-56 object-cover">

                                    <div class="p-5 sm:p-6 flex flex-col flex-grow">
                                        {{-- JUDUL: Mengambil dari database --}}
                                        <p class="text-gray-800 font-semibold leading-relaxed line-clamp-2 sm:line-clamp-3">
                                            {{ $newsItem->title }}
                                        </p>

                                        {{-- TANGGAL: Mengambil dari database dan diformat --}}
                                        <p class="mt-auto pt-4 text-xs sm:text-sm text-gray-400">
                                            {{ \Carbon\Carbon::parse($newsItem->date_published)->translatedFormat('d F Y') }}
                                        </p>
                                    </div>
                                </a>
                            </div>
                        @empty
                            {{-- Tampilan jika tidak ada berita sama sekali --}}
                            <div class="col-span-1 sm:col-span-2 lg:col-span-4 text-center py-12">
                                <p class="text-gray-500">Belum ada berita untuk ditampilkan.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Tombol "Lihat Lebih Banyak" --}}
                    <div class="text-center mt-12">
                        {{-- Ganti '#' dengan link ke halaman daftar berita --}}
                        <a href="{{ route('public.news.index') }}"
                            class="inline-block bg-white border border-gray-200 rounded-xl px-6 py-4 text-sm font-semibold shadow-sm hover:shadow-lg hover:border-gray-300 transition-all duration-300">
                            <span class="text-gray-600">Mau Baca Lebih Banyak?</span>
                            <span class="ml-1 font-bold" style="color: {{ $amaliahGreen }};">Ayo Kesini</span>
                            <i class="fas fa-chevron-right ml-2 text-xs" style="color: {{ $amaliahGreen }};"></i>
                        </a>
                    </div>
                </div>
            </section>

            <section class="bg-[#ffffff] py-16 sm:py-24 mt-[-50px] fade-in-section">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                    <!-- Judul dan Deskripsi Section -->
                    <div class="max-w-3xl mx-auto text-center mt-[-30px]">
                        <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">
                            Discover Our Story
                        </h2>
                        <p class="mt-4 text-lg text-gray-600">
                            Watch the video below to get a glimpse into our values, mission, and the people behind our
                            success.
                        </p>
                    </div>

                    <!-- Kontainer Video Responsif 16:9 -->
                    <div class="mt-12 max-w-4xl mx-auto">
                        <div class="relative w-full" style="padding-top: 56.25%;">
                            <!--
                                                                                              Catatan: padding-top: 56.25% adalah hasil dari 9 / 16,
                                                                                              yang menciptakan rasio aspek 16:9 yang responsif.
                                                                                            -->
                            <iframe class="absolute top-0 left-0 w-full h-full rounded-xl shadow-2xl"
                                src="https://www.youtube-nocookie.com/embed/STOhZZmY6Co?si=34QAmdyIwXbAXs-7&amp;controls=0"
                                loading="lazy" title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                            </iframe>
                        </div>
                    </div>

                </div>
            </section>
            <section class="py-16 sm:py-24" style="background-color: {{ $amaliahDark }}; fade-in-section">
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
                        <h2 class="text-3xl md:text-4xl font-bold text-white">Fasilitas</h2>
                        <p class="mt-2 text-gray-400">Get To Know About Our Facilities</p>
                        <div class="w-24 h-px bg-gray-600 mx-auto mt-4"></div>
                    </div>

                    {{-- Galeri Gambar Mozaik Dinamis Responsif --}}
                    @php
                        $facilityFallbacks = [
                            'assets/image/Classmeet.JPG',
                            'assets/image/DroneView.jpg',
                            'assets/image/17Agustus.JPG',
                            'assets/image/Classmeet.JPG',
                            'assets/image/DroneView.jpg',
                        ];
                    @endphp
                    <div class="mt-8 sm:mt-12 w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 md:grid-rows-2 gap-3 sm:gap-4 h-auto md:h-[32rem]">

                        {{-- Gambar 1 (Tinggi di Kiri pada desktop) --}}
                        <div class="col-span-1 md:row-span-2 h-48 sm:h-56 md:h-full rounded-xl overflow-hidden shadow-sm">
                            @php
                                $f1 = isset($facilities[0]) && $facilities[0]->image ? img_url($facilities[0]->image, 1000, 625) : asset($facilityFallbacks[0]);
                            @endphp
                            <img src="{{ $f1 }}" alt="{{ $facilities[0]->name ?? 'Fasilitas 1' }}" loading="lazy" width="1000" height="625"
                                onerror="this.onerror=null;this.src='{{ asset($facilityFallbacks[0]) }}';"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>

                        {{-- Gambar 2 (Tengah Atas) --}}
                        <div class="col-span-1 md:row-span-1 h-44 sm:h-48 md:h-full rounded-xl overflow-hidden shadow-sm">
                            @php
                                $f2 = isset($facilities[1]) && $facilities[1]->image ? img_url($facilities[1]->image, 1000, 625) : asset($facilityFallbacks[1]);
                            @endphp
                            <img src="{{ $f2 }}" alt="{{ $facilities[1]->name ?? 'Fasilitas 2' }}" loading="lazy" width="1000" height="625"
                                onerror="this.onerror=null;this.src='{{ asset($facilityFallbacks[1]) }}';"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>

                        {{-- Gambar 3 (Kanan Atas) --}}
                        <div class="col-span-1 sm:col-span-2 md:col-span-2 md:row-span-1 h-44 sm:h-48 md:h-full rounded-xl overflow-hidden shadow-sm">
                            @php
                                $f3 = isset($facilities[2]) && $facilities[2]->image ? img_url($facilities[2]->image, 1000, 625) : asset($facilityFallbacks[2]);
                            @endphp
                            <img src="{{ $f3 }}" alt="{{ $facilities[2]->name ?? 'Fasilitas 3' }}" loading="lazy" width="1000" height="625"
                                onerror="this.onerror=null;this.src='{{ asset($facilityFallbacks[2]) }}';"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>

                        {{-- Gambar 4 (Tengah Bawah) --}}
                        <div class="col-span-1 md:row-span-1 h-44 sm:h-48 md:h-full rounded-xl overflow-hidden shadow-sm">
                            @php
                                $f4 = isset($facilities[3]) && $facilities[3]->image ? img_url($facilities[3]->image, 1000, 625) : asset($facilityFallbacks[3]);
                            @endphp
                            <img src="{{ $f4 }}" alt="{{ $facilities[3]->name ?? 'Fasilitas 4' }}" loading="lazy" width="1000" height="625"
                                onerror="this.onerror=null;this.src='{{ asset($facilityFallbacks[3]) }}';"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>

                        {{-- Gambar 5 (Kanan Bawah) --}}
                        <div class="col-span-1 sm:col-span-2 md:col-span-2 md:row-span-1 h-44 sm:h-48 md:h-full rounded-xl overflow-hidden shadow-sm">
                            @php
                                $f5 = isset($facilities[4]) && $facilities[4]->image ? img_url($facilities[4]->image, 1000, 625) : asset($facilityFallbacks[4]);
                            @endphp
                            <img src="{{ $f5 }}" alt="{{ $facilities[4]->name ?? 'Fasilitas 5' }}" loading="lazy" width="1000" height="625"
                                onerror="this.onerror=null;this.src='{{ asset($facilityFallbacks[4]) }}';"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>
                    </div>

                    {{-- Tombol Selengkapnya --}}
                    <div class="text-right mt-6">
                        <a href="{{ route('public.facilities.index') }}" class="inline-flex items-center group">
                            <span class="text-sm font-semibold text-white mr-3">Selengkapnya</span>
                            <div
                                class="bg-gray-200 rounded-full p-2 group-hover:bg-gray-300 transition-transform duration-300 group-hover/button:translate-x-1 ease-in-out group-hover:translate-x-1">
                                <i class="fas fa-arrow-right text-gray-800 text-sm"></i>
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

            <section class="bg-gray-50 py-16 sm:py-24 fade-in-section">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                    {{-- Header Section --}}
                    <div class="text-center">
                        {{-- ... Kode header tetap sama ... --}}
                        <h2 class="text-3xl md:text-4xl font-bold" style="color: {{ $amaliahDark }};">Testimoni</h2>
                        <div class="flex items-center justify-center gap-x-2 mx-auto mt-4">
                            <div class="w-20 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                            <div class="w-4 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                            <div class="w-4 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                        </div>
                    </div>

                    {{-- Slider Testimoni: Satu Kartu + Otomatis (Alpine.js) --}}
<div class="mx-auto max-w-3xl mt-12" x-data="{
        index: 0,
        total: {{ $testimonials->count() }},
        timer: null,
        init() {
            if (this.total > 1) {
                this.timer = setInterval(() => this.next(), 5000);
            }
        },
        destroy() {
            if (this.timer) { clearInterval(this.timer); }
        },
        next() { this.index = (this.index + 1) % this.total; },
        prev() { this.index = (this.index - 1 + this.total) % this.total; },
        go(i) { this.index = i; }
    }"
    @mouseenter="if (this.timer) { clearInterval(this.timer); this.timer = null; }"
    @mouseleave="if (!this.timer && this.total > 1) { this.timer = setInterval(() => this.next(), 5000); }">

    <div x-cloak class="relative">
        @forelse ($testimonials as $i => $t)
            <div x-show="index === {{ $i }}"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-3"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="bg-white border border-gray-200 rounded-2xl shadow-sm px-7 py-8 sm:px-12 sm:py-12 text-center">
                <div class="ts-quote"><i class="fas fa-quote-left"></i></div>
                <p class="text-gray-700 text-sm sm:text-lg leading-relaxed mt-4 sm:mt-6">"{{ $t->description }}"</p>
                <div class="mt-6 sm:mt-8 flex items-center justify-center gap-3 sm:gap-4">
                    @if ($t->photo)
<img src="{{ img_url($t->photo, 96, 96) }}" alt="{{ $t->name }}" loading="lazy" width="96" height="96"
                            class="w-12 h-12 sm:w-16 sm:h-16 rounded-full object-cover shadow-md">
                    @else
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center text-white font-bold text-sm sm:text-base"
                            style="background-color: {{ $amaliahGreen }}">{{ collect(explode(' ', $t->name))->take(2)->map(fn($w) => strtoupper(mb_substr($w, 0, 1)))->implode('') }}</div>
                    @endif
                    <div class="text-left">
                        <p class="font-bold text-gray-900 text-sm sm:text-base">{{ $t->name }}</p>
                        <p class="text-xs sm:text-sm" style="color: {{ $amaliahGreen }};">
                            Alumni Jurusan {{ $t->major->name ?? 'N/A' }} · {{ $t->alumni_year }}
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
                <p class="text-gray-500">Belum ada testimoni untuk ditampilkan.</p>
            </div>
        @endforelse
    </div>

    @if ($testimonials->count() > 1)
        {{-- Tombol Navigasi Desktop (Samping Kartu) --}}
        <button @click="prev()" class="ts-arrow ts-arrow-left hidden sm:flex" aria-label="Testimoni sebelumnya">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button @click="next()" class="ts-arrow ts-arrow-right hidden sm:flex" aria-label="Testimoni berikutnya">
            <i class="fas fa-chevron-right"></i>
        </button>

        {{-- Kontrol Navigasi Mobile & Indikator Dots --}}
        <div class="flex items-center justify-center gap-3 mt-6">
            <button @click="prev()" class="sm:hidden w-8 h-8 rounded-full bg-gray-800 text-white flex items-center justify-center hover:bg-[#63cd00] transition shadow-sm" aria-label="Testimoni sebelumnya">
                <i class="fas fa-chevron-left text-xs"></i>
            </button>
            <div class="ts-dots !mt-0">
                @foreach ($testimonials as $i => $t)
                    <button class="ts-dot" :class="{ 'active': index === {{ $i }} }" @click="go({{ $i }})"
                        :aria-label="'Ke testimoni {{ $i + 1 }}'"></button>
                @endforeach
            </div>
            <button @click="next()" class="sm:hidden w-8 h-8 rounded-full bg-gray-800 text-white flex items-center justify-center hover:bg-[#63cd00] transition shadow-sm" aria-label="Testimoni berikutnya">
                <i class="fas fa-chevron-right text-xs"></i>
            </button>
        </div>
    @endif

    <style>
        [x-cloak] {
            display: none !important;
        }

        .ts-quote {
            font-size: 32px;
            line-height: 1;
            color: #63cd00;
        }

        @media (min-width: 640px) {
            .ts-quote { font-size: 42px; }
        }

        .ts-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            border-radius: 9999px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #282829;
            color: #fff;
            box-shadow: 0 4px 12px rgba(40, 40, 41, .25);
            transition: transform .2s, opacity .2s;
            z-index: 10;
        }

        .ts-arrow:hover {
            opacity: .85;
            transform: translateY(-50%) scale(1.05);
        }

        .ts-arrow-left {
            left: 6px;
        }

        .ts-arrow-right {
            right: 6px;
        }

        @media (min-width: 640px) {
            .ts-arrow {
                width: 44px;
                height: 44px;
            }
        }

        @media (min-width: 840px) {
            .ts-arrow-left {
                left: -22px;
            }

            .ts-arrow-right {
                right: -22px;
            }
        }

        .ts-dots {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
        }

        .ts-dot {
            width: 8px;
            height: 8px;
            border-radius: 9999px;
            border: none;
            cursor: pointer;
            background: #d1d5db;
            padding: 0;
            transition: all .3s;
        }

        .ts-dot.active {
            width: 28px;
            background: #282829;
        }
    </style>
</div>

                    {{-- Tombol "Baca Semua" --}}
                    <div class="text-center mt-12">
                        <a href="{{ route('public.about.index') }}"
                            class="inline-block bg-white border border-gray-300 rounded-xl px-6 py-3 text-sm font-semibold text-gray-800 shadow-sm hover:shadow-lg hover:border-gray-400 transition-all duration-300">
                            Baca Testimoni Alumni SMK Amaliah
                        </a>
                    </div>

                </div>
            </section>
            {{-- ================================================================= --}}
            {{-- SECTION INSTAGRAM (GRID ALA INSTAGRAM) --}}
            {{-- ================================================================= --}}
            <section class="social-feed-section bg-white py-16 sm:py-24 fade-in-section">

                <div class="social-feed-container max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="social-feed-grid grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                        <div class="min-w-0">
                            <div class="text-center mb-10">
                                <h2 class="text-3xl md:text-4xl font-bold" style="color: {{ $amaliahDark }};">
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
                            </div>
                        </div>

                        <div class="min-w-0">
                            <div class="text-center mb-10">
                                <h2 class="text-3xl md:text-4xl font-bold" style="color: {{ $amaliahDark }};">
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

                    .ig-card {
                        width: 100%;
                        max-width: 935px;
                        background: #fff;
                        border: 1px solid #dbdbdb;
                        border-radius: 4px;
                        overflow: hidden;
                    }

                    .ig-head {
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        padding: 14px 16px;
                        border-bottom: 1px solid #dbdbdb;
                    }

                    .tiktok-card {
                        display: flex;
                        flex-direction: column;
                        gap: 0;
                        width: 100%;
                        min-width: 0;
                        overflow: hidden;
                        border: 1px solid #dbdbdb;
                        border-radius: 4px;
                        background: #fff;
                    }

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

            <section class="bg-gray-50 py-16 sm:py-24 fade-in-section">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                    {{-- Tombol Virtual Tour di Atas (Responsif) --}}
                    <div class="text-center mb-8 sm:mb-10">
                        <a href="https://yourdisc710.itch.io/amaliah-tour"
                            class="inline-flex items-center bg-white border border-gray-300 rounded-full px-5 sm:px-8 py-3 sm:py-4 text-xs sm:text-base font-semibold shadow-md hover:shadow-lg hover:border-gray-400 transition-all duration-300 group max-w-full">
                            <span class="text-gray-800">Mau Lihat SMK Amaliah?</span>
                            <span class="ml-1.5 sm:ml-2 font-bold" style="color: {{ $amaliahGreen }};">Virtual Tour!</span>
                            <div
                                class="ml-2.5 sm:ml-4 bg-white rounded-full p-1.5 sm:p-2 flex items-center justify-center border border-gray-300 group-hover:border-gray-400 transition-all flex-shrink-0">
                                <i class="fas fa-chevron-right text-xs sm:text-sm" style="color: {{ $amaliahGreen }};"></i>
                            </div>
                        </a>
                    </div>

                    {{-- Container Utama untuk Peta dan Info Responsif --}}
                    <div class="relative w-full rounded-2xl overflow-hidden shadow-2xl h-[320px] sm:h-[420px] md:h-[500px]">

                        {{-- KODE IFRAME GOOGLE MAPS --}}
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
