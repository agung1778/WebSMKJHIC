{{-- ============================================================== --}}
{{-- FLOATING TOOLS MENU (Posisi Kanan Bawah)                      --}}
{{-- ============================================================== --}}
<div x-data="{ toolsOpen: false }" @click.outside="toolsOpen = false">
    <div class="fixed bottom-6 lg:bottom-8 right-5 lg:right-8 z-50 flex flex-col items-center gap-3">

        {{-- Kumpulan Tombol Expandable (Muncul ke atas saat di-klik) --}}
        <div x-show="toolsOpen" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-10 scale-50"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-10 scale-50"
            style="display: none;" class="flex flex-col gap-3 origin-bottom pb-2 items-center">

            {{-- Tombol Tanya AI --}}
            <button type="button" @click.stop="toolsOpen = false; window.dispatchEvent(new CustomEvent('open-ai-chat'))"
                class="w-12 h-12 lg:w-[50px] lg:h-[50px] rounded-full text-white shadow-lg flex items-center justify-center transition-transform hover:scale-110"
                style="background-color: {{ $amaliahGreen ?? '#63cd00' }};"
                aria-label="{{ __('Tanya AI') }}">
                <i class="fas fa-robot text-xl lg:text-2xl"></i>
            </button>

            {{-- Tombol WhatsApp --}}
            <a href="https://wa.me/{{ $whatsappNumber ?? '6285649011449' }}?text={{ urlencode($whatsappMessage ?? __('Halo, saya ingin bertanya tentang informasi SMK Amaliah 1 & 2 Ciawi')) }}"
                target="_blank" rel="noopener noreferrer" aria-label="{{ __('Hubungi via WhatsApp') }}"
                class="w-12 h-12 lg:w-[50px] lg:h-[50px] rounded-full text-white shadow-lg flex items-center justify-center transition-transform hover:scale-110"
                style="background-color: #25D366;">
                <i class="fab fa-whatsapp text-xl lg:text-2xl"></i>
            </a>

            {{-- Tombol Traffic Website --}}
            <a href="{{ route('public.traffic.index') }}" target="_blank" rel="noopener noreferrer"
                aria-label="{{ __('Lihat Traffic Website') }}"
                class="w-12 h-12 lg:w-[50px] lg:h-[50px] rounded-full text-white shadow-lg flex items-center justify-center transition-transform hover:scale-110"
                style="background-color: #94a3b8;">
                <i class="fa-solid fa-chart-line text-xl lg:text-2xl"></i>
            </a>
        </div>

        {{-- TOMBOL UTAMA (TOGGLE MENU TOOLS) --}}
        <button @click.stop="toolsOpen = !toolsOpen"
            class="w-14 h-14 lg:w-[56px] lg:h-[56px] rounded-full text-white shadow-xl flex items-center justify-center transition-transform active:scale-95 relative"
            style="background-color: {{ $amaliahDark ?? '#282829' }};">

            {{-- Icon Setting/Tools (Muncul saat tertutup) --}}
            <i class="fas fa-sliders-h text-xl lg:text-2xl transition-all duration-300 absolute"
                :class="toolsOpen ? 'rotate-90 opacity-0 scale-50' : 'opacity-100 scale-100'"></i>

            {{-- Icon Close 'X' (Muncul saat terbuka) --}}
            <i class="fas fa-times text-xl lg:text-2xl transition-all duration-300 absolute"
                :class="toolsOpen ? 'opacity-100 rotate-0 scale-100' : '-rotate-90 opacity-0 scale-50'"></i>
        </button>

    </div>
</div>