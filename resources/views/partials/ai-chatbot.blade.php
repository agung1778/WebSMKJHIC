<!--
    Partial: AI Chatbot - Amaliah AI Assistant
    Design: Modern / Professional / Figma-style
    Menggunakan Alpine.js + Tailwind CSS
-->

@php
    $forestA = '#0a3a21';
    $forestB = '#14532d';
    $forestC = '#1c6b40';
    $forestSoft = '#dff0e2';
@endphp

<div
    x-data="aiChatbot()"
    x-init="init()"
    @keydown.escape.window="open = false"
>

    {{-- =========================================================
         MODAL CHAT
    ========================================================== --}}
    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-6"
        role="dialog"
        aria-modal="true"
    >

        {{-- BACKDROP (Frosted glass / heavy blur) --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false"
            class="absolute inset-0 bg-slate-900/50 backdrop-blur-2xl backdrop-saturate-150"
        ></div>


        {{-- =====================================================
             CHAT CARD
        ====================================================== --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-5"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-5"

            class="
                relative z-10
                w-full
                max-w-[480px]
                h-[680px]
                max-h-[calc(100dvh-24px)]
                sm:max-h-[calc(100dvh-48px)]
                overflow-hidden
                flex flex-col
                rounded-[28px]
                bg-white
                shadow-[0_40px_120px_rgba(10,40,25,0.45)]
                ring-1 ring-black/5
            "
        >

            {{-- =================================================
                 HEADER (Deep Forest Green Accent Bar)
            ================================================== --}}
            <header
                class="relative flex-shrink-0 overflow-hidden text-white"
                style="
                    background:
                        radial-gradient(circle at 88% 0%, rgba(255,255,255,0.14), transparent 42%),
                        radial-gradient(circle at 8% 120%, rgba(255,255,255,0.09), transparent 45%),
                        linear-gradient(
                            140deg,
                            {{ $forestA }} 0%,
                            {{ $forestB }} 55%,
                            {{ $forestC }} 100%
                        );
                "
            >

                {{-- Pola titik dekoratif --}}
                <div
                    class="absolute inset-0 opacity-[0.07]"
                    style="
                        background-image:
                        radial-gradient(circle at 1px 1px, white 1px, transparent 0);
                        background-size: 16px 16px;
                    "
                ></div>

                <div class="relative px-5 pt-4 pb-5">

                    <div class="flex items-center gap-3">

                        {{-- LOGO SEKOLAH --}}
                        <div
                            class="
                                w-12 h-12
                                rounded-2xl
                                bg-white
                                shadow-[0_10px_25px_rgba(0,0,0,0.18)]
                                ring-1 ring-black/5
                                flex items-center justify-center
                                p-1.5
                                flex-shrink-0
                            "
                        >
                            <img
                                src="{{ asset('assets/logo/am.webp') }}"
                                alt="Logo SMK Amaliah"
                                class="w-full h-full object-contain"
                            />
                        </div>

                        {{-- NAMA + STATUS --}}
                        <div class="flex-1 min-w-0">

                            <h3 class="font-bold text-[15px] leading-tight tracking-tight">
                                Amaliah AI Assistant
                            </h3>

                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-1.5">

                                {{-- Badge Online --}}
                                <span
                                    class="
                                        inline-flex items-center gap-1.5
                                        px-2 py-0.5
                                        rounded-full
                                        bg-emerald-300/20
                                        ring-1 ring-white/20
                                        text-[10px] font-semibold
                                        tracking-wide
                                    "
                                >
                                    <span class="relative flex w-1.5 h-1.5">
                                        <span
                                            class="absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75 animate-ping"
                                        ></span>
                                        <span
                                            class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-300"
                                        ></span>
                                    </span>
                                    Online
                                </span>

                                <span class="text-[10.5px] opacity-80 truncate">
                                    {{ __('Asisten Info Sekolah') }}
                                </span>

                            </div>
                        </div>


                        {{-- HEADER BUTTONS (minimal) --}}
                        <div class="flex items-center gap-1.5 flex-shrink-0">

                            {{-- Refresh --}}
                            <button
                                type="button"
                                @click="resetChat()"
                                title="{{ __('Mulai percakapan baru') }}"
                                class="
                                    w-9 h-9
                                    rounded-full
                                    bg-white/10
                                    hover:bg-white/25
                                    border border-white/15
                                    flex items-center justify-center
                                    backdrop-blur-sm
                                    transition
                                    active:scale-90
                                "
                            >
                                <i
                                    class="fas fa-rotate-right text-[13px]"
                                    :class="loading ? 'animate-spin' : ''"
                                ></i>
                            </button>

                            {{-- Close --}}
                            <button
                                type="button"
                                @click="open = false"
                                title="{{ __('Tutup') }}"
                                class="
                                    w-9 h-9
                                    rounded-full
                                    bg-white/10
                                    hover:bg-white/25
                                    border border-white/15
                                    flex items-center justify-center
                                    backdrop-blur-sm
                                    transition
                                    active:scale-90
                                "
                            >
                                <i class="fas fa-xmark text-[13px]"></i>
                            </button>

                        </div>

                    </div>

                </div>

                {{-- Garis aksen tipis di bawah header --}}
                <div
                    class="absolute bottom-0 left-0 right-0 h-[3px] opacity-80"
                    style="
                        background: linear-gradient(
                            90deg,
                            rgba(255,255,255,0.35),
                            rgba(255,255,255,0.05),
                            rgba(255,255,255,0.35)
                        );
                    "
                ></div>

            </header>


            {{-- =================================================
                 CHAT BODY
            ================================================== --}}
            <div
                x-ref="chatBox"
                class="
                    flex-1
                    overflow-y-auto
                    px-4 sm:px-5
                    py-5
                    space-y-4
                    min-h-0
                    scroll-smooth
                "
                style="
                    background:
                        radial-gradient(
                            circle at 10% 10%,
                            rgba(20,83,45,.05),
                            transparent 30%
                        ),
                        radial-gradient(
                            circle at 90% 85%,
                            rgba(28,107,64,.045),
                            transparent 32%
                        ),
                        #f8fafc;
                "
            >

                {{-- MESSAGES --}}
                <template
                    x-for="(m, i) in messages"
                    :key="i"
                >
                    <div
                        class="flex items-end gap-2.5"
                        :class="
                            m.role === 'user'
                            ? 'justify-end'
                            : 'justify-start'
                        "
                    >

                        {{-- BOT AVATAR --}}
                        <template x-if="m.role === 'bot'">

                            <div
                                class="
                                    w-8 h-8
                                    rounded-xl
                                    flex items-center justify-center
                                    text-white
                                    flex-shrink-0
                                    mb-1
                                    shadow-sm
                                "
                                style="
                                    background:
                                        linear-gradient(
                                            140deg,
                                            {{ $forestB }} 0%,
                                            {{ $forestC }} 100%
                                        );
                                "
                            >
                                <i class="fas fa-robot text-xs"></i>
                            </div>

                        </template>


                        {{-- MESSAGE BUBBLE --}}
                        <div
                            class="
                                max-w-[82%]
                                px-4 py-3
                                text-[13px]
                                leading-[1.65]
                                shadow-sm
                            "
                            :class="
                                m.role === 'user'
                                ? `
                                    bg-[#14532d]
                                    text-white
                                    rounded-[20px]
                                    rounded-br-[6px]
                                    shadow-[0_5px_15px_rgba(20,83,45,.20)]
                                `
                                : `
                                    bg-white
                                    text-slate-700
                                    rounded-[20px]
                                    rounded-bl-[6px]
                                    border border-slate-100
                                    shadow-[0_4px_16px_rgba(15,23,42,.05)]
                                `
                            "
                        >

                            {{-- USER --}}
                            <span
                                x-show="m.role === 'user'"
                                x-text="m.text"
                            ></span>


                            {{-- BOT --}}
                            <div
                                x-show="m.role === 'bot'"
                                x-html="formatBot(m.text)"
                            ></div>

                        </div>


                        {{-- USER AVATAR --}}
                        <template x-if="m.role === 'user'">

                            <div
                                class="
                                    w-8 h-8
                                    rounded-xl
                                    flex items-center justify-center
                                    bg-slate-200
                                    text-slate-500
                                    flex-shrink-0
                                    mb-1
                                "
                            >
                                <i class="fas fa-user text-xs"></i>
                            </div>

                        </template>

                    </div>
                </template>


                {{-- =================================================
                     TYPING INDICATOR
                ================================================== --}}
                <div
                    x-show="loading"
                    x-transition
                    class="flex items-end gap-2.5"
                >

                    <div
                        class="
                            w-8 h-8
                            rounded-xl
                            flex items-center justify-center
                            text-white
                            flex-shrink-0
                        "
                        style="
                            background:
                                linear-gradient(
                                    140deg,
                                    {{ $forestB }} 0%,
                                    {{ $forestC }} 100%
                                );
                        "
                    >
                        <i class="fas fa-robot text-xs"></i>
                    </div>


                    <div
                        class="
                            bg-white
                            rounded-[20px]
                            rounded-bl-[6px]
                            border border-slate-100
                            shadow-sm
                            px-4 py-3
                        "
                    >

                        <div class="flex items-center gap-1.5">

                            <span
                                class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"
                            ></span>

                            <span
                                class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"
                                style="animation-delay:.15s"
                            ></span>

                            <span
                                class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"
                                style="animation-delay:.3s"
                            ></span>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     WELCOME CARD + QUICK ACTIONS
                ================================================== --}}
                <div
                    x-show="!loading && messages.length <= 1"
                    x-transition
                    class="pt-1"
                >

                    {{-- WELCOME CARD --}}
                    <div
                        class="
                            rounded-2xl
                            bg-white
                            border border-slate-100
                            shadow-[0_8px_30px_rgba(15,23,42,0.06)]
                            overflow-hidden
                            mb-4
                        "
                    >
                        <div class="p-4">

                            <div class="flex items-start gap-3">

                                {{-- Icon --}}
                                <div
                                    class="
                                        w-10 h-10
                                        rounded-xl
                                        flex items-center justify-center
                                        flex-shrink-0
                                        text-[#14532d]
                                    "
                                    style="
                                        background-color:
                                        {{ $forestSoft }};
                                    "
                                >
                                    <i class="fas fa-hand-sparkles text-sm"></i>
                                </div>

                                <div class="min-w-0">

                                    <h2 class="font-bold text-[14px] text-slate-900 tracking-tight">
                                        {{ __('Selamat Datang di Amaliah AI Assistant') }}
                                    </h2>

                                    <p class="text-[12px] text-slate-500 leading-snug mt-1">
                                        {{ __('Saya siap membantu menjawab pertanyaan seputar SMK Amaliah 1 & 2 Ciawi, mulai dari profil sekolah, jurusan, hingga guru pengajar. 🏫') }}
                                    </p>

                                </div>
                            </div>

                            {{-- METADATA TAGS --}}
                            <div class="mt-3.5 pt-3.5 border-t border-slate-100">

                                <p
                                    class="
                                        text-[10px]
                                        uppercase
                                        tracking-[0.14em]
                                        font-semibold
                                        text-slate-400
                                        mb-2
                                    "
                                >
                                    <i class="fa-solid fa-database text-[9px] mr-1"></i>
                                    {{ __('Basis Pengetahuan') }}
                                </p>

                                <div class="flex flex-wrap gap-1.5">

                                    <span
                                        class="
                                            inline-flex items-center gap-1.5
                                            px-2.5 py-1
                                            rounded-full
                                            bg-green-50 text-emerald-700
                                            ring-1 ring-emerald-100
                                            text-[10.5px] font-medium
                                        "
                                    >
                                        <i class="fas fa-school text-[9px]"></i>
                                        {{ __('Profil Sekolah') }}
                                    </span>

                                    <span
                                        class="
                                            inline-flex items-center gap-1.5
                                            px-2.5 py-1
                                            rounded-full
                                            bg-blue-50 text-blue-700
                                            ring-1 ring-blue-100
                                            text-[10.5px] font-medium
                                        "
                                    >
                                        <i class="fas fa-graduation-cap text-[9px]"></i>
                                        {{ __('Jurusan') }}
                                    </span>

                                    <span
                                        class="
                                            inline-flex items-center gap-1.5
                                            px-2.5 py-1
                                            rounded-full
                                            bg-violet-50 text-violet-700
                                            ring-1 ring-violet-100
                                            text-[10.5px] font-medium
                                        "
                                    >
                                        <i class="fas fa-chalkboard-user text-[9px]"></i>
                                        {{ __('Guru') }}
                                    </span>

                                </div>

                            </div>

                        </div>
                    </div>


                    {{-- QUICK ACTION PILLS --}}
                    <div class="flex items-center gap-2 mb-3.5">

                        <div
                            class="w-6 h-6 rounded-full flex items-center justify-center"
                            style="
                                background-color:
                                {{ $forestSoft }};
                                color:
                                {{ $forestB }};
                            "
                        >
                            <i class="fas fa-bolt text-[10px]"></i>
                        </div>

                        <p class="text-[12px] font-bold text-slate-600">
                            {{ __('Pertanyaan Cepat') }}
                        </p>

                    </div>


                    <div class="grid grid-cols-2 gap-2.5">

                        <template
                            x-for="(s, si) in suggestions"
                            :key="si"
                        >

                            <button
                                @click="ask(s.text)"
                                type="button"
                                class="
                                    group
                                    flex items-center gap-2.5
                                    px-3 py-2.5
                                    rounded-full
                                    bg-white
                                    border border-slate-200
                                    hover:border-[#14532d]/40
                                    hover:shadow-[0_10px_25px_rgba(20,83,45,0.10)]
                                    hover:-translate-y-0.5
                                    transition-all
                                    duration-200
                                    active:scale-[.97]
                                    text-left
                                    w-full
                                "
                            >

                                {{-- Pastel Icon Badge --}}
                                <span
                                    class="
                                        w-8 h-8
                                        rounded-full
                                        flex items-center justify-center
                                        flex-shrink-0
                                        transition
                                        group-hover:scale-105
                                    "
                                    :class="s.color"
                                >
                                    <i
                                        :class="s.icon"
                                        class="text-[11px]"
                                    ></i>
                                </span>


                                <span
                                    class="
                                        min-w-0
                                        text-[11.5px]
                                        font-semibold
                                        text-slate-600
                                        leading-snug
                                        group-hover:text-slate-900
                                        transition
                                    "
                                    x-text="s.text"
                                ></span>

                            </button>

                        </template>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 FOOTER / INPUT
            ================================================== --}}
            <div
                class="
                    flex-shrink-0
                    px-3 sm:px-4
                    pt-3
                    pb-4
                    bg-white
                    border-t border-slate-100
                "
            >

                <form
                    @submit.prevent="send()"
                    class="
                        relative
                        flex items-center gap-1.5
                        bg-slate-50
                        rounded-full
                        border border-slate-200
                        p-1.5 pl-2
                        transition-all
                        focus-within:bg-white
                        focus-within:border-[#14532d]/50
                        focus-within:shadow-[0_0_0_4px_rgba(20,83,45,0.08)]
                    "
                >

                    {{-- INPUT --}}
                    <input
                        x-model="input"
                        type="text"
                        autocomplete="off"
                        placeholder="{{ __('Tulis pertanyaanmu...') }}"
                        class="
                            flex-1
                            min-w-0
                            h-10
                            bg-transparent
                            border-0
                            outline-none
                            px-1
                            text-[13px]
                            text-slate-700
                            placeholder:text-slate-400
                        "
                    />


                    {{-- SEND --}}
                    <button
                        type="submit"
                        :disabled="loading || !input.trim()"
                        class="
                            w-10 h-10
                            rounded-full
                            text-white
                            flex items-center justify-center
                            shadow-md
                            transition-all
                            active:scale-90
                            disabled:opacity-40
                            disabled:cursor-not-allowed
                            disabled:shadow-none
                            flex-shrink-0
                        "
                        style="
                            background:
                                linear-gradient(
                                    140deg,
                                    {{ $forestB }} 0%,
                                    {{ $forestC }} 100%
                                );
                            box-shadow: 0 6px 18px rgba(20,83,45,0.30);
                        "
                    >
                        <i
                            class="fas fa-paper-plane text-xs"
                            :class="loading ? 'opacity-0' : ''"
                        ></i>

                        <i
                            x-show="loading"
                            class="fas fa-spinner animate-spin text-xs"
                        ></i>

                    </button>

                </form>


                {{-- FOOTNOTE --}}
                <div class="flex items-center justify-center gap-1.5 mt-2.5">

                    <i class="fas fa-shield-halved text-[9px] text-slate-300"></i>

                    <p class="text-[9px] text-slate-400">
                        {{ __('Amaliah AI dapat memberikan jawaban yang kurang tepat.') }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
     JAVASCRIPT
============================================================= --}}
<script>

    function aiChatbot() {

        let eventHandler = null;

        return {

            open: false,

            loading: false,

            messages: [],

            input: '',


            {{-- QUICK QUESTIONS --}}
            suggestions: [

                {
                    text: @json(__('Siapa saja guru RPL?')),
                    icon: 'fas fa-users',
                    color: 'bg-green-50 text-green-600'
                },

                {
                    text: @json(__('Ada jurusan apa saja?')),
                    icon: 'fas fa-graduation-cap',
                    color: 'bg-blue-50 text-blue-600'
                },

                {
                    text: @json(__('Apa itu ekskul pramuka?')),
                    icon: 'fas fa-campground',
                    color: 'bg-violet-50 text-violet-600'
                },

                {
                    text: @json(__('Berita terbaru sekolah?')),
                    icon: 'fas fa-newspaper',
                    color: 'bg-orange-50 text-orange-600'
                },

                {
                    text: @json(__('Dimana alamat sekolah?')),
                    icon: 'fas fa-location-dot',
                    color: 'bg-pink-50 text-pink-600'
                },

                {
                    text: @json(__('Bagaimana cara daftar?')),
                    icon: 'fas fa-clipboard-check',
                    color: 'bg-cyan-50 text-cyan-600'
                }

            ],


            {{-- =================================================
                 INIT
            ================================================== --}}
            init() {

                if (this.messages.length === 0) {

                    this.pushBot(
                        'Halo! Saya Amaliah AI Assistant. Saya siap membantu menjawab pertanyaan seputar SMK Amaliah 1 & 2 Ciawi, mulai dari jurusan, SPMB/PPDB, fasilitas, kegiatan, hingga informasi sekolah.'
                    );

                }


                {{-- Scroll otomatis --}}
                this.$watch('messages', () => {

                    this.$nextTick(() => {
                        this.scroll();
                    });

                });


                {{-- Event pembuka chatbot --}}
                if (!eventHandler) {

                    eventHandler = () => {
                        this.open = true;
                    };

                    window.addEventListener(
                        'open-ai-chat',
                        eventHandler
                    );

                }


                {{-- Reset loading ketika ditutup --}}
                this.$watch('open', (val) => {

                    if (!val) {
                        this.loading = false;
                    }

                });

            },


            {{-- =================================================
                 DESTROY
            ================================================== --}}
            destroy() {

                if (eventHandler) {

                    window.removeEventListener(
                        'open-ai-chat',
                        eventHandler
                    );

                    eventHandler = null;

                }

            },


            {{-- =================================================
                 PUSH BOT
            ================================================== --}}
            pushBot(text) {

                this.messages.push({
                    role: 'bot',
                    text: text
                });

            },


            {{-- =================================================
                 RESET CHAT
            ================================================== --}}
            resetChat() {

                if (this.loading) return;

                this.messages = [];

                this.input = '';

                this.pushBot(
                    @json(__('Halo! 👋 Saya Amaliah AI Assistant. Ada yang ingin kamu tanyakan tentang SMK Amaliah 1 & 2 Ciawi? 🏫'))
                );

                this.$nextTick(() => {
                    this.scroll();
                });

            },


            {{-- =================================================
                 ASK
            ================================================== --}}
            ask(q) {

                q = (q || '').trim();

                if (!q || this.loading) return;

                this.input = '';

                this.messages.push({
                    role: 'user',
                    text: q
                });

                this.loading = true;


                const token =
                    document.querySelector(
                        'meta[name=csrf-token]'
                    )?.content;


                fetch('{{ route('public.ai.ask') }}', {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'Accept': 'application/json',

                        'X-CSRF-TOKEN': token

                    },

                    body: JSON.stringify({
                        question: q
                    })

                })

                .then(async response => {

                    if (!response.ok) {
                        throw new Error('Server error');
                    }

                    return response.json();

                })

                .then(data => {

                    this.pushBot(

                        (data && data.reply)

                            ? data.reply

                            : @json(__('Maaf, saya belum dapat menemukan jawaban yang sesuai. Silakan coba pertanyaan lainnya ya. 😊'))

                    );

                })

                .catch(() => {

                    this.pushBot(
                        @json(__('Maaf, terjadi kendala koneksi. 😊 Silakan coba lagi beberapa saat.'))
                    );

                })

                .finally(() => {

                    this.loading = false;

                    this.$nextTick(() => {
                        this.scroll();
                    });

                });

            },


            {{-- =================================================
                 SEND
            ================================================== --}}
            send() {

                this.ask(this.input);

            },


            {{-- =================================================
                 SCROLL
            ================================================== --}}
            scroll() {

                const el = this.$refs.chatBox;

                if (!el) return;

                el.scrollTop = el.scrollHeight;

            },


            {{-- =================================================
                 FORMAT BOT
            ================================================== --}}
            formatBot(text) {

                let s = String(text || '');


                {{-- Escape HTML --}}
                s = s
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');


                {{-- Bold --}}
                s = s.replace(
                    /\*\*(.+?)\*\*/g,
                    '<strong>$1</strong>'
                );


                {{-- Italic --}}
                s = s.replace(
                    /(^|[^*])\*([^*]+)\*([^*]|$)/g,
                    '$1<em>$2</em>$3'
                );


                {{-- Code --}}
                s = s.replace(
                    /`(.+?)`/g,
                    '<code class="px-1.5 py-0.5 bg-slate-100 text-slate-700 rounded-md text-[11px] font-mono">$1</code>'
                );


                {{-- Markdown link --}}
                s = s.replace(
                    /\[(.+?)\]\((.+?)\)/g,
                    '<a href="$2" target="_blank" rel="noopener noreferrer" class="text-[#14532d] font-semibold underline hover:text-[#0a3a21]">$1</a>'
                );


                {{-- Paragraph --}}
                const paragraphs = s
                    .split(/\n\n+/)
                    .map(p => p.trim())
                    .filter(Boolean);


                return paragraphs.map(p => {

                    const lines = p
                        .split('\n')
                        .map(l => l.trim())
                        .filter(Boolean);


                    if (!lines.length) return '';


                    const output = [];

                    let inList = false;
                    let listType = null;


                    for (const line of lines) {

                        const bullet =
                            line.match(/^[-*]\s+(.+)/);

                        const numbered =
                            line.match(/^\d+\.\s+(.+)/);


                        if (bullet || numbered) {

                            const currentType =
                                bullet ? 'ul' : 'ol';


                            if (!inList) {

                                inList = true;

                                listType = currentType;

                                output.push(
                                    currentType === 'ul'
                                        ? '<ul class="list-disc list-inside space-y-1.5 my-2.5">'
                                        : '<ol class="list-decimal list-inside space-y-1.5 my-2.5">'
                                );

                            }


                            output.push(
                                `<li>${bullet ? bullet[1] : numbered[1]}</li>`
                            );

                        } else {

                            if (inList) {

                                output.push(
                                    listType === 'ul'
                                        ? '</ul>'
                                        : '</ol>'
                                );

                                inList = false;
                                listType = null;

                            }


                            output.push(
                                `<p class="my-1.5">${line}</p>`
                            );

                        }

                    }


                    if (inList) {

                        output.push(
                            listType === 'ul'
                                ? '</ul>'
                                : '</ol>'
                        );

                    }


                    return output.join('');

                }).join('');

            }

        };

    }

</script>