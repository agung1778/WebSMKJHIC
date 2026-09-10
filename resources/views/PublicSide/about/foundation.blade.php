@extends('layouts.public-app')

@section('title', 'Yayasan — SMK Amaliah 1 & 2')

@section('content')
    @include('PublicSide.partials.about-hero', [
        'active' => 'foundation',
        'title' => 'Yayasan Kami',
        'lead' => 'Yayasan Pusat Studi Pengembangan Islam Amaliyah Indonesia (YPSPIAI) — fondasi yang menaungi perjalanan pendidikan kami.',
        'showCta' => false,
    ])

    {{-- ===================== PROFIL YAYASAN ===================== --}}
    <section class="bg-white py-16 lg:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="fade-in-section">
                    <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">YPSPIAI</span>
                    <h2 class="mt-3 text-3xl lg:text-4xl font-bold text-[#282829] leading-tight">Mengenal Yayasan Kami</h2>
                    <div class="mt-4 w-20 h-1.5 rounded-full bg-[#63cd00]"></div>
                    <p class="mt-6 text-base lg:text-lg text-gray-600 leading-relaxed">
                        YPSPIAI didirikan sebagai pusat studi untuk mengembangkan pendidikan Islam yang bersifat
                        <span class="font-semibold text-[#282829]">"amaliyah"</span> — mengintegrasikan ilmu pengetahuan
                        dengan pengamalan nilai-nilai luhur dalam kehidupan sehari-hari, sesuai filosofi utama kami:
                        <span class="font-bold text-[#3f9b00]">Menyatu dalam Tauhid</span>.
                    </p>
                </div>

                <div class="fade-in-section grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @php
                        $facts = [
                            ['icon' => 'fa-graduation-cap', 'label' => 'Menaungi', 'value' => 'SMK & Kampus'],
                            ['icon' => 'fa-building-columns', 'label' => 'Pembina', 'value' => 'SMK Amaliah 1 & 2'],
                            ['icon' => 'fa-university', 'label' => 'Sinergi', 'value' => 'Universitas Djuanda'],
                            ['icon' => 'fa-book-quran', 'label' => 'Pondasi', 'value' => 'Pendidikan Bertauhid'],
                        ];
                    @endphp
                    @foreach($facts as $f)
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 hover:border-[#63cd00] hover:shadow-lg transition-all duration-300">
                            <div class="w-11 h-11 rounded-xl bg-[#eafad7] flex items-center justify-center">
                                <i class="fa-solid {{ $f['icon'] }} text-lg text-[#3f9b00]"></i>
                            </div>
                            <div class="mt-3 text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $f['label'] }}</div>
                            <div class="mt-0.5 font-bold text-[#282829]">{{ $f['value'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== PILAR YAYASAN ===================== --}}
    <section class="bg-gray-50 py-16 lg:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto fade-in-section">
                <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">Pilar Yayasan</span>
                <h2 class="mt-3 text-3xl lg:text-4xl font-bold text-[#282829]">Nilai yang Kami Pegang</h2>
                <p class="mt-4 text-gray-600 text-base lg:text-lg">Empat prinsip yang menjadi nafas seluruh unit pendidikan di bawah YPSPIAI.</p>
            </div>

            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
                @php
                    $vals = [
                        ['icon' => 'fa-building-columns', 'title' => 'Profil & Tujuan Utama', 'desc' => 'Pusat studi pengembangan pendidikan Islam aplikatif yang mengintegrasikan ilmu dengan pengamalan nilai luhur.'],
                        ['icon' => 'fa-graduation-cap', 'title' => 'Pembina Institusi Pendidikan', 'desc' => 'Menjadi pilar utama yang menaungi dan membina SMK Amaliah 1 & 2 serta Universitas Djuanda, menciptakan ekosistem pendidikan yang sinergis.'],
                        ['icon' => 'fa-book-quran', 'title' => 'Filosofi Bertauhid', 'desc' => 'Setiap aspek pendidikan berlandaskan nilai keimanan, membentuk lulusan yang kompeten secara akademis dan kokoh dalam karakter.'],
                        ['icon' => 'fa-award', 'title' => 'Komitmen pada Kualitas', 'desc' => 'Menjaga dan meningkatkan standar mutu melalui manajemen profesional, kurikulum relevan, dan layanan pendidikan prima.'],
                    ];
                @endphp
                @foreach($vals as $i => $v)
                    <div class="fade-in-section group flex gap-5 bg-white border border-gray-200 rounded-2xl p-7 hover:border-transparent hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 rounded-2xl bg-[#2D2D2D] flex items-center justify-center flex-shrink-0 group-hover:bg-[#63cd00] transition-colors duration-300">
                            <i class="fa-solid {{ $v['icon'] }} text-xl text-white"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-[#63cd00]">Pilar {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                            <h3 class="mt-1 font-bold text-[#282829] text-lg">{{ $v['title'] }}</h3>
                            <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $v['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== DETAIL YAYASAN ===================== --}}
    <section class="bg-white py-16 lg:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="fade-in-section lg:col-span-1">
                    <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">Detail Yayasan</span>
                    <h2 class="mt-3 text-3xl lg:text-4xl font-bold text-[#282829]">Informasi Lengkap</h2>
                    <div class="mt-4 w-20 h-1.5 rounded-full bg-[#63cd00]"></div>
                </div>
                <div class="lg:col-span-2 fade-in-section bg-gray-50 border border-gray-200 rounded-2xl p-8 lg:p-10">
                    @if(isset($foundationContent) && $foundationContent && trim(strip_tags($foundationContent->content)) !== '')
                        <div class="text-base lg:text-lg text-gray-600 leading-relaxed">
                            {!! \App\Support\HtmlSanitizer::clean($foundationContent->content) !!}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto w-16 h-16 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-file-lines text-2xl"></i>
                            </div>
                            <h3 class="mt-4 font-semibold text-gray-800">Konten Belum Tersedia</h3>
                            <p class="mt-1 text-sm text-gray-500">Informasi yayasan sedang dilengkapi oleh tim kami.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CTA ===================== --}}
    <section class="bg-[#282829] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-80 h-80 rounded-full opacity-20"
            style="background: radial-gradient(circle, #63cd00 0%, transparent 70%)"></div>
        <div class="relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center fade-in-section">
            <h2 class="text-3xl lg:text-4xl font-bold text-white">Lanjut Jelajahi Bagian Lain</h2>
            <p class="mt-3 text-white/75">Kenali sejarah, visi, dan jurusan yang kami miliki.</p>
            <div class="mt-7 flex flex-wrap justify-center gap-3">
                <a href="{{ route('public.about.history') }}"
                    class="inline-flex items-center gap-2 bg-[#63cd00] text-[#282829] font-semibold px-6 py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300">
                    <i class="fa-solid fa-landmark"></i> Lihat Sejarah
                </a>
                <a href="{{ route('public.majors.index') }}"
                    class="inline-flex items-center gap-2 bg-white text-[#282829] font-semibold px-6 py-3 rounded-full hover:bg-gray-100 hover:-translate-y-0.5 transition-all duration-300">
                    <i class="fa-solid fa-sitemap"></i> Jurusan
                </a>
            </div>
        </div>
    </section>
@endsection