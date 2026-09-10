@extends('layouts.admin-app')

@section('content')

    
        <style>
            

            /* === TAMBAHKAN INI: Styling untuk Trix Editor === */
            trix-toolbar [data-trix-button-group="file-tools"] {
                display: none;
                /* Sembunyikan tombol upload file bawaan */
            }

            trix-editor {
                background-color: white;
                min-height: 300px;
                /* Atur tinggi minimal editor */
                border-radius: 0.5rem;
                border-width: 1px;
                border-color: #d1d5db;
                /* Sesuaikan dengan warna border input Anda */
            }

            trix-editor:focus-within {
                outline: 2px solid transparent;
                outline-offset: 2px;
                border-color: #6CF600;
                /* Warna focus ring */
                box-shadow: 0 0 0 2px #6CF600;
            }

            /* ================================================ */
        </style>


        <div class="main-content flex-1 p-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-[#292929]">Buat Konten Baru</h1>
                    <a href="{{ route('admin.writings.index') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Kembali</span>
                    </a>
                </div>

                <form action="{{ route('admin.writings.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="title_option" class="block text-sm font-medium text-gray-700 mb-1">Judul Konten</label>

                        {{-- 1. Dropdown dengan pilihan yang ada --}}
                        <select id="title_option"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600]">
                            <option value="About">About</option>
                            <option value="VisiMisi">Visi & Misi</option>
                            <option value="History">History</option>
                            <option value="Foundation">Foundation</option>
                            <option value="Achievement">Achievement</option>
                            <option value="custom">Tulis Judul Kustom</option>
                        </select>

                        {{-- 2. Input teks untuk judul kustom (awalnya disembunyikan) --}}
                        <div id="custom_title_wrapper"
                            class="max-h-0 opacity-0 overflow-hidden transition-all duration-300 ease-in-out mt-2">
                            <input type="text" id="title_custom" placeholder="Masukkan judul kustom Anda di sini..."
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600]">
                        </div>

                        {{-- 3. Input tersembunyi yang akan dikirim ke controller --}}
                        <input type="hidden" name="title" id="final_title" value="{{ old('title') }}">

                        {{-- Menampilkan error validasi --}}
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Letakkan script ini di bawah form Anda atau di dalam section @push('scripts') --}}
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const titleOptionSelect = document.getElementById('title_option');
                            const customTitleWrapper = document.getElementById('custom_title_wrapper');
                            const customTitleInput = document.getElementById('title_custom');
                            const finalTitleInput = document.getElementById('final_title');

                            // Fungsi untuk mengupdate nilai input tersembunyi
                            function updateFinalTitle() {
                                if (titleOptionSelect.value === 'custom') {
                                    finalTitleInput.value = customTitleInput.value;
                                } else {
                                    finalTitleInput.value = titleOptionSelect.value;
                                }
                            }

                            // Fungsi untuk mengatur keadaan awal form (penting untuk old input)
                            function setInitialState() {
                                const oldValue = finalTitleInput.value;
                                // Cek apakah nilai lama ada di dalam opsi dropdown
                                const isOldValueInOptions = [...titleOptionSelect.options].some(option => option.value === oldValue);

                                if (oldValue && !isOldValueInOptions) {
                                    // Jika nilai lama adalah kustom
                                    titleOptionSelect.value = 'custom';
                                    customTitleInput.value = oldValue;
                                    customTitleWrapper.classList.remove('max-h-0', 'opacity-0');
                                    customTitleWrapper.classList.add('max-h-96', 'opacity-100');
                                } else if (oldValue) {
                                    // Jika nilai lama ada di dropdown
                                    titleOptionSelect.value = oldValue;
                                }
                            }

                            // Event listener untuk dropdown
                            titleOptionSelect.addEventListener('change', function () {
                                if (this.value === 'custom') {
                                    // Tampilkan input kustom
                                    customTitleWrapper.classList.remove('max-h-0', 'opacity-0');
                                    customTitleWrapper.classList.add('max-h-96', 'opacity-100');
                                    customTitleInput.focus();
                                } else {
                                    // Sembunyikan input kustom
                                    customTitleWrapper.classList.remove('max-h-96', 'opacity-100');
                                    customTitleWrapper.classList.add('max-h-0', 'opacity-0');
                                }
                                updateFinalTitle();
                            });

                            // Event listener untuk input kustom
                            customTitleInput.addEventListener('input', updateFinalTitle);

                            // Atur keadaan awal saat halaman dimuat
                            setInitialState();
                        });
                    </script>

                    <!-- === UBAH BAGIAN INI: Ganti Textarea dengan Trix Editor === -->
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Isi Konten</label>

                        <!-- Input hidden ini akan diisi oleh Trix. `name` tetap "content" -->
                        <input id="content" type="hidden" name="content" value="{{ old('content') }}">

                        <!-- Ini adalah elemen Trix Editor. `input` dihubungkan ke `id` di atas -->
                        <trix-editor input="content" class="@error('content') border-red-500 @enderror"></trix-editor>

                        @error('content')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- ======================================================== -->

                    <div class="mb-4 flex space-x-4">
                        <div class="w-1/2">
                            <label for="publisher" class="block text-sm font-medium text-gray-700 mb-1">Publisher</label>
                            <input type="text" name="publisher" id="publisher"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('publisher') border-red-500 @enderror"
                                value="{{ old('publisher') }}">
                            @error('publisher')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="w-1/2">
                            <label for="release_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Rilis</label>
                            <input type="date" name="release_date" id="release_date"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6CF600] @error('release_date') border-red-500 @enderror"
                                value="{{ old('release_date') }}">
                            @error('release_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <button type="submit"
                            class="bg-[#6CF600] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#5bd300] transition-colors duration-200">
                            Simpan Konten
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </body>

    </html>

@endsection