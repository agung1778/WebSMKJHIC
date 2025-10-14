@extends('layouts.public-app')

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
        <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    </head>
    @php
        $amaliahGreen = '#63cd00';
        $amaliahDark = '#282829';
        $amaliahBlue = '#E0E7FF';

        // Cek Variabel 
        $hasImages = isset($facilityImages) && $facilityImages->isNotEmpty();
    @endphp

    <body>
        <section class="relative max-w-screen">
            {{-- Slider Gambar Dinamis --}}
            @if($hasImages)
                <div x-data="{ activeSlide: 1, totalSlides: {{ $facilityImages->count() }} }"
                    x-init="setInterval(() => { activeSlide = activeSlide % totalSlides + 1 }, 5000)">
                    <div class="relative w-full h-[300px] overflow-hidden">
                        @foreach($facilityImages as $image)
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
                                    <a href="{{ route('public.facilities.index') }}"
                                        class="ml-2 font-medium text-white hover:text-white md:ml-3 transition-colors">Facilities</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="bg-white py-20 sm:py-24">
            <div class="container mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 items-center gap-y-16 gap-x-8 lg:grid-cols-2">

                    <div class="flex items-end justify-center gap-4 lg:justify-start">

                        {{--
                        PERUBAHAN DI SINI:
                        Looping sekarang menggunakan variabel $image untuk setiap objek dari collection.
                        --}}
                        @foreach ($gridImages as $image)
                            @php
                                // Logika untuk ukuran dinamis tetap sama, menggunakan $loop->iteration
                                $sizeClasses = [
                                    1 => 'h-48 w-28 shadow-sm',
                                    2 => 'h-64 w-32 shadow-md',
                                    3 => 'h-80 w-36 shadow-lg',
                                ][$loop->iteration] ?? 'h-48 w-28 shadow-sm';
                            @endphp

                            <div class="rounded-xl bg-gray-100 {{ $sizeClasses }}">
                                {{--
                                PERUBAHAN DI SINI:
                                - `src` sekarang mengakses properti 'path' dari objek $image.
                                - `alt` bisa mengambil dari properti 'alt' atau 'title' untuk aksesibilitas yang lebih baik.
                                --}}
                                <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->alt_text ?? $image->title }}"
                                    class="h-full w-full rounded-xl object-cover">
                            </div>
                        @endforeach

                    </div>

                    <div class="text-center lg:text-left">
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                            Fasilitas Modern untuk Pengalaman Belajar Terbaik
                        </h2>
                        <p class="mt-4 text-lg leading-8 text-gray-600">
                            Kami menyediakan perangkat dan platform terkini yang memungkinkan siswa berkolaborasi dalam
                            proyek-proyek kreatif, sama seperti di industri profesional.
                        </p>

                        <ul class="mt-8 space-y-4">
                            {{-- Poin keunggulan tetap sama --}}
                            <li class="flex items-start justify-center lg:justify-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-xl text-[#63cd00]"></i>
                                </div>
                                <span class="ml-3 text-base text-gray-700">Laboratorium Berstandar Industri</span>
                            </li>
                            <li class="flex items-start justify-center lg:justify-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-xl text-[#63cd00]"></i>
                                </div>
                                <span class="ml-3 text-base text-gray-700">Proyek Tim yang Kolaboratif</span>
                            </li>
                            <li class="flex items-start justify-center lg:justify-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-xl text-[#63cd00]"></i>
                                </div>
                                <span class="ml-3 text-base text-gray-700">Simulasi Dunia Kerja Nyata</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>


        <section class="bg-white py-16 sm:py-24 mt-[-40px]">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- KEPALA BAGIAN (JUDUL DI KIRI, TAB DI KANAN) --}}
                <div class="flex flex-col md:flex-row justify-between md:items-end gap-8 mb-12">

                    {{-- Kolom Kiri: Judul dan Deskripsi --}}
                    <div class="md:w-1/2 lg:w-2/3">
                        <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 tracking-tight">
                            Fasilitas Unggulan Kami
                        </h2>
                        <p class="mt-4 text-lg text-gray-600">
                            Jelajahi beragam sarana dan prasarana modern yang kami sediakan untuk mendukung proses belajar.
                        </p>
                    </div>

                    {{-- Kolom Kanan: Tombol Tab Filter --}}
                    <div class="flex-shrink-0">
                        {{-- Container untuk tombol tab --}}
                        <div id="tabs-container" class="flex flex-wrap items-center justify-start md:justify-end gap-3">
                            {{-- Loop untuk membuat tombol tab dari setiap tipe fasilitas --}}
                            @foreach($groupedFacilities->keys() as $type)
                                <button data-tab="{{ Str::slug($type) }}"
                                    class="tab-button px-4 py-2 text-sm font-semibold rounded-lg transition-colors duration-200">
                                    {{ $type }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>


                @foreach($groupedFacilities as $type => $facilities)
                    <div id="{{ Str::slug($type) }}" class="tab-content">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @forelse($facilities as $facility)
                                {{-- KARTU FASILITAS (Sangat disarankan untuk dijadikan Blade Component) --}}
                                @php
                                    $iconClass = 'fa-star'; // Ikon default
                                    if ($facility->type == 'Akademik')
                                        $iconClass = 'fa-book-open';
                                    elseif ($facility->type == 'Olahraga')
                                        $iconClass = 'fa-futbol';
                                    elseif ($facility->type == 'Umum')
                                        $iconClass = 'fa-building';
                                @endphp

                                <div
                                    class="group bg-white border border-gray-200 rounded-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                    {{-- GAMBAR FASILITAS --}}
                                    <div class="relative h-56 w-full">
                                        <img src="{{ $facility->image ? Storage::url($facility->image) : 'https://placehold.co/600x400/e2e8f0/64748b?text=Gambar' }}"
                                            alt="Gambar {{ $facility->name }}" class="w-full h-full object-cover">
                                    </div>

                                    {{-- KONTEN CARD --}}
                                    <div class="p-6 flex flex-col flex-grow">
                                        {{-- TIPE & IKON --}}
                                        <div class="flex items-center text-sm font-semibold text-blue-600 mb-2">
                                            <i class="fas {{ $iconClass }} mr-2 w-4 text-center"></i>
                                            <span>{{ $facility->type }}</span>
                                        </div>
                                        {{-- NAMA FASILITAS --}}
                                        <h3 class="text-xl font-bold text-gray-900 mb-2 leading-tight">
                                            {{ $facility->name }}
                                        </h3>
                                        {{-- DESKRIPSI SINGKAT --}}
                                        <p class="text-gray-600 text-sm flex-grow mb-6">
                                            {{ Str::limit($facility->description, 120) }}
                                        </p>
                                        {{-- TOMBOL AKSI --}}
                                        <div class="mt-auto">
                                            <a href="{{ route('public.facilities.show', $facility) }}"
                                                class="inline-flex items-center font-semibold text-blue-600 group/link">
                                                Baca Selengkapnya
                                                <i
                                                    class="fas fa-arrow-right ml-2 text-xs transition-transform duration-300 group-hover/link:translate-x-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                {{-- TAMPILAN JIKA TIDAK ADA FASILITAS PADA TIPE INI --}}
                                <div class="col-span-full border-2 border-dashed border-gray-300 rounded-lg p-12 text-center">
                                    <p class="text-gray-500 font-medium">Fasilitas untuk kategori "{{ $type }}" tidak ditemukan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- SCRIPT UNTUK MEKANISME TAB --}}
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const tabsContainer = document.getElementById('tabs-container');
                    if (tabsContainer) {
                        const tabButtons = tabsContainer.querySelectorAll('.tab-button');
                        const tabContents = document.querySelectorAll('.tab-content');
                        const firstTabName = tabButtons.length > 0 ? tabButtons[0].dataset.tab : null;

                        const activeClasses = ['bg-[#59E300]', 'text-white', 'shadow-sm'];
                        const inactiveClasses = ['bg-gray-100', 'text-gray-700', 'hover:bg-gray-200'];

                        function switchTab(tabName) {
                            if (!tabName) return;

                            // Sembunyikan semua konten
                            tabContents.forEach(content => {
                                content.classList.add('hidden');
                            });

                            // Atur style tombol
                            tabButtons.forEach(button => {
                                if (button.dataset.tab === tabName) {
                                    button.classList.add(...activeClasses);
                                    button.classList.remove(...inactiveClasses);
                                } else {
                                    button.classList.add(...inactiveClasses);
                                    button.classList.remove(...activeClasses);
                                }
                            });

                            // Tampilkan konten yang dipilih
                            const activeContent = document.getElementById(tabName);
                            if (activeContent) {
                                activeContent.classList.remove('hidden');
                            }
                        }

                        // Tambahkan event listener ke setiap tombol
                        tabButtons.forEach(button => {
                            button.addEventListener('click', () => {
                                switchTab(button.dataset.tab);
                            });
                        });

                        // Atur tab default saat halaman dimuat (tab pertama)
                        if (firstTabName) {
                            switchTab(firstTabName);
                        } else {
                            // Jika tidak ada tab sama sekali, sembunyikan semua konten
                            tabContents.forEach(content => {
                                content.classList.add('hidden');
                            });
                        }
                    }
                });
            </script>
        </section>


        <section class="bg-[#ffffff] py-16 sm:py-24 mt-[-50px]">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Judul dan Deskripsi Section -->
                <div class="max-w-3xl mx-auto text-center mt-[-30px]">
                    <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">
                        Discover Our Facility Video
                    </h2>
                    <p class="mt-4 text-lg text-gray-600">
                        Watch the video below to get a glimpse into our facility
                    </p>
                </div>

                <!-- Kontainer Video Responsif 16:9 -->
                <div class="mt-12 max-w-4xl mx-auto">
                    <div class="relative w-full" style="padding-top: 56.25%;">
                        <!-- 
                                      Catatan: padding-top: 56.25% adalah hasil dari 9 / 16, 
                                      yang menciptakan rasio aspek 16:9 yang responsif.
                                    -->
                        <iframe class="absolute top-0 left-0 w-full h-full rounded-xl shadow-2xl" <iframe width="560"
                            height="315"
                            src="https://www.youtube-nocookie.com/embed/V1itS-cUH4M?si=uZIO58_CPQb9nwDA&amp;controls=0"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>>
                        </iframe>
                    </div>
                </div>

            </div>
        </section>







    </body>

    </html>


@endsection