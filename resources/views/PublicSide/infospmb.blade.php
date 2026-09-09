@extends('layouts.public-app')

@section('title', 'Info SPMB 2026/2027')

@section('content')

    {{-- Konfigurasi Warna Utama --}}
    @php
        $amaliahGreen = '#63cd00';
        $amaliahDark = '#282829';
    @endphp

    <div class="font-['Poppins'] bg-gray-50 min-h-screen pt-8 md:pt-16 pb-24">

        {{-- ================= HERO SECTION ================= --}}
        <section class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 mb-20 fade-in-section">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start">

                {{-- KOLOM KIRI: Teks & Tombol --}}
                <div class="lg:col-span-7 pt-4">

                    {{-- Badges --}}
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        @if ($spmbSetting && $spmbSetting->status == 'Buka')
                            <span
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-gray-800 text-sm font-semibold shadow-sm border border-gray-100">
                                <span class="w-2.5 h-2.5 rounded-full animate-pulse"
                                    style="background-color: {{ $amaliahGreen }};"></span>
                                Pendaftaran Buka
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-gray-800 text-sm font-semibold shadow-sm border border-gray-100">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                                Pendaftaran Tutup
                            </span>
                        @endif

                        @if ($spmbSetting && $spmbSetting->wave_name)
                            <span
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-gray-800 text-sm font-semibold shadow-sm border border-gray-100">
                                <i class="fas fa-bullhorn text-[#63cd00]"></i> {{ $spmbSetting->wave_name }}
                            </span>
                        @endif
                    </div>

                    {{-- Judul Utama --}}
                    <h1 class="text-4xl sm:text-5xl lg:text-[56px] font-bold text-gray-900 leading-[1.1] mb-6">
                        Membangun Generasi <br class="hidden md:block">
                        <span style="color: {{ $amaliahGreen }};">Tauhid & Berkompeten</span>
                    </h1>

                    {{-- Deskripsi --}}
                    <p class="text-gray-600 text-base md:text-lg leading-relaxed max-w-xl mb-10">
                        Bergabunglah menjadi bagian dari keluarga besar SMK Amaliah 1 & 2 Ciawi untuk Tahun Ajaran
                        2026/2027.
                    </p>

                    {{-- Tombol Aksi (IDENTIK DENGAN WELCOME BLADE) --}}
                    <div
                        class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4 mt-8">
                        @if ($spmbSetting && $spmbSetting->status == 'Buka' && $spmbSetting->registration_link)
                            <a href="{{ $spmbSetting->registration_link }}" target="_blank"
                                class="group inline-flex items-center justify-between text-white pl-6 pr-2 py-2 rounded-lg font-semibold shadow-lg transition-all duration-300 hover:shadow-xl hover:opacity-90 w-full sm:w-auto"
                                style="background-color: {{ $amaliahGreen }};">
                                <span class="mr-4">Daftar Sekarang</span>
                                <span
                                    class="bg-white rounded-full h-8 w-8 flex items-center justify-center transition-transform duration-300 group-hover:translate-x-1">
                                    <i class="fas fa-arrow-right text-sm" style="color: {{ $amaliahGreen }};"></i>
                                </span>
                            </a>
                        @endif

                        <a href="#jurusan"
                            class="group inline-flex items-center justify-between text-gray-700 bg-white pl-6 pr-2 py-2 rounded-lg font-semibold shadow-md transition-all duration-300 hover:shadow-lg w-full sm:w-auto">
                            <span class="mr-4">Lihat Jurusan</span>
                            <span
                                class="bg-gray-100 rounded-full h-8 w-8 flex items-center justify-center transition-transform duration-300 group-hover:translate-y-1">
                                <i class="fas fa-arrow-down text-sm text-gray-600"></i>
                            </span>
                        </a>
                    </div>
                </div>

                {{-- KOLOM KANAN: Timeline Gelombang & Brosur --}}
                <div class="lg:col-span-5 flex flex-col gap-6 w-full mt-10 lg:mt-0">

                    {{-- KOTAK 1: INFORMASI GELOMBANG & PERIODE (DIPERBARUI) --}}
                    <div class="bg-white rounded-2xl p-8 shadow-lg relative border-t-4 flex flex-col"
                        style="border-color: {{ $amaliahDark }}; min-height: 280px;">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Informasi Pendaftaran</h3>
                            <i class="fas fa-info-circle text-3xl opacity-20" style="color: {{ $amaliahDark }};"></i>
                        </div>

                        {{-- Periode Tanggal --}}
                        <div class="mb-5">
                            <p class="text-sm text-gray-500 font-medium mb-1">Periode Pelaksanaan</p>
                            <p class="text-gray-800 font-bold text-lg flex items-center gap-2">
                                <i class="far fa-calendar-check" style="color: {{ $amaliahGreen }};"></i>
                                {{ $spmbSetting->period_date ?? 'Menunggu Jadwal' }}
                            </p>
                        </div>

                        {{-- Blok Gelombang (Tampil Terang & Jelas di dalam card) --}}
                        @if ($spmbSetting && ($spmbSetting->wave_category || $spmbSetting->wave_description))
                            <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-5">
                                <div class="flex items-center gap-2 mb-2 border-b border-gray-200 pb-3">
                                    <span
                                        class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-md uppercase tracking-wide">
                                        {{ $spmbSetting->wave_category ?? 'Gelombang' }}
                                    </span>
                                    @if ($spmbSetting->wave_name)
                                        <span class="text-gray-800 font-bold text-sm">{{ $spmbSetting->wave_name }}</span>
                                    @endif
                                </div>
                                @if ($spmbSetting->wave_description)
                                    <p class="text-sm text-gray-600 leading-relaxed mt-3">
                                        {{ $spmbSetting->wave_description }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        {{-- Kuota (Nempel di bawah) --}}
                        @if ($spmbSetting && $spmbSetting->quota_note)
                            <div class="mt-auto pt-4 border-t border-gray-100">
                                <p class="text-sm font-bold text-red-500 flex items-center gap-2">
                                    <i class="fas fa-exclamation-circle text-lg"></i> {{ $spmbSetting->quota_note }}
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- KOTAK 2: BROSUR DIGITAL --}}
                    <div class="bg-white rounded-2xl p-6 shadow-lg border-t-4" style="border-color: {{ $amaliahGreen }};">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-5">
                            <h3 class="text-lg font-bold text-gray-900">Brosur Digital</h3>

                            {{-- Tombol Download Diperjelas --}}
                            @if ($spmbSetting && $spmbSetting->brochure_file)
                                <a href="{{ asset('storage/' . $spmbSetting->brochure_file) }}" target="_blank" download
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg text-sm font-bold border border-gray-200 transition-colors">
                                    <i class="fas fa-download" style="color: {{ $amaliahGreen }};"></i> Unduh PDF
                                </a>
                            @endif
                        </div>

                        {{-- Gambar Preview Brosur (Ratio Aman & Tidak Terpotong) --}}
                        <div class="relative w-full aspect-video rounded-xl overflow-hidden bg-gray-50 border border-gray-100 cursor-pointer group flex items-center justify-center p-2"
                            id="btn-buka-brosur">
                            <img src="{{ $spmbSetting && $spmbSetting->brochure_image_1 ? asset('storage/' . $spmbSetting->brochure_image_1) : asset('assets/image/Brosur.jpeg') }}"
                                alt="Pratinjau Brosur"
                                class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-[1.02]">

                            <div
                                class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span
                                    class="bg-white text-gray-900 px-4 py-2 rounded-full text-xs font-bold shadow-md flex items-center gap-2">
                                    <i class="fas fa-search-plus" style="color: {{ $amaliahGreen }};"></i> Perbesar
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- ================= JURUSAN SECTION ================= --}}
        <section id="jurusan" class="py-16 bg-white fade-in-section">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- Header Sesuai Tema Welcome --}}
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Pilihan Jurusan</h2>
                    <div class="flex items-center justify-center gap-x-2 mx-auto mt-4">
                        <div class="w-20 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                        <div class="w-4 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                        <div class="w-4 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                    {{-- SMK 1 --}}
                    <div
                        class="bg-gray-50 rounded-2xl p-8 shadow-sm border-2 border-transparent hover:border-[#63cd00] hover:bg-white transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-200">
                            <div
                                class="w-14 h-14 rounded-xl flex items-center justify-center bg-blue-100 text-blue-600 shadow-sm">
                                <i class="fas fa-laptop-code text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">SMK Amaliah 1</h3>
                        </div>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3 text-gray-600 font-medium">
                                <i class="fas fa-check-circle text-blue-500 mt-1"></i> Desain Komunikasi Visual (DKV)
                            </li>
                            <li class="flex items-start gap-3 text-gray-600 font-medium">
                                <i class="fas fa-check-circle text-blue-500 mt-1"></i> Teknik Komputer & Jaringan (TKJ)
                            </li>
                            <li class="flex items-start gap-3 text-gray-600 font-medium">
                                <i class="fas fa-check-circle text-blue-500 mt-1"></i> Animasi (AN)
                            </li>
                            <li class="flex items-start gap-3 text-gray-600 font-medium">
                                <i class="fas fa-check-circle text-blue-500 mt-1"></i> Rekayasa Perangkat Lunak (RPL)
                            </li>
                        </ul>
                    </div>

                    {{-- SMK 2 --}}
                    <div
                        class="bg-gray-50 rounded-2xl p-8 shadow-sm border-2 border-transparent hover:border-[#63cd00] hover:bg-white transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-200">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-sm"
                                style="background-color: {{ $amaliahGreen }}; color: white;">
                                <i class="fas fa-briefcase text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">SMK Amaliah 2</h3>
                        </div>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3 text-gray-600 font-medium">
                                <i class="fas fa-check-circle mt-1" style="color: {{ $amaliahGreen }};"></i> Akuntansi
                                (AK)
                            </li>
                            <li class="flex items-start gap-3 text-gray-600 font-medium">
                                <i class="fas fa-check-circle mt-1" style="color: {{ $amaliahGreen }};"></i> Layanan
                                Perbankan Syariah (LPS)
                            </li>
                            <li class="flex items-start gap-3 text-gray-600 font-medium">
                                <i class="fas fa-check-circle mt-1" style="color: {{ $amaliahGreen }};"></i> Desain &
                                Produksi Busana (DPB)
                            </li>
                            <li class="flex items-start gap-3 text-gray-600 font-medium">
                                <i class="fas fa-check-circle mt-1" style="color: {{ $amaliahGreen }};"></i> Bisnis Ritel
                                (BR)
                            </li>
                            <li class="flex items-start gap-3 text-gray-600 font-medium">
                                <i class="fas fa-check-circle mt-1" style="color: {{ $amaliahGreen }};"></i> Manajemen
                                Perkantoran (MP)
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================= KEUNGGULAN KAMI ================= --}}
        <section class="py-16 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 fade-in-section">

            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Keunggulan Kami</h2>
                <div class="flex items-center justify-center gap-x-2 mx-auto mt-4">
                    <div class="w-20 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                    <div class="w-4 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                    <div class="w-4 h-1.5 rounded-full" style="background-color: {{ $amaliahGreen }};"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $advantages = [
                        [
                            'icon' => 'fa-briefcase',
                            'title' => 'Siap Kerja',
                            'desc' => 'Lulusan kami dibekali skill industri.',
                        ],
                        [
                            'icon' => 'fa-heart',
                            'title' => 'Pembinaan Akhlak',
                            'desc' => 'Pendidikan karakter berlandaskan tauhid.',
                        ],
                        [
                            'icon' => 'fa-quran',
                            'title' => 'Program Tahfidz',
                            'desc' => 'Program hafalan Al-Qur\'an untuk siswa.',
                        ],
                        [
                            'icon' => 'fa-building',
                            'title' => 'Fasilitas Lengkap',
                            'desc' => 'Sarana prasarana modern & lab memadai.',
                        ],
                        [
                            'icon' => 'fa-handshake',
                            'title' => 'Mitra Industri',
                            'desc' => 'Bekerja sama dengan ratusan perusahaan.',
                        ],
                        [
                            'icon' => 'fa-university',
                            'title' => 'Binaan UNIDA',
                            'desc' => 'Di bawah naungan Universitas Djuanda.',
                        ],
                        [
                            'icon' => 'fa-mosque',
                            'title' => 'Sholat Berjamaah',
                            'desc' => 'Pembiasaan ibadah rutin di sekolah.',
                        ],
                        [
                            'icon' => 'fa-globe-asia',
                            'title' => 'Program Magang',
                            'desc' => 'Pengalaman kerja langsung di lapangan.',
                        ],
                    ];
                @endphp

                @foreach ($advantages as $adv)
                    <div
                        class="bg-gray-50 p-6 rounded-2xl flex flex-col items-start border-2 border-transparent hover:border-[#63cd00] hover:bg-white transition-all duration-300 shadow-sm hover:shadow-lg transform hover:-translate-y-1">
                        <div class="p-4 rounded-xl mb-4" style="background-color: {{ $amaliahGreen }};">
                            <i class="fas {{ $adv['icon'] }} text-2xl text-white"></i>
                        </div>
                        <h2 class="text-lg font-bold mb-2" style="color: {{ $amaliahDark }};">{{ $adv['title'] }}</h2>
                        <p class="text-sm text-gray-600 flex-grow">{{ $adv['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ================= KONTAK BANTUAN ================= --}}
        <section class="py-16 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 fade-in-section">
            <div
                class="bg-white rounded-2xl p-8 lg:p-12 flex flex-col lg:flex-row items-center justify-between gap-8 border-2 border-transparent hover:border-[#63cd00] transition-colors duration-300 shadow-lg">

                <div class="text-center lg:text-left">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Butuh Bantuan?</h2>
                    <p class="text-gray-600 font-medium text-lg">Hubungi panitia PPDB kami untuk informasi lebih lanjut.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    {{-- Tombol Kontak 1 --}}
                    <a href="https://wa.me/6285649011449" target="_blank"
                        class="group inline-flex items-center justify-between text-white pl-6 pr-2 py-2 rounded-lg font-semibold shadow-md transition-all duration-300 hover:shadow-lg w-full sm:w-auto"
                        style="background-color: {{ $amaliahGreen }};">
                        <div class="flex flex-col text-left mr-4">
                            <span class="text-[10px] uppercase opacity-90">Admin 1</span>
                            <span>0856-4901-1449</span>
                        </div>
                        <span
                            class="bg-white rounded-full h-8 w-8 flex items-center justify-center transition-transform duration-300 group-hover:scale-110">
                            <i class="fab fa-whatsapp text-lg" style="color: {{ $amaliahGreen }};"></i>
                        </span>
                    </a>

                    {{-- Tombol Kontak 2 --}}
                    <a href="https://wa.me/628561922827" target="_blank"
                        class="group inline-flex items-center justify-between text-white pl-6 pr-2 py-2 rounded-lg font-semibold shadow-md transition-all duration-300 hover:shadow-lg w-full sm:w-auto"
                        style="background-color: {{ $amaliahDark }};">
                        <div class="flex flex-col text-left mr-4">
                            <span class="text-[10px] uppercase opacity-70">Admin 2</span>
                            <span>0856-1922-827</span>
                        </div>
                        <span
                            class="bg-white rounded-full h-8 w-8 flex items-center justify-center transition-transform duration-300 group-hover:scale-110">
                            <i class="fab fa-whatsapp text-lg" style="color: {{ $amaliahDark }};"></i>
                        </span>
                    </a>
                </div>
            </div>
        </section>

    </div>

    {{-- ================= MODAL FULLSCREEN BROSUR (MURNI JAVASCRIPT) ================= --}}
    <div id="brosur-modal"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 p-4 md:p-10 backdrop-blur-sm">
        <button id="btn-tutup-brosur"
            class="absolute top-4 right-4 md:top-8 md:right-8 w-12 h-12 bg-white/10 hover:bg-red-600 rounded-full flex items-center justify-center text-white z-50 transition-colors">
            <i class="fas fa-times text-2xl"></i>
        </button>

        <div class="relative w-full h-full flex items-center justify-center">
            <img src="{{ $spmbSetting && $spmbSetting->brochure_full_image ? asset('storage/' . $spmbSetting->brochure_full_image) : asset('assets/image/brosur-spmb-full.jpg') }}"
                class="max-w-full max-h-full rounded-lg object-contain select-none shadow-2xl">
        </div>
    </div>

    {{-- SCRIPT MODAL BROSUR --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var btnBuka = document.getElementById('btn-buka-brosur');
            var btnTutup = document.getElementById('btn-tutup-brosur');
            var modal = document.getElementById('brosur-modal');

            if (btnBuka && modal) {
                btnBuka.addEventListener('click', function() {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }

            if (btnTutup && modal) {
                btnTutup.addEventListener('click', function() {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                });
            }

            if (modal) {
                modal.addEventListener('click', function(event) {
                    if (event.target === modal || event.target.tagName !== 'IMG') {
                        modal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    }
                });
            }
        });
    </script>
@endsection
