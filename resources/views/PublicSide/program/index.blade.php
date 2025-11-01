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
        <div id="loader-wrapper">
            <div class="loader-content">
                <div class="loader-spinner"></div>
                <p class="loader-message">Tenang, pengalaman terbaik sedang kami siapkan untuk Anda.</p>
            </div>
        </div>  
        <section class="relative bg-white overflow-hidden mt-[-30px] fade-in-section">
            {{-- Elemen Dekoratif: Gradasi di Latar Belakang --}}
            <div class="absolute top-0 left-0 -translate-x-1/2 -translate-y-1/2">
                <div
                    class="w-[40rem] h-[40rem] bg-gradient-to-tr from-green-100 to-transparent rounded-full opacity-50 blur-3xl">
                </div>
            </div>
            <div class="absolute bottom-0 right-0 translate-x-1/2 translate-y-1/2">
                <div
                    class="w-[30rem] h-[30rem] bg-gradient-to-tl from-green-100 to-transparent rounded-full opacity-40 blur-3xl">
                </div>
            </div>

            <div class="container relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-20 sm:py-24 lg:py-32">
                <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-12 lg:gap-x-16">

                    {{-- Bagian Kiri: Teks dan Tombol --}}
                    <div class="text-center lg:text-left">
                        <h1
                            class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight tracking-tight">
                            Mulai Perjalananmu dengan Program
                            <span class="text-[#63cd00]">Unggulan Kami</span>
                        </h1>
                        <p class="mt-6 text-lg text-gray-600 max-w-xl mx-auto lg:mx-0">
                            Program pendidikan yang dirancang khusus, didukung oleh kurikulum inovatif dan fasilitas modern
                            untuk mengembangkan potensi terbaik siswa.
                        </p>

                        {{-- Tombol Aksi (CTA) --}}
                        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                            <a href="href=" {{ route('public.about.index') }}
                                class="inline-flex items-center justify-center px-7 py-3 border border-transparent text-base font-semibold rounded-lg shadow-lg text-white bg-[#63cd00] hover:bg-[#52a800] transition-all duration-300 transform hover:-translate-y-0.5">
                                About
                            </a>
                            <a href="https://wa.me/6285649011449"
                                class="inline-flex items-center justify-center px-7 py-3 border-2 border-gray-300 text-base font-semibold rounded-lg text-gray-700 bg-transparent hover:border-[#63cd00] hover:text-[#63cd00] transition-all duration-300">
                                Hubungi Kami
                            </a>
                        </div>
                    </div>

                    {{-- Bagian Kanan: Gambar --}}
                    <div class="relative flex justify-center lg:justify-end">
                        <div class="w-full max-w-md lg:max-w-none rounded-2xl p-2 bg-white/50 backdrop-blur-sm shadow-2xl">
                            <img class="w-full h-auto rounded-xl object-cover"
                                src="{{ asset('assets/image/DroneView.jpg') }}" alt="Siswa belajar dengan antusias" />
                        </div>
                        {{-- Elemen Dekoratif: Bentuk di pojok gambar --}}
                        <div
                            class="absolute -bottom-6 -left-6 w-24 h-24 bg-green-200 rounded-full opacity-60 blur-lg -z-10">
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="bg-white mt-[-50px] fade-in-section">
            <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">

                <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
                    <h2 class="text-2xl font-bold md:text-4xl md:leading-tight">Pendekatan Program Kami</h2>
                    <p class="mt-1 text-gray-600">Kami tidak hanya mengajar, kami membentuk profesional masa depan melalui
                        program pendidikan yang terintegrasi.</p>
                </div>

                <!-- Grid -->
                <div class="grid lg:grid-cols-2 lg:gap-y-16 gap-10">

                    {{-- Loop untuk setiap program dari database --}}
                    @forelse ($programs as $program)
                        <!-- Card -->
                        <a class="group block rounded-xl overflow-hidden focus:outline-hidden fade-in-section"
                            href="{{ route('public.program.show', $program->id) }}"> {{-- Ganti dengan route Anda --}}
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-5">
                                <div class="shrink-0 relative rounded-xl overflow-hidden w-full sm:w-56 h-44">
                                    <img class="group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out size-full absolute top-0 start-0 object-cover rounded-xl"
                                        src="{{ asset('storage/' . $program->image) }}" {{-- Mengambil gambar dari DB --}}
                                        alt="{{ $program->name }}">
                                </div>

                                <div class="grow">
                                    <h3 class="text-xl font-semibold text-gray-800 group-hover:text-gray-600">
                                        {{ $program->name }} {{-- Mengambil judul dari DB --}}
                                    </h3>
                                    <p class="mt-3 text-gray-600 line-clamp-3"> {{-- line-clamp untuk meratakan panjang teks
                                        --}}
                                        {{ $program->description }} {{-- Mengambil deskripsi dari DB --}}
                                    </p>
                                    <p
                                        class="mt-4 inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 group-hover:underline group-focus:underline font-medium">
                                        Lihat Detail
                                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m9 18 6-6-6-6" />
                                        </svg>
                                    </p>
                                </div>
                            </div>
                        </a>
                        <!-- End Card -->
                    @empty
                        {{-- Pesan jika tidak ada program --}}
                        <div class="lg:col-span-2 text-center py-10">
                            <p class="text-gray-500">Saat ini belum ada program yang tersedia.</p>
                        </div>
                    @endforelse

                </div>
                <!-- End Grid -->
            </div>
        </section>

    </body>

    </html>

@endsection