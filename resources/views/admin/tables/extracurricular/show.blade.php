@extends('layouts.admin-app')

@section('content')

    

        <div class="main-content flex-1 p-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-[#292929]">Detail Ekstrakurikuler</h1>
                    <a href="{{ route('admin.extracurriculars.index') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Kembali</span>
                    </a>
                </div>

                <div class="mb-6">
                    <img src="{{ asset('storage/' . $extracurricular->image) }}" alt="{{ $extracurricular->name }}"
                        class="w-full h-80 object-cover rounded-lg shadow-md">
                </div>

                <div class="prose max-w-none text-gray-700">
                    <h2 class="text-lg font-semibold text-[#292929]">Detail Ekstrakurikuler</h2>
                    <hr class="my-2 border-gray-300">
                    <p><strong>Nama:</strong> {{ $extracurricular->name }}</p>
                    <p><strong>Jenis:</strong> {{ $extracurricular->type }}</p>
                    <p><strong>Pelatih/Pembina:</strong> {{ $extracurricular->coach }}</p>
                    <p><strong>Kontak:</strong> {{ $extracurricular->contact }}</p>
                    <p><strong>Penerbit:</strong> {{ $extracurricular->publisher }}</p>

                    <h2 class="text-lg font-semibold text-[#292929] mt-6">Deskripsi</h2>
                    <hr class="my-2 border-gray-300">
                    <p>{{ $extracurricular->description }}</p>
                </div>

                <div class="mt-8 flex justify-end space-x-2">
                    <a href="{{ route('admin.extracurriculars.edit', $extracurricular->id) }}"
                        class="bg-[#6CF600] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#5bd300] transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <form action="{{ route('admin.extracurriculars.destroy', $extracurricular->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus ekstrakurikuler ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-600 transition-colors duration-200">
                            <i class="fas fa-trash-alt mr-2"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </body>

    </html>

@endsection