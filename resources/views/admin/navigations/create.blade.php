@extends('layouts.admin-app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
            <div class="flex justify-between items-center mb-8 pb-4 border-b border-slate-100">
                <h1 class="text-2xl font-bold text-slate-800">{{ __('Tambah Menu Navigasi') }}</h1>
                <a href="{{ route('admin.navigations.index') }}"
                    class="text-slate-500 hover:text-[#6CF600] transition-colors flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-arrow-left"></i> {{ __('Kembali') }}
                </a>
            </div>

            <form action="{{ route('admin.navigations.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Label Menu') }}</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white transition-all text-sm @error('title') border-red-500 @enderror"
                            placeholder="{{ __('Contoh: Info SPMB') }}">
                        @error('title')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('URL / Link Tujuan') }}</label>
                        <input type="text" name="url" value="{{ old('url') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white transition-all text-sm @error('url') border-red-500 @enderror"
                            placeholder="{{ __('Contoh: /info-spmb atau https://google.com') }}">
                        @error('url')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Posisi Menu') }}</label>
                        <select name="position"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white transition-all text-sm">
                            <option value="top_bar" @if (old('position') == 'top_bar') selected @endif>{{ __('Top Bar') }} ({{ __('Atas') }})
                            </option>
                            <option value="main_menu" @if (old('position') == 'main_menu') selected @endif>{{ __('Menu Utama') }} ({{ __('Hijau') }})
                            </option>
                            <option value="footer" @if (old('position') == 'footer') selected @endif>{{ __('Footer') }} ({{ __('Bawah') }})</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Tipe Tampilan') }}</label>
                        <select name="type"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white transition-all text-sm">
                            <option value="link" @if (old('type') == 'link') selected @endif>{{ __('Teks Link Biasa') }}
                            </option>
                            <option value="button" @if (old('type') == 'button') selected @endif>{{ __('Tombol / Button') }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Target Klik') }}</label>
                        <select name="target"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white transition-all text-sm">
                            <option value="_self" @if (old('target') == '_self') selected @endif>{{ __('Tab Sama') }} (_self)
                            </option>
                            <option value="_blank" @if (old('target') == '_blank') selected @endif>{{ __('Tab Baru') }} (_blank)
                            </option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Nomor Urutan') }}</label>
                        <input type="number" name="order" value="{{ old('order', 1) }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white transition-all text-sm"
                            placeholder="1">
                        <p class="text-[10px] text-slate-500 mt-1">{{ __('Angka lebih kecil akan tampil lebih dulu (paling kiri/atas).') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Status Publikasi') }}</label>
                        <select name="is_active"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white transition-all text-sm">
                            <option value="1" @if (old('is_active') == '1') selected @endif>{{ __('Aktif / Tampilkan') }}
                            </option>
                            <option value="0" @if (old('is_active') == '0') selected @endif>{{ __('Sembunyikan') }}</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit"
                        class="bg-[#6CF600] text-black px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-[#5bd300] transition-colors shadow-sm">
                        {{ __('Simpan Menu') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
