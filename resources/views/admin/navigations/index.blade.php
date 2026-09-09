@extends('layouts.admin-app')

@section('content')
    <style>
        input[type='search']::-webkit-search-decoration,
        input[type='search']::-webkit-search-cancel-button,
        input[type='search']::-webkit-search-results-button,
        input[type='search']::-webkit-search-results-decoration {
            -webkit-appearance: none;
        }
    </style>

    <div class="max-w-7xl mx-auto space-y-6">
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 border-b border-slate-200 gap-4">
            <h1 class="text-2xl font-bold text-slate-800">{{ __('Manajemen Navigasi') }}</h1>
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fa-regular fa-search text-slate-400"></i>
                    </span>
                    <input type="search" id="searchInput" placeholder="{{ __('Cari menu...') }}"
                        class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#6CF600] transition-shadow">
                </div>
                <a href="{{ route('admin.navigations.create') }}"
                    class="bg-[#6CF600] text-black px-4 py-2 rounded-xl text-sm font-bold hover:bg-[#5bd300] transition-colors flex items-center justify-center gap-2 w-full sm:w-auto shadow-sm">
                    <i class="fa-solid fa-plus"></i> {{ __('Tambah Menu') }}
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3">
                <i class="fa-solid fa-circle-check"></i>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mt-4">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                            <th class="py-4 px-6 w-16 text-center">{{ __('Urutan') }}</th>
                            <th class="py-4 px-6">{{ __('Label Menu') }}</th>
                            <th class="py-4 px-6">{{ __('URL / Link') }}</th>
                            <th class="py-4 px-6 text-center">{{ __('Posisi') }}</th>
                            <th class="py-4 px-6 text-center">{{ __('Tipe') }}</th>
                            <th class="py-4 px-6 text-center">{{ __('Status') }}</th>
                            <th class="py-4 px-6 text-center">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-600 divide-y divide-slate-100" id="navTableBody">
                        @forelse($navigations as $nav)
                            <tr class="hover:bg-slate-50 transition-colors nav-row">
                                <td class="py-4 px-6 text-center font-bold text-slate-800">{{ $nav->order }}</td>
                                <td class="py-4 px-6 font-semibold text-slate-800">{{ $nav->title }}</td>
                                <td class="py-4 px-6 text-xs font-mono text-blue-500">{{ $nav->url }}</td>
                                <td class="py-4 px-6 text-center">
                                    <span
                                        class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase">
                                        {{ str_replace('_', ' ', $nav->position) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if ($nav->type == 'button')
                                        <span
                                            class="px-2 py-1 bg-slate-800 text-white rounded text-[10px] font-bold uppercase">{{ __('Button') }}</span>
                                    @else
                                        <span
                                            class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase">{{ __('Link') }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if ($nav->is_active)
                                        <i class="fa-solid fa-check-circle text-green-500 text-lg" title="{{ __('Aktif') }}"></i>
                                    @else
                                        <i class="fa-solid fa-times-circle text-red-500 text-lg" title="{{ __('Nonaktif') }}"></i>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.navigations.edit', $nav->id) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors"
                                            title="{{ __('Edit') }}">
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </a>
                                        <form action="{{ route('admin.navigations.destroy', $nav->id) }}" method="POST"
                                            onsubmit="return confirm('{{ __('Yakin ingin menghapus menu ini?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
                                                title="{{ __('Hapus') }}">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-data">
                                <td colspan="7" class="py-8 text-center text-slate-500 text-sm">{{ __('Belum ada navigasi terdaftar.') }}</td>
                            </tr>
                        @endforelse
                        <tr id="no-results" class="hidden">
                            <td colspan="7" class="py-8 text-center text-slate-500 text-sm">{{ __('Menu tidak ditemukan.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const dataRows = document.querySelectorAll('.nav-row');
            const noResultsRow = document.getElementById('no-results');
            const noDataRow = document.getElementById('no-data');

            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    let visibleRows = 0;

                    dataRows.forEach(row => {
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
