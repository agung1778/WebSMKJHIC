@extends('layouts.admin-app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 border-b border-slate-200 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Edit Proyek</h1>
                <p class="text-xs text-slate-500 mt-1">Memperbarui data: <span
                        class="font-semibold text-slate-800">{{ $pkk->title }}</span></p>
            </div>
            <a href="{{ route('admin.pkk.index') }}"
                class="text-slate-500 hover:text-[#6CF600] font-semibold transition-colors flex items-center gap-2 text-sm bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form action="{{ route('admin.pkk.update', $pkk->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Kolom Kiri --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Identitas Brand --}}
                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200">
                        <h2 class="text-sm font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-store text-[#6CF600]"></i> Identitas Brand
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama
                                    Brand</label>
                                <input type="text" name="brand_name" value="{{ old('brand_name', $pkk->brand_name) }}"
                                    class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:ring-1 focus:ring-[#6CF600] focus:bg-white outline-none transition-all text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Logo
                                    Brand (Ganti?)</label>
                                <input type="file" name="logo"
                                    class="w-full px-4 py-2 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:border-[#6CF600]">
                                @if ($pkk->logo)
                                    <div
                                        class="mt-3 flex items-center gap-3 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                                        <img src="{{ asset('storage/' . $pkk->logo) }}"
                                            class="w-10 h-10 rounded border border-slate-200 object-contain bg-white">
                                        <span class="text-xs text-slate-500 font-bold">Logo Saat Ini</span>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Media
                                    Sosial & Kontak</label>
                                <div class="space-y-3">
                                    <input type="url" name="social_media"
                                        value="{{ old('social_media', $pkk->social_media) }}" placeholder="Link URL"
                                        class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:ring-1 focus:ring-[#6CF600] focus:bg-white outline-none text-sm">
                                    <input type="text" name="contact_info"
                                        value="{{ old('contact_info', $pkk->contact_info) }}" placeholder="No. WhatsApp"
                                        class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:ring-1 focus:ring-[#6CF600] focus:bg-white outline-none text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Produk --}}
                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200">
                        <h2 class="text-sm font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-edit text-blue-500"></i> Detail Produk
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul
                                    Proyek</label>
                                <input type="text" name="title" required value="{{ old('title', $pkk->title) }}"
                                    class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:ring-1 focus:ring-[#6CF600] focus:bg-white outline-none transition-all text-sm">
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori</label>
                                <select name="category" required
                                    class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:ring-1 focus:ring-[#6CF600] focus:bg-white outline-none text-sm">
                                    @foreach (['Wirausaha (Makanan/Minuman)', 'Wirausaha (Jasa)', 'Kerajinan Tangan', 'Teknologi Tepat Guna', 'Rekayasa Perangkat Lunak'] as $cat)
                                        <option value="{{ $cat }}" {{ $pkk->category == $cat ? 'selected' : '' }}>
                                            {{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Harga</label>
                                <input type="number" name="price" value="{{ old('price', $pkk->price) }}"
                                    class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:ring-1 focus:ring-[#6CF600] focus:bg-white outline-none text-sm">
                            </div>

                            <div class="md:col-span-2">
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi</label>
                                <textarea name="description" rows="5" required
                                    class="w-full px-4 py-3 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:ring-1 focus:ring-[#6CF600] focus:bg-white outline-none resize-none text-sm">{{ old('description', $pkk->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-6">

                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200">
                        <h2 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-camera text-slate-500"></i> Foto Produk
                        </h2>
                        <div class="rounded-xl overflow-hidden mb-4 border border-slate-200 shadow-sm bg-slate-50">
                            <img src="{{ asset('storage/' . $pkk->photo) }}" class="w-full h-48 object-cover">
                        </div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ganti
                            Foto?</label>
                        <input type="file" name="photo"
                            class="w-full px-4 py-2 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:border-[#6CF600]">
                    </div>

                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200">
                        <h2 class="text-sm font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-users text-amber-500"></i> Tim Pengembang
                        </h2>
                        <div class="space-y-5">
                            <input type="text" name="student_names"
                                value="{{ old('student_names', $pkk->student_names) }}" required placeholder="Nama Siswa"
                                class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:bg-white outline-none text-sm">
                            <input type="text" name="student_class"
                                value="{{ old('student_class', $pkk->student_class) }}" required placeholder="Kelas"
                                class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:bg-white outline-none text-sm">
                            <select name="major_id" required
                                class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:bg-white outline-none text-sm">
                                @foreach ($majors as $major)
                                    <option value="{{ $major->id }}"
                                        {{ $pkk->major_id == $major->id ? 'selected' : '' }}>{{ $major->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#6CF600] text-black py-3.5 rounded-xl font-bold text-sm shadow-sm hover:bg-[#5bd300] transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
