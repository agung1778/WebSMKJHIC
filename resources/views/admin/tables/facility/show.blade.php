@extends('layouts.admin-app')
@section('content')



    <div class="main-content flex-1 p-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-[#292929]">{{ $facility->name }}</h1>
                <a href="{{ route('admin.facilities.index') }}"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200 flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Kembali</span>
                </a>
            </div>

            <div class="mb-6">
                <img src="{{ asset('storage/' . $facility->image) }}" alt="{{ $facility->name }}"
                    class="w-full h-80 object-cover rounded-lg shadow-md">
            </div>

            <div class="prose max-w-none text-gray-700">
                <h2 class="text-lg font-semibold text-[#292929]">Deskripsi</h2>
                <p>{{ $facility->description }}</p>

                <div class="mt-4 text-sm font-medium text-gray-500">
                    <p>Jenis Fasilitas: <span class="text-gray-700">{{ $facility->type }}</span></p>
                    <p>Dipublikasikan oleh: <span class="text-gray-700">{{ $facility->publisher }}</span></p>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-2">
                <a href="{{ route('admin.facilities.edit', $facility->id) }}"
                    class="bg-[#6CF600] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#5bd300] transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <form action="{{ route('admin.facilities.destroy', $facility->id) }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?');">
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