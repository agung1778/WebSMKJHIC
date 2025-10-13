@extends('layouts.public-app')

@section('content')

    <!DOCTYPE html>
    <html lang="en" class="dark">

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
                                    <a href="{{ route('public.help.faq') }}"
                                        class="ml-2 font-medium text-white hover:text-white md:ml-3 transition-colors">FAQs</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="bg-gray-50 py-12 sm:py-16 lg:py-20">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        Frequently Asked Questions (FAQ)
                    </h2>
                    <p class="mt-4 text-lg leading-8 text-gray-600">
                        Temukan jawaban untuk pertanyaan paling umum mengenai pendaftaran, akademik, dan kehidupan di SMK
                        Amaliah.
                    </p>
                </div>

                <div x-data="{ open: null }" class="space-y-4">

                    <h3 class="text-xl font-semibold text-gray-800 pt-6 border-t border-gray-200">Seputar Sekolah</h3>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 1 ? null : 1"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apa saja jurusan yang tersedia di SMK Amaliah?</span>
                            <svg :class="{'rotate-45': open === 1}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 1" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>SMK Amaliah memiliki beberapa program keahlian unggulan yang dirancang sesuai kebutuhan
                                industri saat ini, di antaranya:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1 pl-2">
                                <li><strong>Teknik Komputer dan Jaringan (TKJ):</strong> Fokus pada infrastruktur jaringan,
                                    keamanan siber, dan administrasi server.</li>
                                <li><strong>Rekayasa Perangkat Lunak (RPL):</strong> Mempelajari pengembangan aplikasi web,
                                    mobile, dan desktop.</li>
                                <li><strong>Akuntansi dan Keuangan Lembaga (AKL):</strong> Mendalami siklus akuntansi,
                                    perpajakan, dan manajemen keuangan.</li>
                                <li><strong>Multimedia / Desain Komunikasi Visual (DKV):</strong> Kreativitas dalam desain
                                    grafis, videografi, dan animasi.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 2 ? null : 2"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apa keunggulan utama SMK Amaliah?</span>
                            <svg :class="{'rotate-45': open === 2}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 2" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Keunggulan kami terletak pada <strong>kurikulum berbasis industri</strong>, <strong>fasilitas
                                    laboratorium modern</strong>, program <strong>magang (PKL) di perusahaan
                                    ternama</strong>, serta <strong>pembinaan karakter dan kewirausahaan</strong> untuk
                                memastikan lulusan tidak hanya terampil secara teknis, tetapi juga siap kerja dan berakhlak
                                mulia.</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-800 pt-6 border-t border-gray-200">Pendaftaran Siswa Baru
                    </h3>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 3 ? null : 3"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Bagaimana alur pendaftaran di SMK Amaliah?</span>
                            <svg :class="{'rotate-45': open === 3}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 3" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Alur pendaftaran kami sederhanakan untuk kemudahan Anda:</p>
                            <ol class="list-decimal list-inside mt-2 space-y-1 pl-2">
                                <li><strong>Pengisian Formulir:</strong> Bisa dilakukan secara online melalui website resmi
                                    kami atau datang langsung ke sekolah.</li>
                                <li><strong>Pengumpulan Berkas:</strong> Menyerahkan dokumen persyaratan seperti fotokopi
                                    ijazah, SKL, Kartu Keluarga, dan pas foto.</li>
                                <li><strong>Tes Seleksi:</strong> Mengikuti tes potensi akademik dan wawancara minat bakat.
                                </li>
                                <li><strong>Pengumuman Hasil:</strong> Hasil seleksi akan diumumkan melalui website dan
                                    papan pengumuman sekolah.</li>
                                <li><strong>Daftar Ulang:</strong> Melakukan registrasi ulang bagi calon siswa yang
                                    dinyatakan lulus.</li>
                            </ol>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @ PERTANYAAN BARU MENGENAI BEASISWAg"
                            class="w-6 h-6 transform transition-transform duration-300" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 5" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Tentu. Kami berkomitmen untuk memberikan akses pendidikan yang merata. SMK Amaliah
                                menyediakan beberapa jalur beasiswa, di antaranya:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1 pl-2">
                                <li><strong>Beasiswa Prestasi:</strong> Untuk siswa dengan pencapaian akademik atau
                                    non-akademik yang luar biasa.</li>
                                <li><strong>Beasiswa Yatim:</strong> Program keringanan biaya khusus bagi siswa yatim piatu.
                                </li>
                                <li><strong>Beasiswa Putra/Putri Guru:</strong> Sebagai bentuk apresiasi kami terhadap
                                    dedikasi para pendidik.</li>
                            </ul>
                            <p class="mt-3">Syarat dan ketentuan berlaku untuk setiap program. Silakan hubungi tim PPDB
                                untuk informasi lebih detail.</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-800 pt-6 border-t border-gray-200">Akademik & Prospek Karir
                    </h3>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 6 ? null : 6"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apakah lulusan SMK Amaliah dijamin dapat kerja?</span>
                            <svg :class="{'rotate-45': open === 6}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 6" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Kami tidak memberikan jaminan, namun kami berkomitmen kuat untuk menyalurkan lulusan terbaik.
                                Melalui <strong>Bursa Kerja Khusus (BKK)</strong>, kami secara aktif menjalin kerja sama
                                dengan puluhan perusahaan mitra untuk rekrutmen. Data kami menunjukkan tingkat keterserapan
                                lulusan yang sangat tinggi, baik untuk bekerja, melanjutkan studi, maupun berwirausaha
                                (BWM).</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 7 ? null : 7"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apa saja kegiatan ekstrakurikuler yang tersedia?</span>
                            <svg :class="{'rotate-45': open === 7}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 7" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Kami percaya pengembangan diri di luar kelas sangat penting. Berbagai ekstrakurikuler
                                tersedia untuk menyalurkan bakat dan minat siswa, seperti:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1 pl-2">
                                <li><strong>Bidang Olahraga:</strong> Futsal, Basket, Voli, dan Pencak Silat.</li>
                                <li><strong>Bidang Seni & Kreativitas:</strong> Paskibra, Pramuka, dan Marawis.</li>
                                <li><strong>Bidang Akademik & Teknologi:</strong> English Club, IT Club, dan Kelompok Ilmiah
                                    Remaja.</li>
                                <li><strong>Bidang Kerohanian:</strong> Rohani Islam (Rohis).</li>
                            </ul>
                        </div>
                    </div>

                </div>

                <div class="text-center mt-12">
                    <p class="text-gray-600">Tidak menemukan jawaban yang Anda cari?</p>
                    <a href="#" class="mt-2 inline-block text-lg font-semibold text-cyan-600 hover:text-cyan-500">
                        Hubungi Tim Penerimaan Siswa Baru &rarr;
                    </a>
                </div>

            </div>
        </section>




    </body>

    </html>
@endsection