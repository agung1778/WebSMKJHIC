@extends('layouts.public-app')

@section('title', 'Visi & Misi — SMK Amaliah 1 & 2')

@section('content')
    @include('PublicSide.partials.about-hero', [
        'active' => 'vision',
        'title' => 'Visi & Misi',
        'lead' => 'Tujuan dan cita-cita yang menjadi panduan seluruh ekosistem SMK Amaliah 1 & 2 dalam mendidik generasi berkualitas.',
        'showCta' => false,
    ])

    {{-- ===================== VISI ===================== --}}
    <section class="bg-white py-16 lg:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center fade-in-section">
                <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">Visi</span>
                <h2 class="mt-3 text-3xl lg:text-4xl font-bold text-[#282829]">Visi SMK Amaliah 1 & 2</h2>
            </div>

            <div class="mt-10 max-w-3xl mx-auto fade-in-section relative bg-[#282829] rounded-3xl p-8 lg:p-12 text-center overflow-hidden shadow-2xl">
                <div class="absolute top-0 right-0 w-60 h-60 rounded-full opacity-20"
                    style="background: radial-gradient(circle, #63cd00 0%, transparent 70%)"></div>
                <i class="fa-solid fa-quote-left text-4xl text-[#63cd00]"></i>
                <p class="mt-6 text-2xl lg:text-3xl text-white font-medium leading-relaxed">
                    "Menjadi Sekolah Menengah Kejuruan Berkualitas Yang Menyatu Dalam Tauhid"
                </p>
                <div class="mt-8 inline-flex items-center gap-2 text-sm text-gray-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#63cd00]"></span>
                    Visi SMK Amaliah 1 &amp; 2 Ciawi
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== MISI ===================== --}}
    <section class="bg-gray-50 py-16 lg:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto fade-in-section">
                <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">Misi</span>
                <h2 class="mt-3 text-3xl lg:text-4xl font-bold text-[#282829]">Langkah Mewujudkan Visi</h2>
                <p class="mt-4 text-gray-600 text-base lg:text-lg">Komitmen kami dalam proses pendidikan sehari-hari.</p>
            </div>

            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $missions = [
                        ['icon' => 'fa-hands-holding-circle', 'title' => 'Integrasi Nilai Tauhid', 'desc' => 'Mengintegrasikan nilai-nilai Tauhid pada setiap mata pelajaran untuk membentuk karakter yang kuat.'],
                        ['icon' => 'fa-tools', 'title' => 'Orientasi Praktik', 'desc' => 'Pembelajaran praktik dengan komposisi 70% praktik dan 30% teori untuk kesiapan kerja.'],
                        ['icon' => 'fa-puzzle-piece', 'title' => 'Pembelajaran Menyenangkan', 'desc' => 'Proses belajar yang menyenangkan namun tetap aplikatif dan dapat diterapkan langsung.'],
                        ['icon' => 'fa-clipboard-check', 'title' => 'Penilaian Berbasis Kompetensi', 'desc' => 'Penilaian berdasarkan ketuntasan kompetensi untuk menjaga standar kualitas lulusan.'],
                        ['icon' => 'fa-user-graduate', 'title' => 'Lulusan Terampil', 'desc' => 'Memberikan bekal keterampilan yang bermanfaat dan relevan bagi masyarakat dan industri.'],
                        ['icon' => 'fa-handshake-angle', 'title' => 'Kerja Sama Industri', 'desc' => 'Menjalin kemitraan dengan dunia usaha dan industri untuk magang maupun penempatan kerja.'],
                    ];
                @endphp
                @foreach($missions as $i => $m)
                    <div class="fade-in-section flex gap-4 bg-white border border-gray-200 rounded-2xl p-6 hover:border-[#63cd00] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="w-11 h-11 rounded-xl bg-[#eafad7] flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid {{ $m['icon'] }} text-lg text-[#3f9b00]"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-[#63cd00]">Misi {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                            <h3 class="mt-1 font-bold text-[#282829] text-base">{{ $m['title'] }}</h3>
                            <p class="mt-1.5 text-sm text-gray-600 leading-relaxed">{{ $m['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== DETAIL (konten dinamis) ===================== --}}
    @if(isset($visionContent) && $visionContent && trim(strip_tags($visionContent->content)) !== '')
        <section class="bg-white py-16 lg:py-24">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    <div class="fade-in-section lg:col-span-1">
                        <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">Detail</span>
                        <h2 class="mt-3 text-3xl lg:text-4xl font-bold text-[#282829]">Lebih Jauh Tentang Visi & Misi</h2>
                        <div class="mt-4 w-20 h-1.5 rounded-full bg-[#63cd00]"></div>
                    </div>
                    <div class="lg:col-span-2 fade-in-section text-base lg:text-lg text-gray-600 leading-relaxed">
                        {!! \App\Support\HtmlSanitizer::clean($visionContent->content) !!}
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection