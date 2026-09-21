@extends('layouts.public-app')

@section('title', $news->title . ' | SMK Amaliah 1 & 2')
@section('description', \Illuminate\Support\Str::limit(strip_tags($news->description), 160) ?? 'Berita resmi SMK Amaliah 1 & 2 Ciawi-Bogor.')

@include('PublicSide.news._styles')

@php
    $published = \Carbon\Carbon::parse($news->date_published);
    $readMinutes = max(1, (int) ceil(str_word_count(strip_tags($news->description)) / 200));
@endphp

@section('content')

    {{-- ============================ HERO ============================ --}}
    <section class="relative bg-[#282829]">
        <div class="relative h-[340px] lg:h-[440px] overflow-hidden">
            @if ($news->image)
                <img src="{{ img_url($news->image, 1600, 900) }}" width="1600" height="900" alt="{{ $news->title }}"
                    class="w-full h-full object-cover">
            @else
                <div class="nw-thumb-fallback nw-thumb-fallback--big"><i class="fa-solid fa-newspaper"></i></div>
            @endif

            <div class="absolute inset-0"
                style="background: linear-gradient(180deg, rgba(40,40,41,.35) 0%, rgba(40,40,41,.78) 100%)"></div>

            <div class="relative z-10 h-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-end pb-10">
                <span class="nw-chip w-fit mb-4"><i class="fa-solid fa-newspaper"></i> Berita Sekolah</span>
                <h1 class="text-white text-3xl lg:text-5xl font-extrabold leading-tight max-w-4xl">
                    {{ $news->title }}
                </h1>
                <div class="mt-5 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-white/85">
                    <span class="inline-flex items-center gap-2">
                        <i class="fa-regular fa-calendar"></i>
                        {{ $published->format('d F Y') }}
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <i class="fa-solid fa-user-pen"></i>
                        {{ $news->publisher }}
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <i class="fa-regular fa-clock"></i>
                        {{ $readMinutes }} menit baca
                    </span>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <div style="background-color:#2D2D2D;">
            <div class="max-w-screen-xl min-h-14 mx-auto px-4 sm:px-6 lg:px-8 flex items-center py-3">
                <nav aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 md:space-x-3 text-sm">
                        <li class="inline-flex items-center flex-shrink-0">
                            <a href="/" class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                Home
                            </a>
                        </li>
                        <li class="inline-flex items-center flex-shrink-0">
                            <i class="fa-solid fa-chevron-right text-white/40 text-xs"></i>
                            <a href="{{ route('public.news.index') }}"
                                class="inline-flex items-center ml-2 md:ml-3 font-medium text-gray-300 hover:text-white transition-colors">
                                Berita
                            </a>
                        </li>
                        <li class="inline-flex items-center min-w-0">
                            <i class="fa-solid fa-chevron-right text-white/40 text-xs flex-shrink-0"></i>
                            <span class="ml-2 md:ml-3 font-medium text-[#63cd00] truncate max-w-[40vw] sm:max-w-[55vw]" title="{{ $news->title }}">
                                {{ $news->title }}
                            </span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ============================ KONTEN ============================ --}}
    <section class="bg-white">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-x-12 gap-y-10">

                {{-- Kolom kiri (2/3): artikel --}}
                <div class="lg:col-span-2 min-w-0">
                    <a href="{{ route('public.news.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#63cd00] transition-colors duration-200 mb-6">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Kembali ke Daftar Berita
                    </a>

                    {{-- Isi artikel --}}
                    <article class="nw-article bg-gray-50 border border-[#eef0f3] rounded-2xl p-6 sm:p-10">
                        {!! \App\Support\HtmlSanitizer::clean($news->description) !!}
                    </article>

                    {{-- Bagikan --}}
                    <footer class="mt-8 bg-[#282829] rounded-2xl p-6 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="font-bold text-white">Bagikan artikel ini</p>
                            <p class="text-sm text-white/60 mt-0.5">Bantu sebarkan informasi untuk teman &amp; keluarga.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-[#1877F2] hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke Facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($news->title) }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-black hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke X (Twitter)">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($news->title . ' - ' . url()->current()) }}" target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-[#25D366] hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Bagikan ke WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            <a href="{{ url()->current() }}" onclick="navigator.clipboard?.writeText(this.href); alert('Tautan disalin!'); return false;"
                                class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-[#63cd00] hover:text-[#282829] hover:-translate-y-0.5 transition-all duration-200"
                                aria-label="Salin tautan">
                                <i class="fa-solid fa-link"></i>
                            </a>
                        </div>
                    </footer>
                </div>

                {{-- Kolom kanan (1/3): sidebar --}}
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-8 space-y-6">

                        {{-- Info artikel --}}
                        <div class="nw-side">
                            <h3 class="nw-side__head"><i class="fa-solid fa-circle-info"></i> Info Artikel</h3>
                            <div class="nw-side__body space-y-4 text-sm">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-lg bg-white flex items-center justify-center text-[#63cd00] border border-gray-200">
                                        <i class="fa-regular fa-calendar"></i>
                                    </span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tanggal Terbit</p>
                                        <p class="font-semibold text-gray-800">{{ $published->format('d F Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-lg bg-white flex items-center justify-center text-[#63cd00] border border-gray-200">
                                        <i class="fa-solid fa-user-pen"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Penulis</p>
                                        <p class="font-semibold text-gray-800 truncate">{{ $news->publisher }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-lg bg-white flex items-center justify-center text-[#63cd00] border border-gray-200">
                                        <i class="fa-regular fa-clock"></i>
                                    </span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Waktu Baca</p>
                                        <p class="font-semibold text-gray-800">{{ $readMinutes }} menit</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Berita lainnya --}}
                        <div class="nw-side">
                            <h3 class="nw-side__head"><i class="fa-solid fa-newspaper"></i> Berita Lainnya</h3>
                            <div class="nw-side__body">
                                @php $suggested = $randomNews ?? collect([]); @endphp
                                @forelse ($suggested->take(4) as $item)
                                    <a href="{{ route('public.news.show', $item) }}"
                                        class="nw-rel" aria-label="{{ $item->title }}">
                                        <div class="nw-rel__thumb">
                                            @if ($item->image)
                                                <img src="{{ img_url($item->image, 400, 400) }}" width="400" height="400" alt="{{ $item->title }}" loading="lazy">
                                            @else
                                                <i class="fa-solid fa-newspaper"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 leading-snug line-clamp-2 hover:text-[#63cd00] transition-colors duration-200">
                                                {{ $item->title }}
                                            </p>
                                            <span class="nw-meta mt-1.5 inline-flex">
                                                <i class="fa-regular fa-calendar"></i>
                                                {{ \Carbon\Carbon::parse($item->date_published)->diffForHumans() }}
                                            </span>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-sm text-gray-500 py-4 text-center">Tidak ada berita lain untuk ditampilkan.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </section>

@endsection