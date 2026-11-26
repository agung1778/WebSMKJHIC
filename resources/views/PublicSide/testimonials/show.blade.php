@extends('layouts.public-app')

@section('title', 'Testimoni ' . $testimonial->name . ' — SMK Amaliah')
@section('description', $testimonial->description ? strip_tags($testimonial->description) : 'Testimoni alumni SMK Amaliah 1 & 2 Ciawi-Bogor.')

@section('content')
    @php
        $amaliahGreen = '#63cd00';
        $amaliahDark = '#282829';
    @endphp

    <div>

        {{-- HEADER TAUTAN + BREADCRUMB --}}
        <div class="bg-gradient-to-r from-[#63cd00] to-emerald-500">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16 text-center">
                <nav class="flex justify-center text-xs sm:text-sm mb-3 sm:mb-4 overflow-x-auto scrollbar-hide" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center gap-1.5 sm:gap-2 whitespace-nowrap">
                        <li>
                            <a href="/" class="font-medium text-white/80 hover:text-white transition-colors">{{ __('Beranda') }}</a>
                        </li>
                        <li>
                            <i class="fas fa-chevron-right text-white/60 text-[10px]"></i>
                        </li>
                        <li>
                            <a href="{{ route('public.testimonials.index') }}"
                                class="font-medium text-white/80 hover:text-white transition-colors">{{ __('Testimoni') }}</a>
                        </li>
                        <li>
                            <i class="fas fa-chevron-right text-white/60 text-[10px]"></i>
                        </li>
                        <li>
                            <span class="font-semibold text-white truncate max-w-xs">{{ $testimonial->name }}</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight">
                    {{ __('Testimoni Alumni') }}
                </h1>
            </div>
        </div>

        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
            <div class="max-w-3xl mx-auto">

                <a href="{{ route('public.testimonials.index') }}"
                    class="text-gray-700 hover:text-gray-900 text-xs sm:text-sm font-bold mb-6 sm:mb-8 inline-flex items-center transition-colors">
                    <i class="fas fa-arrow-left mr-2 text-xs"></i>
                    {{ __('Kembali ke Daftar Testimoni') }}
                </a>

                {{-- KARTU TESTIMONI --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="h-1.5 sm:h-2 bg-[#63cd00]"></div>
                    <div class="p-5 sm:p-8 lg:p-10">
                        <div class="flex items-center gap-4 mb-6">
                            @if ($testimonial->photo)
                                <img src="{{ img_url($testimonial->photo, 400, 400) }}" width="400" height="400" loading="lazy" alt="{{ __('Foto') }} {{ $testimonial->name }}"
                                    class="w-20 h-20 rounded-full object-cover ring-4 ring-white shadow-lg">
                            @else
                                <div
                                    class="w-20 h-20 rounded-full flex items-center justify-center text-3xl font-bold text-[#1c1c1d] bg-[#63cd00]">
                                    {{ strtoupper(mb_substr(trim($testimonial->name), 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $testimonial->name }}</h2>
                                @if (!empty($testimonial->alumni_year))
                                    <p class="text-sm font-medium text-gray-600">{{ __('Alumni Angkatan') }} {{ $testimonial->alumni_year }}</p>
                                @endif
                                @if ($testimonial->major)
                                    <span
                                        class="inline-flex items-center mt-1 text-xs font-bold px-3 py-1 rounded-full text-emerald-800 bg-emerald-50 border border-emerald-200">
                                        {{ $testimonial->major->name }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <blockquote
                            class="text-lg lg:text-xl text-gray-800 leading-relaxed border-l-4 pl-6 italic"
                            style="border-color: #2e7000;">
                            &ldquo;{{ $testimonial->description }}&rdquo;
                        </blockquote>

                        @if (!empty($testimonial->publisher))
                            <p class="mt-6 text-xs text-gray-500 font-medium">{{ __('Diterbitkan oleh') }} {{ $testimonial->publisher }}</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection