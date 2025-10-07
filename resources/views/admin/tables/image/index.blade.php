@extends('layouts.admin-app')

@section('content')

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Galeri Media</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f0f2f5;
            }
        </style>
    </head>

    <body class="bg-gray-100">

        <div class="main-content flex-1 p-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold text-[#292929] mb-6">Galeri Media</h1>

                {{-- CONTAINER UNTUK PANDUAN DAN FORM UPLOAD --}}
                <div class="flex flex-col lg:flex-row gap-6 mb-8">

                    {{-- BOX PANDUAN JUDUL GAMBAR (BARU) --}}
                    <div id="guide-box"
                        class="lg:w-1/3 p-4 bg-[#292929]  border border-[#6CF600] rounded-lg shadow-sm h-fit">
                        <h2 class="text-lg font-bold text-[#6CF600] mb-3 flex items-center">
                            <i class="fas fa-lightbulb mr-2"></i> Panduan Judul Gambar
                        </h2>
                        <p class="text-sm text-white mb-3">Gunakan judul spesifik berikut agar gambar terhubung dengan
                            fungsi di sistem:</p>
                        <ul class="list-disc list-inside text-sm text-white space-y-1 ml-4">
                            <li><span class="text-[#eaf600]">Main</span> : <span
                                    class="text-[#6CF600] text-bold">All</span>.</li>
                            <li><span class="text-[#eaf600]">MainImage</span> : <span
                                    class="text-[#6CF600] text-bold">Home</span>.</li>
                            <li><span class="text-[#eaf600]">GridImage</span> : <span
                                    class="text-[#6CF600] text-bold">Home</span>.
                            <li><span class="text-[#eaf600]">MajorGrid</span> : <span
                                    class="text-[#6CF600] text-bold">Home</span>.
                            </li>
                            <li><span class="text-[#eaf600]">MajorsImage</span> : <span
                                    class="text-[#6CF600] text-bold">Major Competency</span>.
                            </li>
                            <li><span class="text-[#eaf600]">NewsImage</span> : <span
                                    class="text-[#6CF600] text-bold">News</span>.
                            </li>
                            <li><span class="text-[#eaf600]">PartnersImage</span> : <span
                                    class="text-[#6CF600] text-bold">Industry Partners</span>.
                            </li>
                            <li><span class="text-[#eaf600]">FacilityImage</span> : <span
                                    class="text-[#6CF600] text-bold">Facilities</span>.
                            </li>
                        </ul>
                        <p class="text-xs mt-3 text-red-500">Gambar yang memiliki judul di atas akan diprioritaskan oleh
                            sistem.</p>
                    </div>

                    {{-- FORM UPLOAD --}}
                    <div class="lg:w-2/3 border border-gray-200 p-4 rounded-lg">
                        <h2 class="text-xl font-semibold mb-3">Unggah Gambar Baru</h2>
                        @if(session('success'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg shadow-sm"
                                role="alert">
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg shadow-sm"
                                role="alert">
                                <p>{{ session('error') }}</p>
                            </div>
                        @endif

                        <form action="{{ route('admin.image.store') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf

                            {{-- Kolom Title (SUDAH DIUBAH MENJADI DROPDOWN) --}}
                            <div>
                                <label for="title_option" class="block text-sm font-medium text-gray-700 mb-1">Judul Gambar
                                    (Wajib)</label>

                                {{-- Dropdown untuk pilihan judul --}}
                                <select id="title_option"
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('title') border-red-500 @enderror">
                                    <option value="" disabled selected>Pilih Judul</option>
                                       <option value="Main">Main</option>
                                    <option value="MainImage">MainImage</option>
                                    <option value="GridImage">GridImage</option>
                                    <option value="MajorGrid">MajorGrid</option>
                                    <option value="MajorsImage">MajorsImage</option>
                                    <option value="NewsImage">NewsImage</option>
                                    <option value="PartnersImage">PartnersImage</option>
                                    <option value="FacilityImage">FacilityImage</option>
                                    <option value="custom">Custom</option>
                                </select>

                                {{-- Input teks untuk judul kustom, disembunyikan secara default --}}
                                <div id="custom_title_wrapper" class="hidden mt-2">
                                    <label for="title_custom" class="block text-sm font-medium text-gray-700 mb-1">Judul
                                        Kustom:</label>
                                    <input type="text" id="title_custom" name="title_custom"
                                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600]"
                                        placeholder="Contoh: Foto Kegiatan PPLG">
                                </div>

                                {{-- Input tersembunyi yang akan mengirimkan nilai judul final ke server --}}
                                <input type="hidden" name="title" id="final_title">

                                @error('title')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>


                            {{-- Kolom Description --}}
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi
                                    (Opsional)</label>
                                <textarea name="description" id="description" rows="3"
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Kolom File Input --}}
                            <div class="flex flex-col md:flex-row items-end space-y-3 md:space-y-0 md:space-x-3">
                                <div class="flex-1 w-full">
                                    <label for="image_file" class="block text-sm font-medium text-gray-700 mb-1">Pilih File
                                        Gambar (Max 5MB)</label>
                                    <input type="file" name="image_file" id="image_file"
                                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('image_file') border-red-500 @enderror">
                                    @error('image_file')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit"
                                    class="bg-[#6CF600] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#5bd300] transition-colors duration-200 w-full md:w-auto">
                                    <i class="fas fa-upload mr-2"></i> Unggah
                                </button>
                            </div>
                        </form>
                    </div>
                    {{-- END FORM UPLOAD --}}


                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const titleOptionSelect = document.getElementById('title_option');
                            const customTitleWrapper = document.getElementById('custom_title_wrapper');
                            const customTitleInput = document.getElementById('title_custom');
                            const finalTitleInput = document.getElementById('final_title');

                            // Fungsi untuk mengupdate nilai input tersembunyi
                            function updateFinalTitle() {
                                const selectedValue = titleOptionSelect.value;
                                if (selectedValue === 'custom') {
                                    finalTitleInput.value = customTitleInput.value;
                                } else {
                                    finalTitleInput.value = selectedValue;
                                }
                            }

                            // Event listener untuk dropdown
                            titleOptionSelect.addEventListener('change', function () {
                                if (this.value === 'custom') {
                                    customTitleWrapper.classList.remove('hidden');
                                    customTitleInput.focus();
                                } else {
                                    customTitleWrapper.classList.add('hidden');
                                }
                                updateFinalTitle();
                            });

                            // Event listener untuk input kustom
                            customTitleInput.addEventListener('input', function () {
                                updateFinalTitle();
                            });

                            // Panggil sekali saat load untuk inisialisasi
                            updateFinalTitle();
                        });
                    </script>

                </div>
                {{-- END CONTAINER --}}


                {{-- Daftar Gambar --}}
                <h2 class="text-xl font-semibold mb-4 mt-6">Daftar Gambar Tersedia</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full rounded-lg overflow-hidden border-collapse">
                        <thead>
                            <tr class="bg-[#292929] text-white uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Preview</th>
                                <th class="py-3 px-6 text-left">Judul</th>
                                <th class="py-3 px-6 text-left">Path</th>
                                <th class="py-3 px-6 text-left">Ukuran</th>
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
                                    <td class="py-4 px-6 text-left">{{ number_format($image->size / 1024 / 1024, 2) }} MB</td>
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
                                    <td colspan="5" class="py-8 text-center text-gray-500">Belum ada gambar yang diunggah.</td>
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