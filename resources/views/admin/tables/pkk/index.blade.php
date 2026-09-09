@extends('layouts.admin-app')

@section('content')
    <style>
        /* Menyembunyikan panah default pada input search */
        input[type='search']::-webkit-search-decoration,
        input[type='search']::-webkit-search-cancel-button,
        input[type='search']::-webkit-search-results-button,
        input[type='search']::-webkit-search-results-decoration {
            -webkit-appearance: none;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }
    </style>

    <div class="max-w-7xl mx-auto space-y-6">
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 border-b border-slate-200 gap-4 animate-fade-up">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Galeri PKK</h1>
                <p class="text-xs text-slate-500 mt-1">Kelola hasil karya produk kreatif dan kewirausahaan siswa.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                {{-- Fitur Pencarian Ditambahkan --}}
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fa-regular fa-search text-slate-400"></i>
                    </span>
                    <input type="search" id="searchInput" placeholder="Cari proyek/brand..."
                        class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#6CF600] transition-shadow bg-white">
                </div>
                <a href="{{ route('admin.pkk.create') }}"
                    class="bg-[#6CF600] text-black px-4 py-2 rounded-xl text-sm font-bold hover:bg-[#5bd300] transition-colors flex items-center justify-center gap-2 w-full sm:w-auto shadow-sm">
                    <i class="fa-solid fa-plus"></i> Tambah Proyek
                </a>
            </div>
        </div>

        @if (session('success'))
            <div
                class="animate-fade-up bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check"></i>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 transition"><i
                        class="fas fa-times"></i></button>
            </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden animate-fade-up"
            style="animation-delay: 0.1s;">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                            <th class="py-4 px-6">Produk / Brand</th>
                            <th class="py-4 px-6">Tim Pengembang</th>
                            <th class="py-4 px-6">Kategori</th>
                            <th class="py-4 px-6">Jurusan</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-600 divide-y divide-slate-100" id="pkkTableBody">
                        @forelse($projects as $item)
                            {{-- Class pkk-row untuk trigger pencarian --}}
                            <tr class="hover:bg-slate-50 transition-colors pkk-row">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="relative flex-shrink-0">
                                            <img src="{{ asset('storage/' . $item->photo) }}"
                                                class="w-12 h-12 object-cover rounded-lg shadow-sm border border-slate-200 bg-white">
                                            @if ($item->logo)
                                                <img src="{{ asset('storage/' . $item->logo) }}"
                                                    class="absolute -bottom-2 -right-2 w-6 h-6 rounded-full border border-slate-200 bg-white object-contain shadow-sm">
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $item->brand_name ?? $item->title }}</p>
                                            @if ($item->brand_name)
                                                <p class="text-xs text-slate-500 truncate max-w-[150px]">{{ $item->title }}
                                                </p>
                                            @endif
                                            @if ($item->price)
                                                <p class="text-[11px] font-bold text-[#6CF600] mt-0.5">Rp
                                                    {{ number_format($item->price, 0, ',', '.') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-sm font-semibold text-slate-700">{{ $item->student_names }}</p>
                                    <p class="text-xs text-slate-500">Kelas {{ $item->student_class }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[10px] font-bold text-slate-700 bg-slate-100 border border-slate-200 uppercase tracking-wider">
                                        {{ $item->major->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.pkk.show', $item->id) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"
                                            title="Detail">
                                            <i class="fa-regular fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('admin.pkk.edit', $item->id) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors"
                                            title="Edit">
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </a>
                                        <form action="{{ route('admin.pkk.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
                                                title="Hapus">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-data">
                                <td colspan="5" class="py-12 text-center text-slate-500">
                                    <div
                                        class="bg-slate-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-100">
                                        <i class="fa-solid fa-box-open text-2xl text-slate-300"></i>
                                    </div>
                                    <p class="text-sm">Belum ada data proyek.</p>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="no-results" class="hidden">
                            <td colspan="5" class="py-12 text-center text-slate-500 text-sm">Proyek tidak ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const dataRows = document.querySelectorAll('.pkk-row');
            const noResultsRow = document.getElementById('no-results');
            const noDataRow = document.getElementById('no-data');

            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    let visibleRows = 0;

                    dataRows.forEach(row => {
                        // Cari berdasarkan kolom pertama (Brand/Judul) dan kedua (Nama Siswa)
                        const brandCell = row.cells[0]?.textContent.toLowerCase() || '';
                        const studentCell = row.cells[1]?.textContent.toLowerCase() || '';

                        if (brandCell.includes(searchTerm) || studentCell.includes(searchTerm)) {
                            row.style.display = '';
                            visibleRows++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    if (!noDataRow) {
                        if (visibleRows === 0) {
                            noResultsRow.classList.remove('hidden');
                        } else {
                            noResultsRow.classList.add('hidden');
                        }
                    }
                });
            }
        });
    </script>
@endsection
