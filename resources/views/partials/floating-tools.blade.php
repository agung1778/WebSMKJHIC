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

<div x-data="{ open: false, waModal: false }"
    @click.outside="open = false"
    @keydown.escape.window="open = false; waModal = false"
    class="fl-float-wrap">

    {{-- Modal Pilihan Kontak WhatsApp Terarah --}}
    <div x-show="waModal" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
        @click.self="waModal = false">
        
        <div class="w-full max-w-md bg-white rounded-3xl p-5 sm:p-6 shadow-2xl border border-gray-100 text-left relative overflow-hidden"
            @click.stop>
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-emerald-500 via-[#25D366] to-[#63cd00]"></div>

            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-[#25D366] flex items-center justify-center text-xl shadow-xs">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 leading-tight">Hubungi Kami via WhatsApp</h3>
                        <p class="text-xs text-gray-500">Pilih layanan tujuan untuk respon lebih cepat</p>
                    </div>
                </div>
                <button type="button" @click="waModal = false" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg">
                    <i class="fa-solid fa-times text-base"></i>
                </button>
            </div>

            <div class="space-y-2.5">
                {{-- 1. SPMB SMK Amaliah 1 --}}
                <a href="https://wa.me/628561922827?text={{ urlencode('Halo Panitia SPMB SMK Amaliah 1, saya ingin bertanya tentang informasi pendaftaran siswa baru...') }}"
                    target="_blank" rel="noopener noreferrer"
                    @click="waModal = false"
                    class="group flex items-center justify-between p-3.5 rounded-2xl bg-gray-50 hover:bg-emerald-50/60 border border-gray-200/80 hover:border-emerald-300 transition-all duration-200">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 text-[#3f8600] flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                            <i class="fa-solid fa-school text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-gray-900 group-hover:text-emerald-800 transition">SPMB SMK Amaliah 1</p>
                            <p class="text-[11px] text-gray-500 truncate">Bisnis Manajemen, Akuntansi, Perhotelan</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-emerald-600 group-hover:translate-x-0.5 transition flex items-center gap-1">
                        Chat <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </span>
                </a>

                {{-- 2. SPMB SMK Amaliah 2 --}}
                <a href="https://wa.me/6285649011449?text={{ urlencode('Halo Panitia SPMB SMK Amaliah 2, saya ingin bertanya tentang informasi pendaftaran siswa baru...') }}"
                    target="_blank" rel="noopener noreferrer"
                    @click="waModal = false"
                    class="group flex items-center justify-between p-3.5 rounded-2xl bg-gray-50 hover:bg-emerald-50/60 border border-gray-200/80 hover:border-emerald-300 transition-all duration-200">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 text-[#1d4ed8] flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                            <i class="fa-solid fa-school-flag text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-gray-900 group-hover:text-emerald-800 transition">SPMB SMK Amaliah 2</p>
                            <p class="text-[11px] text-gray-500 truncate">Teknologi Informasi, PPLG, DKV, Animasi</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-emerald-600 group-hover:translate-x-0.5 transition flex items-center gap-1">
                        Chat <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </span>
                </a>

                {{-- 3. Layanan Administrasi & Tata Usaha --}}
                <a href="https://wa.me/6285649011449?text={{ urlencode('Halo Bagian Tata Usaha SMK Amaliah, saya ingin berkonsultasi mengenai informasi umum sekolah...') }}"
                    target="_blank" rel="noopener noreferrer"
                    @click="waModal = false"
                    class="group flex items-center justify-between p-3.5 rounded-2xl bg-gray-50 hover:bg-emerald-50/60 border border-gray-200/80 hover:border-emerald-300 transition-all duration-200">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 text-gray-700 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                            <i class="fa-solid fa-users-gear text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-gray-900 group-hover:text-emerald-800 transition">Tata Usaha & Informasi Umum</p>
                            <p class="text-[11px] text-gray-500 truncate">Administrasi, Legalisir, & Layanan Sekolah</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-emerald-600 group-hover:translate-x-0.5 transition flex items-center gap-1">
                        Chat <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </span>
                </a>
            </div>

            <div class="mt-4 pt-3.5 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-400">
                <span>Jam Pelayanan: Senin - Jumat (07.30 - 15.30 WIB)</span>
                <button type="button" @click="waModal = false" class="text-gray-500 hover:text-gray-700 font-medium">Tutup</button>
            </div>
        </div>
    </div>

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

        {{-- 2. Tombol WhatsApp Terarah --}}
        <div class="fl-sub-item"
            @click="waModal = true; open = false"
            title="{{ __('Hubungi via WhatsApp') }}">
            <span class="fl-sub-label">WhatsApp</span>
            <div class="fl-sub-btn fl-btn--wa fl-float-btn--whatsapp" aria-label="WhatsApp Kami">
                <i class="fab fa-whatsapp text-base sm:text-lg"></i>
            </div>
        </div>

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
