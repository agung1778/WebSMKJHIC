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

        // Cek Variabel 
        $hasImages = isset($mainImages) && $mainImages->isNotEmpty();
    @endphp

    <body>
        <section class="relative max-w-screen">
            {{-- Slider Gambar Dinamis --}}
            @if($hasImages)
                <div x-data="{ activeSlide: 1, totalSlides: {{ $mainImages->count() }} }"
                    x-init="setInterval(() => { activeSlide = activeSlide % totalSlides + 1 }, 5000)">
                    <div class="relative w-full h-[300px] overflow-hidden">
                        @foreach($mainImages as $image)
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
                                    <a href="{{ route('public.teachers.index') }}"
                                        class="ml-2 font-medium text-white hover:text-white md:ml-3 transition-colors">Teachers
                                        & Staff</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <section class="bg-white py-16 sm:py-24">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- KEPALA BAGIAN (JUDUL DI KIRI, FILTER/TAB DI KANAN) --}}
                <div class="flex flex-col md:flex-row justify-between md:items-end gap-8 mb-12">

                    {{-- Kolom Kiri: Judul dan Deskripsi --}}
                    <div class="md:w-1/2 lg:w-2/3">
                        <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 tracking-tight">
                            Tenaga Pendidik Kami
                        </h2>
                        <p class="mt-4 text-lg text-gray-600">
                            Kenali para pendidik profesional dan berdedikasi yang menjadi pilar pendidikan di sekolah kami.
                        </p>
                    </div>

                    {{-- Kolom Kanan: Tombol Filter (Adaptasi dari Tab) --}}
                    <div class="flex-shrink-0">
                        <div id="tabs-container" class="flex flex-wrap items-center justify-start md:justify-end gap-3">
                            <button data-tab="semua"
                                class="tab-button px-4 py-2 text-sm font-semibold rounded-lg transition-colors duration-200">
                                Semua
                            </button>
                            <button data-tab="amaliah1"
                                class="tab-button px-4 py-2 text-sm font-semibold rounded-lg transition-colors duration-200">
                                Amaliah 1
                            </button>
                            <button data-tab="amaliah2"
                                class="tab-button px-4 py-2 text-sm font-semibold rounded-lg transition-colors duration-200">
                                Amaliah 2
                            </button>
                        </div>
                    </div>
                </div>

                {{-- KONTEN TAB (GRID DAFTAR GURU) --}}
                @php
                    // Mengubah koleksi yang dikelompokkan menjadi satu daftar flat untuk perulangan yang lebih sederhana
                    $allTeachers = isset($groupedTeachers) ? $groupedTeachers->flatten() : collect();
                @endphp

                {{-- Konten untuk Amaliah 1 --}}
                <div id="semua" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        @forelse ($allTeachers as $teacher)
                            <div
                                class="group bg-white border border-gray-200 rounded-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                {{-- GAMBAR GURU --}}
                                <div class="relative h-64 w-full">
                                    <img src="{{ $teacher->photo ? asset('storage/' . $teacher->photo) : 'https://placehold.co/400x500/e2e8f0/64748b?text=' . urlencode(substr($teacher->name, 0, 1)) }}"
                                        alt="Foto {{ $teacher->name }}" class="w-full h-full object-cover object-top"
                                        onerror="this.onerror=null;this.src='https://placehold.co/400x500/e2e8f0/64748b?text=Error';">
                                </div>

                                {{-- KONTEN CARD --}}
                                <div class="p-5 flex flex-col flex-grow">
                                    {{-- POSISI & IKON --}}
                                    <div
                                        class="flex items-center text-xs font-semibold text-[#63cd00] mb-2 uppercase tracking-wider">
                                        <i class="fas fa-chalkboard-teacher mr-2 w-4 text-center"></i>
                                        <span>{{ $teacher->position }}</span>
                                    </div>

                                    {{-- NAMA GURU --}}
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 leading-tight">
                                        {{ $teacher->name }}
                                    </h3>

                                    {{-- MATA PELAJARAN --}}
                                    <p class="text-gray-600 text-sm flex-grow">
                                        {{ $teacher->subject }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full border-2 border-dashed border-gray-300 rounded-lg p-12 text-center">
                                <p class="text-gray-500 font-medium">Data guru untuk tidak ditemukan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Konten untuk Amaliah 1 --}}
                <div id="amaliah1" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        @forelse ($allTeachers->where('school', 'Amaliah 1') as $teacher)
                            <div
                                class="group bg-white border border-gray-200 rounded-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                {{-- GAMBAR GURU --}}
                                <div class="relative h-64 w-full">
                                    <img src="{{ $teacher->photo ? asset('storage/' . $teacher->photo) : 'https://placehold.co/400x500/e2e8f0/64748b?text=' . urlencode(substr($teacher->name, 0, 1)) }}"
                                        alt="Foto {{ $teacher->name }}" class="w-full h-full object-cover object-top"
                                        onerror="this.onerror=null;this.src='https://placehold.co/400x500/e2e8f0/64748b?text=Error';">
                                </div>

                                {{-- KONTEN CARD --}}
                                <div class="p-5 flex flex-col flex-grow">
                                    {{-- POSISI & IKON --}}
                                    <div
                                        class="flex items-center text-xs font-semibold text-[#63cd00] mb-2 uppercase tracking-wider">
                                        <i class="fas fa-chalkboard-teacher mr-2 w-4 text-center"></i>
                                        <span>{{ $teacher->position }}</span>
                                    </div>

                                    {{-- NAMA GURU --}}
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 leading-tight">
                                        {{ $teacher->name }}
                                    </h3>

                                    {{-- MATA PELAJARAN --}}
                                    <p class="text-gray-600 text-sm flex-grow">
                                        {{ $teacher->subject }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full border-2 border-dashed border-gray-300 rounded-lg p-12 text-center">
                                <p class="text-gray-500 font-medium">Data guru untuk Amaliah 1 tidak ditemukan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Konten untuk Amaliah 2 --}}
                <div id="amaliah2" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        @forelse ($allTeachers->where('school', 'Amaliah 2') as $teacher)
                            <div
                                class="group bg-white border border-gray-200 rounded-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                {{-- GAMBAR GURU --}}
                                <div class="relative h-64 w-full">
                                    <img src="{{ $teacher->photo ? asset('storage/' . $teacher->photo) : 'https://placehold.co/400x500/e2e8f0/64748b?text=' . urlencode(substr($teacher->name, 0, 1)) }}"
                                        alt="Foto {{ $teacher->name }}" class="w-full h-full object-cover object-top"
                                        onerror="this.onerror=null;this.src='https://placehold.co/400x500/e2e8f0/64748b?text=Error';">
                                </div>

                                {{-- KONTEN CARD --}}
                                <div class="p-5 flex flex-col flex-grow">
                                    {{-- POSISI & IKON --}}
                                    <div
                                        class="flex items-center text-xs font-semibold text-[#63cd00] mb-2 uppercase tracking-wider">
                                        <i class="fas fa-chalkboard-teacher mr-2 w-4 text-center"></i>
                                        <span>{{ $teacher->position }}</span>
                                    </div>

                                    {{-- NAMA GURU --}}
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 leading-tight">
                                        {{ $teacher->name }}
                                    </h3>

                                    {{-- MATA PELAJARAN --}}
                                    <p class="text-gray-600 text-sm flex-grow">
                                        {{ $teacher->subject }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full border-2 border-dashed border-gray-300 rounded-lg p-12 text-center">
                                <p class="text-gray-500 font-medium">Data guru untuk Amaliah 2 tidak ditemukan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const tabsContainer = document.getElementById('tabs-container');
                    const tabButtons = tabsContainer.querySelectorAll('.tab-button');
                    const tabContents = document.querySelectorAll('.tab-content');

                    // Warna dari referensi desain Anda
                    const activeClasses = ['bg-[#59E300]', 'text-white', 'shadow-sm'];
                    const inactiveClasses = ['bg-gray-100', 'text-gray-700', 'hover:bg-gray-200'];

                    function switchTab(tabName) {
                        // Sembunyikan semua konten
                        tabContents.forEach(content => {
                            content.classList.add('hidden');
                        });

                        // Atur style tombol
                        tabButtons.forEach(button => {
                            if (button.dataset.tab === tabName) {
                                button.classList.add(...activeClasses);
                                button.classList.remove(...inactiveClasses);
                            } else {
                                button.classList.add(...inactiveClasses);
                                button.classList.remove(...activeClasses);
                            }
                        });

                        // Tampilkan konten yang dipilih
                        const activeContent = document.getElementById(tabName);
                        if (activeContent) {
                            activeContent.classList.remove('hidden');
                        }
                    }

                    // Tambahkan event listener ke setiap tombol
                    tabButtons.forEach(button => {
                        button.addEventListener('click', () => {
                            switchTab(button.dataset.tab);
                        });
                    });

                    // Atur tab default saat halaman dimuat
                    switchTab('semua');
                });
            </script>
        </section>
    </body>

    </html>
@endsection