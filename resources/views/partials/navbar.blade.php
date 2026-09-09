<header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-50 bg-white shadow-md">
    <div class="border-b border-gray-200">
        <div class="max-w-screen-xl mx-auto flex items-center justify-between px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex-shrink-0 flex items-center space-x-4">
                <img src="{{ asset('assets/logo/amaliah.webp') }}" alt="Logo SMK Amaliah" class="h-12 w-12">
                <div class="flex flex-col">
                    <a href="/" class="nav-link {{ Request::is('/') ? 'nav-active' : '' }}">
                    <span class="text-gray-900 font-times text-base font-bold whitespace-nowrap">SMK AMALIAH 1&2
                        CIAWI</span>
                        </a></a>
                    <span class="text-xs font-times text-gray-600"><i>{{ __('Tauhid Adalah Landasan Kami') }}</i></span>
                </div>
            </div>

            <div class="hidden lg:flex items-center space-x-10">
                <button @click="searchModalOpen = true"
                    class="group flex items-center space-x-3 bg-gray-100 border border-transparent hover:border-gray-200 hover:bg-white rounded-full px-5 py-2.5 transition-all duration-300 shadow-sm hover:shadow-md">
                    <i
                        class="fa-solid fa-magnifying-glass text-gray-400 group-hover:text-[#63cd00] transition-colors"></i>
                    <span class="text-sm text-gray-500 font-medium">{{ __('Cari di SMK Amaliah...') }}</span>
                </button>

                {{-- LOOPING TOP BAR LINKS (Tipe: Link Biasa) --}}
                <div class="flex items-center space-x-8 text-sm text-gray-700">
                    @foreach ($topBarNavs->where('type', 'link') as $nav)
                        <a href="{{ url($nav->url) }}" target="{{ $nav->target }}"
                            class="hover:text-[#63cd00] transition-colors whitespace-nowrap">
                            {{ $nav->title }}
                        </a>
                    @endforeach
                </div>

{{-- LOOPING TOP BAR BUTTON (Tipe: Button) --}}
                @foreach ($topBarNavs->where('type', 'button') as $nav)
                    <a href="{{ url($nav->url) }}" target="{{ $nav->target }}"
                        class="bg-[#282829] text-white px-6 py-2.5 rounded-full font-semibold hover:bg-opacity-80 transition-colors whitespace-nowrap text-sm">
                        {{ $nav->title }}
                    </a>
                @endforeach
            </div>

            <div class="lg:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-2xl text-gray-700 p-2"
                    aria-label="{{ __('Buka menu navigasi') }}" :aria-expanded="mobileMenuOpen.toString()">
                    <i class="fa-solid fa-bars" x-show="!mobileMenuOpen"></i>
                    <i class="fa-solid fa-times" x-show="mobileMenuOpen" x-cloak></i>
                </button>
            </div>
        </div>
    </div>

    <nav class="bg-[#63cd00] hidden lg:block text-white">
        <div class="max-w-screen-xl mx-auto flex items-center justify-center gap-x-14 px-4 h-12">
            <a href="/" class="nav-link {{ Request::is('/') ? 'nav-active' : '' }}">{{ __('Beranda') }}</a>
            <div class="relative group">
                <button class="nav-link">{{ __('Jelajahi Amaliah') }} <i
                        class="fa-solid fa-chevron-down ml-1.5 text-xs"></i></button>
                <div class="absolute dropdown-content bg-white shadow-lg mt-2 rounded-md py-1 w-48 z-10">
                    <a href="{{ route('public.about.index') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Tentang') }}</a>
                    <a href="{{ route('public.partners.index') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Mitra Industri') }}</a>
                    <a href="{{ route('public.teachers.index') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Guru & Staf') }}</a>
                    <a href="{{ route('public.testimonials.index') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Testimoni') }}</a>
                    <a href="{{ route('public.news.index') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Berita') }}</a>
                    <a href="{{ route('public.pkk.index') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Galeri PKK') }}</a>
                </div>
            </div>
            <a href="{{ route('public.majors.index') }}"
                class="nav-link {{ Request::is('majors*') ? 'nav-active' : '' }}">{{ __('Kompetensi Keahlian') }}</a>
            <div class="relative group">
                <button class="nav-link">{{ __('Pratinjau Pendidikan') }} <i
                        class="fa-solid fa-chevron-down ml-1.5 text-xs"></i></button>
                <div class="absolute dropdown-content bg-white shadow-lg mt-2 rounded-md py-1 w-48 z-10">
                    <a href="{{ route('public.achievement.index') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Prestasi') }}</a>
                    <a href="{{ route('public.program.index') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Program Sekolah') }}</a>
                    <a href="{{ route('public.extracurricular.index') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Ekstrakurikuler') }}</a>
                    <a href="https://play.google.com/store/apps/details?id=com.amexam" target="_blank"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">AM
                        Exam</a>
                    <a href="https://lms.smkamaliah.sch.id/"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">E-Learning</a>
                    <a href="https://elib.smkamaliah.sch.id/"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">E-Library</a>
                    <a href="https://yourdisc710.itch.io/amaliah-tour"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Tur Virtual') }}</a>
                </div>
            </div>
            <a href="{{ route('public.facilities.index') }}"
                class="nav-link {{ Request::is('facilities*') ? 'nav-active' : '' }}">{{ __('Fasilitas') }}</a>
            <div class="relative group">
                <button class="nav-link">{{ __('Pusat Bantuan') }} <i class="fa-solid fa-chevron-down ml-1.5 text-xs"></i></button>
                <div class="absolute dropdown-content bg-white shadow-lg mt-2 rounded-md py-1 w-48 z-10">
                    <a href="{{ route('public.help.faq') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">FAQ</a>
                    <a href="{{ route('public.help.feedback') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Masukan') }}</a>
                    <a href="https://wa.me/6285773672525"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Pusat Bantuan BK A1') }}</a>
                    <a href="https://wa.me/6285714805753"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Pusat Bantuan BK A2') }}</a>
                </div>
            </div>
        </div>
    </nav>

    <div x-show="mobileMenuOpen" x-cloak @click.away="mobileMenuOpen = false"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
        class="lg:hidden bg-white w-full absolute shadow-xl">
        <div class="flex flex-col space-y-1 p-4 text-sm max-h-[calc(100vh-80px)] overflow-y-auto">
            <button @click="searchModalOpen = true; mobileMenuOpen = false"
                class="flex items-center justify-between px-4 py-3 text-gray-500 rounded-md bg-gray-100 hover:bg-gray-200 transition-colors">
                <span class="flex items-center gap-2"><i class="fa-solid fa-magnifying-glass"></i> {{ __('Cari di SMK Amaliah...') }}</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>

            <a href="/"
                class="block px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]">{{ __('Beranda') }}</a>

            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex justify-between items-center px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]"><span>{{ __('Jelajahi Amaliah') }}</span><i class="fa-solid fa-chevron-down text-xs transition-transform"
                        :class="{ 'rotate-180': open }"></i></button>
                <div x-show="open" x-transition class="pl-6 pt-2 pb-1 space-y-1">
                    <a href="{{ route('public.about.index') }}"
                        class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">{{ __('Tentang') }}</a>
                    <a href="{{ route('public.partners.index') }}"
                        class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">{{ __('Mitra Industri') }}</a>
                    <a href="{{ route('public.teachers.index') }}"
                        class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">{{ __('Guru & Staf') }}</a>
                    <a href="{{ route('public.testimonials.index') }}"
                        class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">{{ __('Testimoni') }}</a>
                    <a href="{{ route('public.news.index') }}"
                        class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">{{ __('Berita') }}</a>
                    <a href="{{ route('public.pkk.index') }}"
                        class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">{{ __('Galeri PKK') }}</a>
                </div>
            </div>

            <a href="{{ route('public.majors.index') }}"
                class="block px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]">{{ __('Kompetensi Keahlian') }}</a>

            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex justify-between items-center px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]"><span>{{ __('Pratinjau Pendidikan') }}</span><i class="fa-solid fa-chevron-down text-xs transition-transform"
                        :class="{ 'rotate-180': open }"></i></button>
                <div x-show="open" x-transition class="pl-6 pt-2 pb-1 space-y-1">
                    <a href="{{ route('public.achievement.index') }}"
                        class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">{{ __('Prestasi') }}</a>
                    <a href="{{ route('public.program.index') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Program Sekolah') }}</a>
                    <a href="{{ route('public.extracurricular.index') }}"
                        class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">{{ __('Ekstrakurikuler') }}</a>
                    <a href="https://play.google.com/store/apps/details?id=com.amexam" target="_blank"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">AM
                        Exam</a>
                    <a href="https://lms.smkamaliah.sch.id/"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">E-Learning</a>
                    <a href="https://elib.smkamaliah.sch.id/"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">E-Library</a>
                    <a href="https://yourdisc710.itch.io/amaliah-tour"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Tur Virtual') }}</a>
                </div>
            </div>

            <a href="{{ route('public.facilities.index') }}"
                class="block px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]">{{ __('Fasilitas') }}</a>

            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex justify-between items-center px-4 py-3 text-gray-700 rounded-md hover:bg-gray-100 hover:text-[#59E300]"><span>{{ __('Pusat Bantuan') }}</span><i class="fa-solid fa-chevron-down text-xs transition-transform"
                        :class="{ 'rotate-180': open }"></i></button>
                <div x-show="open" x-transition class="pl-6 pt-2 pb-1 space-y-1">
                    <a href="{{ route('public.help.faq') }}"
                        class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">FAQ</a>
                    <a href="{{ route('public.help.feedback') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#59E300]">{{ __('Masukan') }}</a>
                    <a href="https://wa.me/6285773672525"
                        class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">{{ __('Pusat Bantuan BK A1') }}</a>
                    <a href="https://wa.me/6285714805753"
                        class="block px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 hover:text-[#59E300]">{{ __('Pusat Bantuan BK A2') }}</a>
                </div>
            </div>

            <hr class="my-2 border-gray-100">

            {{-- LOOPING MOBILE MENU TOP BAR --}}
            @foreach ($topBarNavs as $nav)
                @if ($nav->type == 'button')
                    <a href="{{ url($nav->url) }}" target="{{ $nav->target }}"
                        class="block text-center bg-[#282829] text-white px-4 py-3 rounded-full font-semibold hover:bg-opacity-80 transition-colors">
                        {{ $nav->title }}
                    </a>
                @else
                    <a href="{{ url($nav->url) }}" target="{{ $nav->target }}"
                        class="block px-4 py-3 text-gray-700 rounded-md hover:bg-gray-50 hover:text-[#59E300] transition-colors">
                        {{ $nav->title }}
                    </a>
                @endif
            @endforeach

        </div>
    </div>
</header>
