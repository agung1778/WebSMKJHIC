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
                        <img src="{{ img_url($image->path, 1600, 900) }}" width="1600" height="900" alt="{{ $image->description ?? $image->filename }}"
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

        {{-- Overlay gradasi kontras tinggi agar teks selalu terbaca tajam --}}
        <div class="absolute inset-0"
            style="background: linear-gradient(100deg, rgba(20,20,22,0.92) 0%, rgba(20,20,22,0.78) 50%, rgba(20,20,22,0.55) 100%)"></div>

        {{-- Konten hero --}}
        <div class="relative z-10 h-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center">
            <span
                class="inline-flex items-center gap-2 w-fit text-[11px] sm:text-xs font-bold tracking-wider uppercase text-white bg-black/60 backdrop-blur border border-white/20 rounded-full px-3.5 sm:px-4 py-1 sm:py-1.5 mb-2.5 sm:mb-4 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-[#63cd00] shadow-sm shadow-[#63cd00]/80"></span>
                SMK Amaliah 1 & 2 — Ciawi · Bogor
            </span>
            <h1 class="text-white text-2xl sm:text-3xl lg:text-5xl font-extrabold leading-tight tracking-tight drop-shadow-sm">{{ $title }}</h1>
            <p class="mt-2.5 sm:mt-4 max-w-2xl text-gray-100 text-sm sm:text-base lg:text-lg leading-relaxed font-normal">{{ $lead }}</p>

            @if($showCta)
                <div class="mt-5 sm:mt-8 flex flex-wrap items-center gap-2.5 sm:gap-3">
                    <a href="#jelajahi"
                        class="inline-flex items-center gap-2 bg-[#63cd00] text-[#1c1c1d] font-bold text-xs sm:text-sm lg:text-base px-5 sm:px-6 py-2.5 sm:py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-md">
                        <i class="fa-solid fa-compass"></i> Jelajahi Tentang Kami
                    </a>
                    <a href="https://ppdb.smkamaliah.sch.id/login" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 bg-white text-[#1c1c1d] font-bold text-xs sm:text-sm lg:text-base px-5 sm:px-6 py-2.5 sm:py-3 rounded-full hover:bg-gray-100 hover:-translate-y-0.5 transition-all duration-300 shadow-md">
                        <i class="fa-solid fa-rocket text-[#449e00]"></i> Info SPMB
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Breadcrumb bar Responsif --}}
    <div style="background-color:#2D2D2D;">
        <div class="max-w-screen-xl min-h-12 py-2 mx-auto px-4 sm:px-6 lg:px-8 flex items-center overflow-x-auto scrollbar-hide">
            <nav aria-label="Breadcrumb" class="min-w-0">
                <ol class="inline-flex items-center gap-1.5 sm:gap-2.5 text-xs sm:text-sm whitespace-nowrap">
                    @foreach($crumbs as $i => $crumb)
                        <li class="flex items-center gap-1.5 sm:gap-2.5">
                            @if($i > 0)
                                <i class="fa-solid fa-chevron-right text-white/40 text-[10px]"></i>
                            @endif
                            @if(!$loop->last)
                                <a href="{{ $crumb['url'] }}"
                                    class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                    {{ $crumb['label'] }}
                                </a>
                            @else
                                <span class="inline-flex items-center font-medium text-[#63cd00]">
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