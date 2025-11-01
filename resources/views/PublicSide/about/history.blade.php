@extends('layouts.public-app')

@section('content')
    @php
        // Cek Variabel 
        $hasImages = isset($mainImages) && $mainImages->isNotEmpty();
    @endphp

    <div>
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
                                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                                    <a href="{{ route('public.about.index') }}"
                                        class="ml-2 font-medium text-gray-300 hover:text-white md:ml-3 transition-colors">About</a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    {{-- Mengganti warna chevron untuk konsistensi --}}
                                    <i class="fas fa-chevron-right text-white text-xs"></i>
                                    <span class="ml-2 font-medium md:ml-3 text-[#ffffff]">History</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="bg-white py-16 sm:py-24">
            <div class="container mx-auto max-w-4xl px-6 lg:px-8">

                <div class="text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        Sejarah SMK Amaliah 1 & 2 Ciawi
                    </h2>
                    <p class="mt-4 text-lg leading-8 text-gray-600">
                        Perjalanan kami dalam membentuk generasi yang cerdas, berkarakter, dan siap menghadapi tantangan
                        masa depan.
                    </p>
                </div>

                <div class="my-12 border-t border-gray-200"></div>

                <div class="space-y-12">

                    <div class="relative flex items-start">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center">
                            <i class="fas fa-school text-3xl text-[#59E300]"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                Filosofi Pendidikan
                            </h3>
                            <p class="mt-2 text-base leading-7 text-gray-600">
                                SMK Amaliah lahir dari kesadaran bahwa sekolah adalah komunitas utuh yang berperan
                                menumbuhkan nilai-nilai luhur. Kami berfokus mencerdaskan bangsa dengan mengembangkan etika,
                                logika, dan praktika yang berakar pada budaya bangsa.
                            </p>
                        </div>
                    </div>

                    <div class="relative flex items-start">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center">
                            <i class="fas fa-landmark text-3xl text-[#59E300]"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                Fondasi & Pendirian
                            </h3>
                            <p class="mt-2 text-base leading-7 text-gray-600">
                                Berdiri resmi pada tahun 2008 di bawah naungan <b>YPSPIAI</b> dan pembinaan Universitas
                                Djuanda (UNIDA). Sejak awal, kami berkomitmen pada Kualitas, Profesionalitas, dan
                                Pelayanan Prima dalam pendidikan kejuruan.
                            </p>
                        </div>
                    </div>

                    <div class="relative flex items-start">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center">
                            <i class="fas fa-sitemap text-3xl text-[#59E300]"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                Perkembangan & Program Keahlian
                            </h3>
                            <p class="mt-2 text-base leading-7 text-gray-600">
                                Untuk menjawab kebutuhan industri, kami membuka 9 konsentrasi keahlian: TKJ, RPL, DKV,
                                Animasi, MP, Akuntansi, LPS, Desain Busana, dan Bisnis Retail.
                            </p>
                        </div>
                    </div>

                    <div class="relative flex items-start">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center">
                            <i class="fas fa-hands-holding-circle text-3xl text-[#59E300]"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                Komitmen & Resiliensi
                            </h3>
                            <p class="mt-2 text-base leading-7 text-gray-600">
                                Perjalanan kami menghadapi berbagai tantangan. Namun, berkat kerja sama solid seluruh warga
                                sekolah, kesabaran, dan keikhlasan, kami bersyukur dapat terus berkembang hingga saat ini.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <div class="bg-white py-16 sm:py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 tracking-tight">
                        Detail Sejarah
                    </h2>
                    <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-600">
                        Informasi mengenai sejarah perkembangan SMK Amaliah 1 & 2 dari masa ke masa.
                    </p>
                </div>

                @if ($historyContent)
                    <article class="prose prose-lg prose-gray max-w-screen">
                        {!! $historyContent->content !!}
                    </article>
                @else
                    <div class="text-center py-24 px-6 bg-gray-50 rounded-xl border border-gray-200">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Konten Belum Tersedia</h3>
                        <p class="mt-1 text-sm text-gray-500">Halaman ini sedang dalam pengembangan.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection