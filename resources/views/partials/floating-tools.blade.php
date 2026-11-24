{{-- ============================================================== --}}
{{-- FLOATING TOOLS (Expandable Speed-Dial FAB Anti-Ketutupan)      --}}
{{-- Warna solid 2D tanpa gradient, glow, atau efek timbul         --}}
{{-- ============================================================== --}}
<style>
    .fl-float-wrap {
        position: fixed;
        bottom: 16px;
        right: 14px;
        z-index: 45;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 10px;
    }

    @media (min-width: 640px) {
        .fl-float-wrap { bottom: 22px; right: 20px; gap: 12px; }
    }

    @media (min-width: 1024px) {
        .fl-float-wrap { bottom: 26px; right: 24px; gap: 14px; }
    }

    .fl-launcher-btn {
        width: 46px;
        height: 46px;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #059669;
        color: #ffffff;
        box-shadow: none;
        border: 2px solid #ffffff;
        cursor: pointer;
        transition: transform 0.2s ease, background-color 0.2s ease;
        -webkit-tap-highlight-color: transparent;
        position: relative;
    }

    .fl-launcher-btn:hover {
        background: #047857;
        transform: scale(1.06);
    }

    @media (min-width: 640px) {
        .fl-launcher-btn {
            width: 52px;
            height: 52px;
        }
    }

    .fl-sub-item {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
    }

    .fl-sub-label {
        background: rgba(28, 28, 30, 0.92);
        color: #ffffff;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 8px;
        box-shadow: none;
        border: 1px solid rgba(255, 255, 255, 0.12);
        white-space: nowrap;
        pointer-events: none;
    }

    @media (min-width: 640px) {
        .fl-sub-label { font-size: 12px; padding: 5px 12px; }
    }

    .fl-sub-btn {
        width: 42px;
        height: 42px;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        border: 1.5px solid rgba(255, 255, 255, 0.9);
        box-shadow: none;
        filter: none;
        transition: transform 0.2s ease, background-color 0.2s ease;
        flex-shrink: 0;
    }

    .fl-sub-btn:hover {
        transform: scale(1.08);
    }

    .fl-sub-btn:focus-visible,
    .fl-launcher-btn:focus-visible {
        outline: 3px solid #63cd00;
        outline-offset: 3px;
    }

    @media (min-width: 640px) {
        .fl-sub-btn { width: 46px; height: 46px; }
    }

    .fl-float-btn--ai,
    .fl-btn--ai {
        background: #059669;
    }

    .fl-float-btn--whatsapp,
    .fl-btn--wa {
        background: #25D366;
    }

    .fl-btn--traffic {
        background: #282829;
    }
</style>

<div x-data="{ open: false }"
    @click.outside="open = false"
    @keydown.escape.window="open = false"
    class="fl-float-wrap">

    {{-- Daftar Sub-Tombol (Muncul ke Atas Saat Dibuka) --}}
    <div x-show="open" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="flex flex-col items-end gap-2.5 sm:gap-3">

        {{-- 1. Tombol Chatbot AI --}}
        <div class="fl-sub-item"
            onclick="window.dispatchEvent(new CustomEvent('open-ai-chat'))"
            @click="open = false"
            title="{{ __('Tanya AI SMK Amaliah') }}">
            <span class="fl-sub-label">Tanya AI</span>
            <button type="button" class="fl-sub-btn fl-btn--ai fl-float-btn--ai" aria-label="Tanya Asisten AI">
                <i class="fas fa-robot text-sm sm:text-base"></i>
            </button>
        </div>

        {{-- 2. Tombol WhatsApp --}}
        <a href="https://wa.me/{{ $whatsappNumber ?? '6285649011449' }}?text={{ urlencode($whatsappMessage ?? __('Halo, saya ingin bertanya tentang informasi SMK Amaliah 1 & 2 Ciawi')) }}"
            target="_blank" rel="noopener noreferrer"
            class="fl-sub-item"
            title="{{ __('Hubungi via WhatsApp') }}">
            <span class="fl-sub-label">WhatsApp</span>
            <div class="fl-sub-btn fl-btn--wa fl-float-btn--whatsapp" aria-label="WhatsApp Kami">
                <i class="fab fa-whatsapp text-base sm:text-lg"></i>
            </div>
        </a>

        {{-- 3. Tombol Traffic Website --}}
        <a href="{{ route('public.traffic.index') }}" target="_blank" rel="noopener noreferrer"
            class="fl-sub-item"
            title="{{ __('Statistik Kunjungan') }}">
            <span class="fl-sub-label">Traffic Web</span>
            <div class="fl-sub-btn fl-btn--traffic" aria-label="Lihat Traffic">
                <i class="fa-solid fa-chart-line text-sm sm:text-base"></i>
            </div>
        </a>

    </div>

    {{-- Tombol Pemicu Utama (FAB Launcher / Toggle) --}}
    <button type="button"
        @click="open = !open"
        class="fl-launcher-btn"
        :class="{ 'rotate-90': open }"
        aria-label="Menu Bantuan Cepat"
        :title="open ? 'Tutup Menu' : 'Pusat Bantuan & Kontak'">
        <i class="fa-solid fa-headset text-lg sm:text-xl" x-show="!open"></i>
        <i class="fa-solid fa-xmark text-lg sm:text-xl" x-show="open" x-cloak></i>

        {{-- Titik Indikator Online/Aktif --}}
        <span x-show="!open" class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-[#8ef03a] rounded-full border-2 border-white animate-pulse"></span>
    </button>

</div>
