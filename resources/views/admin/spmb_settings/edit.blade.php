@extends('layouts.admin-app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3">
                <i class="fa-solid fa-circle-check"></i>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
            <div class="flex justify-between items-center mb-8 pb-4 border-b border-slate-100">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">{{ __('Pengaturan Info SPMB') }}</h1>
                    <p class="text-xs text-slate-500 mt-1">{{ __('Atur teks, kuota, dan gambar brosur untuk halaman depan.') }}</p>
                </div>
            </div>

            <form action="{{ route('admin.spmb_settings.update') }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf
                @method('PUT')

                {{-- SECTION 1: TEXT & STATUS --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2 border-b pb-2">
                        <i class="fa-solid fa-pen-nib text-[#6CF600]"></i> {{ __('Konten Teks & Status') }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Status Pendaftaran') }}</label>
                            <select name="status"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] transition-all text-sm">
                                <option value="Buka" {{ $setting->status == 'Buka' ? 'selected' : '' }}>{{ __('Pendaftaran Buka') }}
                                </option>
                                <option value="Tutup" {{ $setting->status == 'Tutup' ? 'selected' : '' }}>{{ __('Pendaftaran Tutup') }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Nama Gelombang / Badge Teks') }}</label>
                            <input type="text" name="wave_name" value="{{ old('wave_name', $setting->wave_name) }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] transition-all text-sm"
                                placeholder="{{ __('Cth: Gelombang Inden Dibuka!') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Periode Pendaftaran') }}</label>
                            <input type="text" name="period_date" value="{{ old('period_date', $setting->period_date) }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] transition-all text-sm"
                                placeholder="{{ __('Cth: 1 Oktober 2025 - 4 Januari 2026') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Keterangan Tambahan / Kuota') }}</label>
                            <input type="text" name="quota_note" value="{{ old('quota_note', $setting->quota_note) }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] transition-all text-sm"
                                placeholder="{{ __('Cth: *Kuota Terbatas') }}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Link Pendaftaran (PPDB)') }}</label>
                            <input type="url" name="registration_link"
                                value="{{ old('registration_link', $setting->registration_link) }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] transition-all text-sm"
                                placeholder="{{ __('Cth: https://ppdb.smkamaliah.sch.id') }}">
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: IMAGES & FILES --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2 border-b pb-2">
                        <i class="fa-solid fa-images text-blue-500"></i> {{ __('Media & Brosur') }}
                    </h2>
                    <p class="text-xs text-slate-500 mb-4">{{ __('*Kosongkan file jika tidak ingin mengubah gambar/file yang sudah ada.') }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Brosur Miring Kiri --}}
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
<label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Pratinjau Brosur') }}
    ({{ __('Belakang/Kiri') }})</label>
                            <input type="file" name="brochure_image_1" accept="image/*" class="w-full text-sm mb-3">
                            @if ($setting->brochure_image_1)
                                <img src="{{ asset('storage/' . $setting->brochure_image_1) }}" alt="Brosur 1"
                                    class="h-24 object-cover rounded shadow-sm border border-slate-200">
                            @endif
                        </div>

                        {{-- Brosur Miring Kanan --}}
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Pratinjau Brosur') }} ({{ __('Depan/Kanan') }})</label>
                            <input type="file" name="brochure_image_2" accept="image/*" class="w-full text-sm mb-3">
                            @if ($setting->brochure_image_2)
                                <img src="{{ asset('storage/' . $setting->brochure_image_2) }}" alt="Brosur 2"
                                    class="h-24 object-cover rounded shadow-sm border border-slate-200">
                            @endif
                        </div>

                        {{-- Brosur Fullscreen Modal --}}
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
<label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Brosur Penuh') }} ({{ __('Untuk Modal Pop-up') }})</label>
                            <input type="file" name="brochure_full_image" accept="image/*" class="w-full text-sm mb-3">
                            @if ($setting->brochure_full_image)
                                <img src="{{ asset('storage/' . $setting->brochure_full_image) }}" alt="Brosur Penuh"
                                    class="h-24 object-cover rounded shadow-sm border border-slate-200">
                            @endif
                        </div>

                        {{-- File PDF Download --}}
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('File Brosur Download (PDF)') }}</label>
                            <input type="file" name="brochure_file" accept="application/pdf" class="w-full text-sm mb-3">
                            @if ($setting->brochure_file)
                                <div class="flex items-center gap-2 text-sm text-green-600 font-bold">
                                    <i class="fa-solid fa-file-pdf"></i> {{ __('PDF Tersedia') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end">
                    <button type="submit"
                        class="bg-[#6CF600] text-black px-8 py-3 rounded-xl text-sm font-bold hover:bg-[#5bd300] transition-colors shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> {{ __('Simpan Pengaturan SPMB') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
