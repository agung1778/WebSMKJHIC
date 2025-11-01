@extends('layouts.public-app')

@section('title', 'Halaman Utama')

@section('content')
    {{-- Membungkus seluruh konten dengan tag HTML/Head/Body --}}
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

        @media (max-width: 768px) {
            .custom-none {
                border-radius: 0px:
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    <body class="font-['Poppins'] bg-gray-100">
        @php
            // Asumsi variabel warna sudah didefinisikan
            $amaliahGreen = '#63cd00';
            $amaliahDark = '#282829';

            $schoolFocus = [
                [
                    'title' => 'SMK Amaliah 1 (IT & Kreatif)',
                    'icon' => 'fa-code',
                    'text' => 'Berfokus pada dunia IT (Teknologi Informasi), mencakup keahlian di bidang pemrograman, jaringan, dan multimedia.(PPLG, TJKT, ANIMASI, DKV)',
                    'class' => 'md:border-r md:border-gray-700 md:pr-8', // Garis pemisah di KANAN Amaliah 1
                ],
                [
                    'title' => 'SMK Amaliah 2 (Bisnis & Pariwisata)',
                    'icon' => 'fa-briefcase',
                    'text' => 'Berfokus pada manajemen bisnis, pemasaran digital, dan pariwisata, menyiapkan lulusan untuk sektor jasa dan industri kreatif. (MP, AK, LPS, BR, DPB)',
                    'class' => 'md:pl-8', // Padding di KIRI Amaliah 2
                ],
            ];
        @endphp

        <section class=" bg-gray-50">
            <div class="max-w-screen">

                {{-- BAGIAN 1: HERO IMAGE --}}
                <div class="relative h-[300px]  overflow-hidden shadow-2xl flex items-center p-4 sm:p-8">

                    {{-- Latar Belakang: SLIDER GAMBAR DINAMIS --}}
                    <div class="absolute inset-0 z-0">
                        @if($hasImages && $majorsImages->count() > 0)
                            <div x-data="{ activeSlide: 1, totalSlides: {{ $majorsImages->count() }} }"
                                x-init="setInterval(() => { activeSlide = activeSlide === totalSlides ? 1 : activeSlide + 1 }, 5000)">
                                @foreach($majorsImages as $image)
                                    <div x-show="activeSlide === {{ $loop->iteration }}"
                                        x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-1000"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        class="absolute inset-0">
                                        <img src="{{ asset('storage/' . $image->path) }}"
                                            alt="{{ $image->description ?? $image->filename }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="w-full h-full bg-black flex items-center justify-center">
                                <p class="text-gray-600">Gambar 'MajorsImage' tidak ditemukan</p>
                            </div>
                        @endif
                    </div>



                </div>
                {{-- BAGIAN 2: CARD FOKUS SEKOLAH --}}
                <div class="relative z-20 -mt-16 max-w-5xl mx-auto fade-in-section">
                    <div class="shadow-xl p-8 custom-none bg-[{{ $amaliahDark }}] rounded-none md:rounded-2xl">

                        {{-- Grid 2 kolom di layar sedang ke atas --}}
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-8 text-white divide-y divide-gray-700 md:divide-y-0">
                            @foreach ($schoolFocus as $item)
                                <div class="{{ $item['class'] }} py-4 md:py-0">
                                    <div class="flex items-start gap-3 mb-2">
                                        {{-- Ikon --}}
                                        <i class="fas {{ $item['icon'] }} text-xl flex-shrink-0 mt-1"
                                            style="color: {{ $amaliahGreen }};"></i>
                                        {{-- Judul --}}
                                        <h3 class="text-lg font-semibold leading-tight">{{ $item['title'] }}</h3>
                                    </div>
                                    {{-- Deskripsi --}}
                                    <p class="mt-3 text-sm text-gray-300 leading-relaxed">{{ $item['text'] }}</p>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>


            </div>
        </section>


        <section class="bg-white py-16 sm:py-24 fade-in-section">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- Header Section --}}
                <div class="text-center">
                    <h2 class="text-3xl md:text-4xl font-bold" style="color: {{ $amaliahDark }};">
                        Jurusan
                    </h2>
                    <div class="flex items-center justify-center gap-x-2 mx-auto mt-4">
                        <div class="w-20 h-1 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                        <div class="w-8 h-1 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                        <div class="w-4 h-1 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                    </div>

                    {{-- Bar Logo Jurusan --}}
                    <div class="mt-12 flex flex-wrap justify-center items-center gap-x-10 sm:gap-x-12 gap-y-6">
                        <img src="{{ asset('assets/logo/Logo_Jurusan/pplg.webp') }}" alt="Logo PPLG"
                            class="h-12 lg:h-14 object-contain transition-transform duration-300 hover:scale-110">
                        <img src="{{ asset('assets/logo/Logo_Jurusan/tjkt.webp') }}" alt="Logo TJKT"
                            class="h-12 lg:h-14 object-contain transition-transform duration-300 hover:scale-110">
                        <img src="{{ asset('assets/logo/Logo_Jurusan/animasi.webp') }}" alt="Logo Animasi"
                            class="h-12 lg:h-14 object-contain transition-transform duration-300 hover:scale-110">
                        <img src="{{ asset('assets/logo/Logo_Jurusan/dkv.webp') }}" alt="Logo DKV"
                            class="h-12 lg:h-14 object-contain transition-transform duration-300 hover:scale-110">
                        <img src="{{ asset('assets/logo/Logo_Jurusan/mp.webp') }}" alt="Logo MP"
                            class="h-12 lg:h-14 object-contain transition-transform duration-300 hover:scale-110">
                        <img src="{{ asset('assets/logo/Logo_Jurusan/ak.webp') }}" alt="Logo AK"
                            class="h-12 lg:h-14 object-contain transition-transform duration-300 hover:scale-110">
                        <img src="{{ asset('assets/logo/Logo_Jurusan/lps.webp') }}" alt="Logo LPS"
                            class="h-12 lg:h-14 object-contain transition-transform duration-300 hover:scale-110">
                        <img src="{{ asset('assets/logo/Logo_Jurusan/br.webp') }}" alt="Logo BR"
                            class="h-12 lg:h-14 object-contain transition-transform duration-300 hover:scale-110">
                        <img src="{{ asset('assets/logo/Logo_Jurusan/dpb.webp') }}" alt="Logo DPB"
                            class="h-12 lg:h-14 object-contain transition-transform duration-300 hover:scale-110">
                        {{-- Anda bisa menambahkan logo lainnya di sini --}}
                    </div>
                </div>

                <div x-data="{ activeTab: 'all' }">

                    {{-- Bagian Header: Judul dan Tombol Tab (Sesuai Referensi) --}}
                    <div class="text-center mb-12">
                        <div class="flex justify-center items-center space-x-2 mt-8">

                            {{-- Tombol "Semua" (Ditambahkan kembali) --}}
                            <button @click="activeTab = 'all'" :class="{
                                        'bg-[#63cd00] text-white shadow-lg': activeTab === 'all',
                                        'bg-white text-[#282829] hover:bg-gray-200': activeTab !== 'all'
                                    }" class="px-5 py-2 text-sm font-semibold rounded-full transition-all duration-300">
                                Semua
                            </button>

                            {{-- Tombol "SMK Amaliah 1" --}}
                            <button @click="activeTab = 'SMK Amaliah 1'" :class="{
                                        'bg-[#63cd00] text-white shadow-lg': activeTab === 'SMK Amaliah 1',
                                        'bg-white text-[#282829] hover:bg-gray-200': activeTab !== 'SMK Amaliah 1'
                                    }" class="px-5 py-2 text-sm font-semibold rounded-full transition-all duration-300">
                                SMK Amaliah 1
                            </button>

                            {{-- Tombol "SMK Amaliah 2" --}}
                            <button @click="activeTab = 'SMK Amaliah 2'" :class="{
                                        'bg-[#63cd00] text-white shadow-lg': activeTab === 'SMK Amaliah 2',
                                        'bg-white text-[#282829] hover:bg-gray-200': activeTab !== 'SMK Amaliah 2'
                                    }" class="px-5 py-2 text-sm font-semibold rounded-full transition-all duration-300">
                                SMK Amaliah 2
                            </button>

                        </div>
                    </div>


                    {{-- 2. Grid Kartu Jurusan --}}
                    <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-8 fade-in-section">
                        @forelse ($majors as $major)
                            {{-- Tambahkan x-show dan x-transition di sini --}}
                            <div x-show="activeTab === 'all' || activeTab === '{{ $major->tag }}'"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="bg-white rounded-2xl shadow-lg transition-all duration-300 group overflow-hidden flex flex-col">

                                {{-- BAGIAN GAMBAR UTAMA --}}
                                <a href="{{ route('public.majors.show', $major) }}" class="block h-56 relative overflow-hidden">
                                    <img src="{{ asset('storage/' . $major->image) }}" alt="Gambar {{ $major->name }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.05]">
                                </a>

                                {{-- KONTEN TEKS KARTU --}}
                                <div class="p-6 relative flex flex-col flex-grow">

                                    {{-- LOGO JURUSAN --}}
                                    <div
                                        class="absolute -top-12 left-6 bg-white p-3 rounded-2xl shadow-xl border border-gray-100 transition-shadow duration-300">
                                        @if ($major->logo)
                                            <img src="{{ asset('storage/' . $major->logo) }}"
                                                alt="Logo {{ $major->abbreviation ?? $major->name }}"
                                                class="h-16 w-16 object-contain">
                                        @else
                                            <div
                                                class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 text-xs">
                                                Logo
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Header Teks --}}
                                    <div class="pt-8">
                                        <h3 class="text-2xl font-semibold leading-tight" style="color: {{ $amaliahDark }};">
                                            {{ $major->abbreviation ?? $major->name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-0.5">{{ $major->name }}</p>
                                    </div>

                                    {{-- Body Kartu (Keunggulan menggunakan ADVANTAGE) --}}
                                    <div class="mt-4 flex-grow border-t border-gray-100 pt-4">
                                        <h4 class="font-medium text-gray-700">Skills</h4>
                                        <ul class="mt-2 text-sm text-gray-600 leading-relaxed space-y-2">
                                            @php
                                                $advantages = $major->advantage ? array_filter(explode("\n", $major->advantage)) : [];
                                                $limitedAdvantages = array_slice($advantages, 0, 3);
                                            @endphp
                                            @forelse ($limitedAdvantages as $advantage)
                                                <li class="flex items-start">
                                                    <i class="fas fa-check-circle text-xs mt-1 mr-2 flex-shrink-0"
                                                        style="color: {{ $amaliahDark }};"></i>
                                                    <span>{{ trim($advantage) }}</span>
                                                </li>
                                            @empty
                                                <li class="text-gray-400 italic">Keunggulan belum diinput.</li>
                                            @endforelse
                                        </ul>
                                    </div>

                                    {{-- Footer Kartu (Tombol) --}}
                                    <div class="mt-6 flex items-center gap-4">
                                        <a href="{{ route('public.majors.show', $major) }}"
                                            class="inline-flex items-center text-white px-5 py-2.5 rounded-lg text-sm font-semibold relative overflow-hidden group/button transition-opacity duration-300 hover:opacity-90"
                                            style="background-color: {{ $amaliahDark }};">
                                            <span>Selengkapnya</span>
                                            <div
                                                class="ml-2 bg-white rounded-full p-1 flex items-center justify-center relative z-10 transition-transform duration-300 group-hover/button:translate-x-1">
                                                <i class="fas fa-arrow-right text-xs" style="color: {{ $amaliahDark }};"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="md:col-span-2 text-center py-12">
                                <p class="text-gray-500">Belum ada jurusan yang ditambahkan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
        </section>

        @php
            // Definisikan warna utama
            $amaliahGreen = '#63cd00';
            $amaliahDark = '#282829';
        @endphp

        <section class="bg-white py-16 sm:py-24 space-y-20 fade-in-section">

            {{-- BAGIAN 1: SLIDER (SWIPE) --}}
            <div>
                {{-- Header untuk slider diletakkan di dalam container agar rapi --}}
                {{-- Header Section (Tidak ada perubahan) --}}
                <div class="text-center">
                    <h2 class="text-3xl md:text-4xl font-bold" style="color: {{ $amaliahDark }};">
                        Our Latest Instagram Post
                    </h2>
                    <div class="flex items-center justify-center gap-x-2 mx-auto mt-4">
                        <div class="w-20 h-1 rounded-full" style="background-color: {{ $amaliahDark }};"></div>
                        <div class="w-8 h-1 rounded-full" style="background-color: {{ $amaliahDark }};"></div>
                        <div class="w-4 h-1 rounded-full" style="background-color: {{ $amaliahDark }};"></div>
                    </div>
                </div>


            </div>

            {{-- BAGIAN 2: GRID --}}
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8  ">


                {{-- Wadah untuk grid Curator.io --}}
                <div id="curator-feed-grid-layout">

                    <!-- Place <div> tag where you want the feed to appear -->
                    <div id="curator-feed-default-feed-layout"><a href="https://curator.io" target="_blank"
                            class="crt-logo crt-tag">Powered by Curator.io</a></div>

                    <!-- The Javascript can be moved to the end of the html page before the </body> tag -->
                    <script type="text/javascript">
                        /* curator-feed-default-feed-layout */
                        (function () {
                            var i, e, d = document, s = "script"; i = d.createElement("script"); i.async = 1; i.charset = "UTF-8";
                            i.src = "https://cdn.curator.io/published/9b122a7e-d39e-40c4-abc3-8ab6bc446899.js";
                            e = d.getElementsByTagName(s)[0]; e.parentNode.insertBefore(i, e);
                        })();
                    </script>
                </div>
            </div>
        </section>

        @php
            // Definisikan warna utama
            $amaliahGreen = '#63cd00';
            $amaliahDark = '#282829';

            // Definisikan informasi kontak
            $alamat = 'Jl. Raya Jl. Tol Jagorawi No.1, Ciawi, Kec. Ciawi, Kabupaten Bogor, Jawa Barat 16720';
            $email = 'smkamaliahciawi@gmail.com';
            $phone = '123-456-7890';
        @endphp

        <section class="bg-gray-50 py-16 sm:py-24 fade-in-section">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- Tombol Virtual Tour di Atas --}}
                <div class="text-center mb-10">
                    <a href="https://yourdisc710.itch.io/amaliah-tour"
                        class="inline-flex items-center bg-white border border-gray-300 rounded-full px-8 py-4 text-base font-semibold shadow-md hover:shadow-lg hover:border-gray-400 transition-all duration-300 group">
                        <span class="text-gray-800">Mau Lihat SMK Amaliah?</span>
                        <span class="ml-2 font-bold" style="color: {{ $amaliahGreen }};">Masuk Ke Virtual Tour!</span>
                        <div
                            class="ml-4 bg-white rounded-full p-2 flex items-center justify-center border border-gray-300 group-hover:border-gray-400 transition-all">
                            <i class="fas fa-chevron-right text-sm" style="color: {{ $amaliahGreen }};"></i>
                        </div>
                    </a>
                </div>

                {{-- Container Utama untuk Peta dan Info --}}
                <div class="relative rounded-2xl overflow-hidden shadow-2xl">

                    {{-- KODE IFRAME GOOGLE MAPS --}}
                    {{-- Pastikan Anda mengganti src="..." dengan kode embed Anda --}}
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.945187355167!2d106.8462900750414!3d-6.653716393341009!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c8eec16c788f%3A0x4680dbde73e8b763!2sSMK%20Amaliah%201%20dan%202%20Ciawi!5e0!3m2!1sid!2sid!4v1759652507072!5m2!1sid!2sid"
                        width="1280" height="600" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                    {{-- KARTU INFORMASI DI ATAS PETA --}}

                </div>
            </div>
        </section>
    </body>

    </html>
@endsection