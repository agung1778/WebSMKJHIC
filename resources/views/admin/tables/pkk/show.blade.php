@extends('layouts.admin-app')

@push('styles')
    <style>
        .prose p {
            margin-bottom: 1em;
            line-height: 1.6;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 pb-4 border-b border-slate-200">
            <div>
                <a href="{{ route('admin.pkk.index') }}"
                    class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-800 transition-colors font-medium text-xs mb-2">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Detail Proyek Siswa</h1>
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <a href="{{ route('admin.pkk.edit', $pkk->id) }}"
                    class="flex-1 md:flex-none text-center bg-amber-50 text-amber-600 hover:bg-amber-100 px-4 py-2 rounded-xl text-sm font-bold transition-colors flex items-center justify-center gap-2">
                    <i class="fa-regular fa-pen-to-square"></i> Edit Data
                </a>
                <form action="{{ route('admin.pkk.destroy', $pkk->id) }}" method="POST" class="flex-1 md:flex-none"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data proyek ini secara permanen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full text-center bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-xl text-sm font-bold transition-colors flex items-center justify-center gap-2">
                        <i class="fa-regular fa-trash-can"></i> Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none">
                        <i class="fa-solid fa-certificate text-8xl text-slate-800"></i>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-5 relative z-10">
                        <div class="shrink-0">
                            @if ($pkk->logo)
                                <div
                                    class="w-24 h-24 rounded-2xl border border-slate-100 shadow-sm bg-white p-2 flex items-center justify-center">
                                    <img src="{{ asset('storage/' . $pkk->logo) }}"
                                        class="max-w-full max-h-full object-contain">
                                </div>
                            @else
                                <div
                                    class="w-24 h-24 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
                                    <i class="fa-solid fa-store text-3xl"></i>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                <span
                                    class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-blue-100">
                                    {{ $pkk->category }}
                                </span>
                                @if ($pkk->brand_name)
                                    <span
                                        class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-l border-slate-300 pl-2">
                                        {{ $pkk->brand_name }}
                                    </span>
                                @endif
                            </div>

                            <h2 class="text-3xl font-extrabold text-slate-900 leading-tight mb-2">{{ $pkk->title }}</h2>

                            <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                                <i class="fa-regular fa-calendar"></i>
                                <span>Diupload pada: {{ $pkk->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                        <i class="fa-regular fa-image text-slate-400"></i>
                        <span class="text-sm font-bold text-slate-700">Visualisasi Produk</span>
                    </div>
                    <div class="bg-slate-100 p-4 flex justify-center">
                        <img src="{{ asset('storage/' . $pkk->photo) }}"
                            class="w-full h-auto object-contain max-h-[400px] rounded-xl shadow-sm bg-white">
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
                    <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                        <i class="fa-solid fa-align-left text-slate-400"></i>
                        <span class="text-sm font-bold text-slate-700">Deskripsi & Detail</span>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none text-slate-600 text-sm leading-relaxed">
                            {!! nl2br(e($pkk->description)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Estimasi Harga</p>
                    @if ($pkk->price)
                        <div class="flex items-baseline gap-1">
                            <span class="text-sm font-semibold text-slate-500">Rp</span>
                            <span
                                class="text-3xl font-extrabold text-[#6CF600] tracking-tight">{{ number_format($pkk->price, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2 italic">*Harga dapat berubah sewaktu-waktu</p>
                    @else
                        <span class="text-lg font-bold text-slate-400 italic">Hubungi untuk harga</span>
                    @endif
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-4 bg-slate-800 text-white flex items-center justify-between">
                        <span class="font-bold text-sm">Informasi Kontak</span>
                        <i class="fa-regular fa-address-card opacity-50"></i>
                    </div>
                    <div class="p-5 space-y-5">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-[#6CF600]/20 text-[#5bd300] flex items-center justify-center shrink-0">
                                <i class="fa-brands fa-whatsapp text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">WhatsApp / Telp</p>
                                @if ($pkk->contact_info)
                                    <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $pkk->contact_info }}</p>
                                @else
                                    <p class="text-xs text-slate-400 italic mt-0.5">Tidak tersedia</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-link text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Media Sosial / Link
                                </p>
                                @if ($pkk->social_media)
                                    <a href="{{ $pkk->social_media }}" target="_blank"
                                        class="text-sm font-bold text-blue-600 hover:text-blue-800 hover:underline break-all mt-0.5 inline-block transition-colors">
                                        Kunjungi Tautan <i
                                            class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i>
                                    </a>
                                @else
                                    <p class="text-xs text-slate-400 italic mt-0.5">Tidak tersedia</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <span class="font-bold text-sm text-slate-700">Tim Pengembang</span>
                        <i class="fa-solid fa-users text-slate-300"></i>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center gap-4 mb-5">
                            <div
                                class="w-12 h-12 rounded-full bg-slate-800 text-[#6CF600] flex items-center justify-center shadow-sm shrink-0">
                                <i class="fa-solid fa-user-graduate text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Siswa /
                                    Kelompok</p>
                                <p class="font-bold text-slate-800 leading-tight">{{ $pkk->student_names }}</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500 font-medium">Kelas</span>
                                <span class="font-bold text-slate-800">{{ $pkk->student_class }}</span>
                            </div>
                            <div class="w-full h-px bg-slate-200"></div>
                            <div class="flex justify-between items-start text-sm gap-4">
                                <span class="text-slate-500 font-medium shrink-0 mt-0.5">Jurusan</span>
                                <span class="font-bold text-[#6CF600] text-right leading-tight">
                                    {{ $pkk->major->name ?? 'Jurusan Tidak Ditemukan' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
