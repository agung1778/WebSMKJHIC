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
                            <span class="text-lg">Kapan SMK Amaliah 1 & 2 didirikan?</span>
                            <svg :class="{'rotate-45': open === 1}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 1" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>SMK Amaliah 1 & 2 Ciawi Bogor berdiri pada tahun 2008.SMK
                                Amaliah 1 didirikan berdasarkan SK Pendirian No. 421/104-Disdik tanggal 14 Mei 2008, dan SMK
                                Amaliah 2 berdasarkan SK No. 431/18-Dikmen tanggal 03 Mei 2008.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 2 ? null : 2"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Di bawah naungan yayasan apa SMK Amaliah beroperasi?</span>
                            <svg :class="{'rotate-45': open === 2}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 2" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>SMK Amaliah 1 & 2 berada di bawah naungan Yayasan Pusat Studi Pengembangan Islam
                                Amaliyah Indonesia (YPSPIAI).</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 3 ? null : 3"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apakah SMK Amaliah memiliki afiliasi dengan perguruan tinggi?</span>
                            <svg :class="{'rotate-45': open === 3}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 3" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Ya, SMK Amaliah 1 & 2 berada di bawah pembinaan Universitas Djuanda (UNIDA).
                                </p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 4 ? null : 4"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apa prinsip utama yang dipegang oleh SMK Amaliah?</span>
                            <svg :class="{'rotate-45': open === 4}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 4" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>SMK Amaliah 1 & 2 mengutamakan Kualitas, Profesionalitas, dan Pelayanan Prima
                                dalam penyelenggaraan pendidikannya.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 5 ? null : 5"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apa saja jurusan atau konsentrasi keahlian yang tersedia?</span>
                            <svg :class="{'rotate-45': open === 5}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 5" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>SMK Amaliah 1 & 2 membuka 9 (sembilan) Konsentrasi Keahlian, yaitu: 
                            </p>
                            <ul class="list-disc list-inside mt-2 space-y-1 pl-2">
                                <li>Teknik Komputer dan Jaringan (TKJ)</li>
                                <li>Animasi (AN)</li>
                                <li>Rekayasa Perangkat Lunak (RPL)</li>
                                <li>Desain dan Komunikasi Visual (DKV)</li>
                                <li>Manajemen Perkantoran (MP)</li>
                                <li>Akuntansi (AK)</li>
                                <li>Layanan Perbankan Syariah (LPS)</li>
                                <li>Desain dan Produksi Busana (DPB)</li>
                                <li>Bisnis Retail (BR)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 6 ? null : 6"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apa visi sekolah dalam mencerdaskan bangsa?</span>
                            <svg :class="{'rotate-45': open === 6}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 6" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Sekolah berperan untuk menumbuhkan, memotivasi, dan mengembangkan nilai-nilai
                                budaya yang mencakup etika, logika, estetika, dan praktika, untuk menciptakan manusia
                                Indonesia yang utuh dan berakar pada budaya bangsa.</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-800 pt-6 border-t border-gray-200">Pendaftaran Siswa Baru
                    </h3>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 7 ? null : 7"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Bagaimana alur pendaftaran di SMK Amaliah?</span>
                            <svg :class="{'rotate-45': open === 7}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 7" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
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
                        <button @click="open = open === 8 ? null : 8"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Kapan jadwal pendaftaran siswa baru dibuka?</span>
                            <svg :class="{'rotate-45': open === 8}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 8" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Jadwal pendaftaran siswa baru biasanya diumumkan beberapa bulan sebelum tahun ajaran baru
                                dimulai. Untuk informasi paling akurat dan terkini, silakan kunjungi website resmi kami atau
                                hubungi panitia PPDB sekolah.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 9 ? null : 9"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apa saja persyaratan dokumen yang harus disiapkan?</span>
                            <svg :class="{'rotate-45': open === 9}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 9" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Secara umum, dokumen yang dibutuhkan meliputi:
                            <ul class="list-disc list-inside mt-2 space-y-1 pl-2">
                                <li>Formulir pendaftaran yang telah diisi.</li>
                                <li>Fotokopi Ijazah/Surat Keterangan Lulus (SKL) SMP/sederajat.</li>
                                <li>Fotokopi Kartu Keluarga (KK).</li>
                                <li>Fotokopi Akta Kelahiran.</li>
                                <li>Pas foto terbaru ukuran 3x4 dan 2x3.</li>
                                <li>Fotokopi KTP orang tua/wali.</li>
                            </ul>
                            Harap verifikasi daftar lengkapnya di brosur atau website resmi saat pendaftaran dibuka.
                            </p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 10 ? null : 10"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apakah ada tes masuk untuk calon siswa?</span>
                            <svg :class="{'rotate-45': open === 10}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 10" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Ya, sebagai bagian dari proses seleksi, kami mengadakan tes potensi akademik (TPA) dan
                                wawancara untuk mengetahui minat dan bakat calon siswa, sehingga dapat membantu mengarahkan
                                pada jurusan yang paling sesuai.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 11 ? null : 11"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apakah SMK Amaliah menyediakan program beasiswa?</span>
                            <svg :class="{'rotate-45': open === 11}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 11" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Tentu. Kami berkomitmen memberikan akses pendidikan yang merata. SMK Amaliah menyediakan
                                beberapa jalur beasiswa, di antaranya:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1 pl-2">
                                <li><strong>Beasiswa Prestasi:</strong> Untuk siswa dengan pencapaian akademik atau
                                    non-akademik yang luar biasa.</li>
                                <li><strong>Beasiswa Kurang Mampu:</strong> Program bantuan biaya pendidikan bagi siswa dari
                                    keluarga prasejahtera dengan bukti yang valid.</li>
                                <li><strong>Beasiswa Yatim:</strong> Program keringanan biaya khusus bagi siswa yatim piatu.
                                </li>
                                <li><strong>Beasiswa Putra/Putri Guru:</strong> Sebagai bentuk apresiasi kami terhadap
                                    dedikasi para pendidik.</li>
                            </ul>
                            <p class="mt-3">Syarat dan ketentuan berlaku untuk setiap program. Silakan hubungi tim PPDB
                                untuk informasi lebih detail.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 12 ? null : 12"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Berapa rincian biaya pendidikan di SMK Amaliah?</span>
                            <svg :class="{'rotate-45': open === 12}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 12" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Rincian biaya pendidikan, termasuk uang pangkal, SPP bulanan, dan biaya lainnya, akan
                                disampaikan secara transparan saat periode pendaftaran siswa baru. Informasi detail dapat
                                diperoleh melalui brosur resmi atau dengan menghubungi bagian administrasi sekolah.</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-800 pt-6 border-t border-gray-200">Akademik & Prospek Karir
                    </h3>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 13 ? null : 13"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apa keunggulan utama SMK Amaliah?</span>
                            <svg :class="{'rotate-45': open === 13}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 13" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Keunggulan kami terletak pada <strong>kurikulum berbasis industri</strong>, <strong>fasilitas
                                    laboratorium modern</strong>, program <strong>magang (PKL) di perusahaan
                                    ternama</strong>, serta <strong>pembinaan karakter dan kewirausahaan</strong> untuk
                                memastikan lulusan tidak hanya terampil secara teknis, tetapi juga siap kerja dan berakhlak
                                mulia.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 14 ? null : 14"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Bagaimana prospek kerja lulusan dari masing-masing jurusan?</span>
                            <svg :class="{'rotate-45': open === 14}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 14" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Setiap jurusan dirancang untuk memenuhi kebutuhan industri spesifik:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1 pl-2">
                                <li><strong>TKJ & RPL:</strong> Teknisi Jaringan, Administrator Server, Programmer, Web
                                    Developer, IT Support.</li>
                                <li><strong>AN & DKV:</strong> Animator 2D/3D, Desainer Grafis, Video Editor, Content
                                    Creator.</li>
                                <li><strong>MP & AK:</strong> Staf Administrasi, Sekretaris, Akuntan Junior, Staf Pajak.
                                </li>
                                <li><strong>LPS:</strong> Teller Bank Syariah, Customer Service, Staf Keuangan Lembaga
                                    Syariah.</li>
                                <li><strong>DPB & BR:</strong> Desainer Mode, Fashionpreneur, Visual Merchandiser, Manajer
                                    Toko Retail.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 15 ? null : 15"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apakah lulusan SMK Amaliah dijamin dapat kerja?</span>
                            <svg :class="{'rotate-45': open === 15}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 15" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Kami tidak memberikan jaminan, namun kami berkomitmen kuat untuk menyalurkan lulusan terbaik.
                                Melalui <strong>Bursa Kerja Khusus (BKK)</strong>, kami secara aktif menjalin kerja sama
                                dengan puluhan perusahaan mitra untuk rekrutmen. Data kami menunjukkan tingkat keterserapan
                                lulusan yang sangat tinggi, baik untuk bekerja, melanjutkan studi, maupun berwirausaha
                                (BWM).</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 16 ? null : 16"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apakah siswa bisa melanjutkan kuliah setelah lulus?</span>
                            <svg :class="{'rotate-45': open === 16}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 16" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Tentu saja. Lulusan SMK Amaliah memiliki bekal yang kuat untuk melanjutkan pendidikan ke
                                jenjang yang lebih tinggi, baik di universitas, politeknik, maupun institut. Banyak alumni
                                kami yang sukses menempuh pendidikan di perguruan tinggi negeri maupun swasta ternama,
                                terutama di bidang yang linier dengan jurusannya.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 17 ? null : 17"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Bagaimana sistem Praktik Kerja Lapangan (PKL) di sekolah ini?</span>
                            <svg :class="{'rotate-45': open === 17}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 17" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>PKL adalah program wajib yang dilaksanakan selama 3-6 bulan di perusahaan-perusahaan yang
                                telah menjadi mitra sekolah. Program ini bertujuan untuk memberikan pengalaman kerja nyata,
                                mengaplikasikan ilmu yang didapat di sekolah, dan membangun jaringan profesional sejak dini.
                            </p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 18 ? null : 18"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apakah ada program sertifikasi kompetensi untuk siswa?</span>
                            <svg :class="{'rotate-45': open === 18}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 18" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Ya, kami bekerja sama dengan Lembaga Sertifikasi Profesi (LSP) untuk menyelenggarakan Uji
                                Kompetensi Keahlian (UKK). Siswa yang lulus akan mendapatkan sertifikat kompetensi dari BNSP
                                yang diakui secara nasional dan dapat menjadi nilai tambah saat melamar pekerjaan.</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-800 pt-6 border-t border-gray-200">Kehidupan Sekolah &
                        Fasilitas</h3>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 19 ? null : 19"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apa saja kegiatan ekstrakurikuler yang tersedia?</span>
                            <svg :class="{'rotate-45': open === 19}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 19" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
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

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 20 ? null : 20"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Bagaimana fasilitas pendukung pembelajaran di SMK Amaliah?</span>
                            <svg :class="{'rotate-45': open === 20}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 20" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Kami menyediakan fasilitas lengkap untuk mendukung proses belajar mengajar, antara lain:
                            <ul class="list-disc list-inside mt-2 space-y-1 pl-2">
                                <li>Ruang kelas yang nyaman dan representatif.</li>
                                <li>Laboratorium Komputer sesuai standar industri.</li>
                                <li>Studio Animasi dan Desain.</li>
                                <li>Laboratorium Akuntansi & Bank Mini.</li>
                                <li>Workshop Busana dan Mesin Jahit.</li>
                                <li>Perpustakaan dengan koleksi buku yang lengkap.</li>
                                <li>Koneksi internet (Wi-Fi) di area sekolah.</li>
                                <li>Fasilitas olahraga dan ruang serbaguna.</li>
                            </ul>
                            </p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 21 ? null : 21"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apakah ada program pembinaan karakter di sekolah?</span>
                            <svg :class="{'rotate-45': open === 21}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 21" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Ya, pembinaan karakter adalah salah satu pilar pendidikan di SMK Amaliah. Kami
                                mengintegrasikan nilai-nilai religius, disiplin, tanggung jawab, dan etos kerja melalui
                                kegiatan rutin seperti apel pagi, sholat berjamaah, kegiatan Rohis, dan program Latihan
                                Dasar Kepemimpinan Siswa (LDKS).</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 22 ? null : 22"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Di mana lokasi tepatnya SMK Amaliah 1 & 2?</span>
                            <svg :class="{'rotate-45': open === 22}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 22" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>SMK Amaliah 1 & 2 berlokasi di Ciawi, Bogor. Untuk alamat lengkap dan
                                peta lokasi, silakan merujuk pada halaman kontak di website kami.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 23 ? null : 23"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Bagaimana cara menghubungi pihak sekolah untuk informasi lebih
                                lanjut?</span>
                            <svg :class="{'rotate-45': open === 23}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 23" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Anda dapat menghubungi kami melalui telepon, email, atau mengunjungi kami langsung di sekolah
                                pada jam kerja. Semua informasi kontak tersedia di bagian bawah website ini atau pada
                                halaman "Hubungi Kami".</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 24 ? null : 24"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apakah ada layanan konseling untuk siswa?</span>
                            <svg :class="{'rotate-45': open === 24}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 24" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Tentu. Kami memiliki guru Bimbingan Konseling (BK) yang siap membantu siswa dalam mengatasi
                                masalah akademik, pribadi, maupun sosial, serta memberikan arahan dalam perencanaan karir ke
                                depan.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 25 ? null : 25"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apakah ada seragam khusus untuk siswa SMK Amaliah?</span>
                            <svg :class="{'rotate-45': open === 25}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 25" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Ya, siswa diwajibkan mengenakan seragam yang telah ditentukan oleh sekolah. Terdapat beberapa
                                jenis seragam, seperti seragam putih abu-abu, seragam pramuka, seragam praktik (sesuai
                                jurusan), dan seragam khas sekolah. Detail mengenai ketentuan seragam akan dijelaskan saat
                                proses daftar ulang.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 26 ? null : 26"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Bagaimana sekolah melibatkan orang tua dalam pendidikan siswa?</span>
                            <svg :class="{'rotate-45': open === 26}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 26" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Kami percaya sinergi antara sekolah dan orang tua sangat penting. Keterlibatan orang tua
                                diwujudkan melalui Komite Sekolah, pertemuan rutin wali kelas dengan orang tua, pembagian
                                laporan hasil belajar (rapor), serta seminar parenting yang diadakan secara berkala.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 27 ? null : 27"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apakah ada program kewirausahaan untuk siswa?</span>
                            <svg :class="{'rotate-45': open === 27}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 27" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Ya, kami sangat mendorong jiwa wirausaha. Melalui mata pelajaran Produk Kreatif dan
                                Kewirausahaan (PKK) serta unit produksi di setiap jurusan, siswa dibekali ilmu dan praktik
                                untuk memulai dan mengelola usaha sendiri, sejalan dengan visi sekolah untuk mencetak
                                lulusan yang siap berwirausaha (W).</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 28 ? null : 28"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apakah ada perpustakaan dan bagaimana koleksinya?</span>
                            <svg :class="{'rotate-45': open === 28}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 28" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Kami memiliki perpustakaan yang menyediakan berbagai koleksi buku pelajaran, buku referensi
                                kejuruan, novel, buku pengetahuan umum, serta sumber belajar digital untuk mendukung
                                kegiatan literasi dan memperluas wawasan siswa.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 29 ? null : 29"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Bagaimana budaya dan lingkungan belajar di SMK Amaliah?</span>
                            <svg :class="{'rotate-45': open === 29}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 29" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Kami menciptakan lingkungan belajar yang kondusif, religius, dan berbudaya.
                                Seiring perjalanan waktu, sekolah telah melalui berbagai tantangan, namun berkat kerjasama
                                yang baik seluruh warga sekolah, kesabaran, dan keikhlasan, kami terus membangun komunitas
                                yang solid dan mendukung. Kami menekankan nilai-nilai saling menghormati,
                                disiplin, dan semangat untuk terus belajar.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 30 ? null : 30"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Apakah ada akses internet untuk siswa?</span>
                            <svg :class="{'rotate-45': open === 30}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 30" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Ya, sekolah menyediakan akses internet melalui jaringan Wi-Fi di area-area tertentu yang
                                dapat digunakan siswa untuk keperluan pembelajaran dan mencari sumber informasi positif.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button @click="open = open === 31 ? null : 31"
                            class="flex items-center justify-between w-full text-left font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="text-lg">Saya lulusan jurusan Animasi, apa saja prospek karirnya?</span>
                            <svg :class="{'rotate-45': open === 31}"
                                class="w-6 h-6 transform transition-transform duration-300"
                                xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <div x-show="open === 31" x-collapse x-cloak class="mt-4 text-gray-600 leading-relaxed">
                            <p>Lulusan dari konsentrasi keahlian Animasi (AN)  memiliki prospek karir
                                yang cerah di industri kreatif. Beberapa profesi yang bisa ditekuni antara lain menjadi
                                animator 2D/3D untuk film atau game, storyboard artist, character designer, visual effects
                                (VFX) artist, atau motion graphic designer untuk iklan dan konten digital.</p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-12">
                    <p class="text-gray-600">Tidak menemukan jawaban yang Anda cari?</p>
                    <a href="https://linktr.ee/smkamaliah" class="mt-2 inline-block text-lg font-semibold text-cyan-600 hover:text-cyan-500">
                        Hubungi Tim Penerimaan Siswa Baru &rarr;
                    </a>
                </div>

            </div>
        </section>



    </body>

    </html>
@endsection