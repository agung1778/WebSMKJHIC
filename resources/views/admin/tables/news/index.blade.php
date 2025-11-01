@extends('layouts.admin-app')

@section('content')

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Daftar Berita</title>
        {{-- Tailwind CSS --}}
        <script src="https://cdn.tailwindcss.com"></script>
        {{-- Font Awesome untuk ikon --}}
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        {{-- Google Fonts: Poppins --}}
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f0f2f5;
            }

            /* Menyembunyikan panah default pada input search */
            input[type='search']::-webkit-search-decoration,
            input[type='search']::-webkit-search-cancel-button,
            input[type='search']::-webkit-search-results-button,
            input[type='search']::-webkit-search-results-decoration {
                -webkit-appearance: none;
            }
        </style>
    </head>

    <body class="bg-gray-100">

        <div class="main-content flex-1 p-4 sm:p-6">
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                {{-- Header: Judul, Cari, dan Tombol Tambah --}}
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                    <h1 class="text-2xl font-bold text-[#292929]">Daftar Berita</h1>
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                        {{-- Fitur Pencarian --}}
                        <div class="relative w-full sm:w-64">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-search text-gray-400"></i>
                            </span>
                            <input type="search" id="searchInput" placeholder="Cari berdasarkan judul..."
                                class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#6CF600]">
                        </div>
                        {{-- Tombol Tambah Berita --}}
                        <a href="{{ route('admin.news.create') }}"
                            class="bg-[#6CF600] text-white px-4 py-2 rounded-lg font-semibold hover:bg-[#5bd300] transition-colors duration-200 flex items-center justify-center space-x-2 w-full sm:w-auto">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Berita</span>
                        </a>
                    </div>
                </div>

                {{-- Notifikasi Sukses --}}
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm"
                        role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                {{-- Menghitung statistik --}}
                @php
                    $totalNews = $news->count();
                @endphp

                {{-- Bagian Statistik --}}
                <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Card Total Berita --}}
                    <div
                        class="bg-gray-50 p-4 rounded-lg shadow-sm flex items-center space-x-4 border border-gray-200 sm:col-span-1 lg:col-span-3">
                        <div
                            class="bg-[#e2ffc7] text-[#6CF600] rounded-full h-12 w-12 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-newspaper fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Berita</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalNews }}</p>
                        </div>
                    </div>
                </div>

                {{-- Tabel Data Berita --}}
                <div class="overflow-x-auto rounded-lg">
                    <table class="w-full table-fixed border-collapse">
                        <thead>
                            <tr class="bg-[#292929] text-white uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left w-16">No.</th>
                                <th class="py-3 px-6 text-left">Judul</th>
                                <th class="py-3 px-6 text-left">Gambar</th>

                                {{-- =================================== --}}
                                {{-- 1. TAMBAHKAN HEADER TABEL "TIPE" --}}
                                {{-- =================================== --}}
                                <th class="py-3 px-6 text-left">Tipe</th>

                                <th class="py-3 px-6 text-left">Deskripsi</th>
                                <th class="py-3 px-6 text-left">Penerbit</th>
                                <th class="py-3 px-6 text-left">Tanggal</th>
                                <th class="py-3 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light" id="newsTableBody">
                            @forelse($news as $item)
                                <tr class="border-b border-gray-200 hover:bg-gray-100 transition-colors duration-200">
                                    <td class="py-4 px-6 text-left font-medium">{{ $loop->iteration }}</td>
                                    <td class="py-4 px-6 text-left font-semibold break-words">{{ $item->title }}</td>
                                    <td class="py-4 px-6 text-left">
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                            class="w-16 h-16 object-cover rounded-md shadow-sm bg-gray-50">
                                    </td>

                                    {{-- =================================== --}}
                                    {{-- 2. TAMPILKAN EKSTENSI FILE --}}
                                    {{-- =================================== --}}
                                    <td class="py-4 px-6 text-left text-xs">
                                        <code>{{ pathinfo($item->image, PATHINFO_EXTENSION) ?? 'N/A' }}</code>
                                    </td>

                                    <td class="py-4 px-6 text-left max-w-xs break-words">
                                        <p class="line-clamp-3">{{ $item->description }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-left break-words">{{ $item->publisher }}</td>
                                    <td class="py-4 px-6 text-left">
                                        {{ \Carbon\Carbon::parse($item->date_published)->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('admin.news.show', $item->id) }}"
                                                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-blue-500 rounded-full hover:bg-gray-200 transition-all duration-200"
                                                title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.news.edit', $item->id) }}"
                                                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-green-500 rounded-full hover:bg-gray-200 transition-all duration-200"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 rounded-full hover:bg-gray-200 transition-all duration-200"
                                                    title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="no-data">
                                    {{-- =================================== --}}
                                    {{-- 3. UPDATE COLSPAN JADI 8 --}}
                                    {{-- =================================== --}}
                                    <td colspan="8" class="py-8 text-center text-gray-500">Belum ada berita yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                            {{-- Baris ini akan muncul jika pencarian tidak menemukan hasil --}}
                            <tr id="no-results" class="hidden">
                                {{-- =================================== --}}
                                {{-- 4. UPDATE COLSPAN JADI 8 --}}
                                {{-- =================================== --}}
                                <td colspan="8" class="py-8 text-center text-gray-500">
                                    Berita tidak ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('searchInput');
                const tableBody = document.getElementById('newsTableBody');
                const allRows = tableBody.querySelectorAll('tr:not(#no-results)');
                const noResultsRow = document.getElementById('no-results');
                const noDataRow = document.getElementById('no-data');

                searchInput.addEventListener('keyup', function (e) {
                    const searchTerm = e.target.value.toLowerCase();
                    let visibleRows = 0;

                    allRows.forEach(row => {
                        // Kolom "Judul" adalah kolom kedua (index 1)
                        const newsTitleCell = row.cells[1];
                        if (newsTitleCell) {
                            const newsTitle = newsTitleCell.textContent.toLowerCase();
                            if (newsTitle.includes(searchTerm)) {
                                row.style.display = '';
                                visibleRows++;
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });

                    // Tampilkan pesan "tidak ditemukan" jika tidak ada baris yang cocok
                    if (visibleRows === 0 && !noDataRow) {
                        noResultsRow.style.display = '';
                    } else {
                        noResultsRow.style.display = 'none';
                    }
                });
            });
        </script>

    </body>

    </html>

@endsection