@extends('layouts.admin-app')

@section('content')

    
        <style>
            

            /* === TAMBAHKAN INI: Styling untuk Trix Editor === */
            trix-toolbar [data-trix-button-group="file-tools"] {
                display: none;
                /* Sembunyikan tombol upload file bawaan */
            }

            trix-editor {
                background-color: white;
                min-height: 300px;
                /* Atur tinggi minimal editor */
                border-radius: 0.5rem;
                border-width: 1px;
                border-color: #d1d5db;
                /* Sesuaikan dengan warna border input Anda */
            }

            trix-editor:focus-within {
                outline: 2px solid transparent;
                outline-offset: 2px;
                border-color: #6CF600;
                /* Warna focus ring */
                box-shadow: 0 0 0 2px #6CF600;
            }

            /* ================================================ */
        </style>


        <div class="main-content flex-1 p-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-[#292929]">Edit Konten</h1>
                    <a href="{{ route('admin.writings.index') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Kembali</span>
                    </a>
                </div>

                <form action="{{ route('admin.writings.update', $writing->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Konten</label>
                        <input type="text" name="title" id="title"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('title') border-red-500 @enderror"
                            value="{{ old('title', $writing->title) }}">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- === UBAH BAGIAN INI: Ganti Textarea dengan Trix Editor === -->
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Isi Konten</label>

                        <!-- Input hidden ini diisi dengan data yang ada -->
                        <input id="content" type="hidden" name="content" value="{{ old('content', $writing->content) }}">

                        <!-- Elemen Trix Editor -->
                        <trix-editor input="content" class="@error('content') border-red-500 @enderror"></trix-editor>

                        @error('content')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- ======================================================== -->

                    <div class="mb-4 flex space-x-4">
                        <div class="w-1/2">
                            <label for="publisher" class="block text-sm font-medium text-gray-700 mb-1">Publisher</label>
                            <input type="text" name="publisher" id="publisher"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('publisher') border-red-500 @enderror"
                                value="{{ old('publisher', $writing->publisher) }}">
                            @error('publisher')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="w-1/2">
                            <label for="release_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Rilis</label>
                            <input type="date" name="release_date" id="release_date"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('release_date') border-red-500 @enderror"
                                value="{{ old('release_date', $writing->release_date ? $writing->release_date->format('Y-m-d') : '') }}">
                            @error('release_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <button type="submit"
                            class="bg-[#6CF600] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#5bd300] transition-colors duration-200">
                            Perbarui Konten
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </body>

    </html>

@endsection