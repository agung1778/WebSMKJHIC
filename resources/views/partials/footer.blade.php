<footer style="background-color: {{ $amaliahDark ?? '#282829' }};" class="fade-in-section">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <div class="space-y-6">
                <a href="/" class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo/amaliah_white.webp') }}" alt="Logo SMK Amaliah" class="h-10">
                    <div>
                        <span class="text-white font-semibold text-lg leading-tight">SMK Amaliah 1 & 2</span>
                        <span class="block text-gray-400 text-xs">Ciawi - Bogor</span>
                    </div>
                </a>
                <p class="text-gray-400 text-sm leading-relaxed">{{ __('Berkomitmen untuk mencetak lulusan yang kompeten, berakhlak mulia, dan siap bersaing di dunia industri global.') }}</p>
                <div class="flex items-center space-x-3">
                    <a href="https://youtube.com/@smkamaliahciawi?si=j67hYjVWMNc2F3vK" target="_blank"
                        aria-label="{{ __('Kunjungi YouTube SMK Amaliah') }}"
                        class="group w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white">
                        <i class="fab fa-youtube text-gray-400 text-xl group-hover:text-red-600 transition-colors"></i>
                    </a>
                    <a href="https://www.instagram.com/smkamaliah" target="_blank"
                        aria-label="{{ __('Kunjungi Instagram SMK Amaliah') }}"
                        class="group w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white">
                        <i
                            class="fab fa-instagram text-gray-400 text-xl group-hover:text-pink-600 transition-colors"></i>
                    </a>
                    <a href="https://www.facebook.com/smk.amaliah.1.dan.2" target="_blank"
                        aria-label="{{ __('Kunjungi Facebook SMK Amaliah') }}"
                        class="group w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white">
                        <i
                            class="fab fa-facebook-f text-gray-400 text-xl group-hover:text-blue-600 transition-colors"></i>
                    </a>
                    <a href="https://www.tiktok.com/@smk.amaliah?_t=ZS-90cdH7Gk5Ml&_r=1" target="_blank"
                        aria-label="{{ __('Kunjungi TikTok SMK Amaliah') }}"
                        class="group w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white">
                        <i class="fab fa-tiktok text-gray-400 text-xl group-hover:text-black transition-colors"></i>
                    </a>
                </div>
            </div>
            <div>
                <h4 class="font-semibold text-white tracking-wider uppercase">{{ __('Jelajahi') }}</h4>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="/"
                            class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">{{ __('Beranda') }}</a>
                    </li>
                    <li><a href="{{ route('public.about.index') }}"
                            class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">{{ __('Tentang Kami') }}</a></li>
                    <li><a href="{{ route('public.news.index') }}"
                            class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">{{ __('Berita') }}</a>
                    </li>
                    <li><a href="{{ route('public.majors.index') }}"
                            class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">{{ __('Jurusan') }}</a>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-white tracking-wider uppercase">{{ __('Informasi') }}</h4>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="https://ppdb.smkamaliah.sch.id/login"
                            class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">{{ __('Info SPMB') }}</a></li>
                    <li><a href="{{ route('public.facilities.index') }}"
                            class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">{{ __('Fasilitas') }}</a>
                    </li>
                    <li><a href="https://yourdisc710.itch.io/amaliah-tour"
                            class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">{{ __('Tur Virtual') }}</a></li>
                    <li><a href="https://wa.me/6285649011449"
                            class="text-gray-400 hover:text-white hover:translate-x-1 block transition-all duration-300">{{ __('Kontak') }}</a>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-white tracking-wider uppercase">{{ __('Hubungi Kami') }}</h4>
                <div class="mt-4 flex flex-col gap-4 text-sm">
                    <div class="flex items-start gap-3 text-gray-400">
                        <i class="fas fa-map-marker-alt w-4 h-4 mt-1 flex-shrink-0"></i>
                        <span>Jl. Raya Jl. Tol Jagorawi No.1, Ciawi, Kec. Ciawi, Kabupaten Bogor, Jawa Barat
                            16720</span>
                    </div>
                    <div class="flex items-start gap-3 text-gray-400">
                        <i class="fas fa-envelope w-4 h-4 mt-1 flex-shrink-0"></i>
                        <a href="mailto:{{ $email ?? 'smkamaliahciawi@gmail.com' }}"
                            class="hover:text-white transition">smkamaliahciawi@gmail.com</a>
                    </div>
                    <div class="flex items-start gap-3 text-gray-400">
                        <i class="fas fa-phone-alt w-4 h-4 mt-1 flex-shrink-0"></i>
                        <a href="https://wa.me/6285649011449" class="hover:text-white transition">0856-1922-827 /
                            0856-4901-1449</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="border-t border-gray-800">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4">
                <p class="text-sm text-gray-400 uppercase mb-2">{{ app()->getLocale() === 'id' ? 'Indonesia' : 'English' }}</p>
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} {{ __('Tim IT SMK Amaliah. Hak Cipta Dilindungi.') }}</p>
                <div class="flex space-x-6 text-sm text-gray-500">
                    <a href="{{ route('public.legal.privacy') }}" class="hover:text-white transition">{{ __('Kebijakan Privasi') }}</a>
                    <a href="{{ route('public.legal.terms') }}" class="hover:text-white transition">{{ __('Syarat & Ketentuan') }}</a>
                </div>
            </div>
        </div>
    </div>
</footer>
