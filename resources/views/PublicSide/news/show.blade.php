@extends('layouts.public-app') {{-- Sesuaikan dengan nama file layout utama Anda --}}

@section('content')
    @php
        $amaliahGreen = '#63cd00';
        $amaliahDark = '#282829';
        $amaliahBlue = '#E0E7FF';

        // Cek Variabel 
        $hasImages = isset($newsImages) && $newsImages->isNotEmpty();
    @endphp

    <div>


        {{-- ================================================================= --}}
        {{-- BAGIAN 1: HERO IMAGE (GAMBAR UTAMA BERITA) --}}
        {{-- ================================================================= --}}
        <header class="w-full h-80 lg:h-96 bg-gray-900 overflow-hidden">
            {{-- Mengambil langsung gambar utama berita ($news->image) sebagai Hero Image --}}
            @if ($news->image)
                <img src="{{ asset('storage/' . $news->image) }}" alt="Gambar Utama {{ $news->title }}"
                    class="w-full h-full object-cover opacity-80 transition-opacity duration-300 hover:opacity-100">
                {{-- Tambahan: opacity 80% dengan hover 100% untuk efek visual yang halus --}}
            @else
                {{-- Fallback jika gambar utama tidak tersedia --}}
                <div class="w-full h-full flex items-center justify-center bg-gray-800 text-white text-xl">
                    Gambar Berita Tidak Tersedia
                </div>
            @endif
        </header>
        <div class="bg-[#2D2D2D]">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 h-auto py-3">
                <div class="flex items-center overflow-hidden">
                    <nav class="flex w-full text-sm sm:text-base md:text-lg" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-1 sm:space-x-2 md:space-x-3 w-full overflow-hidden">
                            {{-- 1. Home --}}
                            <li class="inline-flex items-center flex-shrink-0">
                                <a href="/" class="font-medium text-gray-300 hover:text-white transition-colors">
                                    Home
                                </a>
                            </li>

                            {{-- 2. News --}}
                            <li class="inline-flex items-center flex-shrink-0">
                                <i class="fas fa-chevron-right text-gray-300 text-xs mx-1 sm:mx-2"></i>
                                <a href="{{ route('public.news.index') }}"
                                    class="font-medium text-gray-300 hover:text-white transition-colors">
                                    News
                                </a>
                            </li>

                            {{-- 3. Judul berita --}}
                            <li class="inline-flex items-center min-w-0 flex-1">
                                <i class="fas fa-chevron-right text-gray-300 text-xs flex-shrink-0 mx-1 sm:mx-2"></i>
                                <span class="font-medium text-white truncate block" title="{{ $news->title }}">
                                    {{ $news->title }}
                                </span>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-x-12">

                {{-- ========================================================== --}}
                {{-- KOLOM KIRI (2/3): KONTEN UTAMA ARTIKEL --}}
                {{-- ========================================================== --}}
                <div class="lg:col-span-2">
                    {{-- Tombol Kembali --}}
                    <a href="{{ route('public.news.index') }}"
                        class="text-gray-500 hover:text-gray-900 text-sm font-medium mb-8 inline-flex items-center transition-colors">
                        <i class="fas fa-arrow-left mr-2 text-xs"></i>
                        Kembali ke Daftar Berita
                    </a>

                    {{-- Header Artikel --}}
                    <header class="mb-8 border-b border-gray-200 pb-6">
                        <h1 class="text-3xl lg:text-4xl font-extrabold mb-4 text-gray-900 leading-tight">
                            {{ $news->title }}
                        </h1>

                        {{-- Metadata di bawah judul --}}
                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500">
                            <span class="inline-flex items-center">
                                <i class="far fa-calendar-alt mr-2 text-gray-400"></i>
                                Dipublikasikan: <strong
                                    class="ml-1 text-gray-700">{{ \Carbon\Carbon::parse($news->date_published)->format('d F Y') }}</strong>
                            </span>
                            <span class="inline-flex items-center">
                                <i class="far fa-user-circle mr-2 text-gray-400"></i>
                                Oleh: <strong class="ml-1 text-gray-700">{{ $news->publisher }}</strong>
                            </span>
                        </div>
                    </header>

                    {{-- Konten Utama Artikel & Galeri --}}
                    <div x-data="{ modalOpen: false, modalImage: '' }">
                        {{-- Isi Konten Artikel --}}
                        <article class="prose prose-lg max-w-none text-gray-800 leading-relaxed mb-12">
                            {!! $news->description !!}
                        </article>

                        {{-- BAGIAN GALERI MINI (THUMBNAILS) --}}
                        @if (isset($newsImages) && $newsImages->isNotEmpty())
                            <div class="pt-8 mt-12 border-t border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 mb-4">Galeri Foto</h3>
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    @foreach ($newsImages as $image)
                                        <div @click="modalImage = '{{ Storage::url($image->path) }}'; modalOpen = true"
                                            class="aspect-square overflow-hidden rounded-lg cursor-pointer group">
                                            <img src="{{ Storage::url($image->path) }}"
                                                alt="{{ $image->description ?? $image->filename }}"
                                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-300">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- MODAL PREVIEW GAMBAR (TETAP DI SINI) --}}
                        <div x-show="modalOpen"
                            class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-90 flex items-center justify-center"
                            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            @click.away="modalOpen = false" style="display: none;">
                            <div class="relative max-w-4xl w-full p-4 mx-auto">
                                <button @click="modalOpen = false"
                                    class="absolute -top-2 -right-2 m-4 text-white text-4xl hover:text-gray-300 transition-colors z-50">
                                    &times;
                                </button>
                                <img :src="modalImage"
                                    class="w-full h-auto max-h-[90vh] object-contain rounded-lg shadow-2xl">
                            </div>
                        </div>
                    </div>

                    {{-- BAGIAN FOOTER ARTIKEL (SHARING) --}}
                    <footer class="mt-12 pt-8 border-t border-gray-200">
                        <div class="flex items-center gap-4">
                            <h3 class="text-base font-semibold text-gray-700">Bagikan Artikel:</h3>
                            <div class="flex items-center space-x-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                                    target="_blank"
                                    class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all duration-200">
                                    <i class="fab fa-facebook-f text-lg"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($news->title) }}"
                                    target="_blank"
                                    class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-black hover:text-white transition-all duration-200">
                                    <i class="fab fa-twitter text-lg"></i>
                                </a>
                                <a href="https://api.whatsapp.com/send?text={{ urlencode($news->title . ' - ' . url()->current()) }}"
                                    target="_blank"
                                    class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-green-500 hover:text-white transition-all duration-200">
                                    <i class="fab fa-whatsapp text-lg"></i>
                                </a>
                            </div>
                        </div>
                    </footer>
                </div>

                {{-- ========================================================== --}}
                {{-- KOLOM KANAN (1/3): SUGGESTION SIDEBAR --}}
                {{-- ========================================================== --}}
                <aside class="lg:col-span-1 mt-12 lg:mt-0">
                    {{-- Wrapper untuk membuat 'sticky' --}}
                    <div class="lg:sticky lg:top-8">
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-5 pb-4 border-b">Baca Juga</h3>
                            <ul class="space-y-4">
                                @php
                                    // Pastikan variabel $randomNews ada dari controller Anda
                                    $suggestedNews = $randomNews ?? collect([]);
                                @endphp

                                @forelse ($suggestedNews->take(4) as $item)
                                    <li>
                                        {{-- PERBAIKAN: Menggunakan $item->id bukan $item->slug --}}
                                        <a href="{{ route('public.news.show', $item->id) }}" class="group block">
                                            <p
                                                class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors duration-200 leading-snug">
                                                {{ $item->title }}
                                            </p>
                                            <span
                                                class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($item->date_published)->diffForHumans() }}</span>
                                        </a>
                                    </li>
                                @empty
                                    <li class="text-sm text-gray-500">Tidak ada berita lain untuk ditampilkan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </div>
    </div>

@endsection