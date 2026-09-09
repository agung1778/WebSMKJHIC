@extends('layouts.admin-app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 border-b border-slate-200 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Tambah Proyek Baru</h1>
                <p class="text-xs text-slate-500 mt-1">Isi formulir berikut untuk menambahkan karya siswa.</p>
            </div>
            <a href="{{ route('admin.pkk.index') }}"
                class="text-slate-500 hover:text-[#6CF600] font-semibold transition-colors flex items-center gap-2 text-sm bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form action="{{ route('admin.pkk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Kolom Kiri --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Identitas Usaha --}}
                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200">
                        <h2 class="text-sm font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-store text-[#6CF600]"></i> Identitas Usaha (Opsional)
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama
                                    Brand / Merek</label>
                                <input type="text" name="brand_name" value="{{ old('brand_name') }}"
                                    placeholder="Contoh: Chips Mantap"
                                    class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:ring-1 focus:ring-[#6CF600] focus:bg-white outline-none transition-all text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Logo
                                    Brand</label>
                                <div
                                    class="relative group cursor-pointer border-2 border-dashed border-slate-300 rounded-xl p-4 text-center hover:border-[#6CF600] transition-colors bg-slate-50 flex flex-col items-center justify-center min-h-[120px]">
                                    <input type="file" name="logo" id="logoInput"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                        onchange="previewImage(event, 'logoPreview')">
                                    <div id="logoPreviewContainer" class="hidden">
                                        <img id="logoPreview" src="#"
                                            class="mx-auto h-16 w-auto object-contain mb-2 rounded border border-slate-200 bg-white p-1">
                                        <span class="text-[10px] text-[#6CF600] font-bold uppercase tracking-wider">Ganti
                                            Logo</span>
                                    </div>
                                    <div id="logoPlaceholder">
                                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-300 mb-2"></i>
                                        <p class="text-xs text-slate-500 font-medium">Upload Logo</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Media
                                    Sosial & Kontak</label>
                                <div class="space-y-3">
                                    <input type="url" name="social_media" value="{{ old('social_media') }}"
                                        placeholder="Link Instagram/Web"
                                        class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:ring-1 focus:ring-[#6CF600] focus:bg-white outline-none text-sm">
                                    <input type="text" name="contact_info" value="{{ old('contact_info') }}"
                                        placeholder="No. WhatsApp"
                                        class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:ring-1 focus:ring-[#6CF600] focus:bg-white outline-none text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Produk --}}
                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200">
                        <h2 class="text-sm font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-box-open text-blue-500"></i> Detail Produk
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul
                                    Proyek <span class="text-red-500">*</span></label>
                                <input type="text" name="title" required value="{{ old('title') }}"
                                    class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:ring-1 focus:ring-[#6CF600] focus:bg-white outline-none transition-all text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori
                                    <span class="text-red-500">*</span></label>
                                <select name="category" required
                                    class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:ring-1 focus:ring-[#6CF600] focus:bg-white outline-none text-sm">
                                    <option value="Wirausaha (Makanan/Minuman)">Wirausaha (Makanan/Minuman)</option>
                                    <option value="Wirausaha (Jasa)">Wirausaha (Jasa)</option>
                                    <option value="Kerajinan Tangan">Kerajinan Tangan</option>
                                    <option value="Teknologi Tepat Guna">Teknologi Tepat Guna</option>
                                    <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Harga
                                    (Opsional)</label>
                                <input type="number" name="price" value="{{ old('price') }}" placeholder="Contoh: 50000"
                                    class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:ring-1 focus:ring-[#6CF600] focus:bg-white outline-none text-sm">
                            </div>

                            <div class="md:col-span-2">
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi
                                    Lengkap <span class="text-red-500">*</span></label>
                                <textarea name="description" rows="5" required
                                    class="w-full px-4 py-3 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:ring-1 focus:ring-[#6CF600] focus:bg-white outline-none resize-none text-sm">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-6">

                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200">
                        <h2 class="text-sm font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-camera text-slate-500"></i> Foto Produk <span
                                class="text-red-500">*</span>
                        </h2>
                        <div
                            class="relative group cursor-pointer border-2 border-dashed border-slate-300 rounded-xl p-2 h-64 hover:border-[#6CF600] transition-colors bg-slate-50 flex items-center justify-center overflow-hidden">
                            <input type="file" name="photo" required accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                onchange="previewImage(event, 'photoPreview')">
                            <img id="photoPreview" class="hidden w-full h-full object-cover rounded-lg">
                            <div id="photoPlaceholder" class="text-center p-4">
                                <i class="fa-solid fa-cloud-arrow-up text-4xl text-slate-300 mb-3 block"></i>
                                <p class="text-sm text-slate-500 font-medium">Klik untuk upload foto</p>
                                <p class="text-xs text-slate-400 mt-1">JPG/PNG, Max 2MB</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200">
                        <h2 class="text-sm font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-users text-amber-500"></i> Tim Pengembang
                        </h2>
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama
                                    Siswa / Kelompok</label>
                                <input type="text" name="student_names" value="{{ old('student_names') }}" required
                                    placeholder="Ex: Ahmad, Budi"
                                    class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:bg-white outline-none text-sm">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kelas</label>
                                <input type="text" name="student_class" value="{{ old('student_class') }}" required
                                    placeholder="Ex: XII RPL 1"
                                    class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:bg-white outline-none text-sm">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jurusan</label>
                                <select name="major_id" required
                                    class="w-full px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-[#6CF600] focus:bg-white outline-none text-sm">
                                    <option value="">-- Pilih Jurusan --</option>
                                    @foreach ($majors as $major)
                                        <option value="{{ $major->id }}">{{ $major->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#6CF600] text-black py-3.5 rounded-xl font-bold text-sm shadow-sm hover:bg-[#5bd300] transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save"></i> Simpan Proyek
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event, previewId) {
            const input = event.target;
            const preview = document.getElementById(previewId);
            const placeholderId = previewId === 'logoPreview' ? 'logoPlaceholder' : 'photoPlaceholder';
            const containerId = previewId === 'logoPreview' ? 'logoPreviewContainer' : null;
            const placeholder = document.getElementById(placeholderId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                    if (containerId) document.getElementById(containerId).classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
