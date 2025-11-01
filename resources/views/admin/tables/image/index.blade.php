@extends('layouts.admin-app')

@section('content')

    <!DOCTYPE html>
    <html lang="id">

    <head>
        {{-- Aset dan style disalin dari view galeri untuk konsistensi --}}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Galeri Media</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f0f2f5;
            }

            .poppins {
                font-family: 'Poppins', sans-serif;
            }
        </style>
    </head>

    <body class="bg-gray-100">

        <style>
            /* Styling untuk Tom Select agar cocok dengan tema */
            .ts-control {
                border-radius: 0.5rem;
                border-color: #e5e7eb;
                padding: 0.5rem 0.75rem;
            }

            .ts-control:focus,
            .ts-control.focus {
                border-color: #6CF600;
                box-shadow: 0 0 0 2px rgba(108, 246, 0, 0.4);
            }

            .ts-dropdown {
                border-radius: 0.5rem;
            }
        </style>

        <div class="main-content flex-1 p-6 bg-gray-50 font-sans">
            <div class="bg-white rounded-lg shadow-md">
                {{-- HEADER --}}
                <div class="p-6 border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-800">Galeri Media</h1>
                    <p class="text-gray-500 mt-1">Unggah dan kelola aset gambar untuk website Anda.</p>
                </div>

                {{-- BAGIAN KONTEN UTAMA --}}
                <div class="p-6">

                    {{-- Notifikasi --}}
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm"
                            role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm"
                            role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @php
                        $totalImages = $images->count();
                        $totalSizeMB = $images->sum('size') / 1024 / 1024;
                    @endphp

                    {{-- Bagian Statistik Ringkas --}}
                    <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Card Total Gambar --}}
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm flex items-center space-x-4 border border-gray-200">
                            <div
                                class="bg-blue-100 text-blue-500 rounded-full h-12 w-12 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-images fa-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Gambar</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $totalImages }}</p>
                            </div>
                        </div>
                        {{-- Card Total Ukuran --}}
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm flex items-center space-x-4 border border-gray-200">
                            <div
                                class="bg-purple-100 text-purple-500 rounded-full h-12 w-12 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-hdd fa-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Ukuran File</p>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format($totalSizeMB, 2) }} MB</p>
                            </div>
                        </div>
                    </div>


                    {{-- 1. PANEL STATISTIK GAMBAR --}}
                    <div id="stats-box" class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-700 mb-3">Statistik Gambar Terpakai</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3">
                            @php
                                // Daftar judul bisa diambil dari controller atau didefinisikan di sini
                                $allTitles = ['MainImage', 'MajorsImage', 'NewsImage', 'PartnersImage', 'FacilityImage', 'ExtracurricularImage', 'AchievementImage', 'ProgramImage', 'GridImage', 'MajorGrid', 'Main', 'PortraitImage'];
                            @endphp

                            @foreach ($allTitles as $title)
                                @php $count = $imageCounts[$title] ?? 0; @endphp
                                <div class="flex items-center p-2.5 bg-gray-100 rounded-lg border border-gray-200">
                                    <input type="checkbox" disabled @if($count > 0) checked @endif
                                        class="h-4 w-4 rounded-sm border-2 border-green-500/40 bg-gray-800/50 text-[#6CF600] focus:ring-0 focus:ring-offset-0 disabled:opacity-100 checked:bg-[#6CF600]">
                                    <div class="ml-2.5">
                                        <code class="text-sm font-semibold text-gray-800 poppins">{{ $title }}</code>
                                        <p class="text-xs text-gray-500">{{ $count }} gambar</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t border-gray-200 my-8"></div>

                    {{-- 2. FORM UPLOAD BARU --}}
                    <div id="upload-form-container">
                        <h2 class="text-lg font-semibold text-gray-700 mb-3">Unggah Gambar Baru</h2>

                        <form action="{{ route('admin.image.store') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-5">
                            @csrf

                            {{-- Grid untuk Judul & Deskripsi --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                {{-- Kolom Judul Gambar --}}
                                <div>
                                    <label for="title_option" class="block text-sm font-medium text-gray-700 mb-1">Judul
                                        Gambar (Wajib)</label>
                                    <select id="title_option">
                                        <option value="Main">Main</option>
                                        <option value="MainImage">MainImage</option>
                                        <option value="GridImage">GridImage</option>
                                        <option value="MajorGrid">MajorGrid</option>
                                        <option value="MajorsImage">MajorsImage</option>
                                        <option value="NewsImage">NewsImage</option>
                                        <option value="PartnersImage">PartnersImage</option>
                                        <option value="FacilityImage">FacilityImage</option>
                                        <option value="ExtracurricularImage">ExtracurricularImage</option>
                                        <option value="AchievementImage">AchievementImage</option>
                                        <option value="PortraitImage">PortraitImage</option>
                                        <option value="ProgramImage">ProgramImage</option>
                                        <option value="custom">Custom</option>
                                    </select>

                                    <div id="custom_title_wrapper"
                                        class="max-h-0 opacity-0 overflow-hidden transition-all duration-300 ease-in-out mt-2">
                                        <label for="title_custom" class="block text-sm font-medium text-gray-700 mb-1">Judul
                                            Kustom:</label>
                                        <input type="text" id="title_custom" name="title_custom"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg  "
                                            placeholder="Contoh: Foto Kegiatan PPLG">
                                    </div>
                                    <input type="hidden" name="title" id="final_title">
                                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Kolom Deskripsi --}}
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi
                                        (Opsional)</label>
                                    <textarea name="description" id="description" rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600]"
                                        placeholder="Tulis deskripsi singkat gambar...">{{ old('description') }}</textarea>
                                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Komponen Input File Drag & Drop --}}
                            <div>
                                <label for="image_file" class="block text-sm font-medium text-gray-700 mb-1">Pilih File
                                    Gambar</label>
                                <div id="drop-zone"
                                    class="flex justify-center items-center w-full p-6 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                    <div class="text-center">
                                        <svg class="mx-auto h-10 w-10 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">
                                            <span class="font-semibold text-[#5bd300]">Klik untuk memilih</span> atau seret
                                            file ke sini
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP (MAX. 5MB)</p>
                                        <p id="file-name-display" class="text-sm font-medium text-gray-700 mt-2"></p>
                                    </div>
                                    <input type="file" name="image_file" id="image_file" class="hidden">
                                </div>
                                @error('image_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="flex justify-end pt-2">
                                <button type="submit"
                                    class="inline-flex items-center bg-[#6CF600] text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-[#5bd300] transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6CF600]">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                    Unggah Gambar
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="border-t border-gray-200 my-8"></div>

                    {{-- 3. DAFTAR GAMBAR --}}
                    <h2 class="text-xl font-semibold mb-4 mt-6">Daftar Gambar Tersedia</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full rounded-lg overflow-hidden border-collapse">
                            <thead>
                                <tr class="bg-[#292929] text-white uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Preview</th>
                                    <th class="py-3 px-6 text-left">Judul</th>
                                    <th class="py-3 px-6 text-left">Path</th>
                                    <th class="py-3 px-6 text-left">Ukuran</th>

                                    {{-- =================================== --}}
                                    {{-- 1. TAMBAHKAN HEADER TABEL "TIPE" --}}
                                    {{-- =================================== --}}
                                    <th class="py-3 px-6 text-left">Tipe</th>

                                    <th class="py-3 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @forelse($images as $image)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100 transition-colors duration-200">
                                        <td class="py-4 px-6 text-left">
                                            <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->filename }}"
                                                class="w-16 h-16 object-cover rounded-md shadow-sm cursor-pointer"
                                                onclick="window.open(this.src)">
                                        </td>
                                        <td class="py-4 px-6 text-left font-medium">{{ $image->title ?? 'N/A' }}</td>
                                        <td class="py-4 px-6 text-left max-w-sm text-xs break-all">
                                            <code>{{ 'storage/' . $image->path }}</code>
                                        </td>
                                        <td class="py-4 px-6 text-left">{{ number_format($image->size / 1024 / 1024, 2) }} MB
                                        </td>

                                        {{-- =================================== --}}
                                        {{-- 2. TAMPILKAN DATA MIME_TYPE --}}
                                        {{-- =================================== --}}
                                        <td class="py-4 px-6 text-left text-xs">
                                            <code>{{ $image->mime_type ?? 'N/A' }}</code>
                                        </td>

                                        <td class="py-4 px-6 text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('admin.image.show', $image->id) }}"
                                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-blue-500 transition-colors duration-200">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.image.edit', $image->id) }}"
                                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-green-500 transition-colors duration-200">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.image.destroy', $image->id) }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus gambar ini? Ini akan menghapusnya secara permanen.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type
                                                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors duration-200">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        {{-- =================================== --}}
                                        {{-- 3. UPDATE COLSPAN JADI 6 --}}
                                        {{-- =================================== --}}
                                        <td colspan="6" class="py-8 text-center text-gray-500">Belum ada gambar yang diunggah.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // === LOGIKA UNTUK DROPDOWN JUDUL (Tom Select) ===
                const titleOptionData = [
                    {
                        label: "Header Halaman",
                        options: [
                            { value: "MainImage", text: "MainImage - Header Home" },
                            { value: "MajorsImage", text: "MajorsImage - Header Jurusan" },
                            { value: "NewsImage", text: "NewsImage - Header Berita" },
                            { value: "PartnersImage", text: "PartnersImage - Header Mitra" },
                            { value: "FacilityImage", text: "FacilityImage - Header Fasilitas" },
                            { value: "AchievementImage", text: "AchievementImage - Header Prestasi" },
                            { value: "ProgramImage", text: "ProgramImage - Header Program" },
                        ],
                    },
                    {
                        label: "Tata Letak & Grid",
                        options: [
                            { value: "GridImage", text: "GridImage - Galeri Grid" },
                            { value: "MajorGrid", text: "MajorGrid - Grid Jurusan" },
                        ],
                    },
                    {
                        label: "Lainnya",
                        options: [
                            { value: "Main", text: "Main - Gambar Umum" },
                            { value: "PortraitImage", text: "PortraitImage - Foto Potret" },
                            { value: "custom", text: "-- Judul Kustom --" },
                        ],
                    },
                ];

                const tomSelect = new TomSelect('#title_option', {
                    placeholder: 'Pilih atau ketik judul...',
                    optgroups: titleOptionData,
                    valueField: 'value',
                    labelField: 'text',
                    searchField: ['text'],
                    render: {
                        optgroup_header: function (data, escape) {
                            return '<div class="optgroup-header font-bold text-sm text-gray-500 py-1 px-2">' + escape(data.label) + '</div>';
                        }
                    }
                });

                const customTitleWrapper = document.getElementById('custom_title_wrapper');
                const customTitleInput = document.getElementById('title_custom');
                const finalTitleInput = document.getElementById('final_title');

                function updateFinalTitle() {
                    const selectedValue = tomSelect.getValue();
                    finalTitleInput.value = (selectedValue === 'custom') ? customTitleInput.value : selectedValue;
                }

                tomSelect.on('change', function (value) {
                    if (value === 'custom') {
                        customTitleWrapper.classList.remove('max-h-0', 'opacity-0');
                        customTitleWrapper.classList.add('max-h-96', 'opacity-100');
                        customTitleInput.focus();
                    } else {
                        customTitleWrapper.classList.remove('max-h-96', 'opacity-100');
                        customTitleWrapper.classList.add('max-h-0', 'opacity-0');
                    }
                    updateFinalTitle();
                });

                customTitleInput.addEventListener('input', updateFinalTitle);
                // Inisialisasi untuk memastikan nilai awal sudah benar
                if (tomSelect.getValue() !== 'custom') {
                    updateFinalTitle();
                }


                // === LOGIKA UNTUK DRAG & DROP FILE INPUT ===
                const dropZone = document.getElementById('drop-zone');
                const imageFileInput = document.getElementById('image_file');
                const fileNameDisplay = document.getElementById('file-name-display');

                // Memicu klik input file saat drop zone diklik
                dropZone.addEventListener('click', () => imageFileInput.click());

                // Menampilkan nama file saat dipilih
                imageFileInput.addEventListener('change', () => {
                    if (imageFileInput.files.length > 0) {
                        fileNameDisplay.textContent = imageFileInput.files[0].name;
                    } else {
                        fileNameDisplay.textContent = '';
                    }
                });

                // Efek visual saat file diseret di atas drop zone
                dropZone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropZone.classList.add('border-green-500', 'bg-green-50');
                });

                dropZone.addEventListener('dragleave', () => {
                    dropZone.classList.remove('border-green-500', 'bg-green-50');
                });

                // Menangani file yang di-drop
                dropZone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropZone.classList.remove('border-green-500', 'bg-green-50');
                    if (e.dataTransfer.files.length > 0) {
                        imageFileInput.files = e.dataTransfer.files;
                        // Memicu event 'change' secara manual agar nama file tampil
                        imageFileInput.dispatchEvent(new Event('change'));
                    }
                });
            });
        </script>

    </body>

    </html>

@endsection