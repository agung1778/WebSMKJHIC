@extends('layouts.public-app')

@section('title', 'Pusat Unduhan — SMK Amaliah 1 & 2')
@section('description', 'Unduh logo sekolah, logo perusahaan, dan contoh laporan PKL yang disediakan pengelola SMK Amaliah 1 & 2.')

@section('content')
    <style>
        .download-page { color: #282829; overflow-wrap: anywhere; }
        .download-container { width: min(100%, 1200px); margin-inline: auto; padding-inline: clamp(16px, 4vw, 32px); }
        .download-hero { padding-block: clamp(36px, 7vw, 72px); background: #15241a; color: #fff; }
        .download-kicker { display: inline-flex; gap: 8px; align-items: center; color: #9cf267; font-size: 12px; letter-spacing: .1em; text-transform: uppercase; font-weight: 700; }
        .download-hero h1 { margin: 12px 0; font-size: clamp(28px, 5vw, 44px); font-weight: 700; line-height: 1.25; }
        .download-hero p { max-width: 650px; margin: 0; color: #d3e0d6; line-height: 1.8; font-size: 14px; }
        .download-content { padding-block: clamp(28px, 5vw, 56px); }
        .download-categories { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 24px; }
        .download-categories a { min-height: 44px; display: inline-flex; align-items: center; padding: 10px 16px; border: 1px solid #dce8d6; border-radius: 10px; color: #286b10; font-size: 13px; text-decoration: none; }
        .download-categories a[aria-current="page"] { background: #286b10; color: #fff; }
        .download-category { display: inline-flex; margin-top: 18px; color: #286b10; font-size: 11px; font-weight: 700; }
        .download-major-filter { display: flex; flex-wrap: wrap; align-items: flex-end; gap: 12px; margin-bottom: 24px; padding: 18px; border: 1px solid #dce8d6; border-radius: 14px; background: #fff; }
        .download-major-field { flex: 1 1 260px; min-width: 0; }
        .download-major-field label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; }
        .download-major-field select { display: block; width: 100%; min-width: 0; max-width: 100%; min-height: 44px; border: 1px solid #dce8d6; border-radius: 8px; padding: 10px; font: inherit; font-size: 13px; background: #fff; color: #282829; }
        .download-major-label { margin-top: 8px; font-size: 12px; color: #526b51; line-height: 1.7; }
        .download-major-filter button { border: 0; cursor: pointer; font-family: inherit; }
        .download-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 24px; }
        .download-card { min-width: 0; padding: clamp(16px, 3vw, 24px); background: #fff; border: 1px solid #e4eae5; border-radius: 20px; }
        .download-preview { display: grid; place-items: center; min-width: 0; padding: 28px; border-radius: 14px; background: #f2f7f0; aspect-ratio: 16 / 9; }
        .download-preview--dark { background: #202b25; }
        .download-preview img { display: block; width: auto; height: auto; max-width: 100%; max-height: 180px; object-fit: contain; }
        .download-file-preview { display: flex; flex-direction: column; align-items: center; gap: 12px; color: #476447; font-size: 14px; }
        .download-file-preview i { font-size: 56px; }
        .download-preview--dark .download-file-preview { color: #e1f0df; }
        .download-card h2 { margin: 20px 0 8px; font-size: 19px; font-weight: 700; line-height: 1.5; }
        .download-description { color: #626e66; font-size: 13px; line-height: 1.8; margin-bottom: 18px; }
        .download-formats { display: grid; gap: 12px; }
        .download-format { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; border-top: 1px solid #e9eee9; padding-top: 14px; }
        .download-format > div { flex: 1 1 130px; min-width: 0; }
        .download-format strong { display: block; font-size: 13px; }
        .download-format small { display: block; margin-top: 4px; font-size: 11px; color: #67736b; line-height: 1.8; }
        .download-link { min-height: 44px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 11px 16px; border-radius: 10px; background: #eaf8e3; color: #286b10; font-size: 12px; font-weight: 700; text-decoration: none; }
        .download-link:hover { background: #d7f2c7; }
        .download-link:focus-visible { outline: 3px solid #419c17; outline-offset: 3px; }
        .download-note { margin-top: 24px; padding: 20px; border: 1px solid #dce8d6; border-radius: 14px; background: #f1f8ed; font-size: 13px; line-height: 1.8; }
        .download-note h2 { margin: 0 0 6px; font-size: 14px; font-weight: 700; }
        .download-empty { padding: 32px 20px; border: 1px dashed #b9c8b9; border-radius: 16px; text-align: center; line-height: 1.8; }
        @media (max-width: 400px) {
            .download-format { align-items: stretch; }
            .download-link { width: 100%; }
            .download-preview { padding: 20px; }
        }
    </style>

    <div class="download-page">
        <section class="download-hero" aria-labelledby="download-title">
            <div class="download-container">
                <span class="download-kicker"><i class="fa-solid fa-cloud-arrow-down" aria-hidden="true"></i> Pusat Unduhan</span>
                <h1 id="download-title">Pusat Unduhan Sekolah</h1>
                <p>Temukan logo sekolah, logo perusahaan, dan contoh laporan PKL yang disediakan pengelola SMK Amaliah 1 &amp; 2. Semua berkas dapat diunduh tanpa login dalam kualitas asli.</p>
            </div>
        </section>

        <div class="download-container download-content">
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
    </div>
@endsection
