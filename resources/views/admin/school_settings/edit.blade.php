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
                    <h1 class="text-2xl font-bold text-slate-800">{{ __('Statistik Sekolah') }}</h1>
                    <p class="text-xs text-slate-500 mt-1">{{ __('Atur data statistik yang tampil di halaman depan (Peserta Didik).') }}</p>
                </div>
            </div>

            <form action="{{ route('admin.school_settings.update') }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <div>
                    <h2 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2 border-b pb-2">
                        <i class="fa-solid fa-users text-[#6CF600]"></i> {{ __('Peserta Didik') }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Total Siswa') }}</label>
                            <input type="number" name="jumlah_siswa" min="0"
                                value="{{ old('jumlah_siswa', $setting->jumlah_siswa) }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] transition-all text-sm"
                                placeholder="{{ __('Cth: 1160') }}">
                            @error('jumlah_siswa')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Tahun Ajaran (opsional)') }}</label>
                            <input type="text" name="tahun_ajaran"
                                value="{{ old('tahun_ajaran', $setting->tahun_ajaran) }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] transition-all text-sm"
                                placeholder="{{ __('Cth: 2025/2026') }}">
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mt-3">
                        {{ __('Angka ini akan selalu diperbarui manual oleh admin tiap tahun ajaran dan langsung tampil di homepage.') }}
                    </p>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end">
                    <button type="submit"
                        class="bg-[#6CF600] text-black px-8 py-3 rounded-xl text-sm font-bold hover:bg-[#5bd300] transition-colors shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> {{ __('Simpan Pengaturan') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection