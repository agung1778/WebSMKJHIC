@extends('layouts.public-app')

@section('title', 'Tentang Kami — SMK Amaliah 1 & 2')

@section('content')
    @include('PublicSide.partials.about-hero', [
        'active' => 'index',
        'title' => 'Tentang Kami',
        'lead' => 'Mengenal lebih dekat SMK Amaliah 1 & 2 Ciawi — sekolah menengah kejuruan yang menyatu dalam Tauhid dan berorientasi pada kesiapan kerja.',
        'showCta' => true,
    ])

    {{-- ===================== STATISTIK SINGKAT ===================== --}}
    <section class="bg-white">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            @php
                $stats = $stats ?? ['founded' => 2008, 'majors' => 0, 'teachers' => 0, 'facilities' => 0, 'programs' => 0];
                $statItems = [
                    ['value' => $stats['founded'], 'suffix' => '', 'label' => 'Tahun Berdiri', 'icon' => 'fa-landmark'],
                    ['value' => $stats['majors'], 'suffix' => '', 'label' => 'Kompetensi Keahlian', 'icon' => 'fa-sitemap'],
                    ['value' => $stats['teachers'], 'suffix' => '+', 'label' => 'Pendidik & Tenaga Kependidikan', 'icon' => 'fa-chalkboard-user'],
                    ['value' => $stats['facilities'], 'suffix' => '', 'label' => 'Fasilitas Penunjang', 'icon' => 'fa-school'],
                    ['value' => $stats['programs'], 'suffix' => '', 'label' => 'Program Sekolah', 'icon' => 'fa-layer-group'],
                ];
            @endphp
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                @foreach($statItems as $item)
                    <div class="fade-in-section group bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 text-center shadow-xs hover:border-[#63cd00] hover:shadow-lg hover:-translate-y-1 transition-all duration-300 {{ $loop->last ? 'col-span-2 sm:col-span-1' : '' }}">
                        <div class="mx-auto w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#eefde2] border border-[#63cd00]/30 flex items-center justify-center shadow-xs group-hover:bg-[#63cd00] group-hover:border-[#63cd00] transition-colors duration-300">
                            <i class="fa-solid {{ $item['icon'] }} text-[#2e7000] group-hover:text-white transition-colors duration-300 text-base sm:text-lg"></i>
                        </div>
                        <div class="mt-2.5 sm:mt-3 text-2xl sm:text-3xl font-extrabold text-[#1c1c1d] tracking-tight">
                            {{ $item['value'] }}<span class="text-[#3b8206]">{{ $item['suffix'] }}</span>
                        </div>
                        <div class="mt-1 text-xs sm:text-sm font-semibold text-gray-700 leading-snug">{{ $item['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== TENTANG / INTRO ===================== --}}
    <section class="bg-gray-50">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12 items-center">
                <div class="fade-in-section">
                    <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">About SMK Amaliah</span>
                    <h2 class="mt-2.5 sm:mt-3 text-2xl sm:text-3xl lg:text-4xl font-bold text-[#282829] leading-tight">
                        Mempersiapkan Generasi Siap Kerja
                    </h2>
                    <div class="mt-3 sm:mt-4 w-16 sm:w-20 h-1.5 rounded-full bg-[#63cd00]"></div>
                    <p class="mt-4 sm:mt-6 text-sm sm:text-base lg:text-lg text-gray-600 leading-relaxed">
                        Selain memberikan fondasi akademis yang kuat, kami berfokus pada pembentukan keahlian praktis
                        yang relevan dengan kebutuhan industri, sehingga lulusan kami dapat langsung berkontribusi
                        secara profesional.
                    </p>

                    @if(isset($aboutContent) && $aboutContent && trim(strip_tags($aboutContent->content)) !== '')
                        <div class="mt-4 sm:mt-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                            {!! \App\Support\HtmlSanitizer::clean($aboutContent->content) !!}
                        </div>
                    @endif

                    <div class="mt-6 sm:mt-8 flex flex-wrap gap-2.5 sm:gap-3">
                        <a href="{{ route('public.about.vision') }}"
                            class="inline-flex items-center gap-2 bg-[#282829] text-white text-xs sm:text-sm font-semibold px-4 sm:px-5 py-2.5 rounded-full hover:bg-black hover:-translate-y-0.5 transition-all duration-300">
                            <i class="fa-solid fa-bullseye"></i> Visi & Misi
                        </a>
                        <a href="{{ route('public.about.history') }}"
                            class="inline-flex items-center gap-2 border-2 border-[#282829] text-[#282829] text-xs sm:text-sm font-semibold px-4 sm:px-5 py-2.5 rounded-full hover:bg-[#282829] hover:text-white transition-all duration-300">
                            <i class="fa-solid fa-arrow-right"></i> Lihat Sejarah
                        </a>
                    </div>
                </div>

                {{-- Panel visual (quote) --}}
                <div class="fade-in-section relative pb-2 sm:pb-6 lg:pb-0">
                    <div class="relative bg-[#282829] rounded-2xl sm:rounded-3xl p-5 sm:p-8 lg:p-10 pb-8 sm:pb-12 lg:pb-14 overflow-hidden shadow-2xl">
                        <div class="absolute top-0 right-0 w-44 sm:w-56 h-44 sm:h-56 rounded-full opacity-20"
                            style="background: radial-gradient(circle, #63cd00 0%, transparent 70%)"></div>
                        <i class="fa-solid fa-quote-left text-2xl sm:text-4xl text-[#63cd00]"></i>
                        <p class="mt-3 sm:mt-6 text-base sm:text-xl lg:text-2xl text-white font-medium leading-relaxed">
                            "Menjadi Sekolah Menengah Kejuruan Berkualitas Yang Menyatu Dalam Tauhid."
                        </p>
                        <div class="mt-5 sm:mt-6 flex items-center gap-3">
                            <span class="w-9 h-9 sm:w-10 sm:h-10 rounded-full inline-flex items-center justify-center text-white text-sm sm:text-lg font-bold flex-shrink-0"
                                style="background: linear-gradient(135deg,#63cd00,#59E300)">
                                SA
                            </span>
                            <div class="min-w-0">
                                <div class="text-white font-semibold text-xs sm:text-base truncate">Visi SMK Amaliah</div>
                                <div class="text-gray-400 text-[11px] sm:text-sm truncate">Titik tuju seluruh ekosistem sekolah</div>
                            </div>
                        </div>
                    </div>

                    <div class="hidden lg:block absolute -bottom-4 right-8 bg-[#63cd00] text-[#282829] rounded-2xl px-6 py-3.5 shadow-xl">
                        <div class="text-xl font-bold">Tauhid</div>
                        <div class="text-xs font-semibold">Is Our Fundament</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== 4 PILAR ===================== --}}
    <section class="bg-white py-12 sm:py-16 lg:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto fade-in-section">
                <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">Pilar Pendidikan</span>
                <h2 class="mt-2.5 sm:mt-3 text-2xl sm:text-3xl lg:text-4xl font-bold text-[#282829]">Apa yang Membuat Kami Berbeda</h2>
                <p class="mt-3 sm:mt-4 text-gray-600 text-sm sm:text-base lg:text-lg">Empat pilar yang menjadi fondasi cara kami mendidik dan membina siswa.</p>
            </div>

            <div class="mt-8 sm:mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @php
                    $pillars = [
                        ['icon' => 'fa-industry', 'title' => 'Kurikulum Berbasis Industri', 'desc' => 'Materi dirancang bersama praktisi industri agar relevan dengan dunia kerja nyata.'],
                        ['icon' => 'fa-tools', 'title' => 'Pembelajaran Praktik', 'desc' => 'Pengalaman langsung melalui workshop, laboratorium, dan proyek nyata.'],
                        ['icon' => 'fa-certificate', 'title' => 'Sertifikasi Kompetensi', 'desc' => 'Lulusan dibekali sertifikat keahlian yang diakui nasional dan industri.'],
                        ['icon' => 'fa-chalkboard-teacher', 'title' => 'Pengajar Profesional', 'desc' => 'Belajar dari guru dan instruktur berpengalaman di bidangnya masing-masing.'],
                    ];
                @endphp
                @foreach($pillars as $p)
                    <div class="fade-in-section group relative bg-white border border-gray-200 rounded-2xl p-5 sm:p-7 shadow-xs hover:border-transparent hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-x-0 top-0 h-1.5 bg-[#63cd00] scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#eefde2] border border-[#63cd00]/30 flex items-center justify-center group-hover:bg-[#63cd00] transition-colors duration-300">
                            <i class="fa-solid {{ $p['icon'] }} text-lg sm:text-xl text-[#2e7000] group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <h3 class="mt-4 sm:mt-5 text-base sm:text-lg font-bold text-[#1c1c1d]">{{ $p['title'] }}</h3>
                        <p class="mt-2 text-xs sm:text-sm leading-relaxed text-gray-700 font-normal">{{ $p['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== JELAJAHI TENTANG KAMI ===================== --}}
    <section id="jelajahi" class="bg-gray-50 py-12 sm:py-16 lg:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto fade-in-section">
                <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">Jelajahi</span>
                <h2 class="mt-2.5 sm:mt-3 text-2xl sm:text-3xl lg:text-4xl font-bold text-[#1c1c1d]">Kenali Lebih Dalam</h2>
                <p class="mt-3 sm:mt-4 text-gray-700 text-sm sm:text-base lg:text-lg">Setiap aspek yang membangun institusi kami, ada di dalam satu genggaman.</p>
            </div>

            <div class="mt-8 sm:mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($aboutLinks as $item)
                    <a href="{{ url($item['url']) }}"
                        class="fade-in-section group bg-white border border-gray-200 rounded-2xl p-4 sm:p-6 flex items-center gap-3.5 sm:gap-4 shadow-xs hover:border-transparent hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#2D2D2D] flex items-center justify-center flex-shrink-0 group-hover:bg-[#63cd00] transition-colors duration-300 shadow-xs">
                            <i class="fa-solid {{ $item['icon'] }} text-xl sm:text-2xl text-white"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base sm:text-lg font-bold text-[#1c1c1d] truncate">{{ $item['title'] }}</h3>
                            <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-gray-600 leading-snug line-clamp-2">{{ $item['description'] }}</p>
                        </div>
                        <i class="fa-solid fa-arrow-right-long text-gray-400 group-hover:text-[#63cd00] group-hover:translate-x-1 transition-all duration-300 text-sm sm:text-base flex-shrink-0"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== VIDEO ===================== --}}
    <section class="bg-white py-12 sm:py-16 lg:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto fade-in-section">
                <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">Discover Our Story</span>
                <h2 class="mt-2.5 sm:mt-3 text-2xl sm:text-3xl lg:text-4xl font-bold text-[#282829]">Lihat Sekilas Perjalanan Kami</h2>
                <p class="mt-3 sm:mt-4 text-gray-600 text-sm sm:text-base lg:text-lg">Tonton video berikut untuk mengenal nilai, misi, dan orang-orang di balik kesuksesan kami.</p>
            </div>

            <div x-data="{ playing: false }" class="mt-12 max-w-4xl mx-auto">
                <div class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl ring-1 ring-black/10 bg-black" style="padding-top:56.25%;">
                    <div x-show="!playing" @click="playing = true" class="absolute inset-0 cursor-pointer group">
                        <img src="{{ asset('assets/image/DroneView.jpg') }}"
                            alt="Video Profil SMK Amaliah 1 & 2"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/40 group-hover:bg-black/25 transition-colors"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-[#63cd00] text-[#282829] flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-play text-xl sm:text-2xl ml-1"></i>
                            </div>
                            <span class="mt-3 text-xs sm:text-sm font-semibold text-white bg-black/60 backdrop-blur px-4 py-1.5 rounded-full border border-white/10">Tonton Video Profil</span>
                        </div>
                    </div>
                    <template x-if="playing">
                        <iframe class="absolute inset-0 w-full h-full"
                            src="https://www.youtube-nocookie.com/embed/STOhZZmY6Co?autoplay=1&rel=0"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                    </template>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CTA ===================== --}}
    <section class="bg-[#282829] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full opacity-20"
            style="background: radial-gradient(circle, #63cd00 0%, transparent 70%)"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 rounded-full opacity-10"
            style="background: radial-gradient(circle, #59E300 0%, transparent 70%)"></div>

        <div class="relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20 text-center fade-in-section">
            <h2 class="text-3xl lg:text-4xl font-bold text-white">Siap Menjadi Bagian dari SMK Amaliah?</h2>
            <p class="mt-4 max-w-2xl mx-auto text-white/75 text-base lg:text-lg">Bergabunglah bersama kami dan wujudkan masa depan yang menyatu dalam Tauhid, siap kerja, dan berkarakter.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="https://ppdb.smkamaliah.sch.id/login" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 bg-[#63cd00] text-[#282829] font-semibold px-7 py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/30">
                    <i class="fa-solid fa-rocket"></i> Daftar via SPMB
                </a>
                <a href="https://wa.me/6285649011449" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 bg-white text-[#282829] font-semibold px-7 py-3 rounded-full hover:bg-gray-100 hover:-translate-y-0.5 transition-all duration-300">
                    <i class="fab fa-whatsapp"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </section>
@endsection