@extends('layouts.admin-app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
            <div class="flex justify-between items-center mb-8 pb-4 border-b border-slate-100">
                <h1 class="text-2xl font-bold text-slate-800">{{ __('Edit Menu Navigasi') }}</h1>
                <a href="{{ route('admin.navigations.index') }}"
                    class="text-slate-500 hover:text-[#6CF600] transition-colors flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-arrow-left"></i> {{ __('Kembali') }}
                </a>
            </div>

            <form action="{{ route('admin.navigations.update', $navigation->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Label Menu') }}</label>
                        <input type="text" name="title" value="{{ old('title', $navigation->title) }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white transition-all text-sm @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('URL / Link Tujuan') }}</label>
                        <input type="text" name="url" value="{{ old('url', $navigation->url) }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white transition-all text-sm @error('url') border-red-500 @enderror">
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
                            <option value="top_bar" @if (old('position', $navigation->position) == 'top_bar') selected @endif>{{ __('Top Bar') }} ({{ __('Atas') }})
                            </option>
                            <option value="main_menu" @if (old('position', $navigation->position) == 'main_menu') selected @endif>{{ __('Menu Utama') }} ({{ __('Hijau') }})
                            </option>
                            <option value="footer" @if (old('position', $navigation->position) == 'footer') selected @endif>{{ __('Footer') }} ({{ __('Bawah') }})</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Tipe Tampilan') }}</label>
                        <select name="type"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white transition-all text-sm">
                            <option value="link" @if (old('type', $navigation->type) == 'link') selected @endif>{{ __('Teks Link Biasa') }}
                            </option>
                            <option value="button" @if (old('type', $navigation->type) == 'button') selected @endif>{{ __('Tombol / Button') }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Target Klik') }}</label>
                        <select name="target"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white transition-all text-sm">
                            <option value="_self" @if (old('target', $navigation->target) == '_self') selected @endif>{{ __('Tab Sama') }} (_self)
                            </option>
                            <option value="_blank" @if (old('target', $navigation->target) == '_blank') selected @endif>{{ __('Tab Baru') }} (_blank)
                            </option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Nomor Urutan') }}</label>
                        <input type="number" name="order" value="{{ old('order', $navigation->order) }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white transition-all text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Status Publikasi') }}</label>
                        <select name="is_active"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white transition-all text-sm">
                            <option value="1" @if (old('is_active', $navigation->is_active) == '1') selected @endif>{{ __('Aktif / Tampilkan') }}
                            </option>
                            <option value="0" @if (old('is_active', $navigation->is_active) == '0') selected @endif>{{ __('Sembunyikan') }}</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit"
                        class="bg-[#6CF600] text-black px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-[#5bd300] transition-colors shadow-sm">
                        {{ __('Perbarui Menu') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
