@extends('layouts.public-app')

@section('title', __('Kebijakan Privasi') . ' — SMK Amaliah')
@section('description', __('Kebijakan privasi situs web resmi SMK Amaliah 1 & 2 Ciawi-Bogor.'))

@section('content')
    @php
        $amaliahGreen = '#63cd00';
        $amaliahDark = '#282829';
    @endphp

    <div>
        {{-- HEADER --}}
        <div class="bg-[#282829]">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20 text-center">
                <span class="inline-block text-xs font-bold uppercase tracking-widest mb-3"
                    style="color: {{ $amaliahGreen }};">{{ __('Hukum') }}</span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">{{ __('Kebijakan Privasi') }}</h1>
                <p class="mt-3 text-gray-400 max-w-xl mx-auto">{{ __('Bagaimana SMK Amaliah 1 & 2 Ciawi mengelola dan melindungi data Anda saat menggunakan situs ini.') }}</p>
            </div>
        </div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed space-y-8">

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('1. Data yang Kami Kumpulkan') }}</h2>
                    <p>{{ __('Situs ini dapat mengumpulkan data secara otomatis seperti alamat IP, jenis perangkat, browser, dan halaman yang dikunjungi (traffic website) untuk keperluan analisis dan perbaikan layanan. Khusus untuk interaksi tertentu, kami juga dapat memproses data yang Anda kirimkan melalui formulir kontak atau feedback, seperti nama dan alamat email.') }}</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('2. Penggunaan Data') }}</h2>
                    <p>{{ __('Data yang kami kumpulkan digunakan untuk:') }}</p>
                    <ul class="list-disc pl-6 space-y-1">
                        <li>{{ __('Menjalankan dan mengelola layanan informasi sekolah.') }}</li>
                        <li>{{ __('Mengukur kunjungan dan performa halaman website.') }}</li>
                        <li>{{ __('Menanggapi pertanyaan, saran, dan feedback yang Anda sampaikan.') }}</li>
                        <li>{{ __('Meningkatkan kualitas konten dan fitur website.') }}</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('3. Cookie dan Penyimpanan') }}</h2>
                    <p>{{ __('Kami dapat menggunakan cookie untuk menjaga sesi login Anda tetap aktif dan mengingat preferensi Anda. Cookie login hanya berlaku selama sesi Anda dan tidak digunakan untuk melacak aktivitas Anda di luar situs ini.') }}</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('4. Keamanan Data') }}</h2>
                    <p>{{ __('Seluruh koneksi ke situs ini dilindungi dengan enkripsi. Akses ke data pengunjung dan data pengelola dibatasi hanya untuk pihak yang berwenang di lingkungan sekolah, dan kami berkomitmen untuk tidak membagikan data pribadi Anda kepada pihak ketiga tanpa izin, kecuali diwajibkan oleh hukum.') }}</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('5. Hak Anda') }}</h2>
                    <p>{{ __('Anda berhak untuk meminta informasi mengenai data yang kami simpan, meminta perbaikan, maupun penghapusan data pribadi Anda. Untuk hal tersebut, silakan hubungi kami melalui email ') }}
                        <a href="mailto:smkamaliahciawi@gmail.com" class="font-semibold" style="color: {{ $amaliahGreen }};">smkamaliahciawi@gmail.com</a>
                        {{ __('atau melalui formulir feedback pada halaman ') }}<a href="{{ route('public.help.feedback') }}"
                            class="font-semibold" style="color: {{ $amaliahGreen }};">{{ __('Hubungi Kami') }}</a>.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('6. Perubahan Kebijakan') }}</h2>
                    <p>{{ __('Kebijakan privasi ini dapat diperbarui sewaktu-waktu. Perubahan akan kami umumkan melalui halaman ini. Dengan terus menggunakan situs ini, Anda dianggap menyetujui kebijakan privasi yang berlaku.') }}</p>
                </section>

                <p class="text-sm text-gray-400 pt-8 border-t border-gray-200">{{ __('Terakhir diperbarui: September 2026') }}</p>
            </div>
        </div>
    </div>
@endsection