{{--
    Component: <x-ppdb-popup :setting="$spmbSetting" :preview="false" />

    Popup pengumuman PPDB. Muncul otomatis di halaman publik selama data
    aktif berstatus "Buka" dan popup tidak dimatikan.

    @param setting  App\Models\SpmbSetting|null  Data sumber tampilan
    @param preview  bool  true = mode pratinjau di admin (tanpa layer fixed)
--}}
@props(['setting' => null, 'preview' => false])

@php
    $isPreview = (bool) $preview;

    $ppdbYear = date('Y') . '/' . (date('Y') + 1);
    $ppdbFrequency = in_array($setting->popup_frequency ?? null, ['always', 'session', 'daily', 'once'], true)
        ? $setting->popup_frequency
        : 'always';
    $ppdbDelay = max(0, (int) ($setting->popup_delay ?? 900));
    $ppdbLogo = $setting->popup_logo ?? null;
    $ppdbImage = ($setting->popup_show_image ?? true) ? ($setting->popup_image ?: $setting->brochure_image_1) : null;
    $ppdbRegisterUrl = $setting->registration_link ?: 'https://ppdb.smkamaliah.sch.id/login';
    $ppdbTitle = $setting->popup_title ?: 'Pendaftaran Murid Baru ' . $ppdbYear . ' Telah Dibuka';
    $ppdbSubtitle = $setting->popup_subtitle ?: 'Penerimaan Peserta Didik Baru';
    $ppdbBadge = $setting->popup_badge ?: 'Pendaftaran Buka';
    $ppdbButton = $setting->popup_button_text ?: 'Daftar Sekarang';
    $ppdbShowDetail = ($setting->popup_show_detail_button ?? true) && \Illuminate\Support\Facades\Route::has('public.spmb.index');

    $ppdbSizes = ['kecil' => 420, 'sedang' => 560, 'besar' => 720];
    $ppdbWidth = $ppdbSizes[$setting->popup_size ?? ''] ?? $ppdbSizes['sedang'];

    $ppdbPositions = [
        'atas' => 'flex-start',
        'tengah' => 'center',
        'bawah' => 'flex-end',
    ];
    $ppdbAlign = $ppdbPositions[$setting->popup_position ?? ''] ?? $ppdbPositions['tengah'];

    $ppdbThemes = [
        'hijau' => 'linear-gradient(135deg, #63cd00 0%, #3e9b00 55%, #282829 100%)',
        'gelap' => 'linear-gradient(135deg, #3f3f46 0%, #27272a 60%, #18181b 100%)',
        'biru' => 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 55%, #172554 100%)',
        'ungu' => 'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 55%, #3b0764 100%)',
        'jingga' => 'linear-gradient(135deg, #f59e0b 0%, #ea580c 55%, #7c2d12 100%)',
    ];
    $ppdbBannerStyle = $ppdbThemes[$setting->popup_theme ?? ''] ?? $ppdbThemes['hijau'];

    $ppdbStorageKey = 'ppdb-popup-' . ($setting->id ?? 'preview') . '-' . ($setting->updated_at ? $setting->updated_at->timestamp : '0');
@endphp

<style>
    .ppdb-popup-layer {
        position: fixed;
        inset: 0;
        z-index: 9998;
        display: flex;
        align-items: var(--ppdb-align, center);
        justify-content: center;
        padding: max(16px, env(safe-area-inset-top)) max(16px, env(safe-area-inset-right)) max(16px, env(safe-area-inset-bottom)) max(16px, env(safe-area-inset-left));
        background: rgba(15, 23, 42, .62);
        -webkit-backdrop-filter: blur(6px);
        backdrop-filter: blur(6px);
    }

    .ppdb-popup-card {
        position: relative;
        width: 100%;
        max-width: var(--ppdb-width, 560px);
        max-height: calc(100vh - 32px);
        max-height: calc(100dvh - 32px);
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 40px 120px rgba(10, 40, 25, .45);
    }

    .ppdb-popup-banner {
        position: relative;
        padding: 20px 18px 28px;
        color: #fff;
        background: linear-gradient(135deg, #63cd00 0%, #3e9b00 55%, #282829 100%);
    }

    .ppdb-popup-banner::after {
        content: '';
        position: absolute;
        inset: auto 0 0 0;
        height: 6px;
        background: linear-gradient(90deg, #63cd00 0%, #8ef03a 50%, #63cd00 100%);
    }

    .ppdb-popup-logo {
        height: 46px;
        width: 46px;
        max-width: 46px;
        border-radius: 12px;
        object-fit: contain;
        background: #fff;
        padding: 4px;
        flex-shrink: 0;
    }

    .ppdb-popup-kicker {
        font-size: .66rem;
        font-weight: 700;
        letter-spacing: .16em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .82);
    }

    .ppdb-popup-title {
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.3;
        margin-top: .1rem;
    }

    .ppdb-popup-badge {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .3rem .7rem;
        border-radius: 9999px;
        background: #fff;
        color: #2f7a00;
        font-size: .66rem;
        font-weight: 800;
        letter-spacing: .09em;
        text-transform: uppercase;
        white-space: nowrap;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ppdb-popup-dot {
        width: 7px;
        height: 7px;
        border-radius: 9999px;
        background: #22c55e;
        flex-shrink: 0;
        animation: ppdb-pulse 1.4s ease-in-out infinite;
    }

    @keyframes ppdb-pulse {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: .35;
            transform: scale(.75);
        }
    }

    .ppdb-popup-close {
        position: absolute;
        top: 12px;
        right: 12px;
        height: 36px;
        width: 36px;
        min-height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        background: rgba(0, 0, 0, .18);
        color: #fff;
        border: 0;
        cursor: pointer;
        transition: background .2s ease;
    }

    .ppdb-popup-close:hover {
        background: rgba(0, 0, 0, .34);
    }

    .ppdb-popup-body {
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .ppdb-popup-text {
        min-width: 0;
    }

    .ppdb-popup-heading {
        font-size: 1.2rem;
        font-weight: 800;
        line-height: 1.25;
        color: #111827;
        overflow-wrap: anywhere;
    }

    .ppdb-popup-sub {
        font-size: .74rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #63cd00;
        margin-bottom: .3rem;
    }

    .ppdb-popup-media {
        width: 100%;
        border-radius: 14px;
        overflow: hidden;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
    }

    .ppdb-popup-media img {
        display: block;
        width: 100%;
        height: auto;
        max-height: 260px;
        object-fit: cover;
    }

    .ppdb-popup-list {
        margin-top: 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .ppdb-popup-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: .85rem;
        color: #374151;
        line-height: 1.5;
    }

    .ppdb-popup-item i {
        margin-top: .2rem;
        color: #63cd00;
        flex-shrink: 0;
    }

    .ppdb-popup-item--warn {
        font-weight: 700;
        color: #dc2626;
    }

    .ppdb-popup-item--warn i {
        color: #dc2626;
    }

    .ppdb-popup-chip {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .25rem .65rem;
        border-radius: 9999px;
        background: #ecfccb;
        color: #3f6212;
        font-size: .74rem;
        font-weight: 600;
        margin-bottom: 10px;
        max-width: 100%;
        overflow-wrap: anywhere;
    }

    .ppdb-popup-desc {
        margin-top: 12px;
        font-size: .82rem;
        line-height: 1.65;
        color: #6b7280;
        overflow-wrap: anywhere;
    }

    .ppdb-popup-actions {
        padding: 16px 18px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        border-top: 1px solid #f1f5f9;
    }

    .ppdb-popup-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .6rem;
        flex: 1 1 100%;
        min-height: 46px;
        padding: .7rem 1rem;
        border-radius: 12px;
        font-size: .875rem;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        border: 1px solid transparent;
        transition: filter .2s ease, background .2s ease;
    }

    .ppdb-popup-btn--primary {
        background: #63cd00;
        color: #fff;
    }

    .ppdb-popup-btn--primary:hover {
        filter: brightness(.94);
    }

    .ppdb-popup-btn--ghost {
        background: #fff;
        border-color: #e5e7eb;
        color: #282829;
    }

    .ppdb-popup-btn--ghost:hover {
        background: #f9fafb;
    }

    .ppdb-popup-note {
        padding: 0 18px 18px;
        font-size: .72rem;
        color: #9ca3af;
        text-align: center;
    }

    .ppdb-popup-note a {
        color: #63cd00;
        font-weight: 600;
        text-decoration: none;
    }

    .ppdb-popup-note a:hover {
        text-decoration: underline;
    }

    /* ---------- Pratinjau di admin ---------- */
    .ppdb-popup-preview {
        position: relative;
        border-radius: 18px;
        padding: 22px 16px;
        display: flex;
        align-items: var(--ppdb-align, center);
        justify-content: center;
        background:
            repeating-linear-gradient(45deg, #f1f5f9 0 10px, #e8edf3 10px 20px);
        overflow: hidden;
    }

    .ppdb-popup-preview-tag {
        position: absolute;
        top: 10px;
        left: 12px;
        font-size: .65rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #64748b;
    }

    @media (min-width: 640px) {

        .ppdb-popup-layer {
            padding-top: max(32px, env(safe-area-inset-top));
        }

        .ppdb-popup-banner {
            padding: 22px 22px 30px;
        }

        .ppdb-popup-body {
            flex-direction: row;
            padding: 22px;
            gap: 18px;
            align-items: flex-start;
        }

        .ppdb-popup-text {
            flex: 1 1 auto;
        }

        .ppdb-popup-media {
            width: 104px;
            flex: 0 0 104px;
        }

        .ppdb-popup-media img {
            height: 138px;
            object-fit: cover;
        }

        .ppdb-popup-btn {
            flex: 1 1 190px;
        }

        .ppdb-popup-note {
            padding: 0 22px 20px;
        }
    }

    @media (max-width: 420px) {
        .ppdb-popup-heading {
            font-size: 1.05rem;
        }

        .ppdb-popup-kicker {
            font-size: .6rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .ppdb-popup-dot {
            animation: none;
        }
    }
</style>

@if ($isPreview)
    <div class="ppdb-popup-preview" data-popup-frame
        style="--ppdb-align: {{ $ppdbAlign }}; --ppdb-width: {{ $ppdbWidth }}px">
        <span class="ppdb-popup-preview-tag"><i class="fa-solid fa-eye"></i> Pratinjau</span>

        <div class="ppdb-popup-card">
            <div class="ppdb-popup-banner" data-popup-preview="banner" style="background: {{ $ppdbBannerStyle }}">
                <div class="flex items-center gap-3">
                    <img src="{{ $ppdbLogo ? asset('storage/' . $ppdbLogo) : asset('assets/logo/amaliah_white.webp') }}"
                        alt="Logo" width="46" height="46" class="ppdb-popup-logo"
                        data-popup-preview="logo">
                    <div>
                        <div class="ppdb-popup-kicker">PPDB {{ $ppdbYear }}</div>
                        <div class="ppdb-popup-title">SMK Amaliah 1 &amp; 2 Ciawi</div>
                    </div>
                </div>

                <div class="mt-4">
                    <span class="ppdb-popup-badge">
                        <span class="ppdb-popup-dot"></span>
                        <span data-popup-preview="popup_badge">{{ $ppdbBadge }}</span>
                    </span>
                </div>
            </div>

            <div class="ppdb-popup-body">
                <div class="ppdb-popup-text">
                    <div class="ppdb-popup-sub" data-popup-preview="popup_subtitle">{{ $ppdbSubtitle }}</div>
                    <div class="ppdb-popup-heading" data-popup-preview="popup_title">{{ $ppdbTitle }}</div>

                    @if ($setting->wave_name || $setting->wave_category)
                        <div class="ppdb-popup-chip">
                            <i class="fa-solid fa-bullhorn"></i>
                            <span data-popup-preview="wave">
                                {{ $setting->wave_category ? $setting->wave_category : 'Gelombang' }}
                                @if ($setting->wave_name)
                                    &middot; {{ $setting->wave_name }}
                                @endif
                            </span>
                        </div>
                    @endif

                    <div class="ppdb-popup-list">
                        <div class="ppdb-popup-item">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span><b>Periode:</b> <span
                                        data-popup-preview="period_date">{{ $setting->period_date ?: 'Menunggu pengumuman jadwal' }}</span></span>
                        </div>

                        @if ($setting->quota_note)
                            <div class="ppdb-popup-item ppdb-popup-item--warn">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span data-popup-preview="quota_note">{{ $setting->quota_note }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="ppdb-popup-media" data-popup-preview="image"
                    @if (! ($setting->popup_show_image ?? true)) style="display:none" @endif>
                    @if ($ppdbImage)
                        <img src="{{ img_url($ppdbImage, 320, 240) }}" alt="Gambar popup PPDB">
                    @else
                        <div
                            style="aspect-ratio:4/3;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;color:#94a3b8;font-size:.7rem;text-align:center;padding:10px">
                            <i class="fa-solid fa-image"></i>
                            <span>Belum ada gambar</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="ppdb-popup-actions">
                <a href="#" onclick="return false" class="ppdb-popup-btn ppdb-popup-btn--primary"
                    data-popup-preview="popup_button_text">
                    <span>{{ $ppdbButton }}</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

                <a href="#" onclick="return false" data-popup-preview="detail" class="ppdb-popup-btn ppdb-popup-btn--ghost"
                    @if (! $ppdbShowDetail) style="display:none" @endif>
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Lihat Detail Info</span>
                </a>
            </div>

            <div class="ppdb-popup-note">
                Butuh bantuan? Hubungi kami via WhatsApp
                <a href="#" onclick="return false">0856-4901-1449</a>
            </div>
        </div>
    </div>
@else
    <div x-data="{ open: false }"
        x-init="
            (function () {
                var mode = '{{ $ppdbFrequency }}';
                var key = '{{ $ppdbStorageKey }}';
                var delay = {{ $ppdbDelay }};
                var alreadySeen = false;

                try {
                    if (mode === 'session') {
                        alreadySeen = window.sessionStorage.getItem(key) !== null;
                    } else if (mode === 'daily') {
                        alreadySeen = window.localStorage.getItem(key) === new Date().toDateString();
                    } else if (mode === 'once') {
                        alreadySeen = window.localStorage.getItem(key) !== null;
                    }
                } catch (e) { alreadySeen = false; }

                if (alreadySeen) return;

                window.setTimeout(function () {
                    open = true;
                    try {
                        if (mode === 'session') window.sessionStorage.setItem(key, '1');
                        if (mode === 'daily') window.localStorage.setItem(key, new Date().toDateString());
                        if (mode === 'once') window.localStorage.setItem(key, '1');
                    } catch (e) {}
                }, delay);
            })();
        "
        x-effect="document.body.style.overflow = open ? 'hidden' : ''"
        @keydown.escape.window="open = false">

        <div id="ppdb-popup-fallback" x-show="open" x-cloak class="ppdb-popup-layer" role="dialog" aria-modal="true"
            aria-labelledby="ppdb-popup-title" style="--ppdb-align: {{ $ppdbAlign }}; --ppdb-width: {{ $ppdbWidth }}px">

            <div x-show="open" class="absolute inset-0" @click="open = false"></div>

            <div x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                @click.stop
                class="ppdb-popup-card relative">

                <div class="ppdb-popup-banner" style="background: {{ $ppdbBannerStyle }}">
                    <button type="button" class="ppdb-popup-close" @click="open = false"
                        aria-label="Tutup pengumuman">
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                    <div class="flex items-center gap-3">
                        <img src="{{ $ppdbLogo ? asset('storage/' . $ppdbLogo) : asset('assets/logo/amaliah_white.webp') }}"
                            alt="Logo" width="46" height="46" class="ppdb-popup-logo">
                        <div>
                            <div class="ppdb-popup-kicker">PPDB {{ $ppdbYear }}</div>
                            <div class="ppdb-popup-title">SMK Amaliah 1 &amp; 2 Ciawi</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <span class="ppdb-popup-badge">
                            <span class="ppdb-popup-dot"></span>
                            <span>{{ $ppdbBadge }}</span>
                        </span>
                    </div>
                </div>

                <div class="ppdb-popup-body">
                    <div class="ppdb-popup-text">
                        <div class="ppdb-popup-sub">{{ $ppdbSubtitle }}</div>
                        <div class="ppdb-popup-heading" id="ppdb-popup-title">{{ $ppdbTitle }}</div>

                        @if ($setting->wave_name || $setting->wave_category)
                            <div class="ppdb-popup-chip">
                                <i class="fa-solid fa-bullhorn"></i>
                                <span>
                                    {{ $setting->wave_category ? $setting->wave_category : 'Gelombang' }}
                                    @if ($setting->wave_name)
                                        &middot; {{ $setting->wave_name }}
                                    @endif
                                </span>
                            </div>
                        @endif

                        <div class="ppdb-popup-list">
                            <div class="ppdb-popup-item">
                                <i class="fa-solid fa-calendar-days"></i>
                                <span>
                                    <b>Periode:</b> {{ $setting->period_date ?: 'Menunggu pengumuman jadwal' }}
                                </span>
                            </div>

                            @if ($setting->quota_note)
                                <div class="ppdb-popup-item ppdb-popup-item--warn">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span>{{ $setting->quota_note }}</span>
                                </div>
                            @endif
                        </div>

                        @if ($setting->wave_description)
                            <p class="ppdb-popup-desc">{{ $setting->wave_description }}</p>
                        @endif
                    </div>

                    @if ($ppdbImage)
                        <div class="ppdb-popup-media">
                            <img src="{{ img_url($ppdbImage, 320, 240) }}" width="104" height="138" loading="lazy"
                                alt="Gambar popup PPDB">
                        </div>
                    @endif
                </div>

                <div class="ppdb-popup-actions">
                    <a href="{{ $ppdbRegisterUrl }}" target="_blank" rel="noopener"
                        class="ppdb-popup-btn ppdb-popup-btn--primary">
                        <span>{{ $ppdbButton }}</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                    @if ($ppdbShowDetail)
                        <a href="{{ route('public.spmb.index') }}" class="ppdb-popup-btn ppdb-popup-btn--ghost">
                            <i class="fa-solid fa-circle-info"></i>
                            <span>Lihat Detail Info</span>
                        </a>
                    @endif
                </div>

                <div class="ppdb-popup-note">
                    Butuh bantuan? Hubungi kami via WhatsApp
                    <a href="https://wa.me/6285649011449" target="_blank" rel="noopener">0856-4901-1449</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Jaring pengaman: kalau Alpine gagal dimuat, popup tetap dibuka agar
         pengunjung tidak pernah kehilangan pengumuman. --}}
    <script>
        (function () {
            var layer = document.getElementById('ppdb-popup-fallback');
            if (!layer) return;

            var tries = 0;
            var timer = window.setInterval(function () {
                tries++;
                if (window.Alpine) {
                    window.clearInterval(timer);
                    return;
                }
                if (tries < 20) return;

                window.clearInterval(timer);
                layer.removeAttribute('x-cloak');
                layer.removeAttribute('x-show');
            }, 250);
        })();
    </script>
@endif