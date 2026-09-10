@extends('layouts.admin-app')

@section('content')


        <style>
        
        /* Tambahkan style dasar untuk konten (opsional) */
        .prose-content p {
            margin-bottom: 1em;
            line-height: 1.6;
        }
    </style>


<div class="main-content flex-1 p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-[#292929]">Detail Konten</h1>
            <a href="{{ route('admin.writings.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200 flex items-center space-x-2">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali ke Daftar</span>
            </a>
        </div>

        <div class="prose max-w-none text-gray-700">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $writing->title }}</h1>
            
            <div class="text-sm text-gray-500 mb-6 pb-3 border-b border-gray-200">
                <p class="mb-0"><strong>Publisher:</strong> {{ $writing->publisher }}</p>
                <p class="mt-1 mb-0"><strong>Tanggal Rilis:</strong> {{ $writing->release_date->format('d F Y') }}</p>
            </div>
            
            <div class="prose-content text-base text-gray-700 mt-4 whitespace-pre-wrap">
                {{-- Gunakan nl2br untuk mengkonversi baris baru menjadi tag <br> jika diperlukan --}}
                {!! nl2br(e($writing->content)) !!}
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-2">
            <a href="{{ route('admin.writings.edit', $writing->id) }}" class="bg-[#6CF600] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#5bd300] transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <form action="{{ route('admin.writings.destroy', $writing->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus konten ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-600 transition-colors duration-200">
                    <i class="fas fa-trash-alt mr-2"></i>Hapus
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>

@endsection