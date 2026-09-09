@extends('layouts.public-app')

@section('title', __('Syarat & Ketentuan') . ' — SMK Amaliah')
@section('description', __('Syarat dan ketentuan penggunaan situs web resmi SMK Amaliah 1 & 2 Ciawi-Bogor.'))

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
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">{{ __('Syarat & Ketentuan') }}</h1>
                <p class="mt-3 text-gray-400 max-w-xl mx-auto">{{ __('Ketentuan yang berlaku bagi pengunjung saat menggunakan situs web SMK Amaliah 1 & 2 Ciawi.') }}</p>
            </div>
        </div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed space-y-8">

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('1. Penerimaan Ketentuan') }}</h2>
                    <p>{{ __('Dengan mengakses dan menggunakan situs ini, Anda dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang tercantum di halaman ini. Jika Anda tidak setuju dengan sebagian atau seluruh ketentuan ini, mohon untuk tidak menggunakan situs ini.') }}</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('2. Penggunaan Konten') }}</h2>
                    <p>{{ __('Seluruh konten pada situs ini — termasuk teks, gambar, logo, dan materi lainnya — adalah milik SMK Amaliah 1 & 2 Ciawi dan dilindungi hak cipta. Anda diperkenankan mengutip atau membagikan informasi untuk keperluan pribadi dan pendidikan dengan mencantumkan sumber. Penggunaan konten untuk tujuan komersial memerlukan izin tertulis dari pihak sekolah.') }}</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('3. Akurasi Informasi') }}</h2>
                    <p>{{ __('Kami berupaya menyajikan informasi (berita, jurusan, kegiatan, dan informasi pendaftaran) secara akurat dan terkini. Namun demikian, kami tidak menjamin bahwa seluruh informasi bebas dari kesalahan. Untuk informasi resmi terkait pendaftaran, mohon mengacu pada kanal resmi SPMB SMK Amaliah.') }}</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('4. Tautan ke Situs Lain') }}</h2>
                    <p>{{ __('Situs ini dapat memuat tautan ke situs pihak ketiga seperti platform SPMB, E-Learning, ujian online, dan media sosial. Kami tidak bertanggung jawab atas isi, kebijakan privasi, maupun praktik situs pihak ketiga tersebut. Penggunaan tautan tersebut sepenuhnya menjadi tanggung jawab Anda.') }}</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('5. Perilaku Pengguna') }}</h2>
                    <p>{{ __('Dilarang menggunakan situs ini untuk tujuan yang melanggar hukum, termasuk namun tidak terbatas pada penyebaran virus, peretasan, spam, atau tindakan lain yang dapat merusak kelancaran dan keamanan layanan website sekolah.') }}</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('6. Perubahan Ketentuan') }}</h2>
                    <p>{{ __('Kami dapat memperbarui syarat dan ketentuan ini sewaktu-waktu. Perubahan akan berlaku segera setelah dipublikasikan pada halaman ini. Anda disarankan untuk meninjau halaman ini secara berkala. Pertanyaan lebih lanjut dapat disampaikan melalui email ') }}
                        <a href="mailto:smkamaliahciawi@gmail.com" class="font-semibold" style="color: {{ $amaliahGreen }};">smkamaliahciawi@gmail.com</a>.</p>
                </section>

                <p class="text-sm text-gray-400 pt-8 border-t border-gray-200">{{ __('Terakhir diperbarui: September 2026') }}</p>
            </div>
        </div>
    </div>
@endsection