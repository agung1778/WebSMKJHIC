@extends('layouts.public-app')

@section('title', ($partner->name ?? 'Mitra Industri') . ' | SMK Amaliah 1 & 2')
@section('description', Str::limit(strip_tags($partner->description ?? ''), 170))

@php
    $amaliahGreen = '#63cd00';
    $amaliahDark = '#282829';
    $hasImages = isset($partnersImages) && $partnersImages->isNotEmpty();
    $slideCount = $hasImages ? $partnersImages->count() : 0;
@endphp

@section('content')

    {{-- ============================ HERO ============================ --}}
    <section class="relative bg-[#282829]">
        <div class="relative h-[340px] lg:h-[420px] overflow-hidden">
            @if($hasImages && $slideCount > 0)
                <div x-data="{ active: 0 }"
                    x-init="setInterval(() => { if ({{ $slideCount }} > 1) active = (active + 1) % {{ $slideCount }} }, 5000)">
                    @foreach($partnersImages as $image)
                        <div x-show="active === {{ $loop->index }}" x-cloak
                            x-transition:enter="transition ease-out duration-1000"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-1000"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="absolute inset-0">
                            <img src="{{ img_url($image->path, 1600, 900) }}" width="1600" height="900"
                                alt="{{ $image->description ?? $image->filename }}"
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

            <div class="absolute inset-0"
                style="background: linear-gradient(100deg, rgba(40,40,41,.87) 0%, rgba(40,40,41,.55) 45%, rgba(40,40,41,.18) 100%)"></div>

            <div class="relative z-10 h-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center">
                <span
                    class="inline-flex items-center gap-2 w-fit text-[11px] lg:text-xs font-semibold tracking-widest uppercase text-[#d9ffb3] bg-white/10 backdrop-blur border border-white/15 rounded-full px-4 py-1.5 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#63cd00]"></span>
                    Detail Mitra — Industry Partners
                </span>
                <h1 class="text-white text-3xl lg:text-5xl font-bold leading-tight">{{ $partner->name }}</h1>
                <p class="mt-4 max-w-2xl text-white/85 text-base lg:text-lg leading-relaxed">
                    @if($partner->sector)
                        <span class="inline-flex items-center gap-2"><i class="fas fa-briefcase"></i> {{ $partner->sector }}</span>
                    @endif
                    @if($partner->city)
                        <span class="inline-flex items-center gap-2 ml-4"><i class="fas fa-map-marker-alt"></i> {{ $partner->city }}</span>
                    @endif
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ route('public.partners.index') }}"
                        class="inline-flex items-center gap-2 bg-[#63cd00] text-[#282829] font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-[#59E300] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                        <i class="fas fa-arrow-left"></i> Semua Mitra
                    </a>
                    @if($partner->company_contact)
                        <a href="https://wa.me/6285649011449?text={{ rawurlencode('Halo, saya ingin bertanya perihal kerja sama dengan ' . $partner->name . '.') }}"
                            target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white font-semibold text-sm lg:text-base px-6 py-3 rounded-full hover:bg-white hover:text-[#282829] hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/20">
                            <i class="fas fa-comments"></i> Konsultasi Kerja Sama
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <div style="background-color:#2D2D2D;">
            <div class="max-w-screen-xl h-14 mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                <nav aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2 md:space-x-3 text-sm">
                        <li class="flex items-center">
                            <a href="/" class="inline-flex items-center font-medium text-gray-300 hover:text-white transition-colors">
                                Home
                            </a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-chevron-right text-white/40 text-xs"></i>
                            <a href="{{ route('public.partners.index') }}"
                                class="inline-flex items-center ml-0 md:ml-3 font-medium text-gray-300 hover:text-white transition-colors">
                                Industry Partners
                            </a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-chevron-right text-white/40 text-xs"></i>
                            <span class="inline-flex items-center ml-0 md:ml-3 font-medium text-[#63cd00] truncate max-w-xs">{{ $partner->name }}</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ============================ KONTEN DETAIL ============================ --}}
    <section class="bg-gray-50 py-16 sm:py-24">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="lg:grid lg:grid-cols-3 lg:gap-x-12">

                {{-- KOLOM KIRI: KONTEN UTAMA --}}
                <div class="lg:col-span-2">
                    <div class="fade-in-section bg-white border border-gray-200 rounded-2xl shadow-lg p-6 sm:p-8">

                        {{-- Header: Logo + Nama + Badge --}}
                        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                            <div
                                class="flex-shrink-0 mx-auto sm:mx-0 h-32 w-32 bg-gray-50 rounded-2xl border border-gray-200 flex items-center justify-center p-4 shadow-sm">
                                @if($partner->logo)
                                    <img src="{{ img_url($partner->logo, 160, 90) }}" width="160" height="90" loading="lazy" alt="Logo {{ $partner->name }}"
                                        class="max-h-full w-auto object-contain">
                                @else
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <i class="fas fa-building text-4xl"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-wider mt-1">Logo</span>
                                    </div>
                                @endif
                            </div>
                            <div class="text-center sm:text-left flex-1">
                                <h2 class="text-2xl sm:text-3xl font-extrabold leading-tight" style="color: {{ $amaliahDark }};">
                                    {{ $partner->name }}
                                </h2>
                                <div class="mt-3 flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                    @if($partner->sector)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#3E9B00] bg-[#63cd00]/10 border border-[#63cd00]/30 px-3 py-1.5 rounded-full">
                                            <i class="fas fa-briefcase"></i> {{ $partner->sector }}
                                        </span>
                                    @endif
                                    @if($partner->city)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 bg-gray-100 border border-gray-200 px-3 py-1.5 rounded-full">
                                            <i class="fas fa-map-marker-alt" style="color: {{ $amaliahGreen }};"></i> {{ $partner->city }}
                                        </span>
                                    @endif
                                    @if($partner->partnership_date)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 bg-gray-100 border border-gray-200 px-3 py-1.5 rounded-full">
                                            <i class="fas fa-calendar-alt" style="color: {{ $amaliahGreen }};"></i> Sejak {{ \Carbon\Carbon::parse($partner->partnership_date)->translatedFormat('M Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-200 my-8">

                        {{-- Tentang Mitra --}}
                        <div>
                            <h3 class="text-xl font-bold text-[#282829] mb-4">Tentang Mitra</h3>
                            <div class="text-gray-600 leading-relaxed">
                                {!! nl2br(e($partner->description ?? '')) ?: '<p class="text-gray-400 italic">Belum ada deskripsi dari mitra ini.</p>' !!}
                            </div>
                        </div>

                        {{-- Info Detail --}}
                        @if($partner->city || $partner->company_contact || $partner->partnership_date)
                            <hr class="border-gray-200 my-8">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
                                @if($partner->city)
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#63cd00]/10 text-[#63cd00] flex items-center justify-center">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Lokasi</p>
                                            <p class="font-semibold text-[#282829] mt-0.5">{{ $partner->city }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($partner->sector)
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#63cd00]/10 text-[#63cd00] flex items-center justify-center">
                                            <i class="fas fa-briefcase"></i>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Sektor Usaha</p>
                                            <p class="font-semibold text-[#282829] mt-0.5">{{ $partner->sector }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($partner->company_contact)
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#63cd00]/10 text-[#63cd00] flex items-center justify-center">
                                            <i class="fas fa-phone-alt"></i>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Kontak</p>
                                            <p class="font-semibold text-[#282829] mt-0.5 break-all">{{ $partner->company_contact }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($partner->partnership_date)
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#63cd00]/10 text-[#63cd00] flex items-center justify-center">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Bergabung Sejak</p>
                                            <p class="font-semibold text-[#282829] mt-0.5">{{ \Carbon\Carbon::parse($partner->partnership_date)->translatedFormat('d F Y') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>

                {{-- SIDEBAR: MITRA LAINNYA --}}
                <div class="lg:col-span-1 mt-10 lg:mt-0">
                    <div class="fade-in-section bg-white border border-gray-200 rounded-2xl p-6 shadow-lg lg:sticky lg:top-24">
                        <h3 class="text-lg font-bold text-[#282829] mb-5 border-b border-gray-200 pb-3 flex items-center gap-2">
                            <i class="fas fa-handshake" style="color: {{ $amaliahGreen }};"></i> Mitra Industri Lainnya
                        </h3>

                        <div class="space-y-3">
                            @forelse($randomPartners as $suggestedPartner)
                                <a href="{{ route('public.partners.show', $suggestedPartner) }}"
                                    class="flex items-center group p-3 rounded-xl border border-transparent hover:border-[#63cd00]/40 hover:bg-[#63cd00]/5 transition-all duration-200">
                                    <div
                                        class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center p-1.5 border border-gray-200 mr-4 flex-shrink-0">
                                        @if($suggestedPartner->logo)
                                            <img src="{{ img_url($suggestedPartner->logo, 160, 90) }}" width="160" height="90" loading="lazy"
                                                alt="Logo {{ $suggestedPartner->name }}" class="max-h-12 w-auto object-contain">
                                        @else
                                            <i class="fas fa-building text-slate-300 text-xl"></i>
                                        @endif
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="font-bold text-[#282829] group-hover:text-[#63cd00] transition-colors leading-tight truncate">
                                            {{ $suggestedPartner->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5 truncate">
                                            @if($suggestedPartner->sector){{ $suggestedPartner->sector }}@endif
                                            @if($suggestedPartner->sector && $suggestedPartner->city) · @endif
                                            @if($suggestedPartner->city){{ $suggestedPartner->city }}@endif
                                        </p>
                                    </div>
                                    <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-[#63cd00] transition-colors text-xs flex-shrink-0"></i>
                                </a>
                            @empty
                                <p class="text-gray-500 text-sm">Tidak ada mitra lain untuk ditampilkan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection