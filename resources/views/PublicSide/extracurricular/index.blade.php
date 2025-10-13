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
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

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
                                    <a href="{{ route('public.extracurricular.index') }}"
                                        class="ml-2 font-medium text-white hover:text-white md:ml-3 transition-colors">Extracurriculars</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Pastikan Anda sudah menjalankan: php artisan storage:link --}}
        {{-- Pastikan route 'public.extracurricular.show' sudah ada di web.php --}}

        <div class="bg-gray-50">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">

                {{-- Header Section --}}
                <div class="md:w-2/3 lg:w-1/2 mb-10">
                    <h2 class="text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight">
                        Kegiatan Ekstrakurikuler
                    </h2>
                    <p class="mt-3 text-base text-gray-600">
                        Temukan dan kembangkan bakat serta minatmu di luar jam pelajaran melalui berbagai pilihan kegiatan
                        yang kami sediakan.
                    </p>
                </div>

                {{-- Grid untuk Kartu Ekstrakurikuler --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                    @forelse ($extracurriculars as $extracurricular)
                        <div
                            class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 group">
                            <a href="{{ route('public.extracurricular.show', $extracurricular) }}" class="block">
                                @if ($extracurricular->image)
                                    <div class="h-48 overflow-hidden">
                                        <img src="{{ asset('storage/' . $extracurricular->image) }}"
                                            alt="Gambar {{ $extracurricular->name }}"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    </div>
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                                        <i class="fas fa-image mr-2"></i> Gambar Tidak Tersedia
                                    </div>
                                @endif
                            </a>

                            <div class="p-5 flex flex-col flex-grow">
                                {{-- Meta Info: Pelatih & Tipe --}}
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                    <span class="font-medium flex items-center" title="Pelatih">
                                        <i class="fas fa-user-tie mr-1.5 text-gray-400"></i>
                                        {{ $extracurricular->coach }}
                                    </span>
                                    <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full font-semibold">
                                        {{ $extracurricular->type }}
                                    </span>
                                </div>

                                {{-- Nama Ekstrakurikuler --}}
                                <a href="{{ route('public.extracurricular.show', $extracurricular) }}"
                                    class="text-lg font-bold text-gray-900 hover:text-[#4ED400] transition-colors block mb-2 line-clamp-2"
                                    title="{{ $extracurricular->name }}">
                                    {{ $extracurricular->name }}
                                </a>

                                {{-- Deskripsi Singkat --}}
                                <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-grow">
                                    {{ $extracurricular->description }}
                                </p>

                                {{-- Tombol Selengkapnya (diletakkan di bawah) --}}
                                <div class="mt-auto pt-3 border-t border-gray-100">
                                    <a href="{{ route('public.extracurricular.show', $extracurricular) }}"
                                        class="text-[#4ED400] hover:text-green-700 text-sm font-semibold flex items-center">
                                        Selengkapnya
                                        <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center col-span-full py-16">
                            Belum ada data ekstrakurikuler yang dipublikasikan.
                        </p>
                    @endforelse
                </div>

                {{-- Link Paginasi --}}
                <div class="mt-12">
                    {{ $extracurriculars->links() }}
                </div>
            </div>
        </div>

    </body>

    </html>


@endsection