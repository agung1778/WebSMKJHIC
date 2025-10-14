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

    @endphp


    <body>
        <header class="w-full h-80 lg:h-96 bg-[#2D2D2D] overflow-hidden">
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

        {{-- ========================================================== --}}
        {{-- BAGIAN DETAIL FASILITAS - V3 (KARTU KECIL & GARIS PEMISAH) --}}
        {{-- ========================================================== --}}
        <section class="bg-white py-16 sm:py-24 ">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- PERUBAHAN: Gap dihapus untuk memberi ruang bagi garis pemisah --}}
                <div class="lg:grid lg:grid-cols-3">

                    {{-- ============================================= --}}
                    {{-- KOLOM KIRI: DETAIL FASILITAS UTAMA --}}
                    {{-- ============================================= --}}
                    <div class="lg:col-span-2 lg:pr-12 xl:pr-16 mt-[-50px]">
                        {{-- JUDUL DAN METADATA --}}
                        <div class="mb-8">
                            <p class="text-base font-semibold text-blue-600 uppercase tracking-wide">{{ $facility->type }}
                            </p>
                            <h1 class="mt-2 text-3xl lg:text-4xl font-extrabold text-[#2D2D2D] tracking-tight">
                                {{ $facility->name }}
                            </h1>
                            <p class="mt-4 text-sm text-gray-500">
                                Dipublikasikan pada {{ $facility->created_at->translatedFormat('d F Y') }}
                            </p>
                        </div>

                        {{-- GAMBAR UTAMA --}}
                        @if ($facility->image)
                            <div class="mb-8 aspect-w-16 aspect-h-9">
                                <img src="{{ asset('storage/' . $facility->image) }}" alt="Gambar {{ $facility->name }}"
                                    class="w-full h-full object-cover rounded-xl shadow-lg">
                            </div>
                        @endif

                        {{-- DESKRIPSI LENGKAP --}}
                        <div class="prose prose-lg max-w-none text-gray-600">
                            {!! $facility->description !!}
                        </div>
                    </div>

                    {{-- ==================================================== --}}
                    {{-- KOLOM KANAN: SIDEBAR FASILITAS LAINNYA --}}
                    {{-- ==================================================== --}}
                    {{-- PERUBAHAN: Ditambahkan border kiri dan padding kiri sebagai garis pemisah --}}
                    <div class="lg:col-span-1 mt-12 lg:mt-0 lg:border-l lg:border-gray-200 lg:pl-12 xl:pl-16">
                        <div class="sticky top-24">
                            <h3 class="text-2xl font-bold text-[#2D2D2D] mb-6 border-b border-gray-200 pb-4">
                                Jelajahi Fasilitas Lain
                            </h3>

                            {{-- PERUBAHAN: Jarak antar kartu dikurangi menjadi space-y-6 --}}
                            <div class="space-y-6">
                                @forelse ($otherFacilities as $other)
                                    <div
                                        class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300">
                                        <a href="{{ route('public.facilities.show', $other) }}" class="block">
                                            @if ($other->image)
                                                {{-- PERUBAHAN: Tinggi gambar diperkecil menjadi h-32 --}}
                                                <img src="{{ asset('storage/' . $other->image) }}" alt="{{ $other->name }}"
                                                    class="w-full h-32 object-cover rounded-t-2xl">
                                            @else
                                                <div class="w-full h-32 bg-gray-100 rounded-t-2xl flex items-center justify-center">
                                                    <i class="fas fa-school text-4xl text-gray-300"></i>
                                                </div>
                                            @endif
                                        </a>
                                        {{-- PERUBAHAN: Padding konten dikurangi menjadi p-4 --}}
                                        <div class="p-4">
                                            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">
                                                {{ $other->type }}
                                            </p>
                                            <a href="{{ route('public.facilities.show', $other) }}">
                                                {{-- PERUBAHAN: Ukuran font judul menjadi text-base --}}
                                                <h4
                                                    class="font-bold text-base text-[#2D2D2D] mt-1 hover:text-blue-700 transition-colors">
                                                    {{ $other->name }}
                                                </h4>
                                            </a>
                                            <p class="text-gray-600 text-sm mt-2">
                                                {{-- PERUBAHAN: Batas karakter deskripsi dikurangi --}}
                                                {{ Str::limit(strip_tags($other->description), 60) }}
                                            </p>
                                            {{-- PERUBAHAN: Ukuran tombol diperkecil --}}
                                            <a href="{{ route('public.facilities.show', $other) }}"
                                                class="inline-block bg-[#2D2D2D] text-white text-sm font-semibold px-4 py-2 rounded-lg mt-4 hover:bg-[#2D2D2D] transition-all duration-200 transform hover:scale-105">
                                                Selengkapnya <span class="ml-1 font-light"><i class="fas fa-chevron-right"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 bg-gray-50 p-4 rounded-lg">Tidak ada fasilitas lain untuk
                                        ditampilkan.</p>
                                @endforelse
                            </div>

                            {{-- Tombol Utama di Bawah Daftar --}}
                            @if ($otherFacilities->isNotEmpty())
                                <div class="mt-8 text-center">
                                    <a href="{{ route('public.facilities.index') }}"
                                        class="inline-block w-full bg-[#2D2D2D] text-white font-bold py-3 px-8 rounded-lg hover:bg-black transition-all duration-300 transform hover:scale-105 shadow-md">
                                        Lihat Semua Fasilitas
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </body>

    </html>


@endsection