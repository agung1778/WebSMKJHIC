@extends('layouts.admin-app')

@section('title', $major->name)

@section('content')

    <div class="main-content flex-1 p-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-[#292929]">{{ $major->name }}</h1>
                <a href="{{ route('admin.majors.index') }}"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200 flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Kembali</span>
                </a>
            </div>

            <div class="mb-6">
                @if($major->image)
                    <img src="{{ Storage::url($major->image) }}" alt="{{ $major->name }}"
                        class="w-full h-80 object-cover rounded-lg shadow-md">
                @endif
            </div>

            <div class="prose max-w-none text-gray-700">
                <h2 class="text-lg font-semibold text-[#292929]">Deskripsi</h2>
                <div>{!! \App\Support\HtmlSanitizer::clean($major->description) !!}</div>

                @if($major->abbreviation)
                    <div class="mt-4 pb-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-700 mb-2">Singkatan</h3>
                        <span class="inline-block bg-[#6CF600]/20 text-[#4a8f00] text-sm font-bold px-3 py-1 rounded-full">
                            {{ $major->abbreviation }}
                        </span>
                    </div>
                @endif

                @if($major->logo)
                    <div class="mt-4 pb-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-700 mb-2">Logo Jurusan</h3>
                        <img src="{{ Storage::url($major->logo) }}" alt="Logo {{ $major->name }}"
                            class="w-16 h-16 object-contain border border-gray-300 p-1 rounded-md">
                    </div>
                @endif

                <div class="mt-6 border-b border-gray-200 pb-4">
                    <h2 class="text-lg font-semibold text-[#292929]">Keunggulan & Kata Kunci</h2>

                    <h3 class="text-base font-semibold text-gray-700 mt-3 mb-1">Tag (Kata Kunci)</h3>
                    @if($major->tag)
                        <p class="text-sm text-gray-600">{{ $major->tag }}</p>
                    @else
                        <p class="text-sm text-gray-400 italic">Tidak ada tag.</p>
                    @endif

                    <h3 class="text-base font-semibold text-gray-700 mt-4 mb-2">Poin Keunggulan (Advantage)</h3>
                    @if($major->advantage)
                        <ul class="list-disc list-inside text-sm text-gray-700 space-y-1 ml-4">
                            @foreach(explode("\n", $major->advantage) as $advantage)
                                <li>{{ trim($advantage) }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-400 italic">Tidak ada poin keunggulan.</p>
                    @endif
                </div>

                <div class="mt-6">
                    <h2 class="text-lg font-semibold text-[#292929]">Kepala Kompetensi</h2>
                    <div class="flex items-center space-x-4 mt-2">
                        @if($major->competency_head_photo)
                            <img src="{{ Storage::url($major->competency_head_photo) }}" alt="Foto Kepala Kompetensi"
                                class="w-20 h-20 object-cover rounded-full shadow-sm">
                        @endif
                        <div class="font-medium">
                            <p class="text-gray-700">{{ $major->competency_head }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-sm font-medium text-gray-500">
                    <p>Dipublikasikan oleh: <span class="text-gray-700">{{ $major->publisher }}</span></p>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-2">
                <a href="{{ route('admin.majors.edit', $major->id) }}"
                    class="bg-[#6CF600] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#5bd300] transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <form action="{{ route('admin.majors.destroy', $major->id) }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurusan ini?');">
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

@endsection