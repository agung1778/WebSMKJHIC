@extends('layouts.public-app')

@section('title', ($major->abbreviation ?? $major->name) . ' | SMK Amaliah 1 & 2')
@section('description', Str::limit(strip_tags($major->description), 160, '...'))

@php
    $amaliahGreen = '#63cd00';
    $amaliahDark = '#282829';
    $advantages = $major->advantage ? array_filter(explode("\n", $major->advantage)) : [];
    $randomOtherMajors = $otherMajors->shuffle()->take(3);
@endphp

@section('content')

    {{-- HERO --}}
    <section class="relative bg-[#282829]">
        <div class="relative h-[320px] lg:h-[380px] overflow-hidden">
            @if($major->image)
                <img src="{{ Storage::url($major->image) }}" alt="Gambar {{ $major->name }}"
                    class="w-full h-full object-cover">
            @else
                <div class="absolute inset-0">
                    <div class="absolute -top-24 -right-16 w-96 h-96 rounded-full opacity-25"
                        style="background: radial-gradient(circle, #63cd00 0%, transparent 70%)"></div>
                </div>
            @endif
            <div class="absolute inset-0"
                style="background: linear-gradient(100deg, rgba(40,40,41,.9) 0%, rgba(40,40,41,.6) 45%, rgba(40,40,41,.2) 100%)"></div>

            <div class="relative z-10 h-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center">
                <span
                    class="inline-flex items-center gap-2 w-fit text-[11px] font-semibold tracking-widest uppercase text-[#d9ffb3] bg-white/10 backdrop-blur border border-white/15 rounded-full px-4 py-1.5 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#63cd00]"></span>
                    Kompetensi Keahlian — {{ $major->tag ?? 'SMK Amaliah' }}
                </span>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    @if($major->logo)
                        <div class="bg-white/95 p-2 rounded-2xl shadow-xl flex-shrink-0">
                            <img src="{{ Storage::url($major->logo) }}" alt="Logo {{ $major->name }}"
                                class="h-16 w-16 object-contain">
                        </div>
                    @endif
                    <div>
                        <h1 class="text-white text-3xl lg:text-4xl font-bold leading-tight">
                            {{ $major->name }}
                        </h1>
                        @if($major->abbreviation)
                            <span class="inline-block mt-2 bg-[#63cd00] text-[#282829] text-xs font-bold px-3 py-1 rounded-full">
                                {{ $major->abbreviation }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <div style="background-color:#2D2D2D;">
            <div class="max-w-screen-xl h-14 mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                <nav aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2 md:space-x-3 text-sm">
                        <li class="flex items-center">
                            <a href="/" class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                Home
                            </a>
                        </li>
                        <li class="flex items-center">
                            <i class="fa-solid fa-chevron-right text-white/40 text-xs"></i>
                            <a href="{{ route('public.majors.index') }}"
                                class="inline-flex items-center ml-0 md:ml-3 font-medium text-gray-300 hover:text-white transition-colors">
                                Major Competency
                            </a>
                        </li>
                        <li class="flex items-center">
                            <i class="fa-solid fa-chevron-right text-white/40 text-xs"></i>
                            <span class="inline-flex items-center ml-0 md:ml-3 font-medium text-[#63cd00]">
                                {{ $major->abbreviation ?? $major->name }}
                            </span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- KONTEN --}}
    <main class="py-16 lg:py-20 bg-gray-50">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-16">

                {{-- Kolom Kiri --}}
                <div class="lg:col-span-2 space-y-12 lg:pr-8 lg:border-r lg:border-gray-200">

                    {{-- Deskripsi --}}
                    <section class="fade-in-section">
                        <h2 class="flex items-center gap-x-2 text-xl font-semibold mb-4" style="color: {{ $amaliahDark }};">
                            <i class="fa-solid fa-circle-info text-lg text-gray-400"></i>
                            <span>Tentang Jurusan</span>
                        </h2>
                        <div class="prose max-w-none text-gray-700 leading-relaxed text-base">
                            <div>{!! \App\Support\HtmlSanitizer::clean($major->description) !!}</div>
                        </div>
                    </section>

                    {{-- Keunggulan --}}
                    @if(!empty($advantages))
                        <section class="fade-in-section">
                            <h2 class="flex items-center gap-x-2 text-xl font-semibold mb-6" style="color: {{ $amaliahDark }};">
                                <i class="fa-solid fa-square-check text-lg text-gray-400"></i>
                                <span>Keunggulan & Konsentrasi Keahlian</span>
                            </h2>
                            <ul class="space-y-3">
                                @foreach($advantages as $advantage)
                                    @if(trim($advantage) != '')
                                        <li class="flex items-start bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                                            <div class="flex-shrink-0 mt-1">
                                                <i class="fa-solid fa-circle-check text-lg" style="color: {{ $amaliahGreen }};"></i>
                                            </div>
                                            <span class="ml-4 text-base text-gray-700">{{ trim($advantage) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    {{-- Testimoni Alumni --}}
                    <section class="fade-in-section">
                        <h2 class="flex items-center gap-x-2 text-xl font-semibold mb-6" style="color: {{ $amaliahDark }};">
                            <i class="fa-solid fa-quote-right text-lg text-gray-400"></i>
                            <span>Kata Mereka Para Alumni</span>
                        </h2>

                        @if($major->testimonials->isNotEmpty())
                            <div id="testimonial-carousel" class="relative overflow-hidden w-full">
                                <div class="flex transition-transform duration-500 ease-in-out" style="transform: translateX(0%);">
                                    @foreach($major->testimonials as $testimonial)
                                        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-md relative flex-shrink-0 w-full snap-center">
                                            <i class="fa-solid fa-quote-left text-4xl absolute top-6 left-6 text-gray-100 opacity-80"></i>
                                            <p class="text-base italic text-gray-700 mt-2 ml-10 leading-relaxed">"{{ $testimonial->description }}"</p>
                                            <div class="flex items-center mt-6 pt-4 border-t border-gray-100">
                                                @if($testimonial->photo)
                                                    <img src="{{ Storage::url($testimonial->photo) }}" alt="Foto {{ $testimonial->name }}"
                                                        class="w-12 h-12 object-cover rounded-full flex-shrink-0 mr-4 border border-gray-100">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-300 rounded-full flex-shrink-0 mr-4"></div>
                                                @endif
                                                <div>
                                                    <p class="font-semibold text-gray-800">{{ $testimonial->name }}</p>
                                                    <p class="text-xs text-gray-500">Alumni
                                                        {{ $testimonial->major->abbreviation ?? $testimonial->major->name }}
                                                        ({{ $testimonial->alumni_year }})
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button id="prev-btn"
                                    class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-white p-2 rounded-full shadow-lg border border-gray-200 hover:bg-gray-100 transition duration-300 -ml-4 z-10 hidden lg:block">
                                    <i class="fa-solid fa-chevron-left text-base text-gray-500"></i>
                                </button>
                                <button id="next-btn"
                                    class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-white p-2 rounded-full shadow-lg border border-gray-200 hover:bg-gray-100 transition duration-300 -mr-4 z-10 hidden lg:block">
                                    <i class="fa-solid fa-chevron-right text-base text-gray-500"></i>
                                </button>
                            </div>

                            <div id="testimonial-dots" class="flex justify-center mt-4 space-x-2"></div>
                        @else
                            <p class="text-gray-500 italic text-sm p-4 bg-white rounded-xl shadow-sm border">Tidak ada testimoni alumni untuk ditampilkan.</p>
                        @endif
                    </section>

                    {{-- Kepala Kompetensi --}}
                    <section class="fade-in-section">
                        <h2 class="flex items-center gap-x-2 text-xl font-semibold mb-6" style="color: {{ $amaliahDark }};">
                            <i class="fa-solid fa-user-tie text-lg text-gray-400"></i>
                            <span>Kepala Kompetensi</span>
                        </h2>
                        <div class="bg-white rounded-2xl p-6 flex flex-col sm:flex-row items-center text-center sm:text-left gap-6 border border-gray-200 shadow-sm">
                            @if($major->competency_head_photo)
                                <img src="{{ Storage::url($major->competency_head_photo) }}"
                                    alt="Foto {{ $major->competency_head }}"
                                    class="w-24 h-24 rounded-full object-cover shadow-lg border-4 border-white flex-shrink-0">
                            @endif
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">{{ $major->competency_head }}</h3>
                                <p class="text-base text-gray-500">Kepala Kompetensi Keahlian {{ $major->name }}</p>
                            </div>
                        </div>
                    </section>
                </div>

                {{-- Sidebar --}}
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-8 space-y-8">
                        <h3 class="text-2xl font-bold mb-4" style="color: {{ $amaliahDark }};">
                            Jelajahi Jurusan Lain
                        </h3>
                        <div class="space-y-6">
                            @forelse($randomOtherMajors as $otherMajor)
                                <div class="bg-white rounded-2xl shadow-md transition-all duration-300 group overflow-hidden flex flex-col hover:shadow-xl">
                                    <a href="{{ route('public.majors.show', $otherMajor) }}" class="block h-32">
                                        @if($otherMajor->image)
                                            <img src="{{ Storage::url($otherMajor->image) }}" alt="Gambar {{ $otherMajor->name }}"
                                                class="w-full h-full object-cover">
                                        @endif
                                    </a>
                                    <div class="p-5 relative flex flex-col flex-grow">
                                        <div class="absolute -top-10 left-4 bg-white p-2 rounded-xl shadow-lg">
                                            @if($otherMajor->logo)
                                                <img src="{{ Storage::url($otherMajor->logo) }}" alt="Logo {{ $otherMajor->abbreviation ?? $otherMajor->name }}"
                                                    class="h-10 w-10 object-contain">
                                            @else
                                                <div class="h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                                                    <i class="fa-solid fa-building"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="pt-6">
                                            <h4 class="text-base font-bold leading-tight" style="color: {{ $amaliahDark }};">
                                                {{ $otherMajor->abbreviation ?? $otherMajor->name }}
                                            </h4>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $otherMajor->name }}</p>
                                        </div>
                                        <a href="{{ route('public.majors.show', $otherMajor) }}"
                                            class="mt-4 inline-flex items-center gap-2 justify-center bg-[#282829] hover:bg-[#63cd00] text-white text-xs font-semibold px-4 py-2 rounded-full transition-colors duration-300 w-full">
                                            Selengkapnya <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 italic text-sm p-4 bg-white rounded-xl shadow-sm border">Tidak ada jurusan lain untuk ditampilkan.</p>
                            @endforelse
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <a href="{{ route('public.majors.index') }}"
                                class="w-full block text-center py-3 rounded-full text-sm font-semibold text-white transition-colors duration-200 hover:bg-[#63cd00]"
                                style="background-color: {{ $amaliahDark }};">
                                Lihat Semua Daftar Jurusan
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const carousel = document.querySelector('#testimonial-carousel .flex');
            if (!carousel) return;

            const items = carousel.children;
            const totalItems = items.length;
            let currentIndex = 0;
            const duration = 5000;

            if (totalItems <= 1) {
                document.getElementById('prev-btn')?.remove();
                document.getElementById('next-btn')?.remove();
                document.getElementById('testimonial-dots')?.remove();
                return;
            }

            function updateCarousel() {
                carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
                updateDots();
            }

            let autoSlide = setInterval(() => {
                currentIndex = (currentIndex + 1) % totalItems;
                updateCarousel();
            }, duration);

            function resetAutoSlide() {
                clearInterval(autoSlide);
                autoSlide = setInterval(() => {
                    currentIndex = (currentIndex + 1) % totalItems;
                    updateCarousel();
                }, duration);
            }

            document.getElementById('prev-btn')?.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + totalItems) % totalItems;
                updateCarousel();
                resetAutoSlide();
            });

            document.getElementById('next-btn')?.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % totalItems;
                updateCarousel();
                resetAutoSlide();
            });

            const dotsContainer = document.getElementById('testimonial-dots');
            function createDots() {
                for (let i = 0; i < totalItems; i++) {
                    const dot = document.createElement('span');
                    dot.classList.add('w-2', 'h-2', 'rounded-full', 'bg-gray-300', 'cursor-pointer', 'transition-colors');
                    dot.dataset.index = i;
                    dot.addEventListener('click', () => {
                        currentIndex = i;
                        updateCarousel();
                        resetAutoSlide();
                    });
                    dotsContainer.appendChild(dot);
                }
            }
            function updateDots() {
                Array.from(dotsContainer.children).forEach((dot, index) => {
                    dot.classList.remove('bg-gray-700');
                    if (index === currentIndex) dot.classList.add('bg-gray-700');
                });
            }

            createDots();
            updateDots();
        });
    </script>

@endsection