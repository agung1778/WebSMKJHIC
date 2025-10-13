@extends('layouts.admin-app')

@section('content')

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Admin</title>
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
                <h1 class="text-2xl font-bold text-[#292929]">Daftar Admin</h1>
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                    {{-- Fitur Pencarian --}}
                    <div class="relative w-full sm:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="search" id="searchInput" placeholder="Cari berdasarkan nama..."
                            class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#6CF600]">
                    </div>
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
                $totalUsers = $users->count();
                // Asumsi admin online jika aktivitas terakhir dalam 5 menit (300 detik)
                $onlineUsers = $users->filter(function ($user) {
                    return optional($user->session)->last_activity && (time() - $user->session->last_activity) < 300;
                })->count();
            @endphp

            {{-- Bagian Statistik Ringkas --}}
            <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Card Total Admin --}}
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm flex items-center space-x-4 border border-gray-200">
                    <div class="bg-gray-200 text-gray-600 rounded-full h-12 w-12 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-users-cog fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Admin</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</p>
                    </div>
                </div>
                {{-- Card Admin Online --}}
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm flex items-center space-x-4 border border-gray-200">
                    <div class="bg-green-100 text-green-500 rounded-full h-12 w-12 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-wifi fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Admin Online</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $onlineUsers }}</p>
                    </div>
                </div>
            </div>

            {{-- Tabel Data --}}
            <div class="overflow-x-auto rounded-lg">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-[#292929] text-white uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left w-16">No.</th>
                            <th class="py-3 px-6 text-left">Admin</th>
                            <th class="py-3 px-6 text-left">Tanggal Dibuat</th>
                            <th class="py-3 px-6 text-left">Aktivitas Terakhir</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light" id="adminTableBody">
                        @forelse($users as $user)
                            <tr class="border-b border-gray-200 hover:bg-gray-100 transition-colors duration-200">
                                <td class="py-4 px-6 text-left font-medium">{{ $loop->iteration }}</td>
                                <td class="py-4 px-6 text-left font-semibold">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-3 flex-shrink-0">
                                            <span class="font-bold text-gray-500">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-left">{{ $user->created_at->format('d M Y, H:i') }}</td>
                                <td class="py-4 px-6 text-left">
                                    @if (optional($user->session)->last_activity)
                                        {{ \Carbon\Carbon::createFromTimestamp($user->session->last_activity)->diffForHumans() }}
                                    @else
                                        <span class="text-gray-400 italic">Belum pernah login</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                       
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-data">
                                <td colspan="5" class="py-8 text-center text-gray-500">Belum ada admin yang ditambahkan.</td>
                            </tr>
                        @endforelse
                        {{-- Baris ini akan muncul jika pencarian tidak menemukan hasil --}}
                        <tr id="no-results" class="hidden">
                             <td colspan="5" class="py-8 text-center text-gray-500">
                                Admin tidak ditemukan.
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
            const tableBody = document.getElementById('adminTableBody');
            const allRows = tableBody.querySelectorAll('tr:not(#no-results)');
            const noResultsRow = document.getElementById('no-results');
            const noDataRow = document.getElementById('no-data');

            searchInput.addEventListener('keyup', function (e) {
                const searchTerm = e.target.value.toLowerCase();
                let visibleRows = 0;

                allRows.forEach(row => {
                    // Kolom "Admin" adalah kolom kedua (index 1)
                    const nameCell = row.cells[1];
                    if (nameCell) {
                        const name = nameCell.textContent.toLowerCase();
                        if (name.includes(searchTerm)) {
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
