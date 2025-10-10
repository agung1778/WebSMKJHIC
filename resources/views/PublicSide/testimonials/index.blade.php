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
        $hasImages = isset($majorsImages) && $majorsImages->isNotEmpty();
    @endphp

    <body>
        <section class="relative max-w-screen">
            {{-- Slider Gambar Dinamis --}}
            @if($hasImages)
                <div x-data="{ activeSlide: 1, totalSlides: {{ $majorsImages->count() }} }"
                    x-init="setInterval(() => { activeSlide = activeSlide % totalSlides + 1 }, 5000)">
                    <div class="relative w-full h-[300px] overflow-hidden">
                        @foreach($majorsImages as $image)
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
                                    <a href="{{ route('public.testimonials.index') }}"
                                        class="ml-2 font-medium text-white hover:text-white md:ml-3 transition-colors">Testimonials</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="bg-gray-50 py-16 sm:py-24">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- Header Section --}}
                <div class="text-center">
                    <h2 class="text-3xl md:text-4xl font-bold" style="color: {{ $amaliahDark }};">Testimoni</h2>
                    <div class="flex items-center justify-center gap-x-2 mx-auto mt-4">
                        <div class="w-20 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                        <div class="w-4 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                        <div class="w-4 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                    </div>
                </div>

                {{-- Container Grid Testimoni --}}
                <div class="mt-12">
                    {{-- Menggunakan grid untuk menampilkan semua kartu --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        @forelse ($testimonials as $testimonial)
                            {{-- Setiap Kartu Testimoni --}}
                            <div
                                class="bg-white border border-gray-200 rounded-2xl p-8 flex flex-col sm:flex-row items-center gap-8 h-full">
                                {{-- Kolom Teks --}}
                                <div class="flex-1 text-center sm:text-left">
                                    <p class="text-gray-700 leading-relaxed">"{{ $testimonial->description }}"</p>
                                    <p class="mt-4 text-gray-800 font-semibold italic">-{{ $testimonial->name }}</p>
                                    <div class="mt-6 flex flex-col sm:flex-row justify-between items-center text-sm">
                                        <span class="text-gray-400">{{ $testimonial->created_at->format('Y-m-d') }}</span>
                                        <span class="font-semibold mt-2 sm:mt-0" style="color: {{ $amaliahGreen }};">
                                            Alumni Jurusan {{ $testimonial->major->name ?? 'N/A' }}
                                            {{ $testimonial->alumni_year }}
                                        </span>
                                    </div>
                                </div>
                                {{-- Kolom Gambar --}}
                                <div class="flex-shrink-0 order-first sm:order-last">
                                    <img src="{{ asset('storage/' . $testimonial->photo) }}" alt="Foto {{ $testimonial->name }}"
                                        class="w-32 h-32 rounded-full object-cover shadow-md">
                                </div>
                            </div>
                        @empty
                            <div class="w-full text-center py-12 md:col-span-2">
                                <p class="text-gray-500">Belum ada testimoni untuk ditampilkan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </section>


    </body>

    </html>
@endsection