@extends('layouts.public-app')

@section('title', 'Pusat Unduhan — SMK Amaliah 1 & 2')
@section('description', 'Unduh logo sekolah, logo perusahaan mitra industri, dan contoh laporan PKL resmi SMK Amaliah 1 & 2 Ciawi-Bogor.')

@php
    $totalDownloads = count($downloads);
    $countSchool = collect($downloads)->where('category_label', 'Logo Sekolah')->count();
    $countPartner = collect($downloads)->where('category_label', 'Logo Perusahaan')->count();
    $countPkl = collect($downloads)->where('category_label', 'Laporan PKL')->count();
@endphp

@section('content')
    <style>
        .download-page { color: #1c1c1d; overflow-wrap: anywhere; }
        .download-hero { position: relative; background: #1f1f20; color: #fff; overflow: hidden; }
        .download-kicker { display: inline-flex; gap: 8px; align-items: center; color: #ffffff; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 9999px; padding: 4px 14px; font-size: 12px; letter-spacing: .08em; text-transform: uppercase; font-weight: 700; backdrop-filter: blur(8px); }
        .download-hero h1 { margin: 12px 0; font-size: clamp(26px, 4.5vw, 44px); font-weight: 800; line-height: 1.25; letter-spacing: -0.02em; }
        .download-hero p { max-width: 680px; margin: 0; color: rgba(255, 255, 255, 0.88); line-height: 1.75; font-size: 15px; }
        
        .download-categories { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 24px; }
        .download-categories a { min-height: 42px; display: inline-flex; align-items: center; padding: 9px 18px; border: 1px solid #e5e7eb; border-radius: 9999px; color: #374151; font-size: 13px; font-weight: 600; text-decoration: none; background: #ffffff; transition: all 0.2s ease; }
        .download-categories a:hover { border-color: #00875a; color: #00875a; }
        .download-categories a[aria-current="page"] { background: #00875a; color: #fff; border-color: #00875a; box-shadow: 0 4px 12px -2px rgba(0, 135, 90, 0.35); }
        
        .download-category { display: inline-flex; margin-top: 14px; color: #00875a; background: #e6f4ea; border-radius: 9999px; padding: 3px 12px; font-size: 11px; font-weight: 700; width: fit-content; }
        .download-major-filter { display: flex; flex-wrap: wrap; align-items: flex-end; gap: 12px; margin-bottom: 24px; padding: 18px; border: 1px solid #e5e7eb; border-radius: 16px; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
        .download-major-field { flex: 1 1 260px; min-width: 0; }
        .download-major-field label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #1c1c1d; }
        .download-major-field select { display: block; width: 100%; min-width: 0; max-width: 100%; min-height: 44px; border: 1px solid #d1d5db; border-radius: 10px; padding: 10px 14px; font: inherit; font-size: 13px; background: #fff; color: #1c1c1d; outline: none; }
        .download-major-field select:focus { border-color: #00875a; box-shadow: 0 0 0 3px rgba(0, 135, 90, 0.15); }
        .download-major-label { margin-top: 8px; font-size: 12px; color: #4b5563; line-height: 1.6; }
        .download-major-filter button { border: 0; cursor: pointer; font-family: inherit; }
        
        .download-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(320px, 100%), 1fr)); gap: 24px; }
        .download-card { display: flex; flex-direction: column; min-width: 0; padding: clamp(16px, 3vw, 24px); background: #fff; border: 1px solid #e5e7eb; border-radius: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease; }
        .download-card:hover { transform: translateY(-3px); box-shadow: 0 14px 28px -10px rgba(0,0,0,0.08); border-color: #00875a; }
        
        .download-preview { display: grid; place-items: center; min-width: 0; padding: 24px; border-radius: 14px; background: #f8fafc; border: 1px solid #f1f5f9; aspect-ratio: 16 / 9; }
        .download-preview--dark { background: #1c1c1d; border-color: #2e2e30; }
        .download-preview img { display: block; width: auto; height: auto; max-width: 100%; max-height: 160px; object-fit: contain; }
        .download-file-preview { display: flex; flex-direction: column; align-items: center; gap: 10px; color: #00875a; font-size: 13px; font-weight: 600; }
        .download-file-preview i { font-size: 52px; color: #00875a; }
        .download-preview--dark .download-file-preview { color: #e6f4ea; }
        .download-preview--dark .download-file-preview i { color: #52c41a; }
        
        .download-card h2 { margin: 16px 0 8px; font-size: 18px; font-weight: 800; line-height: 1.4; color: #1c1c1d; }
        .download-description { color: #6b7280; font-size: 13px; line-height: 1.75; margin-bottom: 16px; flex-grow: 1; }
        .download-formats { display: grid; gap: 10px; }
        .download-format { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px; border-top: 1px solid #f3f4f6; padding-top: 12px; }
        .download-format > div { flex: 1 1 120px; min-width: 0; }
        .download-format strong { display: block; font-size: 13px; color: #1c1c1d; font-weight: 700; }
        .download-format small { display: block; margin-top: 2px; font-size: 11px; color: #9ca3af; }
        
        .download-link { min-height: 40px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 9px 16px; border-radius: 10px; background: #e6f4ea; color: #00875a; font-size: 12px; font-weight: 700; text-decoration: none; transition: all 0.2s ease; }
        .download-link:hover { background: #00875a; color: #ffffff; }
        .download-link:focus-visible { outline: 3px solid #00875a; outline-offset: 2px; }
        
        .download-note { margin-top: 32px; padding: 24px; border: 1px solid #a7f3d0; border-radius: 18px; background: #ecfdf5; font-size: 13px; line-height: 1.8; color: #1f2937; }
        .download-note h2 { margin: 0 0 6px; font-size: 15px; font-weight: 800; color: #065f46; display: flex; align-items: center; gap: 8px; }
        .download-empty { padding: 48px 24px; border: 2px dashed #d1d5db; border-radius: 18px; text-align: center; line-height: 1.8; background: #ffffff; }
        .download-empty h2 { font-size: 18px; font-weight: 800; color: #374151; margin-bottom: 6px; }
        .download-empty p { color: #6b7280; font-size: 14px; max-width: 520px; margin: 0 auto; }
        
        @media (max-width: 480px) {
            .download-format { align-items: stretch; }
            .download-link { width: 100%; }
            .download-preview { padding: 18px; }
        }
    </style>

    <div class="download-page">
        {{-- ============================ HERO ============================ --}}
        <section class="download-hero" aria-labelledby="download-title">
            <div class="absolute inset-0">
                <div class="absolute -top-24 -right-16 w-96 h-96 rounded-full opacity-20"
                    style="background: radial-gradient(circle, #00875a 0%, transparent 70%)"></div>
                <div class="absolute bottom-0 left-0 w-full h-2/3"
                    style="background: radial-gradient(ellipse at bottom left, rgba(0,135,90,.22) 0%, transparent 60%)"></div>
            </div>

            <div class="absolute inset-0"
                style="background: linear-gradient(100deg, rgba(20,20,22,0.94) 0%, rgba(20,20,22,0.82) 48%, rgba(20,20,22,0.55) 100%)"></div>

            <div class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 flex flex-col justify-center w-full">
                <span class="download-kicker w-fit mb-3">
                    <span class="w-2 h-2 rounded-full bg-[#00875a]"></span>
                    <i class="fa-solid fa-cloud-arrow-down" aria-hidden="true"></i> Pusat Unduhan
                </span>
                <h1 id="download-title" class="text-white text-2xl sm:text-4xl lg:text-5xl font-extrabold leading-tight tracking-tight">
                    Pusat Unduhan <span class="text-[#00875a]">Sekolah</span>
                </h1>
                <p class="mt-2.5 sm:mt-4 max-w-2xl text-white/90 text-sm sm:text-base lg:text-lg leading-relaxed">
                    Temukan logo sekolah, logo perusahaan, dan contoh laporan PKL yang disediakan pengelola SMK Amaliah 1 &amp; 2. Semua berkas dapat diunduh tanpa login dalam kualitas asli.
                </p>
            </div>

            {{-- Breadcrumb --}}
            <div style="background-color:#1c1c1d; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06);">
                <div class="max-w-screen-xl min-h-12 py-2.5 mx-auto px-4 sm:px-6 lg:px-8 flex items-center overflow-x-auto scrollbar-hide">
                    <nav aria-label="Breadcrumb" class="min-w-0">
                        <ol class="inline-flex items-center gap-1.5 sm:gap-2.5 text-xs sm:text-sm whitespace-nowrap">
                            <li class="flex items-center">
                                <a href="/" class="inline-flex items-center font-medium text-gray-200 hover:text-white transition-colors">
                                    <i class="fa-solid fa-house text-xs mr-1.5 opacity-80"></i> Home
                                </a>
                            </li>
                            <li class="flex items-center">
                                <i class="fa-solid fa-chevron-right text-white/40 text-xs"></i>
                                <span class="inline-flex items-center ml-1.5 sm:ml-2.5 font-semibold text-[#48bb78]">Pusat Unduhan</span>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </section>

        {{-- ============================ STATISTIK (SESUAI GAMBAR 1) ============================ --}}
        <section class="bg-white border-b border-gray-100">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
                    <div class="group bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 text-center shadow-xs hover:border-[#00875a] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="mx-auto w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#e6f4ea] border border-[#00875a]/30 flex items-center justify-center shadow-xs group-hover:bg-[#00875a] group-hover:border-[#00875a] transition-colors duration-300">
                            <i class="fa-solid fa-folder-open text-[#00875a] group-hover:text-white transition-colors duration-300 text-base sm:text-lg"></i>
                        </div>
                        <div class="mt-2.5 sm:mt-3 text-2xl sm:text-3xl font-extrabold text-[#1c1c1d] tracking-tight">
                            {{ $totalDownloads }}
                        </div>
                        <div class="mt-1 text-xs sm:text-sm font-semibold text-gray-700 leading-snug">Total Berkas</div>
                    </div>

                    <div class="group bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 text-center shadow-xs hover:border-[#00875a] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="mx-auto w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#e6f4ea] border border-[#00875a]/30 flex items-center justify-center shadow-xs group-hover:bg-[#00875a] group-hover:border-[#00875a] transition-colors duration-300">
                            <i class="fa-solid fa-school text-[#00875a] group-hover:text-white transition-colors duration-300 text-base sm:text-lg"></i>
                        </div>
                        <div class="mt-2.5 sm:mt-3 text-2xl sm:text-3xl font-extrabold text-[#1c1c1d] tracking-tight">
                            {{ $countSchool }}
                        </div>
                        <div class="mt-1 text-xs sm:text-sm font-semibold text-gray-700 leading-snug">Logo Sekolah</div>
                    </div>

                    <div class="group bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 text-center shadow-xs hover:border-[#00875a] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="mx-auto w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#e6f4ea] border border-[#00875a]/30 flex items-center justify-center shadow-xs group-hover:bg-[#00875a] group-hover:border-[#00875a] transition-colors duration-300">
                            <i class="fa-solid fa-handshake text-[#00875a] group-hover:text-white transition-colors duration-300 text-base sm:text-lg"></i>
                        </div>
                        <div class="mt-2.5 sm:mt-3 text-2xl sm:text-3xl font-extrabold text-[#1c1c1d] tracking-tight">
                            {{ $countPartner }}
                        </div>
                        <div class="mt-1 text-xs sm:text-sm font-semibold text-gray-700 leading-snug">Mitra Industri</div>
                    </div>

                    <div class="group bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 text-center shadow-xs hover:border-[#00875a] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="mx-auto w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#e6f4ea] border border-[#00875a]/30 flex items-center justify-center shadow-xs group-hover:bg-[#00875a] group-hover:border-[#00875a] transition-colors duration-300">
                            <i class="fa-solid fa-file-lines text-[#00875a] group-hover:text-white transition-colors duration-300 text-base sm:text-lg"></i>
                        </div>
                        <div class="mt-2.5 sm:mt-3 text-2xl sm:text-3xl font-extrabold text-[#1c1c1d] tracking-tight">
                            {{ $countPkl }}
                        </div>
                        <div class="mt-1 text-xs sm:text-sm font-semibold text-gray-700 leading-snug">Laporan PKL</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================ DAFTAR UNDUHAN ============================ --}}
        <section class="bg-gray-50 py-12 sm:py-16">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="download-categories" aria-label="Kategori unduhan">
                    <a href="{{ route('public.downloads.index') }}" @if($category === null) aria-current="page" @endif>Semua Berkas</a>
                    @foreach($categories as $key => $label)
                        <a href="{{ route('public.downloads.index', ['kategori' => $key]) }}" @if($category === $key) aria-current="page" @endif>{{ $label }}</a>
                    @endforeach
                </nav>

                @if($category === 'pkl')
                    <form action="{{ route('public.downloads.index') }}" method="GET" class="download-major-filter">
                        <input type="hidden" name="kategori" value="pkl">
                        <div class="download-major-field">
                            <label for="public-download-major">Jurusan Laporan PKL</label>
                            <select id="public-download-major" name="jurusan">
                                <option value="">Semua Jurusan</option>
                                <option value="umum" @selected($majorFilter === 'umum')>Umum / Lintas Jurusan</option>
                                @foreach($majors as $major)
                                    <option value="{{ $major->id }}" @selected($majorFilter === (string) $major->id)>{{ $major->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="download-link"><i class="fa-solid fa-filter" aria-hidden="true"></i> Filter Jurusan</button>
                        @if($majorFilter !== null)<a href="{{ route('public.downloads.index', ['kategori' => 'pkl']) }}" class="download-link">Reset</a>@endif
                    </form>
                @endif

                @if ($downloads !== [])
                    <div class="download-grid">
                        @foreach ($downloads as $logo)
                            <article class="download-card">
                                <div class="download-preview {{ $logo['dark_preview'] ? 'download-preview--dark' : '' }}">
                                    @if($logo['preview'])
                                        <img src="{{ $logo['preview'] }}" alt="{{ $logo['name'] }}" decoding="async">
                                    @else
                                        <div class="download-file-preview"><i class="fa-solid {{ $logo['document_icon'] }}" aria-hidden="true"></i><span>Dokumen {{ $logo['formats'][0]['label'] }}</span></div>
                                    @endif
                                </div>
                                <span class="download-category">{{ $logo['category_label'] }}</span>
                                @if($logo['major_label'])<p class="download-major-label"><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i> {{ $logo['major_label'] }}</p>@endif
                                <h2>{{ $logo['name'] }}</h2>
                                <p class="download-description">{{ $logo['description'] }}</p>
                                <div class="download-formats">
                                    @foreach ($logo['formats'] as $format)
                                        <div class="download-format">
                                            <div>
                                                <strong>{{ $format['label'] }}</strong>
                                                <small>{{ $format['size'] }} @if($format['dimensions']) · {{ $format['dimensions'] }} @endif</small>
                                            </div>
                                            <a href="{{ $format['url'] }}" class="download-link" aria-label="Unduh {{ $logo['name'] }} format {{ $format['label'] }}">
                                                <i class="fa-solid fa-download" aria-hidden="true"></i> Unduh {{ $format['label'] }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="download-empty" role="status">
                        <h2>Berkas belum tersedia</h2>
                        <p>{{ $majorFilter !== null ? 'Belum ada laporan PKL yang dipublikasikan untuk jurusan yang dipilih.' : ($category ? 'Belum ada berkas yang dipublikasikan untuk kategori ini.' : 'Pengelola belum menyediakan berkas untuk diunduh.') }} Silakan pilih filter lain atau kunjungi kembali nanti.</p>
                    </div>
                @endif

                <section class="download-note" aria-labelledby="download-note-title">
                    <h2 id="download-note-title"><i class="fa-solid fa-circle-info" aria-hidden="true"></i> Panduan penggunaan</h2>
                    <p>Setiap unduhan menggunakan file asli dari pengelola sekolah. Logo sekolah dan perusahaan tersedia sesuai versi yang diunggah. Contoh laporan PKL hanya menjadi panduan; sesuaikan isi dan formatnya dengan arahan sekolah atau pembimbing. Berkas PDF dan DOCX diunduh sebagai lampiran.</p>
                </section>
            </div>
        </section>
    </div>
@endsection
