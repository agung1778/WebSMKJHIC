@extends('layouts.public-app')

@section('content')
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SMK Amaliah</title>

        {{-- Aset disalin dari referensi berita untuk konsistensi --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    </head>

    <body>
        {{-- ================================================================= --}}
        {{-- BAGIAN 1: HERO IMAGE (GAMBAR UTAMA EKSTRAKURIKULER) --}}
        {{-- ================================================================= --}}
        <header class="w-full h-80 lg:h-96 bg-gray-900 overflow-hidden">
            @if ($extracurricular->image)
                <img src="{{ asset('storage/' . $extracurricular->image) }}" alt="Gambar {{ $extracurricular->name }}"
                    class="w-full h-full object-cover opacity-80">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-800 text-white text-xl">
                    Gambar Tidak Tersedia
                </div>
            @endif
        </header>

        {{-- Breadcrumb Navigation --}}
        <div class="bg-[#2D2D2D]">
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
                                    <i class="fas fa-chevron-right text-white text-xs"></i>
                                    <a href="{{ route('public.extracurricular.index') }}"
                                        class="ml-2 font-medium text-gray-300 hover:text-white md:ml-3 transition-colors">Ekstrakurikuler</a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-white text-xs"></i>
                                    <span class="ml-2 font-medium text-white md:ml-3 truncate max-w-xs">
                                        {{ $extracurricular->name }}
                                    </span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-x-12">

                {{-- ========================================================== --}}
                {{-- KOLOM KIRI (2/3): KONTEN UTAMA --}}
                {{-- ========================================================== --}}
                <div class="lg:col-span-2">
                    <a href="{{ route('public.extracurricular.index') }}"
                        class="text-gray-500 hover:text-gray-900 text-sm font-medium mb-8 inline-flex items-center transition-colors">
                        <i class="fas fa-arrow-left mr-2 text-xs"></i>
                        Kembali ke Daftar Ekstrakurikuler
                    </a>

                    {{-- Header Konten --}}
                    <header class="mb-8 border-b border-gray-200 pb-6">
                        <h1 class="text-3xl lg:text-4xl font-extrabold mb-4 text-gray-900 leading-tight">
                            {{-- DIUBAH --}}
                            {{ $extracurricular->name }}
                        </h1>

                        {{-- Metadata: Tipe, Pelatih, Kontak --}}
                        <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-gray-500">
                            <span class="inline-flex items-center" title="Tipe Kegiatan">
                                <i class="fas fa-tag mr-2 text-gray-400"></i>
                                {{-- DIUBAH --}}
                                Tipe: <strong class="ml-1 text-gray-700">{{ $extracurricular->type }}</strong>
                            </span>
                            <span class="inline-flex items-center" title="Pelatih">
                                <i class="fas fa-user-tie mr-2 text-gray-400"></i>
                                {{-- DIUBAH --}}
                                Pelatih: <strong class="ml-1 text-gray-700">{{ $extracurricular->coach }}</strong>
                            </span>
                            <span class="inline-flex items-center" title="Kontak">
                                <i class="fas fa-phone-alt mr-2 text-gray-400"></i>
                                {{-- DIUBAH --}}
                                Kontak: <strong class="ml-1 text-gray-700">{{ $extracurricular->contact }}</strong>
                            </span>
                        </div>
                    </header>

                    {{-- Deskripsi Lengkap --}}
                    <article class="prose prose-lg max-w-none text-gray-800 leading-relaxed mb-12">
                        {{-- DIUBAH --}}
                        {!! nl2br(e($extracurricular->description)) !!}
                    </article>

                    {{-- Tombol Berbagi --}}
                    <footer class="mt-12 pt-8 border-t border-gray-200">
                        <div class="flex items-center gap-4">
                            <h3 class="text-base font-semibold text-gray-700">Bagikan Kegiatan:</h3>
                            <div class="flex items-center space-x-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                                    target="_blank"
                                    class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fab fa-facebook-f text-lg"></i>
                                </a>
                                {{-- DIUBAH --}}
                                <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($extracurricular->name) }}"
                                    target="_blank"
                                    class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-black hover:text-white transition-all">
                                    <i class="fab fa-twitter text-lg"></i>
                                </a>
                                {{-- DIUBAH --}}
                                <a href="https://api.whatsapp.com/send?text={{ urlencode($extracurricular->name . ' - ' . url()->current()) }}"
                                    target="_blank"
                                    class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-green-500 hover:text-white transition-all">
                                    <i class="fab fa-whatsapp text-lg"></i>
                                </a>
                            </div>
                        </div>
                    </footer>
                </div>

                {{-- ========================================================== --}}
                {{-- KOLOM KANAN (1/3): SIDEBAR KEGIATAN LAINNYA --}}
                {{-- ========================================================== --}}
                <aside class="lg:col-span-1 mt-12 lg:mt-0">
                    <div class="lg:sticky lg:top-8">
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-5 pb-4 border-b">Kegiatan Lainnya</h3>
                            <ul class="space-y-4">
                                {{-- Bagian ini sudah benar, jadi tidak diubah --}}
                                @forelse ($suggestedExtracurriculars as $item)
                                    <li>
                                        <a href="{{ route('public.extracurricular.show', $item) }}" class="group block">
                                            <p
                                                class="font-semibold text-gray-800 group-hover:text-[#4ED400] transition-colors duration-200 leading-snug">
                                                {{ $item->name }}
                                            </p>
                                            <span class="text-xs text-gray-500 mt-1">{{ $item->type }}</span>
                                        </a>
                                    </li>
                                @empty
                                    <li class="text-sm text-gray-500">Tidak ada kegiatan lain untuk ditampilkan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </body>

    </html>
@endsection