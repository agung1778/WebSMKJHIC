@extends('layouts.public-app')

@section('title', 'Hasil Pencarian untuk "' . e($query) . '"')

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
        $hasImages = isset($mainImages) && $mainImages->isNotEmpty();
    @endphp

    <body>
        <div class="bg-white py-12 sm:py-16">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- Header Hasil Pencarian --}}
                <div class="max-w-3xl mb-10">
                    <h1 class="text-3xl lg:text-4xl font-extrabold text-[#2D2D2D] tracking-tight">
                        Hasil Pencarian
                    </h1>
                    @if($query)
                        <p class="mt-3 text-lg text-slate-600">
                            Menampilkan {{ $totalResults }} hasil untuk: <span
                                class="font-semibold text-slate-800">"{{ e($query) }}"</span>
                        </p>
                    @else
                        <p class="mt-3 text-lg text-slate-600">
                            Silakan masukkan kata kunci pada kolom pencarian.
                        </p>
                    @endif
                </div>

                @if($totalResults > 0)
                    <div class="space-y-12">
                        {{-- Hasil Berita --}}
                        @if($newsResults->isNotEmpty())
                            <div>
                                <h2 class="text-2xl font-bold text-slate-800 border-b-2 border-[#63cd00] pb-2 mb-6">Berita</h2>
                                <div class="space-y-6">
                                    @foreach($newsResults as $item)
                                        <a href="{{ route('public.news.show', $item) }}"
                                            class="block p-4 rounded-lg hover:bg-gray-50 transition-colors">
                                            <h3 class="font-bold text-lg text-slate-900">{{ $item->title }}</h3>
                                            <p class="text-slate-600 text-sm mt-1">{{ Str::limit(strip_tags($item->description), 150) }}
                                            </p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Hasil Guru --}}
                        @if($teacherResults->isNotEmpty())
                            <div>
                                <h2 class="text-2xl font-bold text-slate-800 border-b-2 border-[#63cd00] pb-2 mb-6">Guru & Staff
                                </h2>
                                <div class="space-y-6">
                                    @foreach($teacherResults as $item)
                                        <a href="{{ route('public.teachers.index') }}"
                                            class="block p-4 rounded-lg hover:bg-gray-50 transition-colors">
                                            <h3 class="font-bold text-lg text-slate-900">{{ $item->name }}</h3>
                                            <p class="text-slate-600 text-sm mt-1">{{ $item->position }} - {{ $item->subject }}</p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Hasil Jurusan --}}
                        @if($majorResults->isNotEmpty())
                            <div>
                                <h2 class="text-2xl font-bold text-slate-800 border-b-2 border-[#63cd00] pb-2 mb-6">Jurusan</h2>
                                <div class="space-y-6">
                                    @foreach($majorResults as $item)
                                        <a href="{{ route('public.majors.show', $item) }}"
                                            class="block p-4 rounded-lg hover:bg-gray-50 transition-colors">
                                            <h3 class="font-bold text-lg text-slate-900">{{ $item->name }}</h3>
                                            <p class="text-slate-600 text-sm mt-1">{{ Str::limit(strip_tags($item->description), 150) }}
                                            </p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Hasil Mitra --}}
                        @if($partnerResults->isNotEmpty())
                            <div>
                                <h2 class="text-2xl font-bold text-slate-800 border-b-2 border-[#63cd00] pb-2 mb-6">Mitra Industri
                                </h2>
                                <div class="space-y-6">
                                    @foreach($partnerResults as $item)
                                        <a href="{{ route('public.partners.show', $item) }}"
                                            class="block p-4 rounded-lg hover:bg-gray-50 transition-colors">
                                            <h3 class="font-bold text-lg text-slate-900">{{ $item->name }}</h3>
                                            <p class="text-slate-600 text-sm mt-1">{{ Str::limit(strip_tags($item->description), 150) }}
                                            </p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Hasil Fasilitas & Ekskul bisa ditambahkan dengan pola yang sama --}}
                    </div>
                @elseif($query)
                    <div class="text-center py-16">
                        <i class="fas fa-search text-5xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700">Tidak ada hasil ditemukan</h3>
                        <p class="text-gray-500 mt-2">Coba gunakan kata kunci yang berbeda.</p>
                    </div>
                @endif
            </div>
        </div>

    </body>

    </html>

@endsection