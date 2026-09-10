{{--
    Hero umum untuk halaman About (induk dari Partial).
    Params:
      $active  : 'index' | 'vision' | 'history' | 'foundation'
      $title   : judul besar di hero
      $lead    : kalimat pengantar
      $showCta : tampilkan tombol CTA (bool)
--}}
@php
    $crumbs = [
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'About', 'url' => route('public.about.index')],
    ];
    $current = match ($active) {
        'vision' => 'Visi & Misi',
        'history' => 'Sejarah',
        'foundation' => 'Yayasan',
        default => 'Tentang Kami',
    };
    $hasHeroImages = isset($mainImages) && $mainImages->isNotEmpty();
    $slideCount = $hasHeroImages ? $mainImages->count() : 0;
@endphp

<section class="relative bg-[#282829]">
    <div class="relative h-[360px] lg:h-[440px] overflow-hidden">
        @if($hasHeroImages && $slideCount > 0)
            <div x-data="{ active: 0 }"
                x-init="setInterval(() => { if ({{ $slideCount }} > 1) active = (active + 1) % {{ $slideCount }} }, 5000)">
                @foreach($mainImages as $image)
                    <div x-show="active === {{ $loop->index }}" x-cloak
                        x-transition:enter="transition ease-out duration-1000"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-1000"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="absolute inset-0">
                        <img src="{{ Storage::url($image->path) }}" alt="{{ $image->description ?? $image->filename }}"
                            class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
        @else
            <div class="absolute inset-0">
                <div class="absolute -top-24 -right-16 w-96 h-96 rounded-full opacity-25"
                    style="background: radial-gradient(circle, #63cd00 0%, transparent 70%)"></div>
                <div class="absolute bottom-0 left-0 w-full h-2/3"
                    style="background: radial-gradient(ellipse at bottom left, rgba(99,205,0,.18) 0%, transparent 60%)"></div>
            </div>
        @endif

        {{-- Overlay gradasi agar teks terbaca --}}
        <div class="absolute inset-0"
            style="background: linear-gradient(100deg, rgba(40,40,41,.86) 0%, rgba(40,40,41,.55) 45%, rgba(40,40,41,.15) 100%)"></div>

        {{-- Konten hero --}}
        <div class="relative z-10 h-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center">
            <span
                class="inline-flex items-center gap-2 w-fit text-[11px] lg:text-xs font-semibold tracking-widest uppercase text-[#d9ffb3] bg-white/10 backdrop-blur border border-white/15 rounded-full px-4 py-1.5 mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[#63cd00]"></span>
                SMK Amaliah 1 &amp; 2 — Ciawi · Bogor
            </span>
            <h1 class="text-white text-3xl lg:text-5xl font-bold leading-tight">{{ $title }}</h1>
            <p class="mt-4 max-w-2xl text-white/85 text-base lg:text-lg leading-relaxed">{{ $lead }}</p>

            @if($showCta)
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="#jelajahi"
                        class="inline-flex items-center gap-2 bg-[#63cd00] text-[#282829] font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-compass"></i> Jelajahi Tentang Kami
                    </a>
                    <a href="https://ppdb.smkamaliah.sch.id/login" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 bg-white text-[#282829] font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-gray-100 hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-rocket"></i> Info SPMB
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Breadcrumb bar --}}
    <div style="background-color:#2D2D2D;">
        <div class="max-w-screen-xl h-14 mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
            <nav aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 md:space-x-3 text-sm">
                    @foreach($crumbs as $i => $crumb)
                        <li @if($i > 0) class="flex items-center" @endif>
                            @if($i > 0)
                                <i class="fa-solid fa-chevron-right text-white/40 text-xs"></i>
                            @endif
                            @if(!$loop->last)
                                <a href="{{ $crumb['url'] }}"
                                    class="inline-flex items-center ml-0 {{ $i > 0 ? 'md:ml-3' : '' }} font-medium text-gray-300 hover:text-white transition-colors">
                                    {{ $crumb['label'] }}
                                </a>
                            @else
                                <span class="inline-flex items-center ml-0 {{ $i > 0 ? 'md:ml-3' : '' }} font-medium text-[#63cd00]">
                                    {{ $current }}
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>
</section>