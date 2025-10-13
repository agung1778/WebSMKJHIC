@extends('layouts.admin-app')

@section('content')

<!DOCTYPE html>
<html lang="id">

<head>
    {{-- Semua aset dan style disalin dari view galeri untuk konsistensi --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Gambar - {{ $image->title ?? $image->filename }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
        }
        .poppins {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="main-content flex-1 p-6 bg-gray-50 font-sans">
        <div class="bg-white rounded-lg shadow-md">
            {{-- HEADER HALAMAN --}}
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Detail Gambar</h1>
                    <p class="text-gray-500 mt-1">Informasi lengkap mengenai aset gambar yang dipilih.</p>
                </div>
                <a href="{{ route('admin.image.index') }}"
                    class="inline-flex items-center bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Galeri
                </a>
            </div>

            {{-- BAGIAN KONTEN UTAMA (Layout 2 Kolom) --}}
            <div class="p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- KOLOM KIRI: PREVIEW GAMBAR --}}
                <div class="lg:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Preview Gambar</h2>
                    <div class="bg-gray-100 p-4 rounded-lg border">
                        <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->filename }}"
                            class="w-full h-auto object-contain rounded-lg shadow-lg cursor-pointer max-h-[60vh]"
                            onclick="window.open(this.src)" title="Klik untuk melihat ukuran penuh">
                    </div>
                </div>

                {{-- KOLOM KANAN: INFORMASI DETAIL --}}
                <div class="lg:col-span-1">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Informasi Detail</h2>
                    <div class="space-y-4 text-sm">
                        {{-- Judul --}}
                        <div class="flex flex-col p-3 bg-gray-50 rounded-md border">
                            <span class="text-xs font-medium text-gray-500">Judul Gambar</span>
                            <span class="text-base font-semibold text-gray-800 mt-1">{{ $image->title ?? 'Tidak ada judul' }}</span>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="flex flex-col p-3 bg-gray-50 rounded-md border">
                            <span class="text-xs font-medium text-gray-500">Deskripsi</span>
                            <p class="text-gray-700 mt-1">
                                {{ $image->description ?? 'Tidak ada deskripsi.' }}
                            </p>
                        </div>

                        {{-- Nama File --}}
                        <div class="flex flex-col p-3 bg-gray-50 rounded-md border">
                            <span class="text-xs font-medium text-gray-500">Nama File</span>
                            <code class="text-gray-800 mt-1 break-all">{{ $image->filename }}</code>
                        </div>

                        {{-- Path --}}
                        <div class="flex flex-col p-3 bg-gray-50 rounded-md border">
                            <span class="text-xs font-medium text-gray-500">Path</span>
                            <div class="flex items-center justify-between mt-1">
                                <code id="image-path" class="text-gray-800 text-xs break-all">{{ 'storage/' . $image->path }}</code>
                                <button id="copy-path-btn" title="Salin Path"
                                        class="ml-2 text-gray-500 hover:text-blue-600">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Ukuran --}}
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md border">
                            <span class="text-xs font-medium text-gray-500">Ukuran File</span>
                            <span class="font-semibold text-gray-800">{{ number_format($image->size / 1024 / 1024, 2) }} MB</span>
                        </div>

                        {{-- Tanggal Upload --}}
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md border">
                            <span class="text-xs font-medium text-gray-500">Diunggah Pada</span>
                            <span class="font-semibold text-gray-800">{{ $image->created_at->format('d F Y, H:i') }}</span>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="border-t border-gray-200 mt-6 pt-6 flex items-center space-x-3">
                        <a href="{{ route('admin.image.edit', $image->id) }}"
                           class="flex-1 inline-flex items-center justify-center bg-green-500 text-white px-4 py-2.5 rounded-lg font-semibold hover:bg-green-600 transition-all duration-300">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        <form action="{{ route('admin.image.destroy', $image->id) }}" method="POST" class="flex-1"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus gambar ini secara permanen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center bg-red-500 text-white px-4 py-2.5 rounded-lg font-semibold hover:bg-red-600 transition-all duration-300">
                                <i class="fas fa-trash-alt mr-2"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script untuk menyalin path gambar
        document.getElementById('copy-path-btn').addEventListener('click', function() {
            const pathText = document.getElementById('image-path').innerText;
            navigator.clipboard.writeText(pathText).then(() => {
                const icon = this.querySelector('i');
                const originalIconClass = icon.className;
                icon.className = 'fas fa-check text-green-500'; // Ganti ikon menjadi centang
                setTimeout(() => {
                    icon.className = originalIconClass; // Kembalikan ikon setelah 1.5 detik
                }, 1500);
            }).catch(err => {
                console.error('Gagal menyalin teks: ', err);
            });
        });
    </script>

</body>
</html>

@endsection