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
        <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    </head>

    @php
        $amaliahGreen = '#63cd00';
        $amaliahDark = '#282829';
        $amaliahBlue = '#E0E7FF';

        // Cek Variabel 
        $hasImages = isset($partnersImages) && $partnersImages->isNotEmpty();
    @endphp

    <body>
        <section class="relative max-w-screen">
            {{-- Slider Gambar Dinamis --}}
            @if($hasImages)
                <div x-data="{ activeSlide: 1, totalSlides: {{ $partnersImages->count() }} }"
                    x-init="setInterval(() => { activeSlide = activeSlide % totalSlides + 1 }, 5000)">
                    <div class="relative w-full h-[300px] overflow-hidden">
                        @foreach($partnersImages as $image)
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
                                    <a href="{{ route('public.partners.index') }}"
                                        class="ml-2 font-medium text-white hover:text-white md:ml-3 transition-colors">Industry
                                        Partners</a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-white text-xs"></i>

                                    {{-- Cukup panggil properti 'name' dari objek $partner --}}
                                    <span class="ml-2 font-medium md:ml-3 truncate max-w-xs" style="color: #ffffff;">
                                        {{ $partner->name }}
                                    </span>

                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>




        <section class="bg-gray-50 py-16 sm:py-24">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- WADAH UTAMA DENGAN LAYOUT 2 KOLOM --}}
                <div class="lg:grid lg:grid-cols-3 lg:gap-x-12">

                    {{-- ============================================= --}}
                    {{-- KOLOM KIRI: KONTEN UTAMA DETAIL PARTNER --}}
                    {{-- ============================================= --}}
                    <div class="lg:col-span-2">

                        {{-- KOTAK PUTIH UTAMA (HEADER + KONTEN) --}}
                        <div class="bg-white border border-slate-200 rounded-xl shadow-lg p-6 sm:p-8">

                            {{-- BAGIAN HEADER: LOGO & NAMA PERUSAHAAN --}}
                            <div class="flex flex-col sm:flex-row items-center">
                                @if($partner->logo)
                                    <img src="{{ Storage::url($partner->logo) }}" alt="Logo {{ $partner->name }}"
                                        class="h-28 w-28 object-contain bg-slate-100 border border-slate-200 rounded-xl p-3 shadow-sm mb-5 sm:mb-0 sm:mr-6 flex-shrink-0">
                                @endif
                                <div class="w-full text-center sm:text-left">
                                    <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-800 tracking-tight">
                                        {{ $partner->name }}
                                    </h1>
                                    <p class="text-slate-500 mt-1 text-base">
                                        Sektor: {{ $partner->sector ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            {{-- GARIS PEMISAH --}}
                            <hr class="border-slate-200 my-8">

                            {{-- BAGIAN KONTEN: DESKRIPSI & INFO LAINNYA --}}
                            <div>
                                <h2 class="text-2xl font-bold text-slate-800 mb-5">Tentang Mitra</h2>

                                {{-- Deskripsi (menghilangkan prose-invert) --}}
                                <div class="prose prose-slate lg:prose-base max-w-none mb-8 text-slate-600">
                                    <p>{{ $partner->description }}</p>
                                </div>

                                {{-- Detail dalam bentuk daftar dengan ikon yang lebih profesional --}}
                                <ul class="space-y-4 text-slate-700">
                                    <li class="flex items-start">
                                        <i class="fas fa-map-marker-alt w-5 text-center mr-3 pt-1 text-blue-600"></i>
                                        <div>
                                            <strong>Lokasi:</strong>
                                            <span class="block text-slate-600">{{ $partner->city ?? 'N/A' }}</span>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-phone-alt w-5 text-center mr-3 pt-1 text-blue-600"></i>
                                        <div>
                                            <strong>Kontak:</strong>
                                            <span
                                                class="block text-slate-600">{{ $partner->company_contact ?? 'N/A' }}</span>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-calendar-alt w-5 text-center mr-3 pt-1 text-blue-600"></i>
                                        <div>
                                            <strong>Bergabung Sejak:</strong>
                                            <span
                                                class="block text-slate-600">{{ \Carbon\Carbon::parse($partner->partnership_date)->translatedFormat('d F Y') }}</span>
                                        </div>
                                    </li>
                                    @if($partner->website)
                                        <li class="flex items-start">
                                            <i class="fas fa-globe w-5 text-center mr-3 pt-1 text-blue-600"></i>
                                            <div>
                                                <strong>Website:</strong>
                                                <a href="{{ $partner->website }}" target="_blank" rel="noopener noreferrer"
                                                    class="block text-blue-600 hover:underline break-all">{{ $partner->website }}</a>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- ==================================================== --}}
                    {{-- KOLOM KANAN: SIDEBAR SUGESTI --}}
                    {{-- ==================================================== --}}
                    <div class="lg:col-span-1 mt-12 lg:mt-0">
                        <div class="bg-white border border-slate-200 rounded-xl p-6 sticky top-24 shadow-lg">
                            <h3 class="text-xl font-bold text-slate-800 mb-5 border-b border-slate-200 pb-3">Mitra Industri
                                Lainnya</h3>

                            <div class="space-y-4">
                                @forelse ($randomPartners as $suggestedPartner)
                                    <a href="{{ route('public.partners.show', $suggestedPartner) }}"
                                        class="flex items-center group p-3 rounded-lg hover:bg-slate-100 transition-colors duration-200">
                                        <div
                                            class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center p-1 border border-slate-200 mr-4 flex-shrink-0">
                                            @if($suggestedPartner->logo)
                                                <img src="{{ Storage::url($suggestedPartner->logo) }}"
                                                    alt="Logo {{ $suggestedPartner->name }}" class="max-h-12 w-auto object-contain">
                                            @endif
                                        </div>
                                        <div class="overflow-hidden">
                                            <p
                                                class="font-bold text-slate-700 group-hover:text-blue-600 transition-colors leading-tight truncate">
                                                {{ $suggestedPartner->name }}
                                            </p>
                                            <p class="text-sm text-slate-500 truncate">{{ $suggestedPartner->sector }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-slate-500 text-sm">Tidak ada mitra lain untuk ditampilkan.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </body>

    </html>


@endsection