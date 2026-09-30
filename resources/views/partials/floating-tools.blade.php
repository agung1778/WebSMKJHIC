{{-- ============================================================== --}}
{{-- FLOATING TOOLS (Kanan Bawah, sejajar vertikal, selalu tampil)   --}}
{{-- Isi vertikal: 1) Chatbot AI  2) WhatsApp  3) Traffic Website    --}}
{{-- Tampilan: gradient tema hijau amaliah, ring, glow, halo AI       --}}
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
        border: 2px solid rgba(255, 255, 255, 0.4);
        box-shadow:
            0 8px 22px rgba(10, 58, 33, 0.35),
            inset 0 2px 4px rgba(255, 255, 255, 0.35),
            inset 0 -3px 6px rgba(0, 0, 0, 0.18);
        text-decoration: none;
        cursor: pointer;
        flex-shrink: 0;
        position: relative;
        -webkit-tap-highlight-color: transparent;
        transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
    }

    .fl-float-btn i {
        font-size: 20px;
        line-height: 1;
        filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.28));
    }

    @media (min-width: 640px) {
        .fl-float-btn { width: 58px; height: 58px; }
        .fl-float-btn i { font-size: 23px; }
    }

    @media (min-width: 1024px) {
        .fl-float-btn { width: 62px; height: 62px; }
        .fl-float-btn i { font-size: 26px; }
    }

    .fl-float-btn:hover {
        transform: translateY(-3px) scale(1.06);
        box-shadow:
            0 12px 26px var(--fl-glow, rgba(10, 58, 33, 0.4)),
            inset 0 2px 4px rgba(255, 255, 255, 0.35),
            inset 0 -3px 6px rgba(0, 0, 0, 0.18);
        filter: saturate(1.1);
    }

    .fl-float-btn:active {
        transform: scale(0.94);
    }

    .fl-float-btn--ai::after {
        content: "";
        position: absolute;
        inset: -5px;
        border-radius: 9999px;
        border: 2px solid rgba(139, 227, 51, 0.5);
        pointer-events: none;
        animation: flHalo 2.4s ease-out infinite;
    }

    @keyframes flHalo {
        0%   { transform: scale(0.9);   opacity: .9; }
        70%  { transform: scale(1.14);  opacity: 0; }
        100% { transform: scale(1.14);  opacity: 0; }
    }
</style>

<div class="fl-float-wrap">

    {{-- Tombol Chatbot AI --}}
    <button type="button"
        onclick="window.dispatchEvent(new CustomEvent('open-ai-chat'))"
        class="fl-float-btn fl-float-btn--ai"
        style="background: linear-gradient(135deg, #8be333 0%, #3aa527 45%, #0a5c2e 100%); --fl-glow: 0 12px 28px rgba(139, 227, 51, 0.5);"
        aria-label="{{ __('Tanya AI') }}"
        title="{{ __('Tanya AI') }}">
        <i class="fas fa-robot"></i>
    </button>

    {{-- Tombol WhatsApp --}}
    <a href="https://wa.me/{{ $whatsappNumber ?? '6285649011449' }}?text={{ urlencode($whatsappMessage ?? __('Halo, saya ingin bertanya tentang informasi SMK Amaliah 1 & 2 Ciawi')) }}"
        target="_blank" rel="noopener noreferrer"
        class="fl-float-btn"
        style="background: linear-gradient(135deg, #3bed7a 0%, #25d366 50%, #0f9d58 100%); --fl-glow: 0 12px 28px rgba(37, 211, 102, 0.45);"
        aria-label="{{ __('Hubungi via WhatsApp') }}"
        title="{{ __('Hubungi via WhatsApp') }}">
        <i class="fab fa-whatsapp"></i>
    </a>

    {{-- Tombol Traffic Website --}}
    <a href="{{ route('public.traffic.index') }}" target="_blank" rel="noopener noreferrer"
        class="fl-float-btn"
        style="background: linear-gradient(135deg, #5b6b5f 0%, #283f31 100%); --fl-glow: 0 12px 28px rgba(122, 142, 132, 0.45);"
        aria-label="{{ __('Lihat Traffic Website') }}"
        title="{{ __('Lihat Traffic Website') }}">
        <i class="fa-solid fa-chart-line"></i>
    </a>

</div>