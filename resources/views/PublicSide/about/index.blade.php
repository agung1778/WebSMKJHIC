@extends('layouts.public-app')

@section('content')

    <!DOCTYPE html>
    <html lang="en" class="dark">

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
        <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    </head>

    @php
        $amaliahGreen = '#63cd00';
        $amaliahDark = '#282829';
        $amaliahBlue = '#E0E7FF';

        // Cek Variabel 
        $hasImages = isset($mainImages) && $mainImages->isNotEmpty();
    @endphp

    <body>
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
                                    <i class="fas fa-chevron-right text-white text-xs"></i>
                                    <a href="{{ route('public.about.index') }}"
                                        class="ml-2 font-medium text-white hover:text-white md:ml-3 transition-colors">About</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>




        <section class="bg-white py-16 sm:py-24">
            <div class="container mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-y-12 lg:grid-cols-3 lg:gap-x-12">

                    <div class="lg:col-span-1">
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                            Mempersiapkan Generasi Siap Kerja
                        </h2>
                        <p class="mt-4 text-lg text-gray-600 leading-relaxed">
                            Selain memberikan fondasi akademis yang kuat, kami berfokus pada pembentukan keahlian praktis
                            yang relevan dengan kebutuhan industri, sehingga lulusan kami dapat langsung berkontribusi
                            secara profesional.
                        </p>
                    </div>

                    <div class="lg:col-span-2 grid grid-cols-1 gap-y-10 sm:grid-cols-2 sm:gap-x-8">

                        <div class="flex items-start">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-[#e3fad5]">
                                <i class="fas fa-industry text-lg text-[#64ff04]"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">Kurikulum Berbasis Industri</h3>
                                <p class="mt-2 text-base leading-7 text-gray-600">
                                    Materi kami dirancang bersama praktisi industri untuk memastikan relevansi dengan dunia
                                    kerja nyata.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-[#e3fad5]">
                                <i class="fas fa-tools text-lg text-[#64ff04]"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">Pembelajaran Praktik</h3>
                                <p class="mt-2 text-base leading-7 text-gray-600">
                                    Siswa mendapatkan pengalaman langsung melalui workshop, laboratorium, dan proyek nyata.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-[#e3fad5]">
                                <i class="fas fa-certificate text-lg text-[#64ff04]"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">Sertifikasi Kompetensi</h3>
                                <p class="mt-2 text-base leading-7 text-gray-600">
                                    Lulusan kami dibekali sertifikat keahlian yang diakui secara nasional dan oleh industri.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-[#e3fad5]">
                                <i class="fas fa-chalkboard-teacher text-lg text-[#64ff04]"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">Pengajar Profesional</h3>
                                <p class="mt-2 text-base leading-7 text-gray-600">
                                    Belajar langsung dari guru dan instruktur yang memiliki pengalaman di bidangnya
                                    masing-masing.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>


        <section class="bg-white py-20 mt-[-40px]">
            <div class="container mx-auto px-4">

                {{-- Judul Seksi --}}
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        Jelajahi Tentang Kami
                    </h2>
                    <p class="mt-3 max-w-2xl mx-auto text-lg leading-8 text-gray-600">
                        Kenali lebih dalam setiap aspek yang membangun institusi kami.
                    </p>
                </div>


                {{--
                DIUBAH DI SINI:
                Ditambahkan kelas `px-4 sm:px-6 lg:px-8` untuk memberi padding horizontal.
                --}}
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-2 gap-4 px-4 sm:px-6 lg:px-8">

                    @foreach ($aboutLinks as $item)
                        <a href="{{ url($item['url']) }}"
                            class="group bg-[#2D2D2D] rounded-xl p-6 flex items-center justify-between hover:bg-[#4A4A4A] transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">

                            {{-- Bagian Kiri: Teks Judul dan Deskripsi --}}
                            <div class="text-white pr-4">
                                <h3 class="text-xl font-bold">{{ $item['title'] }}</h3>
                                <p class="mt-1 text-sm text-gray-300">{{ $item['description'] }}</p>
                            </div>

                            {{-- Bagian Kanan: Ikon --}}
                            <div class="ml-4 flex-shrink-0 w-14 h-14 bg-white rounded-full flex items-center justify-center
                                        group-hover:scale-110 group-hover:bg-[#59E300] transition-all duration-300">

                                <i
                                    class="fas {{ $item['icon'] }} text-2xl text-gray-800 group-hover:text-white transition-colors"></i>

                            </div>
                        </a>
                    @endforeach

                </div>
                {{-- AKHIR DARI GRID --}}

            </div>
        </section>


        <section class="bg-[#ffffff] py-16 sm:py-24 mt-[-50px]">
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
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                        </iframe>
                    </div>
                </div>

            </div>
        </section>






    </body>

    </html>
@endsection