@extends('layouts.admin-app')

@section('content')
    <div class="main-content flex-1 p-6">
        <div class="bg-white rounded-lg shadow-md p-6">

            {{-- 1. HEADER: Judul dan Tombol Aksi Utama --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-[#292929]">Detail Gambar</h1>
                    <p class="text-sm text-gray-500 mt-1">Lihat informasi lengkap mengenai aset media.</p>
                </div>
                <div class="flex items-center space-x-2 mt-4 sm:mt-0">
                    <a href="{{ route('admin.image.index') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200 text-sm">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <a href="{{ route('admin.image.edit', $image->id) }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-600 transition-colors duration-200 text-sm">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                </div>
            </div>
            
            <hr class="mb-6">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- 2. KONTEN: Layout Grid untuk Gambar dan Metadata --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Kolom Kiri: Pratinjau Gambar --}}
                <div class="lg:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Pratinjau</h2>
                    <div class="bg-gray-100 p-4 rounded-lg border">
                        <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->filename }}"
                            class="w-full h-auto object-contain rounded-md shadow-sm max-h-[500px]">
                    </div>
                </div>

                {{-- Kolom Kanan: Detail Metadata --}}
                <div class="lg:col-span-1">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Metadata</h2>
                    <div class="bg-gray-50 border rounded-lg p-4 space-y-4">
                        
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Judul</label>
                            <p class="text-md text-gray-900 font-semibold">{{ $image->title ?? 'Tidak ada judul' }}</p>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Deskripsi</label>
                            <p class="text-sm text-gray-700 italic">
                                {{ $image->description ?? 'Tidak ada deskripsi.' }}
                            </p>
                        </div>

                        <hr>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Nama File Asli</label>
                            <p class="text-sm text-gray-700">{{ $image->filename }}</p>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Path Penyimpanan</label>
                            <code class="text-xs text-gray-700 bg-gray-200 p-1 rounded">{{ 'storage/' . $image->path }}</code>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Tipe File</label>
                            <p class="text-sm text-gray-700">{{ $image->mime_type }}</p>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Ukuran File</label>
                            <p class="text-sm text-gray-700">{{ number_format($image->size / 1024, 2) }} KB</p>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Tanggal Upload</label>
                            <p class="text-sm text-gray-700">{{ $image->created_at->format('d F Y, H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Terakhir Diperbarui</label>
                            <p class="text-sm text-gray-700">{{ $image->updated_at->format('d F Y, H:i') }}</p>
                        </div>

                    </div>

                    {{-- 3. AKSI BERBAHAYA: Tombol Hapus --}}
                    <div class="mt-6">
                         <form action="{{ route('admin.image.destroy', $image->id) }}" method="POST"
                            onsubmit="return confirm('PERINGATAN: Tindakan ini akan menghapus file gambar secara permanen dari server dan database. Apakah Anda benar-benar yakin?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-trash-alt mr-2"></i> Hapus Gambar Ini
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection