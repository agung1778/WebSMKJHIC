@extends('layouts.admin-app')

@section('title', 'Tambah Program')

@section('content')
    <div class="max-w-3xl mx-auto animate-fadein">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-[20px] font-extrabold" style="color:var(--text)">Tambah Program</h2>
                <p class="text-[13px] mt-0.5" style="color:var(--text-3)">Program baru akan menyimpan data program pendidikan.</p>
            </div>
            <a href="{{ route('admin.programs.index') }}" class="app-btn">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="app-card app-card-pad">
            <form id="program-form" action="{{ route('admin.programs.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-5">
                        <div>
                            <label for="name" class="block text-[13px] font-semibold mb-1.5" style="color:var(--text-2)">Nama Program <span style="color:var(--rose)">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="app-input {{ $errors->has('name') ? 'has-error' : '' }}" placeholder="cth: Program Keahlian Rekayasa Perangkat Lunak">
                            @error('name')
                                <p class="text-[12px] mt-1" style="color:var(--rose)"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-[13px] font-semibold mb-1.5" style="color:var(--text-2)">Deskripsi <span style="color:var(--rose)">*</span></label>
                            <textarea name="description" id="description" rows="7"
                                class="app-textarea {{ $errors->has('description') ? 'has-error' : '' }}" placeholder="Jelaskan program pendidikan ini…">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-[12px] mt-1" style="color:var(--rose)"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-[13px] font-semibold mb-1.5" style="color:var(--text-2)">Status Publikasi</label>
                            <select name="status" id="status" class="app-select">
                                <option value="published" @selected(old('status', 'published') === 'published')>Published — tampil di website</option>
                                <option value="draft" @selected(old('status') === 'draft')>Draft — belum tampil</option>
                                <option value="archived" @selected(old('status') === 'archived')>Archived — disimpan</option>
                            </select>
                            <p class="text-[11.5px] mt-1.5" style="color:var(--text-3)">Program berstatus Published langsung tampil di halaman program publik.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold mb-1.5" style="color:var(--text-2)">Gambar <span style="color:var(--rose)">*</span></label>
                        <label for="image" id="dropzone" class="block rounded-xl cursor-pointer text-center p-6 transition"
                            style="border:2px dashed var(--border-strong);background:var(--surface-2)">
                            <div id="dz-empty">
                                <i class="fa-solid fa-cloud-arrow-up text-2xl" style="color:var(--text-3)"></i>
                                <p class="text-[12.5px] mt-2 font-medium" style="color:var(--text-2)">Klik untuk unggah</p>
                                <p class="text-[11px] mt-0.5" style="color:var(--text-3)">JPEG, PNG, GIF, SVG · maks 2MB</p>
                            </div>
                            <img id="dz-preview" class="hidden w-full rounded-lg object-cover mt-2" style="max-height:180px" alt="Pratinjau gambar">
                        </label>
                        <input type="file" name="image" id="image" class="hidden" accept="image/*">
                        @error('image')
                            <p class="text-[12px] mt-1" style="color:var(--rose)"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-8 pt-6" style="border-top:1px solid var(--border)">
                    <span id="save-indicator" class="text-[12px] mr-auto" style="color:var(--text-3)"><i class="fa-solid fa-circle-notch fa-spin hidden mr-1"></i><span>Belum ada perubahan</span></span>
                    <a href="{{ route('admin.programs.index') }}" class="app-btn">Batal</a>
                    <button type="submit" class="app-btn app-btn-primary app-btn-lg">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Program
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('program-form');
            var fileInput = document.getElementById('image');
            var dropzone = document.getElementById('dropzone');
            var preview = document.getElementById('dz-preview');
            var empty = document.getElementById('dz-empty');
            var dirty = false;

            form.addEventListener('input', function () { dirty = true; markDirty(); });
            fileInput.addEventListener('change', function () {
                dirty = true; markDirty();
                var file = fileInput.files[0];
                if (!file) return;
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    empty.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            });

            function markDirty() {
                var ind = document.querySelector('#save-indicator span');
                if (ind && ind.textContent !== 'Perubahan belum disimpan') ind.textContent = 'Perubahan belum disimpan';
            }

            window.addEventListener('beforeunload', function (e) {
                if (!dirty) return;
                e.preventDefault();
                e.returnValue = '';
            });
        });
    </script>
@endpush