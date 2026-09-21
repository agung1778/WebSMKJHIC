<!DOCTYPE html>
<html lang="id">


<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta name="description"
        content="@yield('description', 'Situs web resmi SMK Amaliah 1 & 2 Ciawi-Bogor. Temukan informasi tentang jurusan, pendaftaran, fasilitas, dan berita terbaru.')">
    <link rel="icon" type="image/webp" href="{{ asset('assets/logo/am.webp') }}">

    <title>@yield('title', 'SMK Amaliah 1 & 2')</title>

    {{-- ============================================================ --}}
    {{-- PERFORMANCE: Resource Hints & Preconnects --}}
    {{-- ============================================================ --}}
    {{-- DNS Prefetch for third-party domains --}}
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://elfsightcdn.com">
    <link rel="dns-prefetch" href="https://www.youtube-nocookie.com">
    <link rel="dns-prefetch" href="https://www.google.com">
    <link rel="dns-prefetch" href="https://maps.googleapis.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    {{-- Preconnect for critical third-party origins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://elfsightcdn.com" crossorigin>
    <link rel="preconnect" href="https://www.youtube-nocookie.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

    {{-- ============================================================ --}}
    {{-- FONTS: Google Fonts + FontAwesome with font-display:swap --}}
    {{-- ============================================================ --}}
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    </noscript>

    {{-- Preload ikon font FontAwesome agar paralel dengan CSS (mengurangi flash ikon) --}}
    <link rel="preload" as="font" type="font/woff2" crossorigin href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/webfonts/fa-solid-900.woff2">
    <link rel="preload" as="font" type="font/woff2" crossorigin href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/webfonts/fa-regular-400.woff2">
    <link rel="preload" as="font" type="font/woff2" crossorigin href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/webfonts/fa-brands-400.woff2">



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
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        /* Animasi fade-in hanya aktif jika JS tersedia; tanpa JS konten langsung tampil */
        html.js .fade-in-section {
            opacity: 0;
            transform: translateY(20px);
        }

        html.js .fade-in-section.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    {{-- ============================================================ --}}
    {{-- VITE ASSETS: CSS & JS with versioning --}}
    {{-- ============================================================ --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) }}">
    <script type="module" src="{{ asset('js/app.js') }}?v={{ @filemtime(public_path('js/app.js')) }}"></script>

    {{-- ============================================================ --}}
    {{-- PERFORMANCE: Defer non-critical third-party scripts --}}
    {{-- ============================================================ --}}
    {{-- Elfsight Chatbot - deferred with lazy initialization --}}
    <script>
        // Defer Elfsight loading until user interaction or idle time
        (function() {
            function loadElfsight() {
                if (document.querySelector('.elfsight-app-26bf6423-b36c-42c5-a8db-b1c223ee9ec9')) return;
                var s = document.createElement('script');
                s.src = 'https://elfsightcdn.com/platform.js';
                s.async = true;
                document.head.appendChild(s);
                var d = document.createElement('div');
                d.className = 'elfsight-app-26bf6423-b36c-42c5-a8db-b1c223ee9ec9';
                d.setAttribute('data-elfsight-app-lazy', '');
                document.body.appendChild(d);
                ['click','scroll','mousemove','keydown','touchstart'].forEach(function(e) {
                    window.removeEventListener(e, loadElfsight, {passive:true});
                });
            }
            ['click','scroll','mousemove','keydown','touchstart'].forEach(function(e) {
                window.addEventListener(e, loadElfsight, {passive:true, once:true});
            });
            setTimeout(loadElfsight, 3000);
        })();
    </script>
</head>

<script>
    // Tandai JS aktif agar animasi fade-in aktif; tanpa JS konten langsung tampil
    document.documentElement.classList.add('js');

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
            {{-- TOMBOL CEPAT & WIDGET (WHATSAPP, UP BUTTON) --}}
            {{-- ================================================================= --}}

            {{-- PERBAIKAN: Menambah jarak vertikal (space-y) dan posisi dari bawah (bottom) untuk desktop --}}
            <div
                class="fixed bottom-[90px] lg:bottom-[100px] right-5 z-40 flex flex-col items-end gap-3">

                {{-- TOMBOL CEPAT WHATSAPP --}}
                <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode($whatsappMessage) }}" target="_blank"
                    rel="noopener noreferrer" aria-label="Hubungi via WhatsApp"
                    class="w-12 h-12 lg:w-[65px] lg:h-[65px] rounded-full text-white shadow-lg flex items-center justify-center transition-transform hover:scale-110"
                    style="background-color: {{ $amaliahGreen }};">
                    <i class="fab fa-whatsapp text-xl lg:text-2xl"></i>
                </a>

                {{-- TOMBOL STATISTIK KUNJUNGAN (DI BAWAH WHATSAPP) --}}
                <a href="{{ route('public.traffic.index') }}" target="_blank" rel="noopener noreferrer"
                    aria-label="Lihat statistik kunjungan website" title="Statistik Kunjungan"
                    class="w-12 h-12 lg:w-[55px] lg:h-[55px] rounded-full text-white shadow-lg flex items-center justify-center transition-transform hover:scale-110"
                    style="background-color: {{ $amaliahDark }};">
                    <i class="fa-solid fa-chart-column text-xl lg:text-2xl"></i>
                </a>

            </div>

            {{-- TOMBOL KEMBALI KE ATAS (DIPISAH, POSISI BAWAH TENGAH) --}}
            <div class="fixed bottom-6 inset-x-0 z-40 flex justify-center pointer-events-none">
                <div x-data="{ shown: false }"
                    x-init="window.addEventListener('scroll', () => { shown = window.scrollY > 300 })"
                    class="pointer-events-auto" x-show="shown" x-transition>
                    <button @click="window.scrollTo({ top: 0, behavior: 'smooth' })" aria-label="Kembali ke atas"
                        class="w-12 h-12 lg:w-[65px] lg:h-[65px] rounded-full text-white shadow-lg flex items-center justify-center transition-transform hover:scale-110"
                        style="background-color: {{ $amaliahGreen }};">
                        <i class="fas fa-arrow-up text-xl lg:text-2xl"></i>
                    </button>
                </div>
            </div>
            @yield('content')
        </main>

        {{-- ===== Footer (redesign) ===== --}}
        <style>
            .ft-footer {
                position: relative;
                background: #202021;
                color: #cfcfd2;
                overflow: hidden;
            }

            .ft-footer::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, #63cd00 0%, #8ef03a 50%, #63cd00 100%);
            }

            .ft-footer::after {
                content: '';
                position: absolute;
                top: -160px;
                right: -120px;
                width: 420px;
                height: 420px;
                background: radial-gradient(circle, rgba(99, 205, 0, 0.14) 0%, transparent 65%);
                pointer-events: none;
            }

            .ft-container {
                width: 100%;
                max-width: 80rem;
                margin: 0 auto;
                padding-left: 1rem;
                padding-right: 1rem;
                position: relative;
                z-index: 1;
            }

            .ft-top {
                padding-top: 3.5rem;
                padding-bottom: 3rem;
            }

            .ft-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }

            .ft-brand__logo {
                display: flex;
                align-items: center;
                gap: 0.85rem;
                text-decoration: none;
            }

            .ft-brand__logo img {
                height: 2.75rem;
                width: auto;
            }

            .ft-brand__name {
                display: block;
                color: #ffffff;
                font-weight: 700;
                font-size: 1.05rem;
                line-height: 1.2;
            }

            .ft-brand__loc {
                display: block;
                color: #63cd00;
                font-size: 0.75rem;
                font-weight: 600;
                letter-spacing: 0.05em;
            }

            .ft-brand__desc {
                margin-top: 1.15rem;
                font-size: 0.875rem;
                line-height: 1.75;
                color: #9a9aa0;
                max-width: 22rem;
            }

            .ft-socials {
                display: flex;
                align-items: center;
                gap: 0.65rem;
                margin-top: 1.4rem;
            }

            .ft-social {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 2.4rem;
                height: 2.4rem;
                border-radius: 9999px;
                background: #333335;
                color: #b8b8bd;
                font-size: 1rem;
                text-decoration: none;
                transition: transform 0.25s ease, background-color 0.25s ease, color 0.25s ease, box-shadow 0.25s ease;
            }

            .ft-social:hover {
                transform: translateY(-3px);
                background: #63cd00;
                color: #ffffff;
                box-shadow: 0 10px 20px -8px rgba(99, 205, 0, 0.6);
            }

            .ft-col__title {
                position: relative;
                margin: 0 0 1.25rem;
                padding-bottom: 0.6rem;
                color: #ffffff;
                font-size: 0.8rem;
                font-weight: 700;
                letter-spacing: 0.12em;
                text-transform: uppercase;
            }

            .ft-col__title::after {
                content: '';
                position: absolute;
                left: 0;
                bottom: 0;
                width: 2.25rem;
                height: 3px;
                border-radius: 9999px;
                background: #63cd00;
            }

            .ft-links {
                list-style: none;
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column;
                gap: 0.8rem;
            }

            .ft-link {
                display: inline-flex;
                align-items: center;
                gap: 0.55rem;
                color: #9a9aa0;
                font-size: 0.875rem;
                text-decoration: none;
                transition: color 0.2s ease, transform 0.2s ease;
            }

            .ft-link i {
                color: #63cd00;
                font-size: 0.68rem;
                transition: transform 0.2s ease;
            }

            .ft-link:hover {
                color: #ffffff;
                transform: translateX(4px);
            }

            .ft-link:hover i {
                transform: translateX(2px);
            }

            .ft-contact {
                display: flex;
                flex-direction: column;
                gap: 1.1rem;
            }

            .ft-contact__item {
                display: flex;
                align-items: flex-start;
                gap: 0.85rem;
                font-size: 0.875rem;
                color: #9a9aa0;
                line-height: 1.6;
            }

            .ft-contact__icon {
                flex-shrink: 0;
                width: 2.35rem;
                height: 2.35rem;
                border-radius: 0.7rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: rgba(99, 205, 0, 0.12);
                color: #63cd00;
                font-size: 0.85rem;
            }

            .ft-contact__item a {
                color: #cfcfd2;
                text-decoration: none;
                transition: color 0.2s ease;
            }

            .ft-contact__item a:hover {
                color: #63cd00;
            }

            .ft-contact__nums {
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
            }

            .ft-bottom {
                border-top: 1px solid rgba(255, 255, 255, 0.08);
                background: rgba(0, 0, 0, 0.25);
            }

            .ft-bottom__inner {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.9rem;
                padding-top: 1.4rem;
                padding-bottom: 1.4rem;
                text-align: center;
            }

            .ft-copy {
                margin: 0;
                font-size: 0.8rem;
                color: #7c7c82;
            }

            .ft-copy strong {
                color: #b8b8bd;
                font-weight: 600;
            }

            .ft-legal {
                display: flex;
                align-items: center;
                gap: 0.9rem;
                font-size: 0.8rem;
            }

            .ft-legal a {
                color: #9a9aa0;
                text-decoration: none;
                transition: color 0.2s ease;
            }

            .ft-legal a:hover {
                color: #63cd00;
            }

            .ft-legal__dot {
                color: #4a4a4e;
            }

            @media (min-width: 640px) {
                .ft-container {
                    padding-left: 1.5rem;
                    padding-right: 1.5rem;
                }

                .ft-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }

                .ft-bottom__inner {
                    flex-direction: row;
                    justify-content: space-between;
                    text-align: left;
                }
            }

            @media (min-width: 1024px) {
                .ft-container {
                    padding-left: 2rem;
                    padding-right: 2rem;
                }

                .ft-grid {
                    grid-template-columns: 1.5fr 1fr 1fr 1.3fr;
                    gap: 3rem;
                }
            }
        </style>

        <footer class="ft-footer">
            <div class="ft-container ft-top">
                <div class="ft-grid">

                    {{-- Kolom 1: Brand, deskripsi, sosial media --}}
                    <div>
                        <a href="/" class="ft-brand__logo">
                            <img src="{{ asset('assets/logo/amaliah_white.webp') }}" alt="Logo SMK Amaliah">
                            <span>
                                <span class="ft-brand__name">SMK Amaliah 1 &amp; 2</span>
                                <span class="ft-brand__loc">CIAWI - BOGOR</span>
                            </span>
                        </a>

                        <p class="ft-brand__desc">
                            Berkomitmen untuk mencetak lulusan yang kompeten, berakhlak mulia, dan siap bersaing di
                            dunia industri global.
                        </p>

                        <div class="ft-socials">
                            <a href="https://youtube.com/@smkamaliahciawi?si=j67hYjVWMNc2F3vK" target="_blank"
                                rel="noopener" aria-label="Kunjungi YouTube SMK Amaliah" class="ft-social">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="https://www.instagram.com/smkamaliah" target="_blank" rel="noopener"
                                aria-label="Kunjungi Instagram SMK Amaliah" class="ft-social">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.facebook.com/smk.amaliah.1.dan.2" target="_blank" rel="noopener"
                                aria-label="Kunjungi Facebook SMK Amaliah" class="ft-social">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.tiktok.com/@smk.amaliah?_t=ZS-90cdH7Gk5Ml&_r=1" target="_blank"
                                rel="noopener" aria-label="Kunjungi TikTok SMK Amaliah" class="ft-social">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Kolom 2: Jelajahi --}}
                    <div>
                        <h4 class="ft-col__title">Jelajahi</h4>
                        <ul class="ft-links">
                            <li><a href="/" class="ft-link"><i class="fas fa-chevron-right"></i>Beranda</a></li>
                            <li><a href="{{ route('public.about.index') }}" class="ft-link"><i
                                        class="fas fa-chevron-right"></i>Tentang Kami</a></li>
                            <li><a href="{{ route('public.news.index') }}" class="ft-link"><i
                                        class="fas fa-chevron-right"></i>Berita</a></li>
                            <li><a href="{{ route('public.majors.index') }}" class="ft-link"><i
                                        class="fas fa-chevron-right"></i>Jurusan</a></li>
                        </ul>
                    </div>

                    {{-- Kolom 3: Informasi --}}
                    <div>
                        <h4 class="ft-col__title">Informasi</h4>
                        <ul class="ft-links">
                            <li><a href="https://ppdb.smkamaliah.sch.id/login" target="_blank" rel="noopener"
                                    class="ft-link"><i class="fas fa-chevron-right"></i>Info PPDB</a></li>
                            <li><a href="{{ route('public.facilities.index') }}" class="ft-link"><i
                                        class="fas fa-chevron-right"></i>Fasilitas</a></li>
                            <li><a href="https://yourdisc710.itch.io/amaliah-tour" target="_blank" rel="noopener"
                                    class="ft-link"><i class="fas fa-chevron-right"></i>Tur Virtual</a></li>
                            <li><a href="https://wa.me/6285649011449" target="_blank" rel="noopener" class="ft-link"><i
                                        class="fas fa-chevron-right"></i>Kontak</a></li>
                        </ul>
                    </div>

                    {{-- Kolom 4: Hubungi Kami --}}
                    <div>
                        <h4 class="ft-col__title">Hubungi Kami</h4>
                        <div class="ft-contact">
                            <div class="ft-contact__item">
                                <span class="ft-contact__icon"><i class="fas fa-map-marker-alt"></i></span>
                                <span>Jl. Raya Jl. Tol Jagorawi No.1, Ciawi, Kec. Ciawi, Kabupaten Bogor, Jawa Barat
                                    16720</span>
                            </div>
                            <div class="ft-contact__item">
                                <span class="ft-contact__icon"><i class="fas fa-envelope"></i></span>
                                <a href="mailto:{{ $email ?? 'smkamaliahciawi@gmail.com' }}">smkamaliahciawi@gmail.com</a>
                            </div>
                            <div class="ft-contact__item">
                                <span class="ft-contact__icon"><i class="fas fa-phone-alt"></i></span>
                                <span class="ft-contact__nums">
                                    <a href="https://wa.me/628561922827" target="_blank" rel="noopener">0856-1922-827</a>
                                    <a href="https://wa.me/6285649011449" target="_blank" rel="noopener">0856-4901-1449</a>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="ft-bottom">
                <div class="ft-container ft-bottom__inner">
                    <p class="ft-copy">&copy; {{ date('Y') }} <strong>Tim IT SMK Amaliah</strong>. All Rights Reserved.
                    </p>
                    <div class="ft-legal">
                        <a href="{{ route('public.legal.privacy') }}">Kebijakan Privasi</a>
                        <span class="ft-legal__dot">&bull;</span>
                        <a href="{{ route('public.legal.terms') }}">Syarat &amp; Ketentuan</a>
                    </div>
                </div>
            </div>
        </footer>

    {{-- Pelacakan traffic (kunjungan halaman & klik) --}}
    @include('partials.traffic-tracking')

</body>

</html>