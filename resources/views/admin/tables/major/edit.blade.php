@extends('layouts.admin-app')
@section('content')

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit Jurusan</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f0f2f5;
            }

            /* === 2. TAMBAHKAN INI: Styling Kustom untuk Trix Editor === */
            trix-toolbar [data-trix-button-group="file-tools"] {
                display: none;
                /* Sembunyikan tombol upload file bawaan */
            }

            trix-editor {
                background-color: white;
                min-height: 200px;
                /* Atur tinggi minimal editor */
                border-radius: 0.5rem;
                border-width: 1px;
                border-color: #D1D5DB;
            }

            /* Efek focus ring agar konsisten */
            trix-editor:focus-within {
                outline: 2px solid transparent;
                outline-offset: 2px;
                border-color: #6CF600;
                box-shadow: 0 0 0 2px #6CF600;
            }

            /* Style untuk error state */
            trix-editor.border-red-500 {
                border-color: #EF4444;
            }

            /* ======================================================== */
        </style>
    </head>

    <body class="bg-gray-100">

        <div class="main-content flex-1 p-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-[#292929]">Edit Jurusan</h1>
                    <a href="{{ route('admin.majors.index') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Kembali</span>
                    </a>
                </div>

                <form action="{{ route('admin.majors.update', $major->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Jurusan</label>
                        <input type="text" name="name" id="name"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('name') border-red-500 @enderror"
                            value="{{ old('name', $major->name) }}">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>

                        <input id="description" type="hidden" name="description"
                            value="{{ old('description', $major->description) }}">

                        <trix-editor input="description"
                            class="@error('description') border-red-500 @enderror"></trix-editor>

                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="tag" class="block text-sm font-medium text-gray-700 mb-1">Tag/Kata Kunci (Dipisahkan
                            koma)</label>
                        <textarea name="tag" id="tag" rows="2"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('tag') border-red-500 @enderror">{{ old('tag', $major->tag) }}</textarea>
                        @error('tag')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="advantage" class="block text-sm font-medium text-gray-700 mb-1">Poin Keunggulan
                            (Dipisahkan baris baru)</label>
                        <textarea name="advantage" id="advantage" rows="4"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('advantage') border-red-500 @enderror">{{ old('advantage', $major->advantage) }}</textarea>
                        @error('advantage')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar Jurusan</label>
                        <input type="file" name="image" id="image"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('image') border-red-500 @enderror">
                        @if ($major->image)
                            <p class="text-xs text-gray-500 mt-2">Gambar saat ini:</p>
                            <img src="{{ asset('storage/' . $major->image) }}" alt="Gambar Jurusan"
                                class="w-32 h-32 object-cover rounded-md mt-2">
                        @endif
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo Jurusan
                            (PNG/SVG)</label>
                        <input type="file" name="logo" id="logo"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('logo') border-red-500 @enderror">
                        @if ($major->logo)
                            <p class="text-xs text-gray-500 mt-2">Logo saat ini:</p>
                            <img src="{{ asset('storage/' . $major->logo) }}" alt="Logo Jurusan"
                                class="w-20 h-20 object-contain rounded-md mt-2 border border-gray-200 p-1">
                        @endif
                        @error('logo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="competency_head" class="block text-sm font-medium text-gray-700 mb-1">Kepala
                            Kompetensi</label>
                        <input type="text" name="competency_head" id="competency_head"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('competency_head') border-red-500 @enderror"
                            value="{{ old('competency_head', $major->competency_head) }}">
                        @error('competency_head')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="competency_head_photo" class="block text-sm font-medium text-gray-700 mb-1">Foto Kepala
                            Kompetensi</label>
                        <input type="file" name="competency_head_photo" id="competency_head_photo"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('competency_head_photo') border-red-500 @enderror">
                        @if ($major->competency_head_photo)
                            <p class="text-xs text-gray-500 mt-2">Foto saat ini:</p>
                            <img src="{{ asset('storage/' . $major->competency_head_photo) }}" alt="Foto Kepala Kompetensi"
                                class="w-32 h-32 object-cover rounded-md mt-2">
                        @endif
                        @error('competency_head_photo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit"
                            class="bg-[#6CF600] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#5bd300] transition-colors duration-200">
                            Perbarui Jurusan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </body>

    </html>
@endsection