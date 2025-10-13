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
        $hasImages = isset($newsImages) && $newsImages->isNotEmpty();
    @endphp

    <body>

        <section class="relative max-w-screen">
            {{-- Slider Gambar Dinamis --}}
            @if($hasImages)
                <div x-data="{ activeSlide: 1, totalSlides: {{ $newsImages->count() }} }"
                    x-init="setInterval(() => { activeSlide = activeSlide % totalSlides + 1 }, 5000)">
                    <div class="relative w-full h-[300px] overflow-hidden">
                        @foreach($newsImages as $image)
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
                                    <a href="{{ route('public.majors.index') }}"
                                        class="ml-2 font-medium text-white hover:text-white md:ml-3 transition-colors">News</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="md:w-1/2 mb-8">
                {{-- Ukuran font judul diperkecil --}}
                <h2 class="text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight">
                    Berita Terbaru
                </h2>
                {{-- Ukuran font deskripsi diperkecil --}}
                <p class="mt-3 text-base text-gray-600">
                    Jelajahi beragam berita terbaru yang kami sajikan untuk Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($news as $item)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                        <a href="{{ route('public.news.show', $item) }}" class="block">
                            @if ($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                    class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                                    Gambar Tidak Tersedia
                                </div>
                            @endif
                        </a>
                        <div class="p-5">
                            <span class="text-xs text-gray-500 block mb-2">
                                {{ \Carbon\Carbon::parse($item->date_published)->format('d F Y') }} | Oleh:
                                {{ $item->publisher }}
                            </span>
                            <a href="{{ route('public.news.show', $item) }}"
                                class="text-xl font-semibold text-gray-900 hover:text-blue-600 transition-colors block mb-3 line-clamp-2">
                                {{ $item->title }}
                            </a>
                            <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                {{ strip_tags($item->description) }}
                            </p>
                            <a href="{{ route('public.news.show', $item) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                Baca Selengkapnya
                                <i class="fas fa-arrow-right ml-2 text-xs"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center col-span-full py-10">Belum ada berita yang dipublikasikan.</p>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $news->links() }}
            </div>
        </div>

       
    </body>

    </html>


@endsection