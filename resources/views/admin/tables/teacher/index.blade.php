@extends('layouts.admin-app')

@section('title', 'Guru & Staf')

@section('content')
    @php
        $total = $teachers->count();
        $countAmaliah1 = $teachers->where('school', 'Amaliah 1')->count();
        $countAmaliah2 = $teachers->where('school', 'Amaliah 2')->count();
        $countBoth = $teachers->where('school', 'Amaliah 1 & 2')->count();
        $countStaff = $teachers->where('school', 'Staff')->count();
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-chalkboard-user"
            kicker="Akademik"
            title="Guru & Staf"
            subtitle="Kelola data tenaga pendidik dan kependidikan SMK Amaliah 1 & 2.">
            <x-slot:actions>
                {{-- Action buttons: horizontal flex bar with whitespace-nowrap on every button --}}
                <div class="flex flex-wrap items-center gap-2 sm:gap-2.5 w-full sm:w-auto">
                    <form action="{{ route('admin.teachers.deduplicate') }}" method="POST" class="inline-flex m-0 p-0" onsubmit="return confirm('Satukan data guru/staf yang memiliki nama sama? Tugas dan mata pelajaran dari nama yang sama akan otomatis digabungkan tanpa kehilangan data.')">
                        @csrf
                        <button type="submit" class="app-btn app-btn-secondary app-btn-md sm:app-btn-lg whitespace-nowrap cursor-pointer shrink-0" title="Periksa dan satukan nama-nama yang terduplikasi" aria-label="Satukan Duplikasi">
                            <i class="fa-solid fa-wand-magic-sparkles text-amber-500"></i>
                            <span class="hidden md:inline">Satukan Duplikasi</span>
                            <span class="md:hidden">Rapikan</span>
                        </button>
                    </form>

                    <button type="button" 
                        id="btnOpenTeacherImportModal"
                        onclick="openTeacherImportModal()"
                        class="app-btn app-btn-secondary app-btn-md sm:app-btn-lg whitespace-nowrap cursor-pointer shrink-0"
                        title="Buka jendela impor data guru & staf" 
                        aria-label="Impor Data">
                        <i class="fa-solid fa-file-import text-blue-600"></i>
                        <span class="hidden md:inline">Impor Data</span>
                        <span class="md:hidden">Impor</span>
                    </button>

                    <a href="{{ route('admin.teachers.export') }}" 
                       class="app-btn app-btn-secondary app-btn-md sm:app-btn-lg whitespace-nowrap cursor-pointer shrink-0" 
                       title="Unduh seluruh data guru & staf dalam format CSV / Excel" 
                       aria-label="Ekspor Data">
                        <i class="fa-solid fa-file-arrow-down text-emerald-600"></i>
                        <span class="hidden md:inline">Ekspor Data</span>
                        <span class="md:hidden">Ekspor</span>
                    </a>

                    <a class="app-btn app-btn-primary app-btn-md sm:app-btn-lg whitespace-nowrap shrink-0" 
                       href="{{ route('admin.teachers.create') }}" 
                       title="Tambah Guru atau Staf Baru" 
                       aria-label="Tambah Guru & Staf">
                        <i class="fa-solid fa-plus"></i><span>Tambah Guru & Staf</span>
                    </a>
                </div>
            </x-slot:actions>
        </x-admin-components::page-header>

        {{-- Ringkasan Kartu Statistik Interaktif (Dapat Diklik untuk Memfilter) --}}
        <div class="grid grid-cols-2 gap-2.5 sm:gap-3 sm:grid-cols-2 xl:grid-cols-5 stat-grid-2">
            <div class="cursor-pointer transition-transform hover:scale-[1.02] js-teacher-filter-card" data-filter-school="" title="Klik untuk menampilkan semua guru & staf">
                <x-admin-components::stat-card label="Total Guru & Staf" :value="$total" icon="fa-solid fa-chalkboard-user" tone="brand" />
            </div>
            <div class="cursor-pointer transition-transform hover:scale-[1.02] js-teacher-filter-card" data-filter-school="Amaliah 1 & 2" title="Klik untuk memfilter Amaliah 1 & 2">
                <x-admin-components::stat-card label="Amaliah 1 & 2" :value="$countBoth" icon="fa-solid fa-school-circle-check" tone="amber" />
            </div>
            <div class="cursor-pointer transition-transform hover:scale-[1.02] js-teacher-filter-card" data-filter-school="Amaliah 1" title="Klik untuk memfilter SMK Amaliah 1">
                <x-admin-components::stat-card label="Amaliah 1" :value="$countAmaliah1" icon="fa-solid fa-school" tone="green" />
            </div>
            <div class="cursor-pointer transition-transform hover:scale-[1.02] js-teacher-filter-card" data-filter-school="Amaliah 2" title="Klik untuk memfilter SMK Amaliah 2">
                <x-admin-components::stat-card label="Amaliah 2" :value="$countAmaliah2" icon="fa-solid fa-school" tone="blue" />
            </div>
            <div class="col-span-2 sm:col-span-1 cursor-pointer transition-transform hover:scale-[1.02] js-teacher-filter-card" data-filter-school="Staff" title="Klik untuk memfilter Tenaga Kependidikan / Staf">
                <x-admin-components::stat-card label="Staff" :value="$countStaff" icon="fa-solid fa-users-gear" tone="dark" />
            </div>
        </div>

        {{-- Filter Tab Navigasi Unit Sekolah (Kompak & Bebas Wrap Mid-Word) --}}
        <div class="bg-white dark:bg-gray-800/80 p-2 sm:p-2.5 rounded-2xl border border-gray-200/80 dark:border-gray-700/80 shadow-xs flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-1.5 overflow-x-auto w-full sm:w-auto pb-1 sm:pb-0 text-xs no-scrollbar" id="teacherFilterTabs">
                <button type="button" class="teacher-tab-btn px-3 py-1.5 rounded-xl font-semibold border transition-all cursor-pointer whitespace-nowrap shrink-0 active" data-school="">
                    <i class="fa-solid fa-layer-group text-[11px] mr-1"></i> Semua <span class="ml-1 text-[11px] opacity-75">({{ $total }})</span>
                </button>
                <button type="button" class="teacher-tab-btn px-3 py-1.5 rounded-xl font-semibold border transition-all cursor-pointer whitespace-nowrap shrink-0" data-school="Amaliah 1">
                    <i class="fa-solid fa-school text-emerald-500 text-[11px] mr-1"></i> Amaliah 1 <span class="ml-1 text-[11px] opacity-75">({{ $countAmaliah1 }})</span>
                </button>
                <button type="button" class="teacher-tab-btn px-3 py-1.5 rounded-xl font-semibold border transition-all cursor-pointer whitespace-nowrap shrink-0" data-school="Amaliah 2">
                    <i class="fa-solid fa-school text-blue-500 text-[11px] mr-1"></i> Amaliah 2 <span class="ml-1 text-[11px] opacity-75">({{ $countAmaliah2 }})</span>
                </button>
                <button type="button" class="teacher-tab-btn px-3 py-1.5 rounded-xl font-semibold border transition-all cursor-pointer whitespace-nowrap shrink-0" data-school="Amaliah 1 & 2">
                    <i class="fa-solid fa-school-circle-check text-amber-500 text-[11px] mr-1"></i> Amaliah 1 & 2 <span class="ml-1 text-[11px] opacity-75">({{ $countBoth }})</span>
                </button>
                <button type="button" class="teacher-tab-btn px-3 py-1.5 rounded-xl font-semibold border transition-all cursor-pointer whitespace-nowrap shrink-0" data-school="Staff">
                    <i class="fa-solid fa-users-gear text-indigo-500 text-[11px] mr-1"></i> Staff <span class="ml-1 text-[11px] opacity-75">({{ $countStaff }})</span>
                </button>
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400 font-medium px-1 sm:px-2 whitespace-nowrap" id="teacherResultCount">
                Menampilkan <span class="font-bold text-gray-800 dark:text-gray-200" id="teacherCurrentCount">{{ $total }}</span> data
            </div>
        </div>

        {{-- Tabel Data Guru & Staf --}}
        <div id="teacherTableContainer">
            <div class="sm:hidden text-[11px] text-gray-500 dark:text-gray-400 flex items-center justify-end gap-1.5 mb-1 px-1">
                <i class="fa-solid fa-arrows-left-right text-[10px] text-emerald-600"></i> Geser tabel untuk kolom & aksi lengkap
            </div>
            <x-admin-components::table
                :head="[
                    ['label' => 'Nama & Jabatan', 'class' => 'col-name'],
                    ['label' => 'Sekolah', 'class' => 'hide-mob'],
                    ['label' => 'Kategori'],
                    ['label' => 'Diperbarui', 'class' => 'hide-mob'],
                    ['label' => 'Aksi', 'right' => true, 'class' => 'has-3-actions']
                ]"
                search-hint="Cari nama, jabatan, mapel, tugas tambahan…">
                <x-slot:body>
                    @forelse ($teachers as $t)
                        @php 
                            $isStaff = ($t->school ?? '') === 'Staff';
                            $schoolBadgeClass = match($t->school) {
                                'Amaliah 1' => 'badge-brand',
                                'Amaliah 2' => 'badge-info',
                                'Amaliah 1 & 2' => 'badge-warning',
                                'Staff' => 'badge-indigo',
                                default => 'badge-draft'
                            };
                        @endphp
                        <tr data-row data-school="{{ $t->school }}" data-cat="{{ $t->school }}">
                            <td data-label="Nama & Jabatan">
                                <div class="table-main">
                                    <img class="thumb thumb-round shrink-0" 
                                         src="{{ $t->photo ? asset('storage/' . $t->photo) : 'https://placehold.co/64x64/eff9e3/63cd00?text=GRU' }}" 
                                         alt="{{ $t->name }}" 
                                         loading="lazy">
                                    <div style="min-width:0" class="flex-1">
                                        <div class="cell-main truncate font-medium flex items-center gap-1.5">
                                            <span class="truncate font-semibold">{{ $t->name }}</span>
                                            <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded border border-gray-200 dark:border-gray-700 shrink-0" title="Urutan Hierarki: #{{ $t->order_column ?? 999 }}">#{{ $t->order_column ?? 999 }}</span>
                                        </div>
                                        <div class="cell-sub text-xs space-y-0.5">
                                            <div class="truncate text-gray-700 dark:text-gray-300">
                                                <span class="sm:hidden font-semibold text-[10px] px-1.5 py-0.5 rounded {{ $schoolBadgeClass }} mr-1">{{ $t->school }}</span>
                                                {{ trim(($t->subject ?? '') . (($t->subject && $t->position) ? ' · ' : '') . ($t->position ?? '')) ?: $t->position }}
                                            </div>
                                            @if(!empty($t->additional_duties))
                                                <div class="truncate text-[11px] text-gray-500 dark:text-gray-400">
                                                    <i class="fa-solid fa-list-check text-[10px] text-emerald-600 dark:text-emerald-400 mr-1"></i>{{ $t->additional_duties }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Sekolah" class="hide-mob">
                                <span class="badge {{ $schoolBadgeClass }}">
                                    @if($t->school === 'Amaliah 1' || $t->school === 'Amaliah 2')
                                        <i class="fa-solid fa-school text-[10px]"></i>
                                    @elseif($t->school === 'Amaliah 1 & 2')
                                        <i class="fa-solid fa-school-circle-check text-[10px]"></i>
                                    @elseif($t->school === 'Staff')
                                        <i class="fa-solid fa-users-gear text-[10px]"></i>
                                    @endif
                                    {{ $t->school ?? '-' }}
                                </span>
                            </td>
                            <td data-label="Kategori">
                                <span class="badge {{ $isStaff ? 'badge-amber' : 'badge-published' }}">
                                    {{ $t->category ?? ($isStaff ? 'Staf' : 'Guru') }}
                                </span>
                            </td>
                            <td data-label="Diperbarui" class="hide-mob">
                                <span class="cell-sub" style="white-space:nowrap">
                                    {{ \Carbon\Carbon::parse($t->updated_at)->locale('id')->diffForHumans() }}
                                </span>
                            </td>
                            <td data-label="Aksi" class="has-3-actions text-right">
                                <div class="table-actions">
                                    <a class="act-btn view" href="{{ route('admin.teachers.show', $t) }}" title="Lihat detail guru & staf" aria-label="Lihat detail">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a class="act-btn edit" href="{{ route('admin.teachers.edit', $t) }}" title="Edit data" aria-label="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.teachers.destroy', $t) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus guru/staf ini?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="act-btn delete" type="submit" title="Hapus guru & staf" aria-label="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr data-row data-empty>
                            <td colspan="5">
                                <x-admin-components::empty-state
                                    icon="fa-solid fa-chalkboard-user"
                                    title="Belum ada guru & staf"
                                    description="Tambahkan data guru & staf pertama untuk ditampilkan di website sekolah."
                                    action-label="Tambah Guru & Staf"
                                    action-url="{{ route('admin.teachers.create') }}" />
                            </td>
                        </tr>
                    @endforelse
                </x-slot:body>
            </x-admin-components::table>

            {{-- Navigasi Paginasi Client-Side Responsif --}}
            <div id="teacherPagination" class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-gray-200/80 dark:border-gray-700/80 text-xs">
                <div class="text-gray-500 dark:text-gray-400" id="teacherPaginationInfo">
                    Menampilkan 1–20 dari {{ $total }} data
                </div>
                <div class="flex items-center gap-1.5" id="teacherPaginationControls">
                    {{-- Dinamis di-generate oleh JS --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Impor Massal Guru & Staf (Fixed Viewport, Sempurna di HP & Desktop) --}}
    <div class="modal-overlay" id="teacherImportModal" style="display: none; z-index: 99999;">
        <div class="modal-card w-full m-auto" style="max-width: 520px; max-height: calc(100vh - 32px); display: flex; flex-direction: column;">
            {{-- Header Modal (Fixed Top, flex-nowrap) --}}
            <div class="p-4 sm:p-5 flex flex-nowrap items-center justify-between border-b shrink-0" style="border-color: var(--border);">
                <div class="flex flex-nowrap items-center gap-3 min-w-0 pr-2">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-file-import"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 leading-tight truncate">Impor Massal Guru & Staf</h3>
                        <p class="text-xs text-gray-500 truncate">Unggah file CSV atau Excel (.xlsx) untuk sinkronisasi otomatis</p>
                    </div>
                </div>
                <button type="button" class="icon-btn js-teacher-import-close shrink-0" aria-label="Tutup jendela impor" style="margin-left: auto;">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            {{-- Body Modal (Scrollable Content) --}}
            <div class="p-4 sm:p-5 space-y-4 overflow-y-auto flex-1">
                {{-- Download Template Box (Kompak & Elegan) --}}
                <div class="bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl p-3 flex flex-nowrap items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Template Impor Resmi</p>
                        <p class="text-[11px] text-gray-500">File CSV kompatibel Excel dengan format kolom baku.</p>
                    </div>
                    <a href="{{ route('admin.teachers.template') }}" class="app-btn app-btn-secondary text-xs flex-shrink-0" download>
                        <i class="fa-solid fa-download"></i> Unduh Template
                    </a>
                </div>

                {{-- Accordion Panduan Format Kolom --}}
                <details class="group bg-amber-50/80 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-800/50 rounded-xl p-3 text-xs text-amber-900 dark:text-amber-200">
                    <summary class="font-semibold flex items-center justify-between cursor-pointer list-none select-none text-amber-950 dark:text-amber-100">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle-info text-amber-600"></i> Panduan Kolom Excel / CSV</span>
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform group-open:rotate-180"></i>
                    </summary>
                    <ul class="list-disc list-inside space-y-1 mt-2.5 pt-2 border-t border-amber-200/60 dark:border-amber-800/40 text-[11px] text-amber-800 dark:text-amber-300">
                        <li><strong>Nama Lengkap</strong>: Wajib diisi beserta gelar akademik.</li>
                        <li><strong>Unit / Sekolah</strong>: <code>Amaliah 1</code>, <code>Amaliah 2</code>, <code>Amaliah 1 & 2</code>, atau <code>Staff</code>.</li>
                        <li><strong>Jabatan</strong>: Contoh: <em>Guru Kejuruan RPL</em>, <em>Kepala Lab</em>, <em>Teknisi</em>.</li>
                        <li><strong>Mata Pelajaran</strong>: Mapel yang diampu (bisa dikosongkan untuk staf).</li>
                        <li><strong>Tugas Tambahan</strong>: Contoh: <em>Wali Kelas XII PPLG 1</em>, <em>Pembina OSIS</em>.</li>
                    </ul>
                </details>

                {{-- Form Upload Berkas --}}
                <form id="teacherImportForm" action="{{ route('admin.teachers.import') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            Pilih Berkas CSV atau Excel (.xlsx, .csv) <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="file" id="teacherImportFileInput" accept=".csv, .xlsx, .xls, .txt" required
                            class="block w-full text-xs text-gray-700 dark:text-gray-300 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#63cd00]/15 file:text-[#2d6600] hover:file:bg-[#63cd00]/25 border border-gray-200 dark:border-gray-700 rounded-xl p-2 bg-white dark:bg-gray-800 cursor-pointer focus:outline-none">
                    </div>

                    {{-- Jaminan Keamanan Data & Proteksi Foto --}}
                    <div class="bg-emerald-50/80 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 rounded-xl p-3 flex flex-nowrap items-start gap-2.5">
                        <i class="fa-solid fa-shield-check text-emerald-600 text-base mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-emerald-800 dark:text-emerald-300 leading-relaxed">
                            <strong>Sinkronisasi Pintar & Proteksi Foto:</strong> Foto profil lama dan riwayat guru <strong>tidak akan pernah terhapus</strong>. Data jabatan, mapel, dan tugas tambahan diperbarui otomatis tanpa duplikasi nama.
                        </p>
                    </div>
                </form>
            </div>

            {{-- Footer Modal (Fixed Bottom, flex-nowrap) --}}
            <div class="p-3.5 sm:p-4 border-t flex flex-nowrap items-center justify-end gap-2.5 shrink-0 bg-gray-50/80 dark:bg-gray-800/50" style="border-color: var(--border);">
                <button type="button" class="app-btn app-btn-secondary text-xs js-teacher-import-close cursor-pointer">
                    Batal
                </button>
                <button type="submit" form="teacherImportForm" id="btnSubmitImport" class="app-btn app-btn-primary text-xs cursor-pointer">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Mulai Impor Data
                </button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Styling Tombol Filter Tab Guru */
    .teacher-tab-btn {
        background-color: var(--surface, #ffffff);
        color: var(--text-2, #475569);
        border-color: var(--border, #e2e8f0);
    }
    .teacher-tab-btn:hover {
        background-color: var(--surface-2, #f8fafc);
        color: var(--text, #0f172a);
    }
    .teacher-tab-btn.active {
        background-color: #63cd00 !important;
        color: #ffffff !important;
        border-color: #63cd00 !important;
        box-shadow: 0 2px 8px rgba(99, 205, 0, 0.35);
    }
    .dark .teacher-tab-btn {
        background-color: rgba(30, 41, 59, 0.7);
        color: #94a3b8;
        border-color: #334155;
    }
    .dark .teacher-tab-btn:hover {
        background-color: rgba(51, 65, 85, 0.8);
        color: #f8fafc;
    }

    /* Paginasi Button Styling */
    .teacher-pg-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        font-size: 11.5px;
        font-weight: 600;
        border-radius: 8px;
        border: 1px solid var(--border, #e2e8f0);
        background: var(--surface, #ffffff);
        color: var(--text-2, #475569);
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .teacher-pg-btn:hover:not(:disabled) {
        background: var(--surface-2, #f1f5f9);
        color: var(--text, #0f172a);
    }
    .teacher-pg-btn.active {
        background: #63cd00 !important;
        color: #ffffff !important;
        border-color: #63cd00 !important;
    }
    .teacher-pg-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* Override pasti untuk menyembunyikan baris saat filter / paginasi */
    tr.hidden-row,
    tr[data-hidden="true"],
    .table-app tbody tr.hidden-row,
    .app-content .table-app tbody tr.hidden-row {
        display: none !important;
    }

    /* No-scrollbar utility */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        // --- 1. Manajemen Modal Impor (Sempurna di Body) ---
        function ensureModalInBody() {
            var m = document.getElementById('teacherImportModal');
            if (m && m.parentElement !== document.body) {
                document.body.appendChild(m);
            }
            return m;
        }

        window.openTeacherImportModal = function () {
            var m = ensureModalInBody();
            if (m) {
                var card = m.querySelector('.modal-card');
                if (card) {
                    var body = card.querySelector('.overflow-y-auto');
                    if (body) body.scrollTop = 0;
                }
                m.style.display = 'flex';
                document.body.classList.add('overflow-hidden');
            }
        };

        window.closeTeacherImportModal = function () {
            var m = ensureModalInBody();
            if (m) {
                m.style.display = 'none';
                document.body.classList.remove('overflow-hidden');
            }
        };

        // --- 2. Filter Tab Interaktif & Paginasi Client-Side ---
        var currentSchoolFilter = '';
        var currentSearchQuery = '';
        var currentPage = 1;
        var pageSize = 20;

        function initFilterAndPagination() {
            var root = document.querySelector('[data-filter-root]');
            if (!root) return;

            var searchInput = root.querySelector('input[data-filter-input]');
            var allRows = Array.prototype.slice.call(root.querySelectorAll('tbody tr[data-row]:not([data-empty])'));
            var emptyRow = root.querySelector('tbody tr[data-empty]');
            var tabButtons = document.querySelectorAll('.teacher-tab-btn');
            var filterCards = document.querySelectorAll('.js-teacher-filter-card');
            var resultCountEl = document.getElementById('teacherCurrentCount');
            var paginationInfo = document.getElementById('teacherPaginationInfo');
            var paginationControls = document.getElementById('teacherPaginationControls');

            function applyFilter() {
                var filtered = [];

                allRows.forEach(function (row) {
                    var rowSchool = (row.getAttribute('data-school') || '').trim();
                    var rowText = (row.textContent || '').toLowerCase();

                    var matchesSchool = !currentSchoolFilter || rowSchool === currentSchoolFilter;
                    var matchesSearch = !currentSearchQuery || rowText.indexOf(currentSearchQuery) !== -1;

                    if (matchesSchool && matchesSearch) {
                        filtered.push(row);
                    } else {
                        row.style.setProperty('display', 'none', 'important');
                        row.classList.add('hidden-row');
                        row.setAttribute('data-hidden', 'true');
                    }
                });

                // Update live count indicator
                if (resultCountEl) {
                    resultCountEl.textContent = filtered.length;
                }

                // Empty state handling
                if (emptyRow) {
                    if (filtered.length === 0) {
                        emptyRow.style.removeProperty('display');
                        emptyRow.classList.remove('hidden-row');
                        emptyRow.removeAttribute('data-hidden');
                    } else {
                        emptyRow.style.setProperty('display', 'none', 'important');
                        emptyRow.classList.add('hidden-row');
                        emptyRow.setAttribute('data-hidden', 'true');
                    }
                }

                // Pagination
                var totalFiltered = filtered.length;
                var totalPages = Math.ceil(totalFiltered / pageSize) || 1;
                if (currentPage > totalPages) currentPage = totalPages;
                if (currentPage < 1) currentPage = 1;

                var startIndex = (currentPage - 1) * pageSize;
                var endIndex = startIndex + pageSize;

                filtered.forEach(function (row, idx) {
                    if (idx >= startIndex && idx < endIndex) {
                        row.style.removeProperty('display');
                        row.classList.remove('hidden-row');
                        row.removeAttribute('data-hidden');
                    } else {
                        row.style.setProperty('display', 'none', 'important');
                        row.classList.add('hidden-row');
                        row.setAttribute('data-hidden', 'true');
                    }
                });

                // Render pagination info
                if (paginationInfo) {
                    if (totalFiltered === 0) {
                        paginationInfo.textContent = 'Tidak ada data yang cocok';
                    } else {
                        var from = startIndex + 1;
                        var to = Math.min(endIndex, totalFiltered);
                        paginationInfo.textContent = 'Menampilkan ' + from + '–' + to + ' dari ' + totalFiltered + ' data';
                    }
                }

                // Render pagination buttons
                if (paginationControls) {
                    paginationControls.innerHTML = '';
                    if (totalPages <= 1) {
                        paginationControls.style.display = 'none';
                        return;
                    }
                    paginationControls.style.display = 'flex';

                    // Prev Button
                    var prevBtn = document.createElement('button');
                    prevBtn.type = 'button';
                    prevBtn.className = 'teacher-pg-btn';
                    prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left text-[10px]"></i>';
                    prevBtn.title = 'Halaman Sebelumnya';
                    prevBtn.disabled = currentPage === 1;
                    prevBtn.addEventListener('click', function () {
                        if (currentPage > 1) {
                            currentPage--;
                            applyFilter();
                            scrollToTable();
                        }
                    });
                    paginationControls.appendChild(prevBtn);

                    // Page numbers (max 5 visible buttons)
                    var startPage = Math.max(1, currentPage - 2);
                    var endPage = Math.min(totalPages, startPage + 4);
                    if (endPage - startPage < 4) {
                        startPage = Math.max(1, endPage - 4);
                    }

                    if (startPage > 1) {
                        var firstBtn = createPageBtn(1);
                        paginationControls.appendChild(firstBtn);
                        if (startPage > 2) {
                            var dots = document.createElement('span');
                            dots.className = 'px-1 text-gray-400 text-xs';
                            dots.textContent = '…';
                            paginationControls.appendChild(dots);
                        }
                    }

                    for (var p = startPage; p <= endPage; p++) {
                        paginationControls.appendChild(createPageBtn(p));
                    }

                    if (endPage < totalPages) {
                        if (endPage < totalPages - 1) {
                            var dots2 = document.createElement('span');
                            dots2.className = 'px-1 text-gray-400 text-xs';
                            dots2.textContent = '…';
                            paginationControls.appendChild(dots2);
                        }
                        paginationControls.appendChild(createPageBtn(totalPages));
                    }

                    // Next Button
                    var nextBtn = document.createElement('button');
                    nextBtn.type = 'button';
                    nextBtn.className = 'teacher-pg-btn';
                    nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right text-[10px]"></i>';
                    nextBtn.title = 'Halaman Selanjutnya';
                    nextBtn.disabled = currentPage === totalPages;
                    nextBtn.addEventListener('click', function () {
                        if (currentPage < totalPages) {
                            currentPage++;
                            applyFilter();
                            scrollToTable();
                        }
                    });
                    paginationControls.appendChild(nextBtn);
                }
            }

            function createPageBtn(pageNum) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'teacher-pg-btn' + (pageNum === currentPage ? ' active' : '');
                btn.textContent = pageNum;
                btn.addEventListener('click', function () {
                    currentPage = pageNum;
                    applyFilter();
                    scrollToTable();
                });
                return btn;
            }

            function scrollToTable() {
                var container = document.getElementById('teacherTableContainer');
                if (container) {
                    var y = container.getBoundingClientRect().top + window.pageYOffset - 80;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            }

            function setSchoolFilter(school) {
                currentSchoolFilter = school;
                currentPage = 1;

                // Sync Tab Buttons
                tabButtons.forEach(function (btn) {
                    var btnSchool = btn.getAttribute('data-school') || '';
                    if (btnSchool === currentSchoolFilter) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });

                applyFilter();
            }

            // Tab button click handlers
            tabButtons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    setSchoolFilter(btn.getAttribute('data-school') || '');
                });
            });

            // Stat card click handlers
            filterCards.forEach(function (card) {
                card.addEventListener('click', function () {
                    setSchoolFilter(card.getAttribute('data-filter-school') || '');
                });
            });

            // Search input handler
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    currentSearchQuery = (searchInput.value || '').toLowerCase().trim();
                    currentPage = 1;
                    applyFilter();
                });
            }

            // Initial run
            applyFilter();
        }

        // --- 3. Event Listeners Init ---
        function initHandlers() {
            ensureModalInBody();
            var m = document.getElementById('teacherImportModal');
            var btnOpen = document.getElementById('btnOpenTeacherImportModal');
            var form = document.getElementById('teacherImportForm');

            if (btnOpen) {
                btnOpen.removeEventListener('click', openTeacherImportModal);
                btnOpen.addEventListener('click', function (e) {
                    e.preventDefault();
                    window.openTeacherImportModal();
                });
            }

            document.querySelectorAll('.js-teacher-import-close').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    window.closeTeacherImportModal();
                });
            });

            if (m) {
                m.addEventListener('click', function (e) {
                    if (e.target === m) {
                        window.closeTeacherImportModal();
                    }
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && m && m.style.display === 'flex') {
                    window.closeTeacherImportModal();
                }
            });

            if (form) {
                form.addEventListener('submit', function () {
                    var btn = document.getElementById('btnSubmitImport');
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sedang Mengimpor...';
                    }
                });
            }

            initFilterAndPagination();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initHandlers);
        } else {
            initHandlers();
        }
    })();
</script>
@endpush
