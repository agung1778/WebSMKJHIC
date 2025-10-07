@extends('layouts.admin-app')

@section('content')

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Daftar Jurusan</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f0f2f5;
            }
            /* Menyesuaikan lebar kolom untuk tata letak yang lebih baik */
            .w-10-p { width: 10%; }
            .w-15-p { width: 15%; }
            .w-20-p { width: 20%; }
            /* Menyesuaikan lebar tetap untuk gambar agar tidak memanjang */
            .w-img { width: 6rem; }
        </style>
    </head>

    <body class="bg-gray-100">

        <div class="main-content flex-1 p-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-[#292929]">Daftar Jurusan</h1>
                    <a href="{{ route('admin.majors.create') }}"
                        class="bg-[#6CF600] text-white px-4 py-2 rounded-lg font-semibold hover:bg-[#5bd300] transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-plus mr-2"></i>
                        <span>Tambah Jurusan</span>
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm"
                        role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full rounded-lg overflow-hidden border-collapse">
                        <thead>
                            <tr class="bg-[#292929] text-white uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left w-15-p">Nama Jurusan</th>
                                <th class="py-3 px-6 text-left w-10-p">Logo</th>
                                <th class="py-3 px-6 text-left w-10-p">Gambar</th>
                                <th class="py-3 px-6 text-left w-20-p">Deskripsi</th>
                                <th class="py-3 px-6 text-left w-15-p">Tag</th>
                                <th class="py-3 px-6 text-left w-15-p">Advantage</th>
                                <th class="py-3 px-6 text-left w-15-p">Kepala Kompetensi</th>
                                <th class="py-3 px-6 text-left w-10-p">Foto Kepala Kompetensi</th>
                                <th class="py-3 px-6 text-left w-10-p">Penerbit</th>
                                <th class="py-3 px-6 text-center w-10-p">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @forelse($majors as $major)
                                <tr class="border-b border-gray-200 hover:bg-gray-100 transition-colors duration-200">
                                    <td class="py-4 px-6 text-left font-medium w-15-p">{{ $major->name }}</td>

                                    {{-- TAMPILAN LOGO --}}
                                    <td class="py-4 px-6 text-left w-10-p">
                                        @if($major->logo)
                                            <img src="{{ asset('storage/' . $major->logo) }}" alt="Logo {{ $major->name }}"
                                                class="w-10 h-10 object-contain rounded-md shadow-sm">
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="py-4 px-6 text-left w-10-p">
                                        @if($major->image)
                                            <img src="{{ asset('storage/' . $major->image) }}" alt="{{ $major->name }}"
                                                class="w-16 h-16 object-cover rounded-md shadow-sm">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    
                                    {{-- Deskripsi (dibatasi) --}}
                                    <td class="py-4 px-6 text-left w-20-p">
                                        <p class="line-clamp-3 max-w-xs">{{ Str::limit($major->description, 50) }}</p>
                                    </td>

                                    {{-- TAMPILAN TAG (dibatasi) --}}
                                    <td class="py-4 px-6 text-left w-15-p">
                                        <p class="line-clamp-3 max-w-xs">{{ Str::limit($major->tag, 40) }}</p>
                                    </td>

                                    {{-- TAMPILAN ADVANTAGE (dibatasi) --}}
                                    <td class="py-4 px-6 text-left w-15-p">
                                        <p class="line-clamp-3 max-w-xs">{{ Str::limit($major->advantage, 40) }}</p>
                                    </td>

                                    <td class="py-4 px-6 text-left w-15-p">{{ $major->competency_head }}</td>
                                    
                                    <td class="py-4 px-6 text-left w-10-p">
                                        @if($major->competency_head_photo)
                                            <img src="{{ asset('storage/' . $major->competency_head_photo) }}"
                                                alt="Foto Kepala Kompetensi" class="w-16 h-16 object-cover rounded-md shadow-sm">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-left w-10-p">{{ $major->publisher }}</td>
                                    <td class="py-4 px-6 text-center w-10-p">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('admin.majors.show', $major->id) }}"
                                                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-blue-500 transition-colors duration-200">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.majors.edit', $major->id) }}"
                                                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-green-500 transition-colors duration-200">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.majors.destroy', $major->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurusan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors duration-200">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-8 text-center text-gray-500">Belum ada jurusan yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </body>

    </html>
@endsection