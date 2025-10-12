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
    <style>
        .hero-clip-path {
            clip-path: polygon(0 0, 100% 0, 100% calc(100% - 4rem), calc(100% - 4rem) 100%, 0 100%);
        }

        .custom-mt {
            margin-top: -30px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    <body class="font-['Poppins'] bg-gray-100">
        @php
            $amaliahGreen = '#63cd00';
            $amaliahGreen = '#63cd00';
            $amaliahDark = '#282829';

            // Mengambil program lain dari controller
            $otherPrograms = $otherPrograms ?? collect([]);

            // Logika untuk memproses keunggulan program (dijadikan array)
            $advantages = $program->advantage ? array_filter(explode("\n", $program->advantage)) : [];
        @endphp

        {{-- ================================================================= --}}
        {{-- BAGIAN 1: HERO IMAGE (GAMBAR UTAMA PROGRAM) --}}
        {{-- ================================================================= --}}
        <header class="h-64 lg:h-80 w-full bg-gray-800">
            @if ($program->image)
                <img src="{{ asset('storage/' . $program->image) }}" alt="Gambar Latar {{ $program->title }}"
                    class="w-full h-full object-cover">
            @endif
        </header>

        {{-- ================================================================= --}}
        {{-- BAGIAN 2: BREADCRUMB NAVIGASI --}}
        {{-- ================================================================= --}}
        <div style="background-color: #2D2D2D;">
            <div class="max-w-screen-xl h-[70px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="h-full flex items-center">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-2 md:space-x-3 text-lg">
                            <li class="inline-flex items-center">
                                <a href="/"
                                    class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                    Home
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                                    <a href="{{ route('public.program.index') }}" {{-- DIUBAH: Route ke index program --}}
                                        class="ml-2 font-medium text-gray-300 hover:text-white md:ml-3 transition-colors">Program
                                        Unggulan</a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                                    <span class="ml-2 font-medium md:ml-3 text-white">{{ $program->name }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- ================================================================= --}}
        {{-- BAGIAN 3: KONTEN UTAMA (LAYOUT 2 KOLOM) --}}
        {{-- ================================================================= --}}
        <main class="py-16 lg:py-24 bg-white">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-16">

                    {{-- ========================================================== --}}
                    {{-- Kolom Kiri: Konten Detail Program --}}
                    {{-- ========================================================== --}}
                    <div class="lg:col-span-2 space-y-12">

                        {{-- DIUBAH: Header Judul Program (Lebih Simpel dan Profesional) --}}
                        <section class="pb-6 border-b border-gray-200">
                            <p class="text-base font-semibold uppercase text-gray-500 tracking-wider">Program Unggulan</p>
                            <h1 class="mt-2 text-4xl lg:text-5xl font-extrabold tracking-tight"
                                style="color: {{ $amaliahDark }};">
                                {{ $program->name }}
                            </h1>
                        </section>

                        {{-- Deskripsi Program --}}
                        <section>
                            <h2 class="text-2xl font-bold mb-4" style="color: {{ $amaliahDark }};">
                                Tentang Program
                            </h2>
                            {{-- Kelas `prose` akan memberikan styling default yang rapi untuk teks --}}
                            <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed">
                                {!! $program->description !!} {{-- Menggunakan {!! !!} jika deskripsi mengandung HTML --}}
                            </div>
                        </section>

                        {{-- Poin Keunggulan (DIUBAH: Desain lebih bersih) --}}
                        @if (!empty($advantages))
                            <section>
                                <h2 class="text-2xl font-bold mb-6" style="color: {{ $amaliahDark }};">
                                    Poin-Poin Keunggulan
                                </h2>
                                <ul class="space-y-4">
                                    @foreach ($advantages as $advantage)
                                        @if (trim($advantage) != '')
                                            <li class="flex items-start">
                                                <div class="flex-shrink-0 mt-1">
                                                    <i class="fas fa-check-circle text-xl" style="color: {{ $amaliahGreen }};"></i>
                                                </div>
                                                <span class="ml-3 text-base text-gray-700">{{ trim($advantage) }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </section>
                        @endif

                        {{-- Koordinator Program --}}
                        @if ($program->coordinator_name)
                            <section>
                                <h2 class="text-2xl font-bold mb-6" style="color: {{ $amaliahDark }};">
                                    Koordinator Program
                                </h2>
                                <div
                                    class="bg-gray-50 rounded-xl p-6 flex flex-col sm:flex-row items-center gap-6 border border-gray-200">
                                    @if ($program->coordinator_photo)
                                        <img src="{{ asset('storage/' . $program->coordinator_photo) }}"
                                            alt="Foto {{ $program->coordinator_name }}"
                                            class="w-24 h-24 rounded-full object-cover shadow-md border-4 border-white flex-shrink-0">
                                    @endif
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800">{{ $program->coordinator_name }}</h3>
                                        <p class="text-base text-gray-500">Koordinator Program {{ $program->title }}</p>
                                    </div>
                                </div>
                            </section>
                        @endif
                    </div>

                    {{-- ========================================================== --}}
                    {{-- Kolom Kanan: Sidebar Program Lain --}}
                    {{-- ========================================================== --}}
                    <aside class="lg:col-span-1">
                        <div class="lg:sticky lg:top-8 space-y-6">
                            <h3 class="text-2xl font-bold" style="color: {{ $amaliahDark }};">
                                Jelajahi Program Lain
                            </h3>
                            <div class="space-y-4">
                                {{-- DIUBAH: Kartu sidebar lebih simpel dan modern --}}
                                @forelse ($otherPrograms->take(4) as $otherProgram)
                                    <a href="{{ route('public.program.show', $otherProgram->id) }}"
                                        class="group flex items-center gap-4 p-3 bg-white rounded-lg border border-gray-200 transition-all duration-300 hover:shadow-md hover:border-green-300">
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('storage/' . $otherProgram->image) }}"
                                                alt="Gambar {{ $otherProgram->name }}"
                                                class="w-20 h-20 rounded-md object-cover">
                                        </div>
                                        <div>
                                            <h4
                                                class="font-semibold text-gray-800 leading-tight group-hover:text-green-600 transition-colors">
                                                {{ $otherProgram->name }}
                                            </h4>
                                            <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $otherProgram->description }}
                                            </p>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-gray-500 italic text-sm">Tidak ada program lain.</p>
                                @endforelse
                            </div>

                            {{-- Tombol Lihat Semua --}}
                            <div class="pt-4">
                                <a href="{{ route('public.program.index') }}"
                                    class="w-full block text-center py-3 rounded-lg text-sm font-semibold text-white transition-colors duration-200 hover:opacity-90"
                                    style="background-color: {{ $amaliahDark }};">
                                    Lihat Semua Program
                                </a>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </main>



    </body>

    </html>

@endsection