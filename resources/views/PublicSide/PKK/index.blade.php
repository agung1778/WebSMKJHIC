@extends('layouts.public-app')

@section('content')
    {{-- Resources --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    @php

        $amaliahGreen = $amaliahGreen ?? '#63cd00';
        $amaliahDark = $amaliahDark ?? '#282829';
    @endphp
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8FAFC;
        }

        /* --- Card Styles --- */
        .project-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #E2E8F0;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
        }

        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #3B82F6;
        }

        .card-image-wrapper {
            position: relative;
            overflow: hidden;
            height: 14rem;
            background-color: #f3f4f6;
            border-bottom: 1px solid #f1f5f9;
        }

        .project-card:hover .card-img {
            transform: scale(1.05);
        }

        .card-img {
            transition: transform 0.5s ease;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* --- Search Bar Styles --- */
        .search-wrapper {
            position: relative;
            width: 100%;
            max-width: 500px;
        }

        .search-input {
            width: 100%;
            padding: 1rem 1.5rem;
            padding-right: 140px;
            background-color: #F3F4F6;
            border: 2px solid transparent;
            border-radius: 50px;
            outline: none;
            color: #333;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .search-input:focus {
            background-color: white;
            border-color: #63cd00;
            box-shadow: 0 4px 15px rgba(99, 205, 0, 0.1);
        }

        .search-btn {
            position: absolute;
            right: 5px;
            top: 5px;
            bottom: 5px;
            background-color: #63cd00;
            color: white;
            padding: 0 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: background 0.3s;
        }

        .search-btn:hover {
            background-color: #52a800;
        }
    </style>

    {{-- ======================== SECTION 1: HERO (TETAP) ======================== --}}
    <section class="bg-white border-b border-gray-100 pb-20" style="padding-top: 60px; padding-bottom: 100px;">
        <div class="container mx-auto px-6 md:px-12 lg:px-20">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">

                <div class="w-full lg:w-1/2 text-center lg:text-left space-y-6" style="padding-left: 50px">
                    <span
                        class="inline-block py-1 px-3 rounded-full bg-green-50 text-[#63cd00] text-xs font-bold tracking-wider uppercase mb-2 border border-green-100">
                        {{ __('Galeri Produk TeFa') }}
                    </span>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                        {{ __('Karya Inovatif') }} <br>
                        <span class="text-[#63cd00]">{{ __('Siswa SMK Amaliah') }}</span>
                    </h1>
                    <p class="text-gray-500 text-lg leading-relaxed">
                        {{ __('Platform etalase digital untuk menampilkan produk kreatif, jasa, dan teknologi tepat guna hasil pembelajaran berbasis industri.') }}
                    </p>
                    <div class="mt-8 flex justify-center lg:justify-start">
                        <form action="{{ route('public.pkk.index') }}" method="GET" class="search-wrapper">
                            <input type="text" name="q" value="{{ request('q') }}" class="search-input"
                                placeholder="{{ __('Cari produk...') }}">
                            <button type="submit" class="search-btn">{{ __('Cari') }}</button>
                        </form>
                    </div>
                </div>

                <div class="w-full lg:w-1/2" style="padding-right: 50px">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-4 mt-8">
                            <div class="h-40 w-full rounded-2xl overflow-hidden shadow-md">
                                <img src="{{ asset('assets/image/DroneView.jpg') }}" class="w-full h-full object-cover">
                            </div>
                            <div class="h-56 w-full rounded-2xl overflow-hidden shadow-md">
                                <img src="{{ asset('assets/image/DroneView.jpg') }}" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="h-56 w-full rounded-2xl overflow-hidden shadow-md">
                                <img src="{{ asset('assets/image/Stackup.png') }}" class="w-full h-full object-cover">
                            </div>
                            <div
                                class="bg-gray-50 h-40 rounded-2xl border border-gray-100 flex flex-col items-center justify-center text-center p-4 shadow-sm">
                                <span class="text-3xl font-bold text-[#63cd00]">{{ $projects->total() }}+</span>
                                <span class="text-sm text-gray-500 font-semibold uppercase mt-1">{{ __('Karya Siswa') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="py-12 bg-slate-50">
        <div class="container mx-auto px-4 md:px-12">

            {{-- Header Section --}}
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">
                    {{ __('Proyek PKK Terbaru') }}
                </h2>
                <div class="h-1 w-20 bg-green-500 mx-auto rounded-full"></div>
            </div>

            {{-- Grid System --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                @forelse($projects as $project)
                    {{-- Card Wrapper --}}
                    <div
                        class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden group">

                        {{-- 1. IMAGE SECTION (Slim Height) --}}
                        <div class="relative h-48 bg-gray-100 overflow-hidden">
                            @if ($project->photo)
                                <img src="{{ asset('storage/' . $project->photo) }}" alt="{{ $project->title }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i class="fas fa-image fa-3x"></i>
                                </div>
                            @endif

                            {{-- Badge Kategori (Pojok Kanan Atas) --}}
                            <div class="absolute top-3 right-3">
                                <span
                                    class="bg-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider text-gray-800 shadow-sm">
                                    {{ $project->category ?? 'Teknologi' }}
                                </span>
                            </div>
                        </div>

                        {{-- 2. CONTENT BODY --}}
                        {{-- relative diperlukan agar logo absolute mengacu ke div ini --}}
                        <div class="p-6 relative flex flex-col flex-grow">

                            {{-- LOGO BRAND (Overlapping) --}}
                            {{-- Menggunakan style inline margin-top negatif untuk memaksa posisi naik tanpa JIT --}}
                            <div class="absolute left-6" style="top: -32px;">
                                <div
                                    class="w-16 h-16 bg-white rounded-2xl shadow-lg border border-gray-100 flex items-center justify-center p-2">
                                    @if ($project->logo)
                                        <img src="{{ asset('storage/' . $project->logo) }}"
                                            class="w-full h-full object-contain rounded-lg">
                                    @else
                                        <i class="fas fa-cube text-gray-300 text-xl"></i>
                                    @endif
                                </div>
                            </div>

                            {{-- JUDUL & BRAND --}}
                            {{-- mt-8 memberikan ruang agar teks tidak tertutup logo --}}
                            <div class="mt-8 mb-2">
                                <h3 class="text-xl font-bold text-gray-800 leading-tight">
                                    {{ $project->brand_name ?? __('Nama Brand') }}
                                </h3>
                                <p class="text-sm text-gray-500 font-medium mt-1">
                                    {{ $project->title }}
                                </p>
                            </div>

                            {{-- JURUSAN --}}
                            <div class="mb-4">
                                <div class="inline-flex items-center text-sm font-bold text-[{{ $amaliahGreen }}]">
                                    <i class="fas fa-layer-group mr-2"></i>
                                    {{ $project->major->name ?? __('Jurusan') }}
                                </div>
                            </div>

                            {{-- DIVIDER --}}
                            <hr class="border-gray-100 mb-4">

                            {{-- KREATOR --}}
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wide">Kreator</p>
                                    <p class="text-sm font-bold text-gray-800">
                                        {{ $project->student_names }}
                                    </p>
                                </div>
                            </div>

                            {{-- 3. TOMBOL DETAIL (Style Referensi: Hitam Full Width) --}}
                            <div class="mt-auto">
                                {{-- TOMBOL ACTION (Style Text-Only Hijau) --}}
                                <a href="{{ route('public.pkk.detail', $project->id) }}"
                                    class="relative flex items-center justify-start w-full p-3 text-sm font-bold transition-all duration-300 group/btn hover:underline"
                                    style="color: {{ $amaliahGreen }};">

                                    {{-- Text --}}
                                    <span class="relative z-10" > Lihat Detail Proyek</span>

                                    {{-- Icon Panah Hijau --}}
                                    <div
                                        class="relative z-10 ml-2 transition-transform duration-300 group-hover/btn:translate-x-1">
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-1 md:col-span-3 text-center py-16 bg-white rounded-2xl border border-dashed border-gray-300">
                        <i class="fas fa-folder-open text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 font-medium">Belum ada proyek ditampilkan.</p>
                    </div>
                @endforelse

            </div>

            {{-- Pagination --}}
            <div class="mt-12 flex justify-center">
                {{ $projects->links() }}
            </div>
        </div>
    </section>
@endsection
