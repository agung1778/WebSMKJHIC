@extends('layouts.admin-app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Konten/Artikel</title>
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
            <h1 class="text-2xl font-bold text-[#292929]">Daftar Konten</h1>
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                 {{-- Fitur Pencarian --}}
                 <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                    <input type="search" id="searchInput" placeholder="Cari berdasarkan judul..."
                        class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#6CF600]">
                </div>
                {{-- Tombol Buat Konten --}}
                <a href="{{ route('admin.writings.create') }}" class="bg-[#6CF600] text-white px-4 py-2 rounded-lg font-semibold hover:bg-[#5bd300] transition-colors duration-200 flex items-center justify-center space-x-2 w-full sm:w-auto">
                    <i class="fas fa-plus"></i>
                    <span>Buat Konten</span>
                </a>
            </div>
        </div>

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Menghitung statistik --}}
        @php
            $totalWritings = $writings->count();
            // Cek keberadaan konten berdasarkan judul spesifik (case-insensitive)
            $sejarahExists = $writings->first(fn($w) => strcasecmp(trim($w->title), 'History') === 0) ? 1 : 0;
            $achievementExists = $writings->first(fn($w) => strcasecmp(trim($w->title), 'Achievement') === 0) ? 1 : 0;
            $foundationExists = $writings->first(fn($w) => strcasecmp(trim($w->title), 'Foundation') === 0) ? 1 : 0;
        @endphp

        {{-- Bagian Statistik --}}
        <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Card Total Konten --}}
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm flex items-center space-x-4 border border-gray-200">
                <div class="bg-indigo-100 text-indigo-500 rounded-full h-12 w-12 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-pencil-alt fa-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Konten</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalWritings }}</p>
                </div>
            </div>
             {{-- Card Sejarah --}}
             <div class="bg-gray-50 p-4 rounded-lg shadow-sm flex items-center space-x-4 border border-gray-200">
                <div class="{{ $sejarahExists ? 'bg-green-100 text-green-500' : 'bg-red-100 text-red-500' }} rounded-full h-12 w-12 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-landmark fa-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Sejarah</p>
                    <p class="text-2xl font-bold {{ $sejarahExists ? 'text-gray-800' : 'text-red-500' }}">{{ $sejarahExists ? 'Added' : 'Empty' }}</p>
                </div>
            </div>
            {{-- Card Achievement --}}
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm flex items-center space-x-4 border border-gray-200">
                <div class="{{ $achievementExists ? 'bg-green-100 text-green-500' : 'bg-red-100 text-red-500' }} rounded-full h-12 w-12 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-trophy fa-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Achievement</p>
                    <p class="text-2xl font-bold {{ $achievementExists ? 'text-gray-800' : 'text-red-500' }}">{{ $achievementExists ? 'Added' : 'Empty' }}</p>
                </div>
            </div>
            {{-- Card Foundation --}}
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm flex items-center space-x-4 border border-gray-200">
                <div class="{{ $foundationExists ? 'bg-green-100 text-green-500' : 'bg-red-100 text-red-500' }} rounded-full h-12 w-12 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-building fa-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Foundation</p>
                    <p class="text-2xl font-bold {{ $foundationExists ? 'text-gray-800' : 'text-red-500' }}">{{ $foundationExists ? 'Added' : 'Empty' }}</p>
                </div>
            </div>
        </div>

        {{-- Tabel Data Konten --}}
        <div class="overflow-x-auto rounded-lg">
            <table class="w-full table-fixed border-collapse">
                <thead>
                    <tr class="bg-[#292929] text-white uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left w-16">No.</th>
                        <th class="py-3 px-6 text-left">Judul</th>
                        <th class="py-3 px-6 text-left">Kategori</th>
                        <th class="py-3 px-6 text-left">Isi Konten</th>
                        <th class="py-3 px-6 text-left">Publisher</th>
                        <th class="py-3 px-6 text-left">Tanggal Rilis</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light" id="writingTableBody">
                    @forelse($writings as $writing)
                        <tr class="border-b border-gray-200 hover:bg-gray-100 transition-colors duration-200">
                            <td class="py-4 px-6 text-left font-medium">{{ $loop->iteration }}</td>
                            <td class="py-4 px-6 text-left font-semibold break-words">{{ $writing->title }}</td>
                            <td class="py-4 px-6 text-left break-words">{{ $writing->category ?? 'N/A' }}</td>
                            <td class="py-4 px-6 text-left max-w-xs break-words">
                                <p class="line-clamp-3">{{ strip_tags($writing->content) }}</p>
                            </td>
                            <td class="py-4 px-6 text-left break-words">{{ $writing->publisher }}</td>
                            <td class="py-4 px-6 text-left">{{ \Carbon\Carbon::parse($writing->release_date)->format('d M Y') }}</td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.writings.show', $writing->id) }}" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-blue-500 rounded-full hover:bg-gray-200 transition-all duration-200" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.writings.edit', $writing->id) }}" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-green-500 rounded-full hover:bg-gray-200 transition-all duration-200" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.writings.destroy', $writing->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konten ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 rounded-full hover:bg-gray-200 transition-all duration-200" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="no-data">
                            <td colspan="7" class="py-8 text-center text-gray-500">Belum ada konten yang dibuat.</td>
                        </tr>
                    @endforelse
                    {{-- Baris ini akan muncul jika pencarian tidak menemukan hasil --}}
                    <tr id="no-results" class="hidden">
                        <td colspan="7" class="py-8 text-center text-gray-500">
                           Konten tidak ditemukan.
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
        const tableBody = document.getElementById('writingTableBody');
        const allRows = tableBody.querySelectorAll('tr:not(#no-results)');
        const noResultsRow = document.getElementById('no-results');
        const noDataRow = document.getElementById('no-data');

        searchInput.addEventListener('keyup', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            let visibleRows = 0;

            allRows.forEach(row => {
                // Kolom "Judul" adalah kolom kedua (index 1)
                const titleCell = row.cells[1];
                if (titleCell) {
                    const title = titleCell.textContent.toLowerCase();
                    if (title.includes(searchTerm)) {
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

