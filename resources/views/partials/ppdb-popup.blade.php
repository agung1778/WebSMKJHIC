{{--
    Partial: Popup Pengumuman PPDB / SPMB
    Muncul otomatis saat pengunjung membuka website, selama data SPMB berstatus "Buka".
    Hanya ditampilkan satu kali per versi pengumuman (disimpan di localStorage).
--}}
@php
    $ppdbRegisterUrl = $ppdbSetting->registration_link ?: 'https://ppdb.smkamaliah.sch.id/login';
    $ppdbStorageKey = 'ppdb-popup-' . $ppdbSetting->id . '-' . ($ppdbSetting->updated_at ? $ppdbSetting->updated_at->timestamp : '0');
    $ppdbFrequency = in_array($ppdbSetting->popup_frequency, ['always', 'session', 'daily', 'once'], true)
        ? $ppdbSetting->popup_frequency
        : 'session';
    $ppdbDelay = max(0, (int) ($ppdbSetting->popup_delay ?? 900));
    $ppdbLogo = $ppdbSetting->popup_logo;
    $ppdbImage = $ppdbSetting->popup_image ?: $ppdbSetting->brochure_image_1;
    $ppdbYear = date('Y') . '/' . (date('Y') + 1);
    $ppdbTitle = $ppdbSetting->popup_title ?: 'Pendaftaran Murid Baru ' . $ppdbYear . ' Telah Dibuka';
    $ppdbSubtitle = $ppdbSetting->popup_subtitle ?: 'Penerimaan Peserta Didik Baru';
    $ppdbBadge = $ppdbSetting->popup_badge ?: 'Pendaftaran Buka';
    $ppdbButton = $ppdbSetting->popup_button_text ?: 'Daftar Sekarang';
    $ppdbThemes = [
        'hijau' => 'linear-gradient(135deg, #63cd00 0%, #3e9b00 55%, #282829 100%)',
        'gelap' => 'linear-gradient(135deg, #3f3f46 0%, #27272a 60%, #18181b 100%)',
        'biru' => 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 55%, #172554 100%)',
        'ungu' => 'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 55%, #3b0764 100%)',
        'jingga' => 'linear-gradient(135deg, #f59e0b 0%, #ea580c 55%, #7c2d12 100%)',
    ];
    $ppdbBannerStyle = $ppdbThemes[$ppdbSetting->popup_theme] ?? $ppdbThemes['hijau'];
@endphp

<style>
    .ppdb-popup-layer {
        position: fixed;
        inset: 0;
        z-index: 9998;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        background: rgba(15, 23, 42, .62);
        -webkit-backdrop-filter: blur(6px);
        backdrop-filter: blur(6px);
    }

    .ppdb-popup-card {
        position: relative;
        width: 100%;
        max-width: 560px;
        max-height: calc(100dvh - 32px);
        overflow-y: auto;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 40px 120px rgba(10, 40, 25, .45);
    }

    .ppdb-popup-banner {
        position: relative;
        padding: 22px 22px 30px;
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
        border-radius: 12px;
        object-fit: contain;
        background: #fff;
        padding: 4px;
        flex-shrink: 0;
    }

    .ppdb-popup-kicker {
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .18em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .82);
    }

    .ppdb-popup-title {
        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.3;
        margin-top: .15rem;
    }

    .ppdb-popup-badge {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .3rem .7rem;
        border-radius: 9999px;
        background: #fff;
        color: #2f7a00;
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .1em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .ppdb-popup-dot {
        width: 7px;
        height: 7px;
        border-radius: 9999px;
        background: #22c55e;
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
        top: 14px;
        right: 14px;
        height: 34px;
        width: 34px;
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
        padding: 22px;
        display: flex;
        gap: 18px;
        align-items: flex-start;
    }

    .ppdb-popup-heading {
        font-size: 1.3rem;
        font-weight: 800;
        line-height: 1.25;
        color: #111827;
    }

    .ppdb-popup-sub {
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #63cd00;
        margin-bottom: .35rem;
    }

    .ppdb-popup-brochure {
        width: 92px;
        height: 122px;
        border-radius: 12px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .12);
        flex-shrink: 0;
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
        font-size: .875rem;
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
        font-size: .75rem;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .ppdb-popup-desc {
        margin-top: 12px;
        font-size: .82rem;
        line-height: 1.65;
        color: #6b7280;
    }

    .ppdb-popup-actions {
        padding: 18px 22px 22px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        border-top: 1px solid #f1f5f9;
    }

    .ppdb-popup-btn {
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        flex: 1 1 190px;
        padding: .8rem 1.1rem;
        border-radius: 12px;
        font-size: .875rem;
        font-weight: 700;
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
        padding: 0 22px 20px;
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

    @media (max-width: 480px) {
        .ppdb-popup-body {
            padding: 18px;
        }

        .ppdb-popup-heading {
            font-size: 1.1rem;
        }

        .ppdb-popup-actions {
            padding: 16px 18px 18px;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .ppdb-popup-dot {
            animation: none;
        }
    }
</style>

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

    <div x-show="open" x-cloak class="ppdb-popup-layer" role="dialog" aria-modal="true"
        aria-labelledby="ppdb-popup-title">

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
                <button type="button" class="ppdb-popup-close" @click="open = false" aria-label="Tutup pengumuman">
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
                        <span class="ppdb-popup-dot"></span> {{ $ppdbBadge }}
                    </span>
                </div>
            </div>

            <div class="ppdb-popup-body">
                <div class="flex-1 min-w-0">
                    <div class="ppdb-popup-sub">{{ $ppdbSubtitle }}</div>
                    <h3 class="ppdb-popup-heading" id="ppdb-popup-title">{{ $ppdbTitle }}</h3>

                    @if ($ppdbSetting->wave_name || $ppdbSetting->wave_category)
                        <div class="ppdb-popup-chip">
                            <i class="fa-solid fa-bullhorn"></i>
                            {{ $ppdbSetting->wave_category ? $ppdbSetting->wave_category : 'Gelombang' }}
                            @if ($ppdbSetting->wave_name)
                                &middot; {{ $ppdbSetting->wave_name }}
                            @endif
                        </div>
                    @endif

                    <div class="ppdb-popup-list">
                        <div class="ppdb-popup-item">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span>
                                <b>Periode:</b>
                                {{ $ppdbSetting->period_date ?: 'Menunggu pengumuman jadwal' }}
                            </span>
                        </div>

                        @if ($ppdbSetting->wave_category)
                            <div class="ppdb-popup-item">
                                <i class="fa-solid fa-graduation-cap"></i>
                                <span><b>Jenjang:</b> SMK (SMP/MTs &amp; SMA/MA)</span>
                            </div>
                        @endif

                        @if ($ppdbSetting->quota_note)
                            <div class="ppdb-popup-item ppdb-popup-item--warn">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $ppdbSetting->quota_note }}</span>
                            </div>
                        @endif
                    </div>

                    @if ($ppdbSetting->wave_description)
                        <p class="ppdb-popup-desc">{{ $ppdbSetting->wave_description }}</p>
                    @endif
                </div>

                @if ($ppdbImage)
                    <img src="{{ img_url($ppdbImage, 184, 244) }}" width="92" height="122" loading="lazy"
                        alt="Gambar popup PPDB" class="ppdb-popup-brochure">
                @endif
            </div>

            <div class="ppdb-popup-actions">
                <a href="{{ $ppdbRegisterUrl }}" target="_blank" rel="noopener"
                    class="ppdb-popup-btn ppdb-popup-btn--primary">
                    <span>{{ $ppdbButton }}</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

                <a href="{{ route('public.spmb.index') }}" class="ppdb-popup-btn ppdb-popup-btn--ghost">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Lihat Detail Info</span>
                </a>
            </div>

            <div class="ppdb-popup-note">
                Butuh bantuan? Hubungi kami via WhatsApp
                <a href="https://wa.me/6285649011449" target="_blank" rel="noopener">0856-4901-1449</a>
            </div>
        </div>
    </div>
</div>