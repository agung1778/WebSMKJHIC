@extends('layouts.public-app')
@section('description', 'Selamat datang di SMK Amaliah. Kami memiliki jurusan TKJ, RPL, DKV, dan Akuntansi.')
@section('title', 'SMK Amaliah 1 & 2')

@section('content')
    <style>
        .hero-clip-path {
            clip-path: polygon(0 0, 100% 0, 100% calc(100% - 4rem), calc(100% - 4rem) 100%, 0 100%);
            will-change: transform;
        }

        /* Aturan ini akan aktif jika lebar layar 768px atau kurang */
        @media (max-width: 768px) {
            .custom-none {
                display: none;
            }
        }
    </style>


    <div class="font-['Poppins'] bg-gray-100">
        <div id="loader-wrapper">
            <div class="loader-content">
                <div class="loader-spinner"></div>
                <p class="loader-message">Tenang, pengalaman terbaik sedang kami siapkan untuk Anda.</p>
            </div>
        </div>
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

                        <div class="relative h-[550px] overflow-hidden hero-clip-path rounded-3xl">

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

                                        <img src="{{ Storage::url($image->path) }}"
                                            alt="{{ $image->description ?? $image->filename }}" class="w-full h-full object-cover"
                                            {{--==========================================================--}} {{-- PERBAIKAN LCP:
                                            Tambahkan fetchpriority dan loading --}}
                                            {{--==========================================================--}} @if($loop->first)
                                            fetchpriority="high" @endif loading="eager">
                                    </div>
                                @endforeach

                                {{-- Tombol "Watch Video" di Pojok Kanan Atas --}}
                                <button @click="showVideo = true"
                                    class="absolute top-6 right-6 z-20 flex items-center gap-2 bg-black/50 backdrop-blur-sm text-white px-4 py-2 rounded-full hover:bg-black/70 transition-all duration-300">
                                    <svg xmlns="https://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-semibold">Watch Video</span>
                                </button>
                            </div>

                            {{-- Kontainer Iframe YouTube (Hanya tampil jika showVideo true) --}}
                            <div x-show="showVideo" x-cloak class="w-full h-full">
                                {{-- Iframe yang sudah dimodifikasi --}}
                                <iframe class="w-full h-full"
                                    :src="showVideo ? 'https://www.youtube-nocookie.com/embed/STOhZZmY6Co?autoplay=1&mute=1&controls=0&loop=1&playlist=STOhZZmY6Co&rel=0&iv_load_policy=3&modestbranding=1' : ''"
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen>
                                </iframe>

                                {{-- Tombol "Close" untuk Video --}}
                                <button @click="showVideo = false" aria-label="Tutup video"
                                    class="absolute top-6 right-6 z-20 flex items-center justify-center w-10 h-10 bg-black/50 backdrop-blur-sm text-white rounded-full hover:bg-black/70 transition-all duration-300">
                                    <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Bagian bawah (kartu berita dan logo) --}}
                       <div class="absolute bottom-12 left-8 md:left-12 z-10 w-[calc(100%-4rem)] max-w-md md:!block"
        x-show="!showVideo"
        x-transition:enter="transition transform ease-in-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition transform ease-in-out duration-500"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-10">

        {{-- KODE LOGO ANDA (LENGKAP) --}}
        <div class="bg-white/90 backdrop-blur-md border border-white/30 rounded-xl p-3 shadow-lg mb-4">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center justify-between w-full pr-2">
                    <img src="{{ asset('assets/logo/infra.webp') }}" alt="Logo Partner 1"
                        class="h-7 object-contain transition duration-300">
                    <img src="{{ asset('assets/logo/jh.webp') }}" alt="Logo Partner 5"
                        class="h-7 object-contain transition duration-300">
                    <img src="{{ asset('assets/logo/komdigi.webp') }}" alt="Logo Partner 2"
                        class="h-7 object-contain transition duration-300">
                    <img src="{{ asset('assets/logo/maspionit.webp') }}" alt="Logo Partner 3"
                        class="h-7 object-contain transition duration-300">
                    <img src="{{ asset('assets/logo/gspark.webp') }}" alt="Logo Partner 4"
                        class="h-7 object-contain transition duration-300">
                </div>

                <a href="https://jagoanhosting.com/" aria-label="Lihat semua partner industri"
                    class="text-[#282829] hover:text-gray-600 transition-colors flex-shrink-0">
                    <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Kontainer Slider Kartu Berita --}}
        <div class="relative w-full h-auto min-h-[250px] overflow-hidden hero-clip-path ">

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
                    class="flex flex-col h-full bg-white/90 backdrop-blur-lg p-6 rounded-2xl shadow-2xl border border-white/30">
                    <h1 class="text-xl font-bold text-gray-900 leading-tight line-clamp-2">
                        {{ $news->title }}
                    </h1>
                    <p class="text-sm mt-2 text-gray-700 line-clamp-3 flex-grow">
                        {{ strip_tags($news->description) }}
                    </p>
                    <p class="text-xs font-medium text-gray-500 mt-4">
                        Diterbitkan
                        {{ \Carbon\Carbon::parse($news->date_published)->translatedFormat('d F Y') }}
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('public.news.show', $news) }}"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-[#282829] px-4 py-2 rounded-full hover:bg-black transition-all duration-300 group">
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
                    {{-- Fallback jika tidak ada data --}}
                    <div class="relative h-[550px] overflow-hidden hero-clip-path rounded-3xl bg-black"></div>
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

                    {{-- Kolom Kanan: Grid Gambar Dinamis dari Database --}}
                    <div class="grid grid-cols-3 grid-rows-3 gap-4 h-96">

                        {{-- Gambar 1 (Slot Paling Kiri, Tinggi) --}}
                        @if(isset($gridImages[0]))
                            <img src="{{ Storage::url($gridImages[0]->path) }}" alt="Grid Image 1"
                                class="w-full h-full object-cover rounded-lg row-span-2">
                        @else
                            <div class="bg-gray-200 rounded-lg row-span-2"></div>
                        @endif

                        {{-- Gambar 2 (Slot Kanan Atas, Besar) --}}
                        @if(isset($gridImages[1]))
                            <img src="{{ Storage::url($gridImages[1]->path) }}" alt="Grid Image 2"
                                class="w-full h-full object-cover rounded-lg col-span-2 row-span-2">
                        @else
                            <div class="bg-gray-200 rounded-lg col-span-2 row-span-2"></div>
                        @endif

                        {{-- Gambar 3 (Slot Kiri Bawah) --}}
                        @if(isset($gridImages[2]))
                            <img src="{{ Storage::url($gridImages[2]->path) }}" alt="Grid Image 3"
                                class="w-full h-full object-cover rounded-lg">
                        @else
                            <div class="bg-gray-200 rounded-lg"></div>
                        @endif

                        {{-- Gambar 4 (Slot Tengah Bawah) --}}
                        @if(isset($gridImages[3]))
                            <img src="{{ Storage::url($gridImages[3]->path) }}" alt="Grid Image 4"
                                class="w-full h-full object-cover rounded-lg">
                        @else
                            <div class="bg-gray-200 rounded-lg"></div>
                        @endif

                        {{-- Gambar 5 (Slot Kanan Bawah) --}}
                        @if(isset($gridImages[4]))
                            <img src="{{ Storage::url($gridImages[4]->path) }}" alt="Grid Image 5"
                                class="w-full h-full object-cover rounded-lg">
                        @else
                            <div class="bg-gray-200 rounded-lg"></div>
                        @endif

                    </div>
                </div>
            </section>
            @php
                // Definisikan variabel warna di atas agar mudah diakses
                $amaliahDark = '#282829';

                // Data untuk bagian statistik, disesuaikan dengan referensi gambar
                $stats = [
                    ['icon' => 'fa-users', 'number' => '1160 +', 'label' => 'Peserta Didik'],
                    ['icon' => 'fa-rocket', 'number' => '80 +', 'label' => 'Tenaga Pendidik'],
                    ['icon' => 'fa-star', 'number' => '40 +', 'label' => 'Fasilitas Unggulan'],
                    ['icon' => 'fa-graduation-cap', 'number' => '85%', 'label' => 'Alumni cepat dapat kerja'],
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
                                            <img src="{{ asset('storage/' . $partner->logo) }}" alt="Logo {{ $partner->name }}"
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
                                            <img src="{{ asset('storage/' . $partner->logo) }}" alt="Logo {{ $partner->name }}"
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
                                    <img src="{{ asset('storage/' . $majorGridImages[0]->path) }}"
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
                                    <img src="{{ asset('storage/' . $majorGridImages[1]->path) }}"
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
                            class="lg:col-span-3 flex space-x-8 overflow-x-auto snap-x snap-mandatory scroll-smooth scrollbar-hide pb-4 -mx-4 px-4">

                            {{-- Loop dinamis untuk Kartu Jurusan dari database --}}
                            @foreach ($majors as $major)
                                <div class="flex-shrink-0 w-80 snap-start flex flex-col slider-item">

                                    {{-- Menampilkan Gambar Jurusan dengan fallback --}}
                                    @if ($major->image)
                                        <img src="{{ asset('storage/' . $major->image) }}" alt="Gambar Jurusan {{ $major->name }}"
                                            class="h-48 w-full rounded-lg object-cover mb-4">
                                    @else
                                        {{-- Placeholder jika tidak ada gambar --}}
                                        <div
                                            class="h-48 w-full rounded-lg object-cover mb-4 bg-gray-700 flex items-center justify-center">
                                            <i class="fas fa-image text-4xl text-gray-500"></i>
                                        </div>
                                    @endif

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
                        <div class="lg:hidden mt-6 flex items-center space-x-4">
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


                    <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        {{-- Menggunakan @forelse untuk menangani jika tidak ada berita --}}
                        @forelse ($latestNews as $newsItem)
                            <div
                                class="bg-white rounded-2xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                {{-- Ganti '#' dengan link detail berita jika ada, misal: route('news.show', $newsItem->id) --}}
                                <a href="{{ route('public.news.show', $newsItem->id) }}" class="block">

                                    {{-- GAMBAR: Mengambil dari storage --}}
                                    <img src="{{ asset('storage/' . $newsItem->image) }}" alt="{{ $newsItem->title }}"
                                        class="w-full h-56 object-cover">

                                    <div class="p-6">
                                        {{-- JUDUL: Mengambil dari database --}}
                                        <p class="text-gray-800 font-semibold leading-relaxed line-clamp-3">
                                            {{ $newsItem->title }}
                                        </p>

                                        {{-- TANGGAL: Mengambil dari database dan diformat --}}
                                        <p class="mt-4 text-sm text-gray-400">
                                            {{ \Carbon\Carbon::parse($newsItem->date_published)->format('Y-m-d') }}
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

            {{--
            {{--
            ============================================================
            == SECTION PARTNER (LOGO DIPERBESAR) ==
            ============================================================
            --}}
            <section class="bg-white py-12 sm:py-16 mt-40px">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-6">

                        {{-- Logo 1: Infra --}}
                        <a href="#" target="_blank" rel="noopener" aria-label="Infra"
                            class="block bg-white p-2 rounded-md opacity-70 hover:opacity-100 transition-opacity duration-300 border border-dashed border-gray-300">
                            {{-- Ukuran diubah dari h-12 menjadi h-16 --}}
                            <img src="{{ asset('assets/logo/infra.webp') }}" alt="Logo Infra" class="h-16">
                        </a>

                        {{-- Logo 2: Jagoan Hosting --}}
                        <a href="#" target="_blank" rel="noopener" aria-label="Jagoan Hosting"
                            class="block bg-white p-2 rounded-md opacity-70 hover:opacity-100 transition-opacity duration-300 border border-dashed border-gray-300">
                            {{-- Ukuran diubah dari h-12 menjadi h-16 --}}
                            <img src="{{ asset('assets/logo/jh.webp') }}" alt="Logo Jagoan Hosting" class="h-16">
                        </a>

                        {{-- Logo 3: Komdigi --}}
                        <a href="#" target="_blank" rel="noopener" aria-label="Komdigi"
                            class="block bg-white p-2 rounded-md opacity-70 hover:opacity-100 transition-opacity duration-300 border border-dashed border-gray-300">
                            {{-- Ukuran diubah dari h-12 menjadi h-16 --}}
                            <img src="{{ asset('assets/logo/komdigi.webp') }}" alt="Logo Komdigi" class="h-16">
                        </a>

                        {{-- Logo 4: Maspion IT --}}
                        <a href="#" target="_blank" rel="noopener" aria-label="Maspion IT"
                            class="block bg-white p-2 rounded-md opacity-70 hover:opacity-100 transition-opacity duration-300 border border-dashed border-gray-300">
                            {{-- Ukuran diubah dari h-12 menjadi h-16 --}}
                            <img src="{{ asset('assets/logo/maspionit.webp') }}" alt="Logo Maspion IT" class="h-16">
                        </a>

                        {{-- Logo 5: GSpark --}}
                        <a href="#" target="_blank" rel="noopener" aria-label="GSpark"
                            class="block bg-white p-2 rounded-md opacity-70 hover:opacity-100 transition-opacity duration-300 border border-dashed border-gray-300">
                            {{-- Ukuran diubah dari h-12 menjadi h-16 --}}
                            <img src="{{ asset('assets/logo/gspark.webp') }}" alt="Logo GSpark" class="h-16">
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

                    {{-- Galeri Gambar Mozaik Dinamis --}}
                    <div class="mt-12 w-full h-[30rem] md:h-[32rem] grid grid-cols-2 md:grid-cols-4 grid-rows-2 gap-4">

                        {{-- Gambar 1 (Tinggi di Kiri) --}}
                        <div class="col-span-1 row-span-2 rounded-xl overflow-hidden">
                            @if (isset($facilities[0]) && $facilities[0]->image)
                                <img src="{{ asset('storage/' . $facilities[0]->image) }}" alt="{{ $facilities[0]->name }}"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                            @else
                                {{-- Placeholder jika gambar tidak ada --}}
                                <div class="w-full h-full bg-black"></div>
                            @endif
                        </div>

                        {{-- Gambar 2 (Tengah Atas) --}}
                        <div class="col-span-1 row-span-1 rounded-xl overflow-hidden">
                            @if (isset($facilities[1]) && $facilities[1]->image)
                                <img src="{{ asset('storage/' . $facilities[1]->image) }}" alt="{{ $facilities[1]->name }}"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                            @else
                                <div class="w-full h-full bg-black"></div>
                            @endif
                        </div>

                        {{-- Gambar 3 (Kanan Atas) --}}
                        <div class="col-span-1 md:col-span-2 row-span-1 rounded-xl overflow-hidden">
                            @if (isset($facilities[2]) && $facilities[2]->image)
                                <img src="{{ asset('storage/' . $facilities[2]->image) }}" alt="{{ $facilities[2]->name }}"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                            @else
                                <div class="w-full h-full bg-black"></div>
                            @endif
                        </div>

                        {{-- Gambar 4 (Tengah Bawah) --}}
                        <div class="col-span-1 row-span-1 rounded-xl overflow-hidden">
                            @if (isset($facilities[3]) && $facilities[3]->image)
                                <img src="{{ asset('storage/' . $facilities[3]->image) }}" alt="{{ $facilities[3]->name }}"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                            @else
                                <div class="w-full h-full bg-black"></div>
                            @endif
                        </div>

                        {{-- Gambar 5 (Kanan Bawah) --}}
                        <div class="col-span-1 md:col-span-2 row-span-1 rounded-xl overflow-hidden">
                            @if (isset($facilities[4]) && $facilities[4]->image)
                                <img src="{{ asset('storage/' . $facilities[4]->image) }}" alt="{{ $facilities[4]->name }}"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                            @else
                                <div class="w-full h-full bg-black"></div>
                            @endif
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

                    {{-- Slider Testimoni (Alpine.js + Tailwind CSS) --}}
                    <div x-data="{
                                                                                                                                                                                                                                                                                                                                                                                                                                                    slider: null,
                                                                                                                                                                                                                                                                                                                                                                                                                                                    init() {
                                                                                                                                                                                                                                                                                                                                                                                                                                                        this.slider = this.$refs.sliderContainer;
                                                                                                                                                                                                                                                                                                                                                                                                                                                    },
                                                                                                                                                                                                                                                                                                                                                                                                                                                    scroll(direction) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                        // Geser sejauh 80% dari lebar area yang terlihat
                                                                                                                                                                                                                                                                                                                                                                                                                                                        let scrollAmount = this.slider.offsetWidth * 0.8;
                                                                                                                                                                                                                                                                                                                                                                                                                                                        this.slider.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
                                                                                                                                                                                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                                                                                                                                                                                }"
                        class="mt-12 relative">
                        {{-- Tombol Panah Kiri --}}
                        <button @click="scroll(-1)"
                            class="absolute top-1/2 -left-2 md:-left-8 -translate-y-1/2 w-12 h-12 rounded-full shadow-lg flex items-center justify-center z-10 hover:bg-opacity-80 transition"
                            style="background-color: {{ $amaliahDark }};" id="testimonialbutton" role="presentation"
                            aria-label="Testimoni sebelumnya">
                            <i class="fas fa-chevron-left text-white"></i>
                        </button>
                        {{-- Container yang bisa di-scroll --}}
                        <div x-ref="sliderContainer"
                            class="flex space-x-8 overflow-x-auto snap-x snap-mandatory scroll-smooth scrollbar-hide py-4">

                            @forelse ($testimonials as $testimonial)
                                {{-- Setiap Kartu Testimoni --}}
                                <div class="flex-shrink-0 w-full sm:w-[48%] snap-start">
                                    <div
                                        class="bg-white border border-gray-200 rounded-2xl p-8 flex flex-col sm:flex-row items-center gap-8 h-full">
                                        {{-- Kolom Teks --}}
                                        <div class="flex-1 text-center sm:text-left">
                                            <p class="text-gray-700 leading-relaxed">"{{ $testimonial->description }}"</p>
                                            <p class="mt-4 text-gray-800 font-semibold italic">-{{ $testimonial->name }}</p>
                                            <div class="mt-6 flex flex-col sm:flex-row justify-between items-center text-sm">
                                                <span class="font-semibold mt-2 sm:mt-0" style="color: {{ $amaliahGreen }};">
                                                    Alumni Jurusan {{ $testimonial->major->name ?? 'N/A' }}
                                                    {{ $testimonial->alumni_year }}
                                                </span>
                                            </div>
                                        </div>
                                        {{-- Kolom Gambar --}}
                                        <div class="flex-shrink-0 order-first sm:order-last">
                                            <img src="{{ asset('storage/' . $testimonial->photo) }}"
                                                alt="Foto {{ $testimonial->name }}"
                                                class="w-32 h-32 rounded-full object-cover shadow-md">
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="w-full text-center py-12">
                                    <p class="text-gray-500">Belum ada testimoni untuk ditampilkan.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Tombol Panah Kanan --}}
                        <button @click="scroll(1)"
                            class="absolute top-1/2 -right-2 md:-right-8 -translate-y-1/2 w-12 h-12 rounded-full shadow-lg flex items-center justify-center z-10 hover:bg-opacity-80 transition"
                            style="background-color: {{ $amaliahDark }};" id="testimonialbutton" role="presentation"
                            aria-label="Testimoni berikutnya">
                            <i class="fas fa-chevron-right text-white"></i>
                        </button>
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
            <section class="bg-[#282829] py-16 sm:py-20 overflow-hidden fade-in-section">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'amaliah1' }">

                    {{-- Bagian Header: Judul dan Tombol Tab --}}
                    <div class="text-center mb-12">
                        <h2 class="text-3xl lg:text-4xl font-extrabold text-white tracking-tight">
                            Get To Know Our School Leaders
                        </h2>
                        <div class="flex justify-center items-center space-x-2 mt-8">
                            <button @click="activeTab = 'amaliah1'"
                                :class="{
                                                                                                                                                                                                                                                                        'bg-[#63cd00] text-white shadow-lg': activeTab === 'amaliah1',
                                                                                                                                                                                                                                                                        'bg-white text-[#282829] hover:bg-gray-200': activeTab !== 'amaliah1'
                                                                                                                                                                                                                                                                    }"
                                class="px-5 py-2 text-sm font-semibold rounded-full transition-all duration-300"
                                id="amaloahbutton" role="presentation" aria-label="button">
                                SMK Amaliah 1
                            </button>
                            <button @click="activeTab = 'amaliah2'"
                                :class="{
                                                                                                                                                                                                                                                                        'bg-[#63cd00] text-white shadow-lg': activeTab === 'amaliah2',
                                                                                                                                                                                                                                                                        'bg-white text-[#282829] hover:bg-gray-200': activeTab !== 'amaliah2'
                                                                                                                                                                                                                                                                    }"
                                class="px-5 py-2 text-sm font-semibold rounded-full transition-all duration-300"
                                id="amaloahbutton" role="presentation" aria-label="button">
                                SMK Amaliah 2
                            </button>
                        </div>
                    </div>

                    {{-- Bagian Konten: Grid 2 Kolom --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mt-16">

                        {{-- Kolom Kiri: Konten Teks --}}
                        <div class="relative min-h-[280px] text-center lg:text-left">
                            {{-- PROFIL KEPALA SEKOLAH 1 --}}
                            <div x-show="activeTab === 'amaliah1'" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform -translate-x-4"
                                x-transition:enter-end="opacity-100 transform translate-x-0"
                                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0" class="absolute w-full">
                                <h3 class="text-2xl lg:text-3xl font-bold text-white">Tisna Sudrajat S.Kom., Gr., ACA</h3>
                                <p class="text-base text-[#63cd00] mt-1 mb-4">Kepala Sekolah SMK Amaliah 1</p>
                                <blockquote class="text-md text-gray-300 italic max-w-lg mx-auto lg:mx-0">
                                    "Pendidikan adalah paspor masa depan karena hari esok adalah milik mereka yang
                                    mempersiapkannya hari ini."
                                </blockquote>
                                <div class="flex items-center space-x-3 mt-6 justify-center lg:justify-start">
                                    <a href="https://www.facebook.com/" aria-label="Facebook"
                                        class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-700 hover:bg-gray-600 text-white transition-colors"><i
                                            class="fab fa-facebook-f"></i></a>
                                    <a href="https://www.instagram.com/" aria-label="Instagram"
                                        class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-700 hover:bg-gray-600 text-white transition-colors"><i
                                            class="fab fa-instagram"></i></a>
                                    <a href="https://id.linkedin.com/" aria-label="LinkedIn"
                                        class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-700 hover:bg-gray-600 text-white transition-colors"><i
                                            class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>

                            {{-- PROFIL KEPALA SEKOLAH 2 --}}
                            <div x-show="activeTab === 'amaliah2'" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform -translate-x-4"
                                x-transition:enter-end="opacity-100 transform translate-x-0"
                                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0" class="absolute w-full" style="display: none;">
                                <h3 class="text-2xl lg:text-3xl font-bold text-white">Dr. Gugun Gunadi, S.Pd.I., M.Pd.</h3>
                                <p class="text-base text-[#63cd00] mt-1 mb-4">Kepala Sekolah SMK Amaliah 2</p>
                                <blockquote class="text-md text-gray-300 italic max-w-lg mx-auto lg:mx-0">
                                    "Sekolah adalah rumah untuk tumbuh — di sini kami membimbing peserta didik tidak hanya
                                    menguasai kompetensi kerja, tetapi juga membentuk akhlak dan sikap profesional. Bersama
                                    orang tua dan mitra industri, kami membuka peluang nyata agar setiap lulusan membangun
                                    masa depan yang bermakna."
                                </blockquote>
                                <div class="flex items-center space-x-3 mt-6 justify-center lg:justify-start">
                                    <a href="https://www.facebook.com/" aria-label="Facebook"
                                        class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-700 hover:bg-gray-600 text-white transition-colors"><i
                                            class="fab fa-facebook-f"></i></a>
                                    <a href="https://www.instagram.com/" aria-label="Instagram"
                                        class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-700 hover:bg-gray-600 text-white transition-colors"><i
                                            class="fab fa-instagram"></i></a>
                                    <a href="https://id.linkedin.com/" aria-label="LinkedIn"
                                        class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-700 hover:bg-gray-600 text-white transition-colors"><i
                                            class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Gambar --}}
                        <div class="relative flex justify-center items-center min-h-[320px] lg:min-h-0">
                            {{-- GAMBAR KEPALA SEKOLAH 1 --}}
                            <div x-show="activeTab === 'amaliah1'" x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 transform scale-90"
                                x-transition:enter-end="opacity-100 transform scale-100" class="absolute">
                                <div
                                    class="w-64 h-64 sm:w-80 sm:h-80 bg-gray-400 rounded-full flex items-center justify-center text-gray-600 shadow-2xl">
                                    <img src="{{ asset('assets/image/kepsek1.webp') }}"
                                        class="w-full h-full object-cover rounded-full items-center"
                                        alt="Kepala Sekolah Amaliah 1">
                                </div>
                            </div>
                            {{-- GAMBAR KEPALA SEKOLAH 2 --}}
                            <div x-show="activeTab === 'amaliah2'" x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 transform scale-90"
                                x-transition:enter-end="opacity-100 transform scale-100" class="absolute"
                                style="display: none;">
                                <div
                                    class="w-64 h-64 sm:w-80 sm:h-80 bg-gray-400 rounded-full flex items-center justify-center text-gray-600 shadow-2xl">
                                    <img src="{{ asset('assets/image/kepsek2.webp') }}"
                                        class="w-full h-full object-cover rounded-full items-center"
                                        alt="Kepala Sekolah Amaliah 2">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            {{-- ================================================================= --}}
            {{-- SECTION INSTAGRAM (SLIDER + GRID DARI CURATOR.IO) --}}
            {{-- ================================================================= --}}
            <section class="bg-white py-16 sm:py-24 space-y-20 fade-in-section">

                {{-- BAGIAN 1: SLIDER (SWIPE) --}}
                <div>
                    {{-- Header untuk slider diletakkan di dalam container agar rapi --}}
                    {{-- Header Section (Tidak ada perubahan) --}}
                    <div class="text-center">
                        <h2 class="text-3xl md:text-4xl font-bold" style="color: {{ $amaliahDark }};">
                            Our Latest Instagram Post
                        </h2>
                        <div class="flex items-center justify-center gap-x-2 mx-auto mt-4">
                            <div class="w-20 h-1 rounded-full" style="background-color: {{ $amaliahDark }};"></div>
                            <div class="w-8 h-1 rounded-full" style="background-color: {{ $amaliahDark }};"></div>
                            <div class="w-4 h-1 rounded-full" style="background-color: {{ $amaliahDark }};"></div>
                        </div>
                    </div>
                </div>

                {{-- BAGIAN 2: GRID --}}
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8  ">


                    {{-- Wadah untuk grid Curator.io --}}
                    <div id="curator-feed-grid-layout">

                        <!-- Place <div> tag where you want the feed to appear -->
                        <div id="curator-feed-default-feed-layout"><a href="https://curator.io" target="_blank"
                                class="crt-logo crt-tag">Powered by Curator.io</a></div>

                        <!-- The Javascript can be moved to the end of the html page before the </div> tag -->
                        <script type="text/javascript">
                            /* curator-feed-default-feed-layout */
                            (function () {
                                var i, e, d = document, s = "script"; i = d.createElement("script"); i.async = 1; i.charset = "UTF-8";
                                i.src = "https://cdn.curator.io/published/9b122a7e-d39e-40c4-abc3-8ab6bc446899.js";
                                e = d.getElementsByTagName(s)[0]; e.parentNode.insertBefore(i, e);
                            })();
                        </script>
                    </div>
                </div>
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

                    {{-- Tombol Virtual Tour di Atas --}}
                    <div class="text-center mb-10">
                        <a href="https://yourdisc710.itch.io/amaliah-tour"
                            class="inline-flex items-center bg-white border border-gray-300 rounded-full px-8 py-4 text-base font-semibold shadow-md hover:shadow-lg hover:border-gray-400 transition-all duration-300 group">
                            <span class="text-gray-800">Mau Lihat SMK Amaliah?</span>
                            <span class="ml-2 font-bold" style="color: {{ $amaliahGreen }};">Masuk Ke Virtual Tour!</span>
                            <div
                                class="ml-4 bg-white rounded-full p-2 flex items-center justify-center border border-gray-300 group-hover:border-gray-400 transition-all">
                                <i class="fas fa-chevron-right text-sm" style="color: {{ $amaliahGreen }};"></i>
                            </div>
                        </a>
                    </div>

                    {{-- Container Utama untuk Peta dan Info --}}
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">

                        {{-- KODE IFRAME GOOGLE MAPS --}}
                        {{-- Pastikan Anda mengganti src="..." dengan kode embed Anda --}}
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.945187355167!2d106.8462900750414!3d-6.653716393341009!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c8eec16c788f%3A0x4680dbde73e8b763!2sSMK%20Amaliah%201%20dan%202%20Ciawi!5e0!3m2!1sid!2sid!4v1759652507072!5m2!1sid!2sid"
                            width="1280" height="600" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade" title="maps"></iframe>

                    </div>
                </div>
            </section>
        </main>
    </div>
@endsection