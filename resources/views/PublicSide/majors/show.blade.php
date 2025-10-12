@extends('layouts.public-app') {{-- Sesuaikan dengan nama file layout utama Anda --}}

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>@yield('title')</title>

        {{-- Link Extensions --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <style>
        .hero-clip-path {
            clip-path: polygon(0 0, 100% 0, 100% calc(100% - 4rem), calc(100% - 4rem) 100%, 0 100%);
        }

        .custom-mt {
            margin-top: -30px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    <body class="font-['Poppins'] bg-gray-100">
        @php
            // Variabel warna untuk konsistensi
            $amaliahGreen = '#63cd00';
            $amaliahDark = '#282829';
            // Mengambil jurusan lain (Asumsi $otherMajors tersedia dari controller)
            // Jika $otherMajors adalah hasil dari $majors->except($major->id), ini sudah benar.
            $otherMajors = $otherMajors ?? collect([]);

            // Logika untuk memproses advantage (dijadikan array)
            $advantages = $major->advantage ? array_filter(explode("\n", $major->advantage)) : [];
        @endphp

        {{-- ================================================================= --}}
        {{-- BAGIAN 1: HERO IMAGE (GAMBAR UTAMA JURUSAN) --}}
        {{-- ================================================================= --}}
        <header class="h-64 lg:h-80 w-full bg-gray-800">
            @if ($major->image)
                <img src="{{ asset('storage/' . $major->image) }}" alt="Gambar Latar {{ $major->name }}"
                    class="w-full h-full object-cover">
            @endif
        </header>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const carousel = document.querySelector('#testimonial-carousel .flex');
                if (!carousel) return; // Keluar jika carousel tidak ditemukan

                const items = carousel.children;
                const totalItems = items.length;
                let currentIndex = 0;
                const duration = 5000; // Ganti setiap 5 detik

                if (totalItems <= 1) {
                    // Sembunyikan tombol navigasi jika hanya ada satu atau tidak ada item
                    document.getElementById('prev-btn')?.remove();
                    document.getElementById('next-btn')?.remove();
                    document.getElementById('testimonial-dots')?.remove();
                    return;
                }

                // Fungsi untuk menggeser carousel
                function updateCarousel() {
                    const offset = -currentIndex * 100; // Geser sebesar 100% dari lebar item
                    carousel.style.transform = `translateX(${offset}%)`;
                    updateDots();
                }

                // --- Navigasi Otomatis ---
                let autoSlide = setInterval(() => {
                    currentIndex = (currentIndex + 1) % totalItems;
                    updateCarousel();
                }, duration);

                // --- Navigasi Tombol (Arrow) ---
                const prevBtn = document.getElementById('prev-btn');
                const nextBtn = document.getElementById('next-btn');

                // Fungsi untuk mereset autoslide setelah interaksi user
                function resetAutoSlide() {
                    clearInterval(autoSlide);
                    autoSlide = setInterval(() => {
                        currentIndex = (currentIndex + 1) % totalItems;
                        updateCarousel();
                    }, duration);
                }

                prevBtn?.addEventListener('click', () => {
                    currentIndex = (currentIndex - 1 + totalItems) % totalItems;
                    updateCarousel();
                    resetAutoSlide();
                });

                nextBtn?.addEventListener('click', () => {
                    currentIndex = (currentIndex + 1) % totalItems;
                    updateCarousel();
                    resetAutoSlide();
                });

                // --- Dots Indicator ---
                const dotsContainer = document.getElementById('testimonial-dots');

                function createDots() {
                    for (let i = 0; i < totalItems; i++) {
                        const dot = document.createElement('span');
                        dot.classList.add('w-2', 'h-2', 'rounded-full', 'bg-gray-300', 'cursor-pointer', 'transition-colors');
                        dot.dataset.index = i;
                        dot.addEventListener('click', () => {
                            currentIndex = i;
                            updateCarousel();
                            resetAutoSlide();
                        });
                        dotsContainer.appendChild(dot);
                    }
                }

                function updateDots() {
                    Array.from(dotsContainer.children).forEach((dot, index) => {
                        dot.classList.remove('bg-gray-700');
                        if (index === currentIndex) {
                            dot.classList.add('bg-gray-700');
                        }
                    });
                }

                createDots();
                updateDots(); // Initial dot setting
            });
        </script>

        {{-- ================================================================= --}}
        {{-- BAGIAN 2: BREADCRUMB NAVIGASI --}}
        {{-- ================================================================= --}}
        <div style="background-color: #2D2D2D;">
            <div class="max-w-screen-xl h-[70px] mx-auto px-4 sm:px-6 lg:px-8">
                {{-- Container ditambahkan flex items-center untuk menengahkan secara vertikal --}}
                <div class="h-full flex items-center">
                    <nav class="flex" aria-label="Breadcrumb">
                        {{-- Text-lg untuk memperbesar ukuran teks --}}
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
                                    <a href="{{ route('public.majors.index') }}"
                                        class="ml-2 font-medium text-gray-300 hover:text-white md:ml-3 transition-colors">Major
                                        Competency</a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    {{-- Mengganti warna chevron untuk konsistensi --}}
                                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                                    <span
                                        class="ml-2 font-medium md:ml-3 text-[#ffffff]">{{ $major->abbreviation ?? $major->name }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- ================================================================= --}}
        {{-- BAGIAN 3: KONTEN UTAMA (LAYOUT 2 KOLOM) --}}
        {{-- ================================================================= --}}
        <main class="py-16 lg:py-24 bg-gray-50">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                {{-- Tambahkan border-r dan border-gray-200 pada kolom kiri untuk pemisah --}}
                {{-- Atau bisa juga pada grid container dengan divider-x jika menggunakan Tailwind JIT --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-16">


                    {{-- Kolom Kiri: Konten Detail Jurusan --}}
                    <div class="lg:col-span-2 space-y-12 lg:pr-8 lg:border-r lg:border-gray-200">
                        {{-- ^^^ PENAMBAHAN: lg:pr-8 untuk padding kanan dan lg:border-r lg:border-gray-200 untuk garis
                        pemisah
                        --}}

                        {{-- Header Judul Jurusan --}}
                        <section class="flex flex-col sm:flex-row items-start gap-6 pt-8 pb-4 border-b border-gray-200">
                            <div class="bg-white p-3 rounded-xl shadow-md border border-gray-200 flex-shrink-0">
                                @if ($major->logo)
                                    <img src="{{ asset('storage/' . $major->logo) }}" alt="Logo {{ $major->name }}"
                                        class="h-20 w-20 object-contain">
                                @else
                                    <div
                                        class="h-20 w-20 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 text-xs">
                                        Logo
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="text-base font-semibold uppercase">Kompetensi
                                    Keahlian</p>
                                {{-- H1: Tetap dipertahankan ukuran besar karena ini adalah judul utama halaman --}}
                                <h1 class="text-3xl lg:text-4xl font-semibold" style="color: {{ $amaliahDark }};">
                                    {{ $major->name }}
                                </h1>
                                @if ($major->abbreviation)
                                    <p class="text-lg text-gray-500 mt-0.5">{{ $major->abbreviation }}</p>
                                @endif
                            </div>
                        </section>

                        {{-- Deskripsi Jurusan --}}
                        <section>
                            {{-- JUDUL DIPERBAIKI: text-xl, font-semibold --}}
                            <h2 class="flex items-center gap-x-2 text-xl font-semibold mb-4"
                                style="color: {{ $amaliahDark }};">
                                {{-- ICON DIPERBAIKI: text-lg (lebih kecil), warna gray-500 --}}
                                <i class="fas fa-info-circle text-lg text-gray-500"></i>
                                <span>Tentang Jurusan</span>
                            </h2>
                            <div class="prose max-w-none text-gray-700 leading-relaxed text-base">
                                <div>{!! $major->description !!}</div>
                            </div>
                        </section>

                        {{-- Poin Keunggulan (ADVANTAGE) --}}
                        @if (!empty($advantages))
                            <section>
                                {{-- JUDUL DIPERBAIKI: text-xl, font-semibold --}}
                                <h2 class="flex items-center gap-x-2 text-xl font-semibold mb-6"
                                    style="color: {{ $amaliahDark }};">
                                    {{-- ICON DIPERBAIKI: text-lg (lebih kecil), warna gray-500, ikon diganti ke fa-check-square
                                    untuk kesan daftar --}}
                                    <i class="fas fa-check-square text-lg text-gray-500"></i>
                                    <span>Keunggulan & Konsentransi Keahlian</span>
                                </h2>
                                <ul class="space-y-3">
                                    @foreach ($advantages as $advantage)
                                        @if (trim($advantage) != '')
                                            <li class="flex items-start bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                                                <div class="flex-shrink-0 mt-1">
                                                    <i class="fas fa-check-circle text-lg" style="color: {{ $amaliahGreen }};"></i>
                                                </div>
                                                <span class="ml-4 text-base text-gray-700">{{ trim($advantage) }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </section>
                        @endif

                        {{-- Testimoni Alumni (Header bagian carousel) --}}
                        <section>
                            {{-- JUDUL DIPERBAIKI: text-xl, font-semibold, label sedikit diubah --}}
                            <h2 class="flex items-center gap-x-2 text-xl font-semibold mb-6"
                                style="color: {{ $amaliahDark }};">
                                {{-- ICON DIPERBAIKI: text-lg (lebih kecil), warna gray-500 --}}
                                <i class="fas fa-quote-right text-lg text-gray-500"></i>
                                <span>Kata Mereka Para Alumni</span>
                            </h2>

                            @if ($major->testimonials->isNotEmpty())
                                {{-- CAROUSEL TESTIMONI BARU --}}
                                <div id="testimonial-carousel" class="relative overflow-hidden w-full">

                                    {{-- Kontainer Slider --}}
                                    <div class="flex transition-transform duration-500 ease-in-out"
                                        style="transform: translateX(0%);">
                                        @foreach ($major->testimonials as $testimonial)
                                            {{-- Item Testimoni --}}
                                            <div
                                                class="bg-white p-6 rounded-xl border border-gray-200 shadow-md relative flex-shrink-0 w-full snap-center">
                                                <i
                                                    class="fas fa-quote-left fa-3x absolute top-6 left-6 text-gray-100 opacity-80"></i>
                                                <p class="text-base italic text-gray-700 mt-2 ml-10 leading-relaxed">
                                                    "{{ $testimonial->description }}"
                                                </p>

                                                <div class="flex items-center mt-6 pt-4 border-t border-gray-100">
                                                    @if ($testimonial->photo)
                                                        <img src="{{ asset('storage/' . $testimonial->photo) }}"
                                                            alt="Foto {{ $testimonial->name }}"
                                                            class="w-12 h-12 object-cover rounded-full flex-shrink-0 mr-4 border border-gray-100">
                                                    @else
                                                        <div class="w-12 h-12 bg-gray-300 rounded-full flex-shrink-0 mr-4"></div>
                                                    @endif
                                                    <div>
                                                        <p class="font-semibold text-gray-800">{{ $testimonial->name }}</p>
                                                        <p class="text-xs text-gray-500">Alumni
                                                            {{ $testimonial->major->abbreviation ?? $testimonial->major->name }}
                                                            ({{ $testimonial->alumni_year }})
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Tombol Navigasi (Arrows) --}}
                                    <button id="prev-btn"
                                        class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-[#ffffff] p-2 rounded-full shadow-lg border border-gray-200 hover:bg-gray-100 transition duration-300 ml-[-20px] z-10 hidden lg:block">
                                        <i class="fas fa-chevron-left text-base text-white"></i>
                                    </button>
                                    <button id="next-btn"
                                        class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-[#ffffff] p-2 rounded-full shadow-lg border border-gray-200 hover:bg-gray-100 transition duration-300 mr-[-20px] z-10 hidden lg:block">
                                        <i class="fas fa-chevron-right text-base text-white"></i>
                                    </button>
                                </div>

                                {{-- Indikator Dots (Opsional, memberikan feedback visual) --}}
                                <div id="testimonial-dots" class="flex justify-center mt-4 space-x-2">
                                    {{-- Dots akan di-inject oleh JavaScript --}}
                                </div>

                            @else
                                <p class="text-gray-500 italic text-sm p-4 bg-white rounded-xl shadow-sm border">Tidak ada
                                    testimoni
                                    alumni untuk ditampilkan.</p>
                            @endif
                        </section>

                        {{-- Profil Kepala Jurusan --}}
                        <section>
                            {{-- JUDUL DIPERBAIKI: text-xl, font-semibold --}}
                            <h2 class="flex items-center gap-x-2 text-xl font-semibold mb-6"
                                style="color: {{ $amaliahDark }};">
                                {{-- ICON DIPERBAIKI: text-lg (lebih kecil), warna gray-500 --}}
                                <i class="fas fa-user-tie text-lg text-gray-500"></i>
                                <span>Kepala Kompetensi</span>
                            </h2>
                            <div
                                class="bg-white rounded-xl p-6 flex flex-col sm:flex-row items-center gap-6 border border-gray-200 shadow-sm">
                                @if ($major->competency_head_photo)
                                    <img src="{{ asset('storage/' . $major->competency_head_photo) }}"
                                        alt="Foto {{ $major->competency_head }}"
                                        class="w-24 h-24 rounded-full object-cover shadow-lg border-4 border-white flex-shrink-0">
                                @endif
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">{{ $major->competency_head }}</h3>
                                    <p class="text-base text-gray-500">Kepala Kompetensi Keahlian {{ $major->name }}</p>
                                </div>
                            </div>
                        </section>
                    </div>

                    {{-- KOLOM KANAN (1/3): SIDEBAR JURUSAN LAIN (STICKY CARD) --}}
                    <aside class="lg:col-span-1">
                        <div class="lg:sticky lg:top-8 space-y-8"> {{-- MENAMBAH space-y-8 untuk jarak antar bagian --}}
                            <h3 class="text-2xl font-bold mb-4 amaliah-dark">
                                Jelajahi Jurusan Lain
                            </h3>
                            <div class="space-y-6"> {{-- MENAMBAH space-y-6 untuk jarak antar kartu --}}
                                {{-- Mengambil 3 jurusan lain secara acak --}}
                                @php
                                    $randomOtherMajors = $otherMajors->shuffle()->take(3);
                                @endphp

                                @forelse ($randomOtherMajors as $otherMajor)
                                    {{-- Sidebar Card menggunakan format INDEX LENGKAP --}}
                                    <div
                                        class="bg-white rounded-2xl shadow-md transition-all duration-300 group overflow-hidden flex flex-col">

                                        {{-- BAGIAN GAMBAR UTAMA (TINGGI DIUBAH menjadi h-32) --}}
                                        <a href="{{ route('public.majors.show', $otherMajor) }}" class="block h-32">
                                            @if ($otherMajor->image)
                                                <img src="{{ asset('storage/' . $otherMajor->image) }}"
                                                    alt="Gambar {{ $otherMajor->name }}" class="w-full h-full object-cover">
                                            @endif
                                        </a>

                                        {{-- KONTEN TEKS KARTU --}}
                                        <div class="p-4 relative flex flex-col flex-grow">

                                            {{-- LOGO JURUSAN --}}
                                            <div class="absolute -top-12 left-4 bg-white p-2 rounded-xl shadow-lg">
                                                @if ($otherMajor->logo)
                                                    <img src="{{ asset('storage/' . $otherMajor->logo) }}"
                                                        alt="Logo {{ $otherMajor->abbreviation ?? $otherMajor->name }}"
                                                        class="h-10 w-10 object-contain">
                                                @else
                                                    <div
                                                        class="h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 text-xs">
                                                        Logo
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Header Teks --}}
                                            <div class="pt-5">
                                                <h3 class="text-xl font-bold amaliah-dark leading-tight">
                                                    {{ $otherMajor->abbreviation ?? $otherMajor->name }}
                                                </h3>
                                                <p class="text-xs text-gray-500">{{ $otherMajor->name }}</p>
                                            </div>

                                            {{-- PENAMBAHAN: Deskripsi singkat untuk kartu jurusan lain --}}
                                            <p class="text-sm text-gray-600 mt-3 line-clamp-3">
                                                {{ Str::limit(strip_tags($otherMajor->description), 100, '...') }}
                                            </p>


                                            {{-- Footer Kartu (Tombol Selengkapnya) --}}
                                            <div class="mt-4 flex items-center">
                                                <a href="{{ route('public.majors.show', $otherMajor) }}"
                                                    class="inline-flex items-center text-white px-4 py-2 rounded-lg text-sm font-semibold relative overflow-hidden group"
                                                    style="background-color: {{ $amaliahDark }};">
                                                    <span>Selengkapnya</span>
                                                    <div
                                                        class="ml-2 bg-white rounded-full p-1 flex items-center justify-center relative z-10 transition-transform duration-300 group-hover:translate-x-1">
                                                        <i class="fas fa-arrow-right text-xs"
                                                            style="color: {{ $amaliahDark }};"></i>
                                                    </div>
                                                    {{-- Efek overlay untuk tombol --}}
                                                    <span
                                                        class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 italic text-sm p-4 bg-white rounded-xl shadow-sm border">Tidak ada
                                        jurusan lain untuk ditampilkan.</p>
                                @endforelse
                            </div>

                            {{-- Tombol Lihat Semua --}}
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <a href="{{ route('public.majors.index') }}"
                                    class="w-full block text-center py-3 rounded-lg text-sm font-semibold text-white transition-colors duration-200 hover:opacity-90"
                                    style="background-color: {{ $amaliahDark }};">
                                    Lihat Semua Daftar Jurusan
                                </a>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </main>



    </body>

    </html>

@endsection