{{-- ============================================================== --}}
{{-- FLOATING TOOLS (Kanan Bawah, sejajar vertikal, selalu tampil)   --}}
{{-- Isi vertikal: 1) Chatbot AI  2) WhatsApp  3) Traffic Website    --}}
{{-- Tampilan: warna solid 2D tanpa gradient, glow, atau efek timbul --}}
{{-- ============================================================== --}}
<style>
    .fl-float-wrap {
        position: fixed;
        bottom: 18px;
        right: 16px;
        z-index: 50;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
    }

    @media (min-width: 640px) {
        .fl-float-wrap { bottom: 24px; right: 22px; gap: 18px; }
    }

    @media (min-width: 1024px) {
        .fl-float-wrap { bottom: 30px; right: 30px; gap: 20px; }
    }

    .fl-float-btn {
        width: 52px;
        height: 52px;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        border: 1px solid #fff;
        background: #283f31;
        box-shadow: none;
        text-decoration: none;
        cursor: pointer;
        flex-shrink: 0;
        position: relative;
        -webkit-tap-highlight-color: transparent;
        transition: background-color .15s ease;
    }

    .fl-float-btn i {
        font-size: 20px;
        line-height: 1;
        filter: none;
    }

    @media (min-width: 640px) {
        .fl-float-btn { width: 58px; height: 58px; }
        .fl-float-btn i { font-size: 23px; }
    }

    @media (min-width: 1024px) {
        .fl-float-btn { width: 62px; height: 62px; }
        .fl-float-btn i { font-size: 26px; }
    }

    .fl-float-btn--ai {
        background: #237c2b;
        --fl-hover: #196322;
    }

    .fl-float-btn--whatsapp {
        background: #12823d;
        --fl-hover: #0b672e;
    }

    .fl-float-btn:hover,
    .fl-float-btn:active {
        background: var(--fl-hover, #1c3024);
    }

    .fl-float-btn:focus-visible {
        outline: 3px solid #63cd00;
        outline-offset: 3px;
    }

    @media (prefers-reduced-motion: reduce) {
        .fl-float-btn { transition: none; }
    }
</style>

<div class="fl-float-wrap">

    {{-- Tombol Chatbot AI --}}
    <button type="button"
        onclick="window.dispatchEvent(new CustomEvent('open-ai-chat'))"
        class="fl-float-btn fl-float-btn--ai"
        aria-label="{{ __('Tanya AI') }}"
        title="{{ __('Tanya AI') }}">
        <i class="fas fa-robot"></i>
    </button>

    {{-- Tombol WhatsApp --}}
    <a href="https://wa.me/{{ $whatsappNumber ?? '6285649011449' }}?text={{ urlencode($whatsappMessage ?? __('Halo, saya ingin bertanya tentang informasi SMK Amaliah 1 & 2 Ciawi')) }}"
        target="_blank" rel="noopener noreferrer"
        class="fl-float-btn fl-float-btn--whatsapp"
        aria-label="{{ __('Hubungi via WhatsApp') }}"
        title="{{ __('Hubungi via WhatsApp') }}">
        <i class="fab fa-whatsapp"></i>
    </a>

    {{-- Tombol Traffic Website --}}
    <a href="{{ route('public.traffic.index') }}" target="_blank" rel="noopener noreferrer"
        class="fl-float-btn"
        aria-label="{{ __('Lihat Traffic Website') }}"
        title="{{ __('Lihat Traffic Website') }}">
        <i class="fa-solid fa-chart-line"></i>
    </a>

</div>
