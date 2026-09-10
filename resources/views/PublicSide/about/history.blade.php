@extends('layouts.public-app')

@section('title', 'Sejarah — SMK Amaliah 1 & 2')

@section('content')
    @include('PublicSide.partials.about-hero', [
        'active' => 'history',
        'title' => 'Sejarah Sekolah',
        'lead' => 'Perjalanan kami dalam membentuk generasi yang cerdas, berkarakter, dan siap menghadapi tantangan masa depan.',
        'showCta' => false,
    ])

    {{-- ===================== TIMELINE ===================== --}}
    <section class="bg-white py-16 lg:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto fade-in-section">
                <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">Perjalanan Kami</span>
                <h2 class="mt-3 text-3xl lg:text-4xl font-bold text-[#282829]">Tonggak Perjalanan</h2>
                <p class="mt-4 text-gray-600 text-base lg:text-lg">Dari cita-cita sederhana hingga institusi yang terus berkembang.</p>
            </div>

            <div class="mt-14 relative mx-auto max-w-3xl">
                {{-- garis timeline --}}
                <div class="absolute left-5 lg:left-1/2 lg:-translate-x-1/2 top-0 bottom-0 w-0.5 bg-[#d9f2b8]"></div>

                @php
                    $milestones = [
                        ['year' => '2008', 'icon' => 'fa-landmark', 'title' => 'Fondasi & Pendirian', 'desc' => 'Berdiri resmi pada tahun 2008 di bawah naungan YPSPIAI dan pembinaan Universitas Djuanda (UNIDA) dengan komitmen Kualitas, Profesionalitas, dan Pelayanan Prima.'],
                        ['year' => 'Berkembang', 'icon' => 'fa-sitemap', 'title' => 'Perluasan Program Keahlian', 'desc' => 'Membuka 9 konsentrasi keahlian: TKJ, RPL, DKV, Animasi, MP, Akuntansi, LPS, Desain Busana, dan Bisnis Retail untuk menjawab kebutuhan industri.'],
                        ['year' => 'Kini', 'icon' => 'fa-hands-holding-circle', 'title' => 'Komitmen & Resiliensi', 'desc' => 'Terus berkembang menghadapi tantangan berkat kerja sama solid warga sekolah, kesabaran, dan keikhlasan hingga menjadi SMK Amaliah 1 & 2 hari ini.'],
                    ];
                @endphp
                @foreach($milestones as $i => $ms)
                    <div class="fade-in-section relative pl-16 lg:pl-0 mb-12 last:mb-0 {{ $i % 2 === 0 ? 'lg:pr-[calc(50%+2.5rem)] lg:text-right' : 'lg:pl-[calc(50%+2.5rem)]' }}">
                        {{-- titik timeline --}}
                        <span class="absolute left-5 lg:left-1/2 lg:-translate-x-1/2 top-2 w-5 h-5 -ml-2.5 lg:ml-0 rounded-full bg-white border-4 border-[#63cd00] shadow"></span>

                        <div class="lg:inline-block bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <div class="flex items-center gap-2 {{ $i % 2 === 0 ? 'lg:justify-end' : '' }}">
                                <span class="bg-[#eafad7] text-[#3f9b00] text-xs font-bold rounded-full px-3 py-1"><i class="fa-solid {{ $ms['icon'] }} mr-1.5"></i>{{ $ms['year'] }}</span>
                            </div>
                            <h3 class="mt-3 font-bold text-[#282829] text-lg">{{ $ms['title'] }}</h3>
                            <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $ms['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== DETAIL SEJARAH ===================== --}}
    <section class="bg-gray-50 py-16 lg:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="fade-in-section lg:col-span-1">
                    <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">Detail Sejarah</span>
                    <h2 class="mt-3 text-3xl lg:text-4xl font-bold text-[#282829]">Sejarah Selengkapnya</h2>
                    <div class="mt-4 w-20 h-1.5 rounded-full bg-[#63cd00]"></div>
                </div>
                <div class="lg:col-span-2 fade-in-section bg-white border border-gray-200 rounded-2xl p-8 lg:p-10">
                    @if(isset($historyContent) && $historyContent && trim(strip_tags($historyContent->content)) !== '')
                        <div class="text-base lg:text-lg text-gray-600 leading-relaxed">
                            {!! \App\Support\HtmlSanitizer::clean($historyContent->content) !!}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-file-lines text-2xl"></i>
                            </div>
                            <h3 class="mt-4 font-semibold text-gray-800">Konten Belum Tersedia</h3>
                            <p class="mt-1 text-sm text-gray-500">Detail sejarah sedang dilengkapi oleh tim kami.</p>
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
            <p class="mt-3 text-white/75">Kenali visi, yayasan, dan jurusan yang kami miliki.</p>
            <div class="mt-7 flex flex-wrap justify-center gap-3">
                <a href="{{ route('public.about.vision') }}"
                    class="inline-flex items-center gap-2 bg-[#63cd00] text-[#282829] font-semibold px-6 py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300">
                    <i class="fa-solid fa-bullseye"></i> Visi & Misi
                </a>
                <a href="{{ route('public.majors.index') }}"
                    class="inline-flex items-center gap-2 bg-white text-[#282829] font-semibold px-6 py-3 rounded-full hover:bg-gray-100 hover:-translate-y-0.5 transition-all duration-300">
                    <i class="fa-solid fa-sitemap"></i> Jurusan
                </a>
            </div>
        </div>
    </section>
@endsection