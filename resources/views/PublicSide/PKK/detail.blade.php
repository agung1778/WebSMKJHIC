@extends('layouts.public-app')

@section('content')
    {{-- Resources --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    @php
        $amaliahGreen = $amaliahGreen ?? '#63cd00';
        $amaliahDark = $amaliahDark ?? '#282829';
    @endphp

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8FAFC;
        }

        /* Custom Card Style */
        .detail-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #E2E8F0;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* Tombol WhatsApp */
        .btn-wa {
            background-color: #25D366;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-wa:hover {
            background-color: #1ebc57;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(37, 211, 102, 0.3);
        }

        /* Sticky Sidebar agar tetap terlihat saat scroll */
        .sticky-sidebar {
            position: sticky;
            top: 100px;
            z-index: 10;
        }
    </style>

    {{-- BREADCRUMB --}}
    <div class="bg-white border-b border-gray-100 py-4 pt-24">
        <div class="container mx-auto px-4 md:px-12">
            <nav class="flex text-sm font-medium text-gray-500 overflow-x-auto whitespace-nowrap pb-1">
                <a href="{{ route('public.pkk.index') }}"
                    class="hover:text-[{{ $amaliahGreen }}] transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Galeri
                </a>
                <span class="mx-3 text-gray-300">/</span>
                <span class="text-gray-800">{{ $project->category ?? 'Proyek' }}</span>
                <span class="mx-3 text-gray-300">/</span>
                <span class="text-[{{ $amaliahGreen }}] font-semibold">{{ Str::limit($project->brand_name, 25) }}</span>
            </nav>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <section class="py-12">
        <div class="container mx-auto px-4 md:px-12">
            <div class="flex flex-col lg:flex-row gap-8">

                {{-- === KOLOM KIRI (FOTO & DESKRIPSI) === --}}
                <div class="w-full lg:w-2/3">
                    <div class="detail-card">

                        {{-- 1. HEADER IMAGE --}}
                        <div class="relative h-[300px] md:h-[400px] bg-gray-100 overflow-hidden group">
                            @if ($project->photo)
                                <img src="{{ asset('storage/' . $project->photo) }}" alt="{{ $project->title }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i class="fas fa-image fa-4x opacity-50"></i>
                                </div>
                            @endif

                            {{-- Overlay Gradient Halus --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent">
                            </div>

                            {{-- Badge Kategori --}}
                            <div class="absolute top-4 right-4">
                                <span
                                    class="bg-white/95 backdrop-blur px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm text-gray-800">
                                    {{ $project->category ?? 'Umum' }}
                                </span>
                            </div>
                        </div>

                        {{-- 2. AREA KONTEN UTAMA --}}
                        <div class="relative px-6 pb-8 md:px-10 md:pb-12">

                            {{-- LOGO BRAND (Floating) --}}
                            {{-- Posisi absolute negatif membuat dia 'nangkring' di perbatasan gambar --}}
                            <div class="absolute -top-12 left-6 md:left-10">
                                <div
                                    class="w-24 h-24 bg-white rounded-2xl shadow-lg p-2 flex items-center justify-center border border-gray-100">
                                    @if ($project->logo)
                                        <img src="{{ asset('storage/' . $project->logo) }}"
                                            class="w-full h-full object-contain rounded-xl">
                                    @else
                                        <i class="fas fa-cube text-gray-300 text-3xl"></i>
                                    @endif
                                </div>
                            </div>

                            {{-- JUDUL & NAMA BRAND --}}
                            {{-- PENTING: pt-16 memberi ruang agar teks tidak tertutup logo --}}
                            <div class="pt-16 mb-8">
                                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-2">
                                    {{ $project->brand_name }}
                                </h1>
                                <p class="text-lg text-gray-500 font-medium">
                                    {{ $project->title }}
                                </p>
                            </div>

                            {{-- Garis Pembatas --}}
                            <hr class="border-gray-100 mb-8">

                            {{-- DESKRIPSI PRODUK --}}
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-align-left text-[{{ $amaliahGreen }}] mr-2"></i>
                                    Tentang Produk
                                </h3>
                                <div class="prose prose-gray max-w-none text-gray-600 leading-relaxed text-sm md:text-base">
                                    {!! nl2br(e($project->description)) !!}
                                </div>
                            </div>

                            {{-- SOCIAL MEDIA (Jika Ada) --}}
                            @if ($project->social_media)
                                <div class="mt-8 pt-6 border-t border-gray-100">
                                    <a href="{{ $project->social_media }}" target="_blank"
                                        class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-[{{ $amaliahGreen }}] transition-colors">
                                        <i class="fab fa-instagram text-xl mr-2"></i>
                                        Kunjungi Social Media Kami
                                    </a>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- === KOLOM KANAN (SIDEBAR INFO) === --}}
                <div class="w-full lg:w-1/3">
                    <div class="sticky-sidebar space-y-6">

                        {{-- CARD 1: HARGA & KONTAK --}}
                        <div class="detail-card p-6 md:p-8 bg-white">
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Harga Produk</p>

                            @if ($project->price > 0)
                                <div class="text-3xl font-extrabold text-gray-900 mb-6">
                                    Rp {{ number_format($project->price, 0, ',', '.') }}
                                </div>
                            @else
                                <div class="text-2xl font-bold text-[{{ $amaliahGreen }}] mb-6">
                                    Hubungi Kami
                                </div>
                            @endif

                            @if ($project->contact_info)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $project->contact_info)) }}?text=Halo%20{{ $project->brand_name }},%20saya%20tertarik%20dengan%20produk%20{{ $project->title }}."
                                    target="_blank"
                                    class="btn-wa w-full py-3.5 rounded-xl font-bold text-base flex items-center justify-center gap-2">
                                    <i class="fab fa-whatsapp text-xl"></i>
                                    Pesan Sekarang
                                </a>
                                <p class="text-xs text-center text-gray-400 mt-3">
                                    Transaksi langsung dengan Siswa Kreator
                                </p>
                            @else
                                <div
                                    class="w-full py-3.5 bg-gray-100 rounded-xl text-gray-400 font-bold text-center text-sm cursor-not-allowed">
                                    Kontak Tidak Tersedia
                                </div>
                            @endif
                        </div>

                        {{-- CARD 2: TIM KREATOR --}}
                        <div class="detail-card p-6 md:p-8 bg-white">
                            <h4 class="text-base font-bold text-gray-900 mb-5 flex items-center">
                                <i class="fas fa-users text-[{{ $amaliahGreen }}] mr-2.5"></i>
                                Tim Kreator
                            </h4>

                            <div class="space-y-5">
                                {{-- Nama Siswa --}}
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 text-gray-400">
                                        <i class="fas fa-user text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-0.5">Nama Siswa</p>
                                        <p class="text-gray-800 font-semibold text-sm md:text-base leading-tight">
                                            {{ $project->student_names }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Kelas (Jika Ada) --}}
                                @if ($project->student_class)
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 text-gray-400">
                                            <i class="fas fa-id-card text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase mb-0.5">Kelas</p>
                                            <p class="text-gray-800 font-medium text-sm">
                                                {{ $project->student_class }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Jurusan --}}
                                <div class="pt-4 border-t border-gray-100 mt-2">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-2">Program Keahlian</p>
                                    <div
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-100">
                                        <i class="fas fa-layer-group mr-2 text-xs"></i>
                                        <span class="text-xs font-bold">{{ $project->major->name ?? 'Umum' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
