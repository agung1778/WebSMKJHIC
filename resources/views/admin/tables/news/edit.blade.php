@extends('layouts.admin-app')

@section('content')

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit Berita</title>
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

            /* === 2. TAMBAHKAN INI: Styling Kustom untuk Trix Editor === -->
            trix-toolbar [data-trix-button-group="file-tools"] {
                display: none; /* Sembunyikan tombol upload file bawaan */
            }
            
            trix-editor {
                background-color: white;
                min-height: 250px; /* Atur tinggi minimal editor */
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
                    <h1 class="text-2xl font-bold text-[#292929]">Edit Berita</h1>
                    <a href="{{ route('admin.news.index') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Kembali</span>
                    </a>
                </div>

                <form action="{{ route('admin.news.update', $newsItem->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Berita</label>
                        <input type="text" name="title" id="title"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('title') border-red-500 @enderror"
                            value="{{ old('title', $newsItem->title) }}">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
                        <input type="file" name="image" id="image"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('image') border-red-500 @enderror">
                        @if($newsItem->image)
                            <p class="text-xs text-gray-500 mt-2">Gambar saat ini:</p>
                            <img src="{{ asset('storage/' . $newsItem->image) }}" alt="Gambar Berita"
                                class="w-32 h-32 object-cover rounded-md mt-2">
                        @endif
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        
                        <input id="description" type="hidden" name="description" value="{{ old('description', $newsItem->description) }}">
                        
                        <trix-editor input="description" class="@error('description') border-red-500 @enderror"></trix-editor>

                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label for="date_published" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publikasi</label>
                        <input type="date" name="date_published" id="date_published"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('date_published') border-red-500 @enderror"
                            value="{{ old('date_published', $newsItem->date_published) }}">
                        @error('date_published')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit"
                            class="bg-[#6CF600] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#5bd300] transition-colors duration-200">
                            Perbarui Berita
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </body>

    </html>

@endsection