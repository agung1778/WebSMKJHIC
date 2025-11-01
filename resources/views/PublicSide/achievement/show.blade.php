@extends('layouts.public-app') {{-- Sesuaikan dengan nama file layout utama Anda --}}

@section('content')
    @php
        $amaliahGreen = '#63cd00';
        $amaliahDark = '#282829';
        $amaliahBlue = '#E0E7FF';

        // Cek Variabel 
        $hasImages = isset($achievementImages) && $achievementImages->isNotEmpty();
    @endphp

    <div>


        {{-- ================================================================= --}}
        {{-- BAGIAN 1: HERO IMAGE (GAMBAR UTAMA BERITA) --}}
        {{-- ================================================================= --}}
        <header class="w-full h-80 lg:h-96 bg-gray-900 overflow-hidden">
            {{-- Mengambil langsung gambar utama berita ($news->image) sebagai Hero Image --}}
            @if ($achievement->image)
                <img src="{{ asset('storage/' . $achievement->image) }}" alt="Gambar Utama {{ $achievement->title }}"
                    class="w-full h-full object-cover opacity-80 transition-opacity duration-300 hover:opacity-100">
                {{-- Tambahan: opacity 80% dengan hover 100% untuk efek visual yang halus --}}
            @else
                {{-- Fallback jika gambar utama tidak tersedia --}}
                <div class="w-full h-full flex items-center justify-center bg-gray-800 text-white text-xl">
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
                            {{-- 1. Home --}}
                            <li class="inline-flex items-center">
                                <a href="/"
                                    class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                    Home
                                </a>
                            </li>

                            {{-- 2. News (Diperbaiki agar mengarah ke index berita) --}}
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-white text-xs"></i>
                                    {{-- Menggunakan route('public.achievement.index') --}}
                                    <a href="{{ route('public.achievement.index') }}"
                                        class="ml-2 font-medium text-gray-300 hover:text-white md:ml-3 transition-colors">Achievement</a>
                                </div>
                            </li>

                            {{-- 3. Judul Berita Saat Ini (Aktif) --}}
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-white text-xs"></i>
                                    {{-- Menggunakan Judul Berita dan warna aktif (hijau) --}}
                                    <span class="ml-2 font-medium md:ml-3 truncate max-w-xs" style="color: #ffffff;">
                                        {{ $achievement->title }}
                                    </span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- AWAL SECTION DETAIL PRESTASI --}}
        <section class="bg-white">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
                <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-x-12">

                    {{-- ========================================================== --}}
                    {{-- KOLOM KIRI (2/3): KONTEN UTAMA PRESTASI --}}
                    {{-- ========================================================== --}}
                    <div class="lg:col-span-2">
                        {{-- Tombol Kembali --}}
                        <a href="{{ route('public.achievement.index') }}" {{-- Pastikan nama route ini benar --}}
                            class="text-gray-500 hover:text-gray-900 text-sm font-medium mb-8 inline-flex items-center transition-colors">
                            <i class="fas fa-arrow-left mr-2 text-xs"></i>
                            Kembali ke Daftar Prestasi
                        </a>

                        {{-- Header Prestasi --}}
                        <header class="mb-8 border-b border-gray-200 pb-6">
                            <h1 class="text-3xl lg:text-4xl font-extrabold mb-4 text-gray-900 leading-tight">
                                {{ $achievement->title }}
                            </h1>

                            {{-- Metadata di bawah judul --}}
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-gray-500">
                                <span class="inline-flex items-center">
                                    <i class="far fa-calendar-alt mr-2 text-gray-400"></i>
                                    Tanggal: <strong
                                        class="ml-1 text-gray-700">{{ \Carbon\Carbon::parse($achievement->date)->translatedFormat('d F Y') }}</strong>
                                </span>
                                <span class="inline-flex items-center">
                                    <i class="fas fa-trophy mr-2 text-gray-400"></i>
                                    Tingkat: <strong class="ml-1 text-gray-700">{{ $achievement->level }}</strong>
                                </span>
                                <span class="inline-flex items-center">
                                    <i class="fas fa-medal mr-2 text-gray-400"></i>
                                    Peringkat: <strong class="ml-1 text-gray-700">Juara {{ $achievement->winner }}</strong>
                                </span>
                            </div>
                        </header>

                        {{-- Gambar Utama Prestasi --}}
                        <div class="mb-8">
                            <img src="{{ asset('storage/' . $achievement->image) }}" alt="{{ $achievement->title }}"
                                class="w-full h-auto rounded-lg shadow-md object-cover">
                        </div>

                        {{-- Deskripsi Prestasi --}}
                        <article class="prose prose-lg max-w-none text-gray-800 leading-relaxed mb-12">
                            {!! nl2br(e($achievement->description)) !!}
                        </article>

                        {{-- BAGIAN FOOTER (SHARING) --}}
                        <footer class="mt-12 pt-8 border-t border-gray-200">
                            <div class="flex items-center gap-4">
                                <h3 class="text-base font-semibold text-gray-700">Bagikan Prestasi:</h3>
                                <div class="flex items-center space-x-2">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                                        target="_blank"
                                        class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                        <i class="fab fa-facebook-f text-lg"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($achievement->title) }}"
                                        target="_blank"
                                        class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-black hover:text-white transition-all">
                                        <i class="fab fa-twitter text-lg"></i>
                                    </a>
                                    <a href="https://api.whatsapp.com/send?text={{ urlencode($achievement->title . ' - ' . url()->current()) }}"
                                        target="_blank"
                                        class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-green-500 hover:text-white transition-all">
                                        <i class="fab fa-whatsapp text-lg"></i>
                                    </a>
                                </div>
                            </div>
                        </footer>
                    </div>

                    {{-- ========================================================== --}}
                    {{-- KOLOM KANAN (1/3): SIDEBAR PRESTASI LAIN --}}
                    {{-- ========================================================== --}}
                    <aside class="lg:col-span-1 mt-12 lg:mt-0">
                        <div class="lg:sticky lg:top-8">
                            <div class="bg-gray-50 rounded-xl p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-5 pb-4 border-b">Prestasi Lainnya</h3>
                                <ul class="space-y-4">
                                    @forelse ($otherAchievements as $item)
                                        <li>
                                            <a href="{{ route('public.achievement.show', $item->id) }}" class="group block">
                                                <p
                                                    class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors duration-200 leading-snug">
                                                    {{ $item->title }}
                                                </p>
                                                <span
                                                    class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($item->date)->diffForHumans() }}</span>
                                            </a>
                                        </li>
                                    @empty
                                        <li class="text-sm text-gray-500">Tidak ada prestasi lain untuk ditampilkan.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </aside>

                </div>
            </div>
        </section>
        {{-- AKHIR SECTION DETAIL PRESTASI --}}


        </div>
    </div>


@endsection