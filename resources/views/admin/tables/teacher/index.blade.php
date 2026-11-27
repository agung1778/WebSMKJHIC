@extends('layouts.admin-app')

@section('title', 'Guru & Staf')

@section('content')
    @php
        $total = $teachers->count();
    @endphp

    <div class="fade-up space-y-6" x-data="{ importModalOpen: false }">

        <x-admin-components::page-header
            icon="fa-solid fa-chalkboard-user"
            kicker="Akademik"
            title="Guru & Staf"
            subtitle="Kelola tenaga pendidik dan kependidikan SMK Amaliah.">
            <x-slot:actions>
                <form action="{{ route('admin.teachers.deduplicate') }}" method="POST" class="inline" onsubmit="return confirm('Satukan data guru/staf yang memiliki nama sama? Tugas dan mata pelajaran dari nama yang sama akan otomatis digabungkan tanpa kehilangan data.')">
                    @csrf
                    <button type="submit" class="app-btn app-btn-secondary app-btn-lg cursor-pointer" title="Periksa dan satukan nama-nama yang terduplikasi">
                        <i class="fa-solid fa-wand-magic-sparkles text-amber-500"></i><span class="hide-mob">Satukan Duplikasi</span>
                    </button>
                </form>
                <button type="button" @click="importModalOpen = true" class="app-btn app-btn-secondary app-btn-lg cursor-pointer">
                    <i class="fa-solid fa-file-import"></i><span class="hide-mob">Impor Data</span>
                </button>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.teachers.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Guru & Staf</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-2 xl:grid-cols-5 stat-grid-2">
            <x-admin-components::stat-card label="Total Guru & Staf" :value="$total" icon="fa-solid fa-chalkboard-user" tone="brand" />
            <x-admin-components::stat-card label="Amaliah 1 & 2" :value="$teachers->where('school','Amaliah 1 & 2')->count()" icon="fa-solid fa-school-circle-check" tone="amber" />
            <x-admin-components::stat-card label="Amaliah 1" :value="$teachers->where('school','Amaliah 1')->count()" icon="fa-solid fa-school" tone="green" />
            <x-admin-components::stat-card label="Amaliah 2" :value="$teachers->where('school','Amaliah 2')->count()" icon="fa-solid fa-school" tone="blue" />
            <div class="col-span-2 sm:col-span-1">
                <x-admin-components::stat-card label="Staff" :value="$teachers->where('school','Staff')->count()" icon="fa-solid fa-users-gear" tone="dark" />
            </div>
        </div>

        <x-admin-components::table
            :head="['Nama', ['label' => 'Sekolah', 'class' => 'hide-mob'], 'Kategori', ['label' => 'Diperbarui', 'class' => 'hide-mob'], ['label' => 'Aksi', 'right' => true, 'class' => 'has-3-actions']]"
            search-hint="Cari nama, jabatan, mapel…"
            :export-url="route('admin.export', ['resource' => 'teachers'])">
            <x-slot:body>
                @forelse ($teachers as $t)
                    @php $isStaff = ($t->school ?? '') === 'Staff'; @endphp
                    <tr data-row>
                        <td data-label="Nama">
                            <div class="table-main">
                                <img class="thumb thumb-round" src="{{ $t->photo ? asset('storage/' . $t->photo) : 'https://placehold.co/64x64/eff9e3/63cd00?text=GRU' }}" alt="{{ $t->name }}" loading="lazy">
                                <div style="min-width:0">
                                    <div class="cell-main truncate font-medium flex items-center gap-2">
                                        <span class="truncate">{{ $t->name }}</span>
                                        <span class="text-[10px] font-bold text-gray-600 bg-gray-100 px-1.5 py-0.5 rounded border border-gray-200 shrink-0" title="Urutan Hierarki: #{{ $t->order_column ?? 999 }}">#{{ $t->order_column ?? 999 }}</span>
                                    </div>
                                    <div class="cell-sub truncate text-xs">
                                        <span class="sm:hidden font-medium text-[var(--brand)]">{{ $t->school ? $t->school . ' · ' : '' }}</span>{{ trim(($t->subject ?? '') . (($t->subject && $t->position) ? ' · ' : '') . ($t->position ?? '')) ?: $t->position }}
                                        @if(!empty($t->additional_duties))
                                            <span class="text-xs text-gray-500 font-normal"> · <i class="fa-solid fa-list-check text-[10px] text-[var(--brand)]"></i> {{ $t->additional_duties }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Sekolah" class="hide-mob"><span class="badge badge-info">{{ $t->school ?? '-' }}</span></td>
                        <td data-label="Kategori">
                            <span class="badge {{ $isStaff ? 'badge-warning' : 'badge-published' }}">{{ $t->category ?? ($isStaff ? 'Staf' : 'Guru') }}</span>
                        </td>
                        <td data-label="Diperbarui" class="hide-mob"><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($t->updated_at)->locale('id')->diffForHumans() }}</span></td>
                        <td data-label="Aksi" class="has-3-actions text-right">
                            <div class="table-actions">
                                <a class="act-btn view" href="{{ route('admin.teachers.show', $t) }}" title="Lihat detail"><i class="fa-regular fa-eye"></i></a>
                                <a class="act-btn edit" href="{{ route('admin.teachers.edit', $t) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.teachers.destroy', $t) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus guru ini?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="act-btn delete" type="submit" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-row data-empty>
                        <td colspan="5">
                            <x-admin-components::empty-state
                                icon="fa-solid fa-chalkboard-user"
                                title="Belum ada guru"
                                description="Tambahkan data guru & staf pertama untuk ditampilkan di website sekolah."
                                action-label="Tambah Guru"
                                action-url="{{ route('admin.teachers.create') }}" />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>

        {{-- Modal Impor Massal Guru & Staf --}}
        <div x-show="importModalOpen" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity duration-200"
            @keydown.escape.window="importModalOpen = false">
            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-5 border border-gray-100"
                @click.outside="importModalOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">

                {{-- Header Modal --}}
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-[#63cd00]/15 text-[#3f8600] flex items-center justify-center text-base">
                            <i class="fa-solid fa-file-import"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 leading-tight">Impor Massal Guru & Staf</h3>
                            <p class="text-xs text-gray-500">Unggah satu file CSV / Excel untuk membuat semua data sekaligus.</p>
                        </div>
                    </div>
                    <button type="button" @click="importModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg cursor-pointer">
                        <i class="fa-solid fa-times text-base"></i>
                    </button>
                </div>

                {{-- Step 1: Download Template --}}
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-3.5 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-800">Format Template Resmi</p>
                        <p class="text-[11px] text-gray-500">Gunakan template CSV/Excel resmi yang sudah menyertakan contoh data guru kejuruan, umum, hingga staf TU & teknisi.</p>
                    </div>
                    <a href="{{ route('admin.teachers.template') }}" class="app-btn app-btn-secondary text-xs flex-shrink-0" download>
                        <i class="fa-solid fa-download"></i> Unduh Template
                    </a>
                </div>

                {{-- Petunjuk Kolom --}}
                <div class="p-3 bg-amber-50/70 border border-amber-200/80 rounded-xl text-[11px] text-amber-900 space-y-1">
                    <div class="font-semibold flex items-center gap-1.5 text-amber-950">
                        <i class="fa-solid fa-lightbulb text-amber-600"></i> Kolom yang Didukung:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-amber-800">
                        <li><strong class="font-semibold">Nama Lengkap</strong>: Wajib diisi beserta gelar akademik.</li>
                        <li><strong class="font-semibold">Unit / Sekolah</strong>: <code>Amaliah 1</code>, <code>Amaliah 2</code>, <code>Amaliah 1 & 2</code>, atau <code>Staff</code>.</li>
                        <li><strong class="font-semibold">Jabatan</strong>: Contoh: <em>Guru Kejuruan RPL</em>, <em>Kepala TU</em>, <em>Teknisi IT</em>.</li>
                        <li><strong class="font-semibold">Mata Pelajaran</strong>: Mapel yang diampu (bisa dikosongkan untuk staf).</li>
                        <li><strong class="font-semibold">Kategori</strong>: Guru (<em>Produktif</em>, <em>Normatif</em>, <em>Adaptif</em>, <em>Umum</em>, <em>Struktural</em>) atau Staf (<em>Administrasi / TU</em>, <em>Laboran</em>, <em>Teknisi</em>, <em>Perpustakaan</em>, <em>Keamanan</em>, <em>Kebersihan</em>, <em>Staf</em>).</li>
                    </ul>
                </div>

                {{-- Form Upload --}}
                <form action="{{ route('admin.teachers.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Pilih File CSV atau Excel (.xlsx, .csv) <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="file" accept=".csv, .xlsx, .xls, .txt" required
                            class="block w-full text-xs text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#63cd00]/15 file:text-[#2d6600] hover:file:bg-[#63cd00]/25 border border-gray-200 rounded-xl p-2 bg-white cursor-pointer focus:outline-none">
                    </div>

                    <div class="space-y-2.5 pt-1">
                        <label class="flex items-start gap-2.5 cursor-pointer text-xs text-gray-700 select-none bg-emerald-50/70 p-2.5 rounded-xl border border-emerald-200/60">
                            <input type="checkbox" name="update_existing" value="1" checked class="mt-0.5 rounded border-gray-300 text-[#63cd00] focus:ring-[#63cd00]">
                            <span><strong>Perbarui otomatis jika nama sudah ada</strong>: jabatan, mapel, tugas tambahan, dan urutan diperbarui otomatis tanpa membuat data ganda (tidak perlu edit satu per satu).</span>
                        </label>

                        <label class="flex items-start gap-2.5 cursor-pointer text-xs text-gray-600 select-none bg-gray-50 p-2.5 rounded-xl border border-gray-200">
                            <input type="checkbox" name="replace_all" value="1" class="mt-0.5 rounded border-gray-300 text-red-600 focus:ring-red-500" onchange="if(this.checked){ if(!confirm('Peringatan: Opsi ini akan mengosongkan semua data guru & staf yang ada terlebih dahulu dan menggantinya dengan isi file yang baru diunggah. Lanjutkan?')) this.checked = false; }">
                            <span><strong>Ganti Seluruh Data (Replace Master)</strong>: Kosongkan semua data lama terlebih dahulu, lalu masukkan semua data dari file ini secara bersih.</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-gray-100">
                        <button type="button" @click="importModalOpen = false" class="app-btn app-btn-secondary text-xs">
                            Batal
                        </button>
                        <button type="submit" class="app-btn app-btn-primary text-xs">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Mulai Impor Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection