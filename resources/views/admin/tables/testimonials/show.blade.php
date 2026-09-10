@extends('layouts.admin-app')

@section('content')

    

        <div class="main-content flex-1 p-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-[#292929]">Testimoni dari {{ $testimonial->name }}</h1>
                    <a href="{{ route('admin.testimonials.index') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Kembali</span>
                    </a>
                </div>

                <div class="flex flex-col items-center justify-center mb-6">
                    <img src="{{ asset('storage/' . $testimonial->photo) }}" alt="Foto {{ $testimonial->name }}"
                        class="w-48 h-48 object-cover rounded-full shadow-md">
                </div>

                <div class="prose max-w-none text-gray-700">
                    <h2 class="text-lg font-semibold text-[#292929]">Detail Testimoni</h2>
                    <hr class="my-2 border-gray-300">
                    <p><strong>Nama:</strong> {{ $testimonial->name }}</p>
                    <p><strong>Jurusan:</strong> {{ $testimonial->major->name }}</p>
                    <p><strong>Tahun Alumni:</strong> {{ $testimonial->alumni_year }}</p>
                    <p><strong>Dipublikasikan oleh:</strong> {{ $testimonial->publisher }}</p>

                    <h2 class="text-lg font-semibold text-[#292929] mt-6">Deskripsi</h2>
                    <hr class="my-2 border-gray-300">
                    <p>{{ $testimonial->description }}</p>
                </div>

                <div class="mt-8 flex justify-end space-x-2">
                    <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}"
                        class="bg-[#6CF600] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#5bd300] transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus testimoni ini?');">
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