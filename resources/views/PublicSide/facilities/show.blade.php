@extends('layouts.public-navbar')

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




    <body>
        <header class="w-full h-80 lg:h-96 bg-gray-900 overflow-hidden">
            {{-- Mengambil langsung gambar utama berita ($news->image) sebagai Hero Image --}}
            @if ($facility->image)
                <img src="{{ asset('storage/' . $facility->image) }}" alt="Gambar Utama {{ $facility->name }}"
                    class="w-full h-full object-cover opacity-80 transition-opacity duration-300 hover:opacity-100">
                {{-- Tambahan: opacity 80% dengan hover 100% untuk efek visual yang halus --}}
            @else
                {{-- Fallback jika gambar utama tidak tersedia --}}
                <div class="w-full h-full flex items-center justify-center bg-black text-white text-xl">
                    Gambar Berita Tidak Tersedia
                </div>
            @endif
        </header>

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
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-white text-xs"></i>

                                    {{-- Cukup panggil properti 'name' dari objek $partner --}}
                                    <span class="ml-2 font-medium md:ml-3 truncate max-w-xs" style="color: #ffffff;">
                                        {{ $facility->name }}
                                    </span>

                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>




    </body>

    </html>


@endsection