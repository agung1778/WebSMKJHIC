@extends('layouts.admin-app')

@section('content')

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
            rel="stylesheet">
        {{-- ... --}}
        <title>Daftar Guru</title>
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
                    <h1 class="text-2xl font-bold text-[#292929]">Daftar Guru</h1>
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                        {{-- Fitur Pencarian --}}
                        <div class="relative w-full sm:w-64">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-search text-gray-400"></i>
                            </span>
                            <input type="search" id="searchInput" placeholder="Cari berdasarkan nama..."
                                class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#6CF600]">
                        </div>
                        {{-- Tombol Tambah Guru --}}
                        <a href="{{ route('admin.teachers.create') }}"
                            class="bg-[#6CF600] text-white px-4 py-2 rounded-lg font-semibold hover:bg-[#5bd300] transition-colors duration-200 flex items-center justify-center space-x-2 w-full sm:w-auto">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Guru</span>
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
                    $totalTeachers = $teachers->count();
                    // Perbaikan: Gunakan filter dengan perbandingan case-insensitive dan trim spasi
                    $productiveTeachers = $teachers->filter(function ($teacher) {
                        return strcasecmp(trim($teacher->category), 'Produktif') === 0;
                    })->count();
                    $adaptiveTeachers = $teachers->filter(function ($teacher) {
                        return strcasecmp(trim($teacher->category), 'Normatif') === 0 || strcasecmp(trim($teacher->category), 'Adaptif') === 0;
                    })->count();
                    $teachersByPosition = $teachers->groupBy('position')->map->count()->sortDesc();
                @endphp

                {{-- Bagian Statistik Ringkas --}}
                <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Card Total Guru --}}
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm flex items-center space-x-4 border border-gray-200">
                        <div
                            class="bg-green-100 text-green-500 rounded-full h-12 w-12 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-chalkboard-teacher fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Guru</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalTeachers }}</p>
                        </div>
                    </div>
                    {{-- Card Guru Produktif --}}
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm flex items-center space-x-4 border border-gray-200">
                        <div
                            class="bg-blue-100 text-blue-500 rounded-full h-12 w-12 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-cogs fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Guru Produktif</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $productiveTeachers }}</p>
                        </div>
                    </div>
                    {{-- Card Guru Normatif/Adaptif --}}
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm flex items-center space-x-4 border border-gray-200">
                        <div
                            class="bg-purple-100 text-purple-500 rounded-full h-12 w-12 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-book-reader fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Guru Normatif/Adaptif</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $adaptiveTeachers }}</p>
                        </div>
                    </div>
                </div>



                {{-- Tabel Data Guru --}}
                <div class="overflow-x-auto rounded-lg">
                    <table class="w-full table-fixed border-collapse">
                        <thead>
                            <tr class="bg-[#292929] text-white uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left w-16">No.</th>
                                <th class="py-3 px-6 text-left">Nama</th>
                                <th class="py-3 px-6 text-left">Foto</th>
                                <th class="py-3 px-6 text-left">Jabatan</th>
                                <th class="py-3 px-6 text-left">Mata Pelajaran</th>
                                <th class="py-3 px-6 text-left">Kategori</th>
                                <th class="py-3 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light" id="teacherTableBody">
                            @forelse($teachers as $teacher)
                                <tr class="border-b border-gray-200 hover:bg-gray-100 transition-colors duration-200">
                                    <td class="py-4 px-6 text-left font-medium">{{ $loop->iteration }}</td>
                                    <td class="py-4 px-6 text-left font-semibold break-words">{{ $teacher->name }}</td>
                                    <td class="py-3 px-6 text-left">
                                        {{-- Area Dropzone untuk FilePond --}}
                                        <div class="photo-dropzone w-20 h-20" data-teacher-id="{{ $teacher->id }}">
                                            {{-- Menampilkan foto yang sudah ada --}}
                                            {{-- BARIS INI TELAH DIPERBAIKI --}}
                                            <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->name }}"
                                                class="current-photo w-full h-full object-cover rounded-md shadow-sm bg-gray-50">
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-left break-words">{{ $teacher->position }}</td>
                                    <td class="py-4 px-6 text-left break-words">{{ $teacher->subject }}</td>
                                    <td class="py-4 px-6 text-left break-words">{{ $teacher->category }}</td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('admin.teachers.show', $teacher->id) }}"
                                                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-blue-500 rounded-full hover:bg-gray-200 transition-all duration-200"
                                                title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.teachers.edit', $teacher->id) }}"
                                                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-green-500 rounded-full hover:bg-gray-200 transition-all duration-200"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus guru ini?');">
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
                                    <td colspan="7" class="py-8 text-center text-gray-500">Belum ada data guru yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                            {{-- Baris ini akan muncul jika pencarian tidak menemukan hasil --}}
                            <tr id="no-results" class="hidden">
                                <td colspan="7" class="py-8 text-center text-gray-500">
                                    Guru tidak ditemukan.
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
                const tableBody = document.getElementById('teacherTableBody');
                const allRows = tableBody.querySelectorAll('tr:not(#no-results)');
                const noResultsRow = document.getElementById('no-results');
                const noDataRow = document.getElementById('no-data');

                searchInput.addEventListener('keyup', function (e) {
                    const searchTerm = e.target.value.toLowerCase();
                    let visibleRows = 0;

                    allRows.forEach(row => {
                        // Kolom "Nama" adalah kolom kedua (index 1)
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
        {{-- Letakkan kode ini di bagian paling bawah file Anda --}}
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
        <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
        <script>
            // Daftarkan plugin FilePond
            FilePond.registerPlugin(FilePondPluginImagePreview);

            document.addEventListener('DOMContentLoaded', function () {
                // Ambil CSRF token dari meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Cari semua elemen dropzone yang kita buat
                const dropzones = document.querySelectorAll('.photo-dropzone');

                // Loop setiap dropzone dan inisialisasi FilePond
                dropzones.forEach(dropzoneElement => {
                    const teacherId = dropzoneElement.dataset.teacherId;
                    const currentPhotoImg = dropzoneElement.querySelector('.current-photo');

                    // Hapus gambar yang sudah ada agar tidak duplikat
                    if (currentPhotoImg) {
                        currentPhotoImg.style.display = 'none';
                    }

                    FilePond.create(dropzoneElement, {
                        labelIdle: `<i class="fas fa-camera"></i><br>Drop foto`,

                        // Menampilkan gambar yang sudah ada sebagai file awal
                        files: [{
                            source: currentPhotoImg.src,
                            options: {
                                type: 'local',
                            }
                        }],

                        stylePanelLayout: 'compact circle',
                        styleLoadIndicatorPosition: 'center bottom',
                        styleProgressIndicatorPosition: 'center bottom',
                        styleButtonRemoveItemPosition: 'center bottom',
                        styleButtonProcessItemPosition: 'center bottom',

                        server: {
                            process: {
                                url: `/admin/teachers/${teacherId}/upload-photo`,
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                onload: (response) => {
                                    const data = JSON.parse(response);
                                    if (data.success) {
                                        // Ganti sumber gambar lama dengan yang baru
                                        currentPhotoImg.src = data.photo_url;
                                        // FilePond akan otomatis reset setelah berhasil
                                        return data.photo_url;
                                    }
                                },
                                onerror: (response) => {
                                    alert('Upload Gagal!');
                                    return response;
                                }
                            },
                            revert: null,
                            load: (source, load, error, progress, abort, headers) => {
                                // Ini untuk memuat gambar yang sudah ada
                                fetch(source).then(res => res.blob()).then(load);
                            },
                        },

                        name: 'photo',
                    });
                });

            });
        </script>

    </body>

    </html>

@endsection