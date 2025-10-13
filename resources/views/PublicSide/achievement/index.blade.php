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
        $hasImages = isset($achievementImages) && $achievementImages->isNotEmpty();
    @endphp

    <body>

        <section class="relative max-w-screen">
            {{-- Slider Gambar Dinamis --}}
            @if($hasImages)
                <div x-data="{ activeSlide: 1, totalSlides: {{ $achievementImages->count() }} }"
                    x-init="setInterval(() => { activeSlide = activeSlide % totalSlides + 1 }, 5000)">
                    <div class="relative w-full h-[300px] overflow-hidden">
                        @foreach($achievementImages as $image)
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
                                    <a href="{{ route('public.achievement.index') }}"
                                        class="ml-2 font-medium text-white hover:text-white md:ml-3 transition-colors">Achievement</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="bg-gray-50 py-16 sm:py-24">
            <div class="container mx-auto max-w-7xl px-6 lg:px-8">

                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        Galeri Prestasi Siswa
                    </h2>
                    <p class="mt-4 max-w-2xl mx-auto text-lg leading-8 text-gray-600">
                        Kebanggaan kami atas pencapaian luar biasa para siswa di berbagai kompetisi.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                    @forelse ($achievements as $achievement)
                        <div
                            class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-all duration-300 ease-in-out hover:shadow-lg hover:-translate-y-1">

                            {{-- Kontainer Gambar --}}
                            <div class="relative overflow-hidden">
                                <a href="{{ route('public.achievement.show', $achievement->id) }}">
                                    <img src="{{ asset('storage/' . $achievement->image) }}" alt="{{ $achievement->title }}"
                                        class="aspect-[4/3] w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                </a>
                            </div>

                            {{-- Konten Teks --}}
                            <div class="flex flex-1 flex-col p-5">
                                {{-- Badges untuk Level & Juara --}}
                                <div class="mb-3 flex items-center gap-x-2">
                                    <span
                                        class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-200">
                                        {{-- DIUBAH: Ikon lebih relevan untuk tingkat/lokasi --}}
                                        <i class="fas fa-map-location-dot mr-1.5 text-gray-500"></i>
                                        {{ $achievement->level }}
                                    </span>
                                    <span
                                        class="inline-flex items-center rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-200">
                                        <i class="fas fa-trophy mr-1.5 text-yellow-600"></i>
                                        Juara {{ $achievement->winner }}
                                    </span>
                                </div>

                                {{-- Judul dan Deskripsi --}}
                                <h3 class="text-base font-semibold leading-tight text-gray-900">
                                    <a href="{{ route('public.achievement.show', $achievement->id) }}"
                                        class="hover:text-blue-600 transition-colors">
                                        {{ $achievement->title }}
                                    </a>
                                </h3>
                                <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $achievement->description }}</p>

                                {{-- Tombol Aksi (Selalu Terlihat) --}}
                                <div class="mt-auto pt-4">
                                    <a href="{{ route('public.achievement.show', $achievement->id) }}"
                                        class="text-sm font-semibold text-blue-600 transition-colors hover:text-blue-800">
                                        Lihat Detail
                                         <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <i class="fas fa-award text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Belum ada prestasi yang ditambahkan.</p>
                        </div>
                    @endforelse

                </div>

                <div class="mt-16">
                    {{ $achievements->links() }}
                </div>

            </div>
        </section>

        <div class="bg-white py-16 sm:py-24 mt-[-100px]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 tracking-tight">
                        Detail Prestasi Siswa
                    </h2>
                    <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-600">
                        Informasi mengenai prestasi siswa SMK Amaliah 1 & 2 dari masa ke masa.
                    </p>
                </div>

                @if ($achievementContent)
                    <article class="prose prose-lg prose-gray max-w-screen">
                        {!! $achievementContent->content !!}
                    </article>
                @else
                    <div class="text-center py-24 px-6 bg-gray-50 rounded-xl border border-gray-200">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Konten Belum Tersedia</h3>
                        <p class="mt-1 text-sm text-gray-500">Halaman ini sedang dalam pengembangan.</p>
                    </div>
                @endif

            </div>
        </div>



    </body>

    </html>


@endsection