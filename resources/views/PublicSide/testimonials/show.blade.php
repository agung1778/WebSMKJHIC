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
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 text-center">
                <nav class="flex justify-center text-sm sm:text-base mb-4" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li>
                            <a href="/" class="font-medium text-white/80 hover:text-white transition-colors">{{ __('Beranda') }}
</a>
                        </li>
                        <li>
                            <i class="fas fa-chevron-right text-white/60 text-xs"></i>
                        </li>
                        <li>
                            <a href="{{ route('public.testimonials.index') }}"
                                class="font-medium text-white/80 hover:text-white transition-colors">{{ __('Testimoni') }}</a>
                        </li>
                        <li>
                            <i class="fas fa-chevron-right text-white/60 text-xs"></i>
                        </li>
                        <li>
                            <span class="font-semibold text-white">{{ $testimonial->name }}</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    {{ __('Testimoni Alumni') }}
                </h1>
            </div>
        </div>

        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="max-w-3xl mx-auto">

                <a href="{{ route('public.testimonials.index') }}"
                    class="text-gray-500 hover:text-gray-900 text-sm font-medium mb-8 inline-flex items-center transition-colors">
                    <i class="fas fa-arrow-left mr-2 text-xs"></i>
                    {{ __('Kembali ke Daftar Testimoni') }}
                </a>

                {{-- KARTU TESTIMONI --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="h-2" style="background: {{ $amaliahGreen }};"></div>
                    <div class="p-8 lg:p-10">
                        <div class="flex items-center gap-4 mb-6">
                            @if ($testimonial->photo)
                                <img src="{{ asset('storage/' . $testimonial->photo) }}" alt="{{ __('Foto') }} {{ $testimonial->name }}"
                                    class="w-20 h-20 rounded-full object-cover ring-4 ring-white shadow-lg">
                            @else
                                <div
                                    class="w-20 h-20 rounded-full flex items-center justify-center text-3xl font-bold text-white"
                                    style="background: {{ $amaliahGreen }};">
                                    {{ strtoupper(mb_substr(trim($testimonial->name), 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $testimonial->name }}</h2>
                                @if (!empty($testimonial->alumni_year))
                                    <p class="text-sm text-gray-500">{{ __('Alumni Angkatan') }} {{ $testimonial->alumni_year }}</p>
                                @endif
                                @if ($testimonial->major)
                                    <span
                                        class="inline-flex items-center mt-1 text-xs font-semibold px-2.5 py-1 rounded-full text-white"
                                        style="background-color: {{ $amaliahGreen }};">
                                        {{ $testimonial->major->name }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <blockquote
                            class="text-lg lg:text-xl text-gray-700 leading-relaxed border-l-4 pl-6 italic"
                            style="border-color: {{ $amaliahGreen }};">
                            &ldquo;{{ $testimonial->description }}&rdquo;
                        </blockquote>

                        @if (!empty($testimonial->publisher))
                            <p class="mt-6 text-xs text-gray-400">{{ __('Diterbitkan oleh') }} {{ $testimonial->publisher }}</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection