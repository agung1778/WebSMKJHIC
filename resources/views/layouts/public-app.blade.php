<!DOCTYPE html>
<html lang="id">


<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta name="description"
        content="@yield('description', 'Situs web resmi SMK Amaliah 1 & 2 Ciawi-Bogor. Temukan informasi tentang jurusan, pendaftaran, fasilitas, dan berita terbaru.')">
    <link rel="icon" type="image/webp" href="{{ asset('assets/logo/am.webp') }}">

    <title>@yield('title', 'SMK Amaliah 1 & 2')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    {{-- 2. PERFORMA: Preconnect ke domain penting untuk mempercepat handshake DNS, TCP, dan TLS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Google Fonts: Poppins --}}
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    </noscript>



    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
        }

        .Poppins {
            font-family: 'Poppins', sans-serif;
        }

        .font-times {
            font-family: 'Times New Roman', serif;
        }

        .nav-link {
            @apply relative text-white px-3 py-2 transition-colors duration-300 flex items-center text-[15px];
        }

        .nav-link::after {
            content: '';
            @apply absolute left-0 -bottom-1 w-0 h-[3px] bg-white transition-all duration-300 ease-in-out;
        }

        .nav-link:hover {
            color: #EEFFD9;
        }

        .nav-link:hover::after {
            @apply w-full;
            background-color: #EEFFD9;
        }

        .nav-active {
            @apply font-semibold;
        }

        .nav-active::after {
            @apply w-full bg-white;
        }

        .dropdown-content {
            opacity: 0;
            transform: translateY(-10px);
            visibility: hidden;
            transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s;
        }

        .group:hover .dropdown-content {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }

        [x-cloak] {
            display: none !important;
        }

        .top-bar-active {
            @apply text-[#63cd00] font-semibold;
            border-bottom: 2px solid #63cd00;
            padding-bottom: 4px;
        }

        .fade-in-section {
            opacity: 0;
            transform: translateY(20px);
            /* Bergerak 20px ke bawah */
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .fade-in-section.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        :root {
            --loader-bg-color: #ffffff;
            --loader-primary-color: #59E300;
            --loader-secondary-color: #E6F7F5;
            --loader-text-color: #333333;
        }


        #loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background-color: var(--loader-bg-color);
            display: flex;
            justify-content: center;
            align-items: center;

            opacity: 1;
            visibility: visible;
            transition: opacity 0.7s ease-out, visibility 0.7s ease-out;
            /* Transisi lebih panjang */
        }

        #loader-wrapper.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }


        .loader-spinner {
            width: 60px;
            /* Lebih besar */
            height: 60px;
            border: 6px solid var(--loader-secondary-color);
            /* Warna dasar spinner */
            border-top: 6px solid var(--loader-primary-color);
            /* Warna utama yang berputar */
            border-radius: 50%;
            animation: spin 1s linear infinite;
            /* Kombinasi animasi */
        }

        /* Animasi untuk teks */
        .loader-message {
            margin-top: 20px;
            /* Jarak dari spinner */
            font-family: 'Poppins', sans-serif;
            /* Ganti dengan font web Anda jika sudah dimuat */
            font-size: 1.1rem;
            color: var(--loader-text-color);
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInMessage 1s ease-out 0.8s forwards;

        }


        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }


        @keyframes fadeInMessage {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<script>



    // Fungsi untuk menyembunyikan loader
    function hideLoader() {
        const loader = document.getElementById('loader-wrapper');

        // Pastikan loader-nya ada
        if (loader) {
            loader.classList.add('hidden');

            // Hapus dari DOM setelah animasi transisi selesai
            setTimeout(() => {
                loader.style.display = 'none';
            }, 700); // 700ms = durasi transisi fade-out di CSS Anda
        }
    }

    setTimeout(hideLoader, 3000); // 10000 milidetik = 10 detik
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        // Pilih semua section yang ingin dianimasikan
        const sections = document.querySelectorAll('.fade-in-section');

        // Opsi untuk IntersectionObserver
        const options = {
            root: null, // 'null' berarti viewport browser
            rootMargin: '0px',
            threshold: 0.1 // Memicu saat 10% section terlihat
        };

        // Callback function yang akan dijalankan saat section terlihat
        const callback = (entries, observer) => {
            entries.forEach(entry => {
                // Jika elemen masuk ke viewport
                if (entry.isIntersecting) {
                    // Tambahkan kelas .is-visible
                    entry.target.classList.add('is-visible');

                    // (Opsional) Berhenti mengamati elemen ini setelah animasinya berjalan
                    observer.unobserve(entry.target);
                }
            });
        };

        // Buat observer baru
        const observer = new IntersectionObserver(callback, options);

        // Minta observer untuk mengamati setiap section
        sections.forEach(section => {
            observer.observe(section);
        });

    });
</script>

<body class="bg-gray-50 Poppins">
    @php
        $amaliahGreen = '#63cd00';
        $amaliahDark = '#282829';
        $amaliahBlue = '#E0E7FF';
        $hasImages = isset($mainImages) && $mainImages->isNotEmpty();
    @endphp

    {{-- WRAPPER UNTUK MODAL SEARCH --}}
    <div x-data="{ searchModalOpen: false }" @keydown.escape.window="searchModalOpen = false">

        <header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-50 bg-white shadow-md">
            {{-- TOP BAR --}}
            <div class="border-b border-gray-200">
                <div class="max-w-screen-xl mx-auto flex items-center justify-between px-4 sm:px-6 lg:px-8 py-3">

                    {{-- Grup 1: Logo & Nama (Kiri) --}}
                    <div class="flex-shrink-0 flex items-center space-x-4">
                        <img src="{{ asset('assets/logo/amaliah.webp') }}" alt="Logo SMK Amaliah" class="h-12 w-12">
                        <div class="flex flex-col">
                            <span class="text-gray-900 font-times text-base font-bold whitespace-nowrap">SMK AMALIAH
                                1&2
                                CIAWI</span>
                            <span class="text-xs font-times text-gray-600"><i>Tauhid Is Our Fundament</i></span>
                        </div>
                    </div>

                    <div class="hidden lg:flex items-center space-x-10">

                        {{-- Grup 2: Tombol Pemicu Search (Style Baru) --}}
                        <button @click="searchModalOpen = true"
                            class="flex items-center space-x-3 bg-gray-100 rounded-full px-6 py-2.5 text-sm text-gray-500 hover:bg-gray-200 transition">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <span>Find About SMK AMALIAH</span>
                        </button>

                        {{-- Grup 3: Tautan Cepat --}}
                        <div class="flex items-center space-x-8 text-sm text-gray-700">
                            <a href="https://ppdb.smkamaliah.sch.id/login"
                                class="hover:text-[#63cd00] transition-colors whitespace-nowrap ">Info SPMB</a>
                            <a href="https://www.instagram.com/bkksmkamaliah/"
                                class="hover:text-[#63cd00] transition-colors whitespace-nowrap">Info BKK</a>
                            <a href="https://lms.smkamaliah.sch.id"
                                class="hover:text-[#63cd00] transition-colors whitespace-nowrap">E-Learning</a>
                            <a href="https://nonton.smkamaliah.sch.id/"
                                class="hover:text-[#63cd00] transition-colors whitespace-nowrap">AM Movie</a>
                        </div>

                        {{-- Grup 4: Tombol Contact Us (Style Baru) --}}
                        <a href="https://wa.me/6285649011449"
                            class="bg-[#282829] text-white px-6 py-2.5 rounded-full font-semibold hover:bg-opacity-80 transition-colors whitespace-nowrap text-sm">Contact
                            Us</a>

                    </div>

                    {{-- Tombol Hamburger (Mobile) --}}
                    <div class="lg:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-2xl text-gray-700 p-2"
                            aria-label="Buka menu navigasi" :aria-expanded="mobileMenuOpen.toString()">
                            <i class="fa-solid fa-bars" x-show="!mobileMenuOpen"></i>
                            <i class="fa-solid fa-times" x-show="mobileMenuOpen" x-cloak></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- MAIN NAV (HIJAU - Desktop) --}}
            <nav class="bg-[#63cd00] hidden lg:block text-white">
                <div class="max-w-screen-xl mx-auto flex items-center justify-center gap-x-14 px-4 h-12">
                    <a href="/" class="nav-link {{ Request::is('/') ? 'nav-active' : '' }}">Home</a>
                    <div class="relative group">
                        <button class="nav-link">Discover Amaliah <i
                                class="fa-solid fa-chevron-down ml-1.5 text-xs"></i></button>
                        <div class="absolute dropdown-content bg-white shadow-lg mt-2 rounded-md py-1 w-48 z-10">
                            <a href="{{ route('public.about.index') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">About</a>
                            <a href="{{ route('public.partners.index') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">Industry
                                Partners</a>
                            <a href="{{ route('public.teachers.index') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">Teacher
                                & Staff</a>
                            <a href="{{ route('public.testimonials.index') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">Testimonials</a>
                            <a href="{{ route('public.news.index') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">
                                News</a>
                        </div>
                    </div>
                    <a href="{{ route('public.majors.index') }}"
                        class="nav-link {{ Request::is('majors*') ? 'nav-active' : '' }}">
                        Major Competency
                    </a>
                    <div class="relative group">
                        <button class="nav-link">Education Preview <i
                                class="fa-solid fa-chevron-down ml-1.5 text-xs"></i></button>
                        <div class="absolute dropdown-content bg-white shadow-lg mt-2 rounded-md py-1 w-48 z-10">
                            <a href="{{ route('public.achievement.index') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">Achievements</a>
                            <a href="{{ route('public.program.index') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">School
                                Programs</a>
                            <a href="{{ route('public.extracurricular.index') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">Extracurricular</a>
                            <a href="" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">AM
                                Exam</a>
                            <a href="https://lms.smkamaliah.sch.id/"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">E-Learning</a>
                            <a href="https://elib.smkamaliah.sch.id/"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">E-Library</a>
                            <a href="https://yourdisc710.itch.io/amaliah-tour"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">Virtual
                                Tour</a>
                        </div>
                    </div>
                    <a href="{{ route('public.facilities.index') }}"
                        class="nav-link {{ Request::is('facilities*') ? 'nav-active' : '' }}">
                        Facilities
                    </a>
                    <div class="relative group">
                        <button class="nav-link">Help Center<i
                                class="fa-solid fa-chevron-down ml-1.5 text-xs"></i></button>
                        <div class="absolute dropdown-content bg-white shadow-lg mt-2 rounded-md py-1 w-48 z-10">
                            <a href="{{ route('public.help.faq') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">FAQs</a>
                            <a href="https://forms.gle/sveGZa9nd9uX62YE9"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">Feedback</a>
                            <a href="https://wa.me/6285649011449"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">Contact
                                Us</a>

                        </div>
                    </div>
                </div>
            </nav>

            {{-- MOBILE MENU --}}
            <div x-show="mobileMenuOpen" x-cloak @click.away="mobileMenuOpen = false"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
                class="lg:hidden bg-white w-full absolute shadow-xl">
                {{-- Konten mobile menu tetap sama --}}
                <div class="flex flex-col space-y-1 p-4 text-sm max-h-[calc(100vh-80px)] overflow-y-auto">
                    <a href="/"
                        class="block px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]">Home</a>
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                            class="w-full flex justify-between items-center px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]"><span>Discover
                                Amaliah</span><i class="fa-solid fa-chevron-down text-xs transition-transform"
                                :class="{ 'rotate-180': open }"></i></button>
                        <div x-show="open" x-transition class="pl-6 pt-2 pb-1 space-y-1">
                            <a href="{{ route('public.about.index') }}"
                                class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">About</a>
                            <a href="{{ route('public.partners.index') }}"
                                class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">Industry
                                Partners</a>
                            <a href="{{ route('public.teachers.index') }}"
                                class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">Teacher
                                & Staff</a>
                            <a href="{{ route('public.testimonials.index') }}"
                                class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">Testimonials</a>
                            <a href="{{ route('public.news.index') }}"
                                class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">News</a>

                        </div>
                    </div>
                    {{-- Ini adalah link, bukan dropdown --}}
                    <a href="{{ route('public.majors.index') }}"
                        class="block px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]">
                        Major Competency
                    </a>
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                            class="w-full flex justify-between items-center px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]"><span>Education
                                Preview</span><i class="fa-solid fa-chevron-down text-xs transition-transform"
                                :class="{ 'rotate-180': open }"></i></button>
                        <div x-show="open" x-transition class="pl-6 pt-2 pb-1 space-y-1">
                            <a href="{{ route('public.achievement.index') }}"
                                class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">Achievements</a>
                            <a href="{{ route('public.program.index') }}"
                                class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">School
                                Programs</a>
                            <a href="{{ route('public.extracurricular.index') }}"
                                class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">Extracurriculars</a>
                            <a href="" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">AM
                                Exam</a>
                            <a href="https://lms.smkamaliah.sch.id/"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">E-Learning</a>
                            <a href="https://elib.smkamaliah.sch.id/"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">E-Library</a>
                            <a href="https://yourdisc710.itch.io/amaliah-tour"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">Virtual
                                Tour</a>
                        </div>
                    </div>
                    <a href="{{ route('public.facilities.index') }}"
                        class="block px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]">Facilitation</a>
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                            class="w-full flex justify-between items-center px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]"><span>Help
                                Center</span><i class="fa-solid fa-chevron-down text-xs transition-transform"
                                :class="{ 'rotate-180': open }"></i></button>
                        <div x-show="open" x-transition class="pl-6 pt-2 pb-1 space-y-1"><a
                                href="{{ route('public.help.faq') }}"
                                class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">FAQs</a>
                            <a href="https://forms.gle/sveGZa9nd9uX62YE9"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">Feedback</a><a
                                href="https://wa.me/6285649011449"
                                class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">Contact</a>
                        </div>
                    </div>


                    <hr class="my-2">
                    <a href="https://ppdb.smkamaliah.sch.id/login"
                        class="block px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]">Info
                        PPDB</a>
                    <a href="https://www.instagram.com/bkksmkamaliah/"
                        class="block px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]">Info
                        BKK</a>
                    <a href="https://lms.smkamaliah.sch.id"
                        class="block px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]">E-Learning</a>
                    <a href="https://nonton.smkamaliah.sch.id/"
                        class="block px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]">AM
                        Movie</a>
                    <a href="https://wa.me/6285649011449"
                        class="block px-4 py-3 text-[#50B70E] font-semibold rounded-md hover:bg-gray-100">Contact
                        Us</a>
                </div>
            </div>
        </header>

        {{-- MODAL PENCARIAN --}}
        <div x-show="searchModalOpen" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-opacity-50 flex items-start justify-center pt-16 sm:pt-24">

            <div @click.away="searchModalOpen = false" x-show="searchModalOpen"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4">

                <form action="{{ route('search') }}" method="GET" class="relative">
                    <input type="search" name="query"
                        class="w-full border-0 rounded-xl py-4 pl-12 pr-6 text-black placeholder-gray-400 focus:ring-2 focus:ring-[#63cd00] text-lg"
                        placeholder="Ketikkan pencarian Anda..." autocomplete="off" autofocus>
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <button type="button" @click="searchModalOpen = false" aria-label="Tutup pencarian"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                </form>
            </div>
        </div>
        <main>
            @php
                // Definisikan variabel warna dan nomor WhatsApp Anda
                $amaliahGreen = '#63cd00';
                $whatsappNumber = '6285649011449'; // Ganti dengan nomor Anda
                $whatsappMessage = 'Halo, saya ingin bertanya tentang informasi SMK Amaliah 1 & 2 Ciawi';
            @endphp

            {{-- ================================================================= --}}
            {{-- TOMBOL CEPAT & WIDGET (WHATSAPP, UP BUTTON, & ELFSIGHT AI) ----}}
            {{-- ================================================================= --}}

            <!-- Elfsight AI Chatbot | Ama Dan Lia -->
            <script src="https://elfsightcdn.com/platform.js" async></script>
            <div class="elfsight-app-26bf6423-b36c-42c5-a8db-b1c223ee9ec9" data-elfsight-app-lazy></div>
            {{-- PERBAIKAN: Menambah jarak vertikal (space-y) dan posisi dari bawah (bottom) untuk desktop --}}
            <div
                class="fixed bottom-[90px] lg:bottom-[100px] right-5 z-40 flex flex-col items-end space-y-4 lg:space-y-5">

                {{-- TOMBOL SCROLL TO TOP (UP BUTTON) --}}
                <div x-data="{ shown: false }"
                    x-init="window.addEventListener('scroll', () => { shown = window.scrollY > 300 })" x-show="shown"
                    x-transition>
                    <button @click="window.scrollTo({ top: 0, behavior: 'smooth' })" aria-label="Kembali ke atas"
                        class="w-12 h-12 lg:w-[65px] lg:h-[65px] rounded-full text-white shadow-lg flex items-center justify-center transition-transform hover:scale-110"
                        style="background-color: {{ $amaliahGreen }};">
                        <i class="fas fa-arrow-up text-xl lg:text-2xl"></i>
                    </button>
                </div>

                {{-- TOMBOL CEPAT WHATSAPP --}}
                <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode($whatsappMessage) }}" target="_blank"
                    rel="noopener noreferrer" aria-label="Hubungi via WhatsApp"
                    class="w-12 h-12 lg:w-[65px] lg:h-[65px] rounded-full text-white shadow-lg flex items-center justify-center transition-transform hover:scale-110"
                    style="background-color: {{ $amaliahGreen }};">
                    <i class="fab fa-whatsapp text-xl lg:text-2xl"></i>
                </a>

            </div>
            @yield('content')
        </main>

        {{--
        Saya memperbaiki 'fade-in-section' yang sebelumnya ada di dalam atribut 'style'.
        Sekarang 'fade-in-section' ada di atribut 'class' agar bisa berfungsi.
        --}}
        <footer style="background-color: {{ $amaliahDark }};" class="fade-in-section">

            {{-- Wrapper Utama Konten --}}
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

                {{-- Konten Utama Footer (Multi-kolom) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">

                    {{-- Kolom 1: Logo, Deskripsi, dan Sosial Media --}}
                    <div class="space-y-6">
                        {{-- 1. Struktur Branding yang lebih rapi --}}
                        <a href="/" class="flex items-center gap-3">
                            <img src="{{ asset('assets/logo/amaliah_white.webp') }}" alt="Logo SMK Amaliah"
                                class="h-10">
                            <div>
                                <span class="text-white font-semibold text-lg leading-tight">SMK Amaliah 1 & 2</span>
                                <span class="block text-gray-400 text-xs">Ciawi - Bogor</span>
                            </div>
                        </a>

                        <p class="text-gray-400 text-sm leading-relaxed">
                            Berkomitmen untuk mencetak lulusan yang kompeten, berakhlak mulia, dan siap bersaing di
                            dunia industri global.
                        </p>

                        {{-- 2. Ikon Sosial Media dengan efek hover modern --}}
                        <div class="flex items-center space-x-3">
                            <a href="https://youtube.com/@smkamaliahciawi?si=j67hYjVWMNc2F3vK" target="_blank"
                                aria-label="Kunjungi YouTube SMK Amaliah"
                                class="group w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white">
                                <i
                                    class="fab fa-youtube text-gray-400 text-xl group-hover:text-red-600 transition-colors"></i>
                            </a>
                            <a href="https://www.instagram.com/smkamaliah" target="_blank"
                                aria-label="Kunjungi Instagram SMK Amaliah"
                                class="group w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white">
                                <i
                                    class="fab fa-instagram text-gray-400 text-xl group-hover:text-pink-600 transition-colors"></i>
                            </a>
                            <a href="https://www.facebook.com/smk.amaliah.1.dan.2" target="_blank"
                                aria-label="Kunjungi Facebook SMK Amaliah"
                                class="group w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white">
                                <i
                                    class="fab fa-facebook-f text-gray-400 text-xl group-hover:text-blue-600 transition-colors"></i>
                            </a>
                            <a href="https://www.tiktok.com/@smk.amaliah?_t=ZS-90cdH7Gk5Ml&_r=1" target="_blank"
                                aria-label="Kunjungi TikTok SMK Amaliah"
                                class="group w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white">
                                <i
                                    class="fab fa-tiktok text-gray-400 text-xl group-hover:text-black transition-colors"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Kolom 2: Link Navigasi Cepat --}}
                    <div>
                        <h4 class="font-semibold text-white tracking-wider uppercase">Jelajahi</h4>
                        <ul class="mt-4 space-y-3 text-sm">
                            <li><a href="/"
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Beranda</a>
                            </li>
                            <li><a href="{{ route('public.about.index') }}"
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Tentang
                                    Kami</a></li>
                            <li><a href="{{ route('public.news.index') }}"
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Berita</a>
                            </li>
                            <li><a href="{{ route('public.majors.index') }}"
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Jurusan</a>
                            </li>
                        </ul>
                    </div>

                    {{-- Kolom 3: Link Informasi --}}
                    <div>
                        <h4 class="font-semibold text-white tracking-wider uppercase">Informasi</h4>
                        <ul class="mt-4 space-y-3 text-sm">
                            <li><a href="https://ppdb.smkamaliah.sch.id/login   "
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Info
                                    PPDB</a></li>
                            <li><a href="{{ route('public.facilities.index') }}"
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Fasilitas</a>
                            </li>
                            <li><a href="https://yourdisc710.itch.io/amaliah-tour"
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Virtual
                                    Tour</a></li>
                            <li><a href="https://wa.me/6285649011449"
                                    class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">Kontak</a>
                            </li>
                        </ul>
                    </div>

                    {{-- Kolom 4: Informasi Kontak --}}
                    <div>
                        <h4 class="font-semibold text-white tracking-wider uppercase">Hubungi Kami</h4>
                        <div class="mt-4 flex flex-col gap-4 text-sm">
                            <div class="flex items-start gap-3 text-gray-400">
                                <i class="fas fa-map-marker-alt w-4 h-4 mt-1 flex-shrink-0"></i>
                                <span>Jl. Raya Jl. Tol Jagorawi No.1, Ciawi, Kec. Ciawi, Kabupaten Bogor, Jawa Barat
                                    16720</span>
                            </div>
                            <div class="flex items-start gap-3 text-gray-400">
                                <i class="fas fa-envelope w-4 h-4 mt-1 flex-shrink-0"></i>
                                <a href="mailto:{{ $email ?? 'smkamaliahciawi@gmail.com' }}"
                                    class="hover:text-white transition">smkamaliahciawi@gmail.com</a>
                            </div>
                            <div class="flex items-start gap-3 text-gray-400">
                                <i class="fas fa-phone-alt w-4 h-4 mt-1 flex-shrink-0"></i>
                                <a href="https://wa.me/6285649011449" class="hover:text-white transition">0856-1922-827
                                    /
                                    0856-4901-1449</a>
                            </div>
                        </div>
                    </div>

                </div> {{--
                ============================================================
                == BAGIAN TAMBAHAN: Logo Partner/Sponsor (Sesuai Permintaan) ==
                ============================================================
                Ditempatkan setelah grid 4-kolom, tapi di dalam wrapper utama.
                Ini persis seperti struktur di gambar referensi.
                --}}
                {{--
                ============================================================
                == BAGIAN LOGO PARTNER (DIMODIFIKASI) ==
                ============================================================
                --}}
                <div class="mt-16 pt-8 border-t border-gray-800">
                    <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-6">

                        {{--
                        PENYESUAIAN:
                        1. Tag <a> kini memiliki background putih (bg-white), padding (p-2), dan sudut (rounded-md).
                            2. Efek opacity dipindahkan ke tag <a> agar background-nya tidak ikut transparan.
                                3. Ukuran <img> diubah dari h-8 menjadi h-12.
                                --}}

                                {{-- Contoh Placeholder 2 (Infra) --}}
                                <a href="#" target="_blank" rel="noopener" aria-label="Infra"
                                    class="block bg-white p-2 rounded-md opacity-70 hover:opacity-100 transition-opacity duration-300">
                                    <img src="{{ asset('assets/logo/infra.webp') }}" alt="Logo Infra" class="h-12">
                                </a>

                                {{-- Contoh Placeholder 1 (Jagoan Hosting) --}}
                                <a href="#" target="_blank" rel="noopener" aria-label="Jagoan Hosting"
                                    class="block bg-white p-2 rounded-md opacity-70 hover:opacity-100 transition-opacity duration-300">
                                    <img src="{{ asset('assets/logo/jh.webp') }}" alt="Logo Jagoan Hosting"
                                        class="h-12">
                                </a>



                                {{-- Contoh Placeholder 3 (Galileo) --}}
                                <a href="#" target="_blank" rel="noopener" aria-label="Galileo"
                                    class="block bg-white p-2 rounded-md opacity-70 hover:opacity-100 transition-opacity duration-300">
                                    <img src="{{ asset('assets/logo/komdigi.webp') }}" alt="Logo Galileo" class="h-12">
                                </a>

                                {{-- Contoh Placeholder 4 (Kominfo) --}}
                                <a href="#" target="_blank" rel="noopener" aria-label="Kominfo"
                                    class="block bg-white p-2 rounded-md opacity-70 hover:opacity-100 transition-opacity duration-300">
                                    <img src="{{ asset('assets/logo/maspionit.webp') }}" alt="Logo Kominfo"
                                        class="h-12">
                                </a>

                                {{-- Contoh Placeholder 5 (Spark) --}}
                                <a href="#" target="_blank" rel="noopener" aria-label="Spark"
                                    class="block bg-white p-2 rounded-md opacity-70 hover:opacity-100 transition-opacity duration-300">
                                    <img src="{{ asset('assets/logo/gspark.webp') }}" alt="Logo Spark" class="h-12">
                                </a>

                                {{-- Tambahkan logo lain di sini jika perlu --}}

                    </div>
                </div>
                {{-- === AKHIR BAGIAN TAMBAHAN === --}}

            </div> {{-- Bagian Copyright di Bawah (Tidak ada perubahan di sini) --}}
            <div class="border-t border-gray-800">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4">
                        <p class="text-sm text-gray-500">
                            &copy; {{ date('Y') }} Tim IT SMK Amaliah. All Rights Reserved.
                        </p>
                        <div class="flex space-x-6 text-sm text-gray-500">
                            <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                            <a href="#" class="hover:text-white transition">Syarat & Ketentuan</a>
                        </div>
                    </div>
                </div>
            </div>

        </footer>

</body>

</html>