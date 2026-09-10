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
                    ['value' => $stats['founded'], 'suffix' => '', 'label' => 'Tahun Berdiri', 'icon' => 'fa-landmark', 'url' => route('public.about.history')],
                    ['value' => $stats['majors'], 'suffix' => '', 'label' => 'Kompetensi Keahlian', 'icon' => 'fa-sitemap', 'url' => route('public.majors.index')],
                    ['value' => $stats['teachers'], 'suffix' => '+', 'label' => 'Pendidik & Tenaga Kependidikan', 'icon' => 'fa-chalkboard-user', 'url' => route('public.teachers.index')],
                    ['value' => $stats['facilities'], 'suffix' => '', 'label' => 'Fasilitas Penunjang', 'icon' => 'fa-school', 'url' => route('public.facilities.index')],
                    ['value' => $stats['programs'], 'suffix' => '', 'label' => 'Program Sekolah', 'icon' => 'fa-layer-group', 'url' => route('public.program.index')],
                ];
            @endphp
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($statItems as $item)
                    <a href="{{ $item['url'] }}"
                        class="fade-in-section group bg-gray-50 border border-gray-200 rounded-2xl p-5 text-center hover:border-[#63cd00] hover:shadow-lg hover:-translate-y-1 transition-all duration-300 block">
                        <div class="mx-auto w-11 h-11 rounded-full bg-white border border-gray-200 flex items-center justify-center shadow-sm group-hover:bg-[#63cd00] group-hover:border-[#63cd00] transition-colors duration-300">
                            <i class="fa-solid {{ $item['icon'] }} text-[#63cd00] group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <div class="mt-3 text-2xl lg:text-3xl font-bold text-[#282829]">
                            {{ $item['value'] }}<span class="text-[#63cd00]">{{ $item['suffix'] }}</span>
                        </div>
                        <div class="mt-1 text-[11px] lg:text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $item['label'] }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== TENTANG / INTRO ===================== --}}
    <section class="bg-gray-50">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="fade-in-section">
                    <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">About SMK Amaliah</span>
                    <h2 class="mt-3 text-3xl lg:text-4xl font-bold text-[#282829] leading-tight">
                        Mempersiapkan Generasi Siap Kerja
                    </h2>
                    <div class="mt-4 w-20 h-1.5 rounded-full bg-[#63cd00]"></div>
                    <p class="mt-6 text-base lg:text-lg text-gray-600 leading-relaxed">
                        Selain memberikan fondasi akademis yang kuat, kami berfokus pada pembentukan keahlian praktis
                        yang relevan dengan kebutuhan industri, sehingga lulusan kami dapat langsung berkontribusi
                        secara profesional.
                    </p>

                    @if(isset($aboutContent) && $aboutContent && trim(strip_tags($aboutContent->content)) !== '')
                        <div class="mt-6 text-base text-gray-600 leading-relaxed">
                            {!! \App\Support\HtmlSanitizer::clean($aboutContent->content) !!}
                        </div>
                    @endif

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('public.about.vision') }}"
                            class="inline-flex items-center gap-2 bg-[#282829] text-white text-sm font-semibold px-5 py-2.5 rounded-full hover:bg-black hover:-translate-y-0.5 transition-all duration-300">
                            <i class="fa-solid fa-bullseye"></i> Visi & Misi
                        </a>
                        <a href="{{ route('public.about.history') }}"
                            class="inline-flex items-center gap-2 border-2 border-[#282829] text-[#282829] text-sm font-semibold px-5 py-2.5 rounded-full hover:bg-[#282829] hover:text-white transition-all duration-300">
                            <i class="fa-solid fa-arrow-right"></i> Lihat Sejarah
                        </a>
                    </div>
                </div>

                {{-- Panel visual (quote) --}}
                <div class="fade-in-section relative">
                    <div class="relative bg-[#282829] rounded-3xl p-8 lg:p-10 overflow-hidden shadow-2xl">
                        <div class="absolute top-0 right-0 w-56 h-56 rounded-full opacity-20"
                            style="background: radial-gradient(circle, #63cd00 0%, transparent 70%)"></div>
                        <i class="fa-solid fa-quote-left text-4xl text-[#63cd00]"></i>
                        <p class="mt-6 text-xl lg:text-2xl text-white font-medium leading-relaxed">
                            "Menjadi Sekolah Menengah Kejuruan Berkualitas Yang Menyatu Dalam Tauhid."
                        </p>
                        <div class="mt-6 flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full inline-flex items-center justify-center text-white text-lg font-bold"
                                style="background: linear-gradient(135deg,#63cd00,#59E300)">
                                SA
                            </span>
                            <div>
                                <div class="text-white font-semibold">Visi SMK Amaliah</div>
                                <div class="text-gray-400 text-sm">Titik tuju seluruh ekosistem sekolah</div>
                            </div>
                        </div>
                    </div>

                    <div class="hidden lg:block absolute -bottom-6 -left-6 bg-[#63cd00] text-[#282829] rounded-2xl px-6 py-4 shadow-xl">
                        <div class="text-2xl font-bold">Tauhid</div>
                        <div class="text-sm font-semibold">Is Our Fundament</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== 4 PILAR ===================== --}}
    <section class="bg-white py-16 lg:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto fade-in-section">
                <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">Pilar Pendidikan</span>
                <h2 class="mt-3 text-3xl lg:text-4xl font-bold text-[#282829]">Apa yang Membuat Kami Berbeda</h2>
                <p class="mt-4 text-gray-600 text-base lg:text-lg">Empat pilar yang menjadi fondasi cara kami mendidik dan membina siswa.</p>
            </div>

            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $pillars = [
                        ['icon' => 'fa-industry', 'title' => 'Kurikulum Berbasis Industri', 'desc' => 'Materi dirancang bersama praktisi industri agar relevan dengan dunia kerja nyata.'],
                        ['icon' => 'fa-tools', 'title' => 'Pembelajaran Praktik', 'desc' => 'Pengalaman langsung melalui workshop, laboratorium, dan proyek nyata.'],
                        ['icon' => 'fa-certificate', 'title' => 'Sertifikasi Kompetensi', 'desc' => 'Lulusan dibekali sertifikat keahlian yang diakui nasional dan industri.'],
                        ['icon' => 'fa-chalkboard-teacher', 'title' => 'Pengajar Profesional', 'desc' => 'Belajar dari guru dan instruktur berpengalaman di bidangnya masing-masing.'],
                    ];
                @endphp
                @foreach($pillars as $p)
                    <div class="fade-in-section group relative bg-white border border-gray-200 rounded-2xl p-7 hover:border-transparent hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-x-0 top-0 h-1 bg-[#63cd00] scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                        <div class="w-12 h-12 rounded-xl bg-[#eafad7] flex items-center justify-center group-hover:bg-[#63cd00] transition-colors duration-300">
                            <i class="fa-solid {{ $p['icon'] }} text-xl text-[#3f9b00] group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <h3 class="mt-5 text-lg font-bold text-[#282829]">{{ $p['title'] }}</h3>
                        <p class="mt-2.5 text-sm leading-relaxed text-gray-600">{{ $p['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== JELAJAHI TENTANG KAMI ===================== --}}
    <section id="jelajahi" class="bg-gray-50 py-16 lg:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto fade-in-section">
                <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">Jelajahi</span>
                <h2 class="mt-3 text-3xl lg:text-4xl font-bold text-[#282829]">Kenali Lebih Dalam</h2>
                <p class="mt-4 text-gray-600 text-base lg:text-lg">Setiap aspek yang membangun institusi kami, ada di dalam satu genggaman.</p>
            </div>

            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($aboutLinks as $item)
                    <a href="{{ url($item['url']) }}"
                        class="fade-in-section group bg-white border border-gray-200 rounded-2xl p-6 flex items-center gap-4 hover:border-transparent hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">
                        <div class="w-14 h-14 rounded-2xl bg-[#2D2D2D] flex items-center justify-center flex-shrink-0 group-hover:bg-[#63cd00] transition-colors duration-300">
                            <i class="fa-solid {{ $item['icon'] }} text-2xl text-white"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-[#282829]">{{ $item['title'] }}</h3>
                            <p class="mt-1 text-sm text-gray-500 leading-snug">{{ $item['description'] }}</p>
                        </div>
                        <i class="fa-solid fa-arrow-right-long text-gray-300 group-hover:text-[#63cd00] group-hover:translate-x-1 transition-all duration-300"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== VIDEO ===================== --}}
    <section class="bg-white py-16 lg:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto fade-in-section">
                <span class="inline-block text-xs font-bold tracking-widest text-[#63cd00] uppercase">Discover Our Story</span>
                <h2 class="mt-3 text-3xl lg:text-4xl font-bold text-[#282829]">Lihat Sekilas Perjalanan Kami</h2>
                <p class="mt-4 text-gray-600 text-base lg:text-lg">Tonton video berikut untuk mengenal nilai, misi, dan orang-orang di balik kesuksesan kami.</p>
            </div>

            <div class="mt-12 max-w-4xl mx-auto">
                <div class="fade-in-section relative w-full rounded-3xl overflow-hidden shadow-2xl ring-1 ring-black/10" style="padding-top:56.25%;">
                    <iframe class="absolute inset-0 w-full h-full" loading="lazy"
                        src="https://www.youtube-nocookie.com/embed/STOhZZmY6Co?si=34QAmdyIwXbAXs-7"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
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