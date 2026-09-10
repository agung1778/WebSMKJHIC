@extends('layouts.admin-app')

@section('title', 'Educational Programs')

@section('content')
    @php
        $programsData = $programs->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'desc' => \Illuminate\Support\Str::limit(strip_tags($p->description), 110),
                'img' => asset('storage/' . $p->image),
                'publisher' => $p->publisher,
                'status' => $p->status ?? 'published',
                'updated' => \Carbon\Carbon::parse($p->updated_at)->locale('id')->diffForHumans(),
                'ts' => $p->updated_at->timestamp,
            ];
        })->values();
        $total = $programs->count();
        $published = $programs->where('status', 'published')->count();
        $drafts = $programs->where('status', 'draft')->count();
        $archived = $programs->where('status', 'archived')->count();
    @endphp

    <div class="animate-fadein" x-data="programIndex" x-init="init('{{ csrf_token() }}')">

        {{-- Header --}}
        <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="text-[22px] font-extrabold" style="color:var(--text)">Educational Programs</h2>
                <p class="text-[13px] mt-1" style="color:var(--text-3)">Kelola program pendidikan yang ditampilkan di website sekolah.</p>
            </div>
            <a href="{{ route('admin.programs.create') }}" class="app-btn app-btn-primary app-btn-lg">
                <i class="fa-solid fa-plus"></i> Tambah Program
            </a>
        </div>

        {{-- Mini stats --}}
        <div class="mini-stats mb-6">
            <div class="mini-stat">
                <div class="ms-label">Total Program</div>
                <div class="ms-value">{{ $total }}</div>
            </div>
            <div class="mini-stat">
                <div class="ms-label">Published</div>
                <div class="ms-value" style="color:var(--green)">{{ $published }}</div>
            </div>
            <div class="mini-stat">
                <div class="ms-label">Draft</div>
                <div class="ms-value" style="color:var(--amber)">{{ $drafts }}</div>
            </div>
            <div class="mini-stat">
                <div class="ms-label">Archived</div>
                <div class="ms-value" style="color:var(--slate)">{{ $archived }}</div>
            </div>
        </div>

        {{-- Card utama --}}
        <div class="app-card">
            {{-- Toolbar --}}
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari program…" x-model="q" @input="applyFilter">
                </div>

                <select class="app-select" style="width:auto" x-model="statusFilter" @change="applyFilter">
                    <option value="all">Semua status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="archived">Archived</option>
                </select>

                <select class="app-select" style="width:auto" x-model="sortBy" @change="applyFilter">
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="az">Nama A–Z</option>
                    <option value="za">Nama Z–A</option>
                </select>

                <select class="app-select hide-mob" style="width:auto" x-model="timeFilter" @change="applyFilter">
                    <option value="all">Semua waktu</option>
                    <option value="7d">7 hari terakhir</option>
                    <option value="30d">30 hari terakhir</option>
                    <option value="year">Tahun ini</option>
                </select>

                <span class="toolbar-spacer"></span>

                <button class="icon-btn" @click="refresh" title="Muat ulang" aria-label="Muat ulang"><i class="fa-solid fa-rotate-right" :class="{'fa-spin': loading}"></i></button>

                <div class="seg-toggle">
                    <button :class="{ 'active': view === 'list' }" @click="view = 'list'; render()" title="Tampilan daftar" aria-label="Tampilan daftar"><i class="fa-solid fa-list"></i></button>
                    <button :class="{ 'active': view === 'grid' }" @click="view = 'grid'; render()" title="Tampilan kartu" aria-label="Tampilan kartu"><i class="fa-solid fa-table-cells-large"></i></button>
                </div>
            </div>

            {{-- GRID VIEW --}}
            <div x-show="view === 'grid'">
                <div class="prog-grid" x-show="!loading">
                    <template x-for="p in paged" :key="p.id">
                        <div class="prog-card">
                            <div class="prog-media">
                                <img :src="p.img" :alt="p.name" loading="lazy">
                                <div style="position:absolute;top:10px;left:10px">
                                    <span class="badge" :class="badgeClass(p.status)" x-text="statusLabel(p.status)"></span>
                                </div>
                                <input type="checkbox" class="checkbox-app" style="position:absolute;top:10px;right:10px" :checked="selected[p.id] === true" @change="toggleSelect(p.id, $event.target.checked)">
                            </div>
                            <div class="prog-body">
                                <span class="text-[11px] font-semibold uppercase tracking-wide" style="color:var(--brand)">Program Sekolah</span>
                                <div class="prog-name" x-text="p.name"></div>
                                <p class="prog-desc" x-text="p.desc"></p>
                                <div class="text-[11.5px] mt-3" style="color:var(--text-3)">Diperbarui <span x-text="p.updated"></span></div>
                            </div>
                            <div class="prog-foot">
                                <span class="prog-by"><i class="fa-regular fa-user mr-1"></i><span x-text="p.publisher"></span></span>
                                <button class="app-btn app-btn-sm app-btn-primary" @click="go('{{ url('/admin/programs') }}/' + p.id + '/edit')">Edit</button>
                                <div class="dropdown">
                                    <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                    <div class="dropdown-menu" style="display:none">
                                        <a class="dropdown-item" :href="'{{ url('/admin/programs') }}/' + p.id"><i class="fa-regular fa-eye"></i><span>Lihat</span></a>
                                        <button class="dropdown-item" type="button" @click="duplicate(p)"><i class="fa-solid fa-copy"></i><span>Duplikat</span></button>
                                        <template x-if="p.status !== 'published'">
                                            <button class="dropdown-item" type="button" @click="setStatus(p, 'published')"><i class="fa-solid fa-cloud-arrow-up"></i><span>Publish</span></button>
                                        </template>
                                        <template x-if="p.status !== 'archived'">
                                            <button class="dropdown-item" type="button" @click="setStatus(p, 'archived')"><i class="fa-solid fa-box-archive"></i><span>Arsipkan</span></button>
                                        </template>
                                        <div class="dropdown-sep"></div>
                                        <button class="dropdown-item danger" type="button" @click="askDelete(p)"><i class="fa-solid fa-trash"></i><span>Hapus</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- LIST VIEW --}}
                <div class="table-wrap" x-show="view === 'list'">
                    <table class="table-app">
                        <thead x-show="!loading">
                            <tr>
                                <th style="width:36px"><input type="checkbox" class="checkbox-app" @change="toggleAll($event.target.checked)" :checked="isAllSelected()"></th>
                                <th>Program</th>
                                <th>Status</th>
                                <th>Penerbit</th>
                                <th>Diperbarui</th>
                                <th style="text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody x-show="!loading">
                            <template x-for="p in paged" :key="p.id">
                                <tr>
                                    <td><input type="checkbox" class="checkbox-app" :checked="selected[p.id] === true" @change="toggleSelect(p.id, $event.target.checked)"></td>
                                    <td>
                                        <div class="flex items-center gap-3" style="min-width:240px">
                                            <img class="thumb" :src="p.img" :alt="p.name" loading="lazy">
                                            <div style="min-width:0">
                                                <div class="cell-main truncate" x-text="p.name"></div>
                                                <div class="cell-sub truncate" style="max-width:320px" x-text="p.desc"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge" :class="badgeClass(p.status)" x-text="statusLabel(p.status)"></span></td>
                                    <td><span style="white-space:nowrap" x-text="p.publisher"></span></td>
                                    <td><span style="white-space:nowrap" x-text="p.updated"></span></td>
                                    <td>
                                        <div class="flex items-center justify-end gap-2">
                                            <a class="app-btn app-btn-sm app-btn-primary" :href="'{{ url('/admin/programs') }}/' + p.id + '/edit'"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                            <div class="dropdown">
                                                <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                                <div class="dropdown-menu" style="display:none">
                                                    <a class="dropdown-item" :href="'{{ url('/admin/programs') }}/' + p.id"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                    <button class="dropdown-item" type="button" @click="duplicate(p)"><i class="fa-solid fa-copy"></i><span>Duplikat</span></button>
                                                    <template x-if="p.status !== 'published'">
                                                        <button class="dropdown-item" type="button" @click="setStatus(p, 'published')"><i class="fa-solid fa-cloud-arrow-up"></i><span>Publish</span></button>
                                                    </template>
                                                    <template x-if="p.status !== 'archived'">
                                                        <button class="dropdown-item" type="button" @click="setStatus(p, 'archived')"><i class="fa-solid fa-box-archive"></i><span>Arsipkan</span></button>
                                                    </template>
                                                    <div class="dropdown-sep"></div>
                                                    <button class="dropdown-item danger" type="button" @click="askDelete(p)"><i class="fa-solid fa-trash"></i><span>Hapus</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Skeleton --}}
                <div x-show="loading" class="p-5 grid gap-4">
                    <template x-for="i in 4" :key="i">
                        <div class="flex items-center gap-4">
                            <div class="skeleton" style="width:48px;height:48px;border-radius:12px"></div>
                            <div style="flex:1">
                                <div class="skeleton" style="height:14px;width:45%;margin-bottom:8px"></div>
                                <div class="skeleton" style="height:11px;width:70%"></div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Empty state --}}
                <div x-show="!loading && empty" x-cloak>
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                        <h3 x-text="emptyTitle"></h3>
                        <p x-text="emptyText"></p>
                        <a href="{{ route('admin.programs.create') }}" class="app-btn app-btn-primary"><i class="fa-solid fa-plus"></i>Tambah Program</a>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="pagination-bar" x-show="!loading && !empty">
                    <span class="range-text" x-text="'Menampilkan ' + rangeStart + '–' + rangeEnd + ' dari ' + filtered.length + ' program'"></span>
                    <div class="page-btns">
                        <button class="page-btn" @click="goPage(page - 1)" :disabled="page <= 1"><i class="fa-solid fa-chevron-left" style="font-size:10px"></i></button>
                        <template x-for="n in pageList" :key="n">
                            <button class="page-btn" :class="{ 'active': n === page }" @click="goPage(n)" x-text="n"></button>
                        </template>
                        <button class="page-btn" @click="goPage(page + 1)" :disabled="page >= totalPages"><i class="fa-solid fa-chevron-right" style="font-size:10px"></i></button>
                    </div>
                    <select class="app-select" style="width:auto" x-model.number="perPage" @change="applyFilter">
                        <option :value="8">8 per halaman</option>
                        <option :value="12">12 per halaman</option>
                        <option :value="24">24 per halaman</option>
                    </select>
                </div>
            </div>

            {{-- Bulk action bar --}}
            <div class="bulk-bar" :class="{ 'visible': selCount() > 0 }">
                <span class="bulk-count" x-text="selCount() + ' dipilih'"></span>
                <span style="width:1px;height:20px;background:var(--border)"></span>
                <button class="app-btn app-btn-sm app-btn-primary" @click="bulk('publish')"><i class="fa-solid fa-cloud-arrow-up"></i><span class="hide-mob">Publish</span></button>
                <button class="app-btn app-btn-sm" @click="bulk('archive')"><i class="fa-solid fa-box-archive"></i><span class="hide-mob">Arsipkan</span></button>
                <button class="app-btn app-btn-sm" @click="bulkExport"><i class="fa-solid fa-file-csv"></i><span class="hide-mob">Export CSV</span></button>
                <button class="app-btn app-btn-sm app-btn-danger" @click="bulk('delete')"><i class="fa-solid fa-trash"></i><span class="hide-mob">Hapus</span></button>
                <span style="width:1px;height:20px;background:var(--border)"></span>
                <button class="icon-btn" style="width:30px;height:30px" @click="clearSelection" aria-label="Bersihkan pilihan"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('programIndex', () => ({
                raw: @json($programsData),
                view: 'list',
                q: '',
                statusFilter: 'all',
                sortBy: 'newest',
                timeFilter: 'all',
                perPage: 8,
                page: 1,
                loading: false,
                selected: {},

                get filtered() {
                    let list = this.raw.slice();
                    const q = this.q.toLowerCase().trim();
                    if (q) list = list.filter(i => (i.name + ' ' + i.publisher + ' ' + i.desc).toLowerCase().includes(q));
                    if (this.statusFilter !== 'all') list = list.filter(i => i.status === this.statusFilter);
                    if (this.timeFilter !== 'all') {
                        const now = Date.now() / 1000;
                        const cut = this.timeFilter === '7d' ? 7 : this.timeFilter === '30d' ? 30 : 365;
                        list = list.filter(i => (now - i.ts) <= cut * 86400);
                    }
                    const key = {
                        newest: ['ts', false], oldest: ['ts', true],
                        az: ['name', false], za: ['name', true]
                    }[this.sortBy];
                    list.sort((a, b) => {
                        const aV = key[0] === 'ts' ? a.ts : a.name.toLowerCase();
                        const bV = key[0] === 'ts' ? b.ts : b.name.toLowerCase();
                        return aV < bV ? (key[1] ? 1 : -1) : aV > bV ? (key[1] ? -1 : 1) : 0;
                    });
                    return list;
                },

                get totalPages() { return Math.max(1, Math.ceil(this.filtered.length / this.perPage)); },
                get paged() {
                    const start = (this.page - 1) * this.perPage;
                    return this.filtered.slice(start, start + this.perPage);
                },
                get empty() { return this.filtered.length === 0; },
                get emptyTitle() { return this.q || this.statusFilter !== 'all' ? 'Tidak ada hasil' : 'Belum ada program'; },
                get emptyText() {
                    return this.q || this.statusFilter !== 'all'
                        ? 'Coba ubah kata kunci atau filter pencarian Anda.'
                        : 'Mulai tambahkan program pendidikan pertama untuk ditampilkan di website sekolah.';
                },
                get rangeStart() { return this.filtered.length === 0 ? 0 : (this.page - 1) * this.perPage + 1; },
                get rangeEnd() { return Math.min(this.page * this.perPage, this.filtered.length); },
                get pageList() {
                    const t = this.totalPages, c = this.page, set = new Set([1, t, c, c - 1, c + 1]);
                    return [...set].filter(n => n >= 1 && n <= t).sort((a, b) => a - b);
                },

                applyFilter() { this.page = 1; this.render(); },
                goPage(n) { this.page = Math.min(Math.max(1, n), this.totalPages); this.render(); this.scrollTop(); },
                scrollTop() { const el = document.querySelector('.app-content'); if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' }); },
                render() { this.$nextTick(() => { this.$el.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none'); }); },
                refresh() {
                    this.loading = true;
                    setTimeout(() => { this.loading = false; this.page = 1; this.applyFilter(); }, 500);
                },

                badgeClass(s) {
                    return { published: 'badge-published', draft: 'badge-draft', archived: 'badge-archived' }[s] || 'badge-archived';
                },
                statusLabel(s) {
                    return { published: 'Published', draft: 'Draft', archived: 'Archived' }[s] || s;
                },

                go(url) { window.location.href = url; },

                toggleSelect(id, checked) {
                    this.selected[id] = !!checked;
                },
                selCount() { return Object.keys(this.selected).filter(k => this.selected[k]).length; },
                selectedIds() { return Object.keys(this.selected).filter(k => this.selected[k]); },
                toggleAll(checked) {
                    const cur = {};
                    if (checked) this.paged.forEach(p => cur[p.id] = true);
                    this.selected = cur;
                },
                isAllSelected() { return this.paged.length > 0 && this.paged.every(p => this.selected[p.id] === true); },
                clearSelection() { this.selected = {}; },

                setStatus(p, status) {
                    const label = status === 'published' ? 'menerbitkan' : 'mengarsipkan';
                    const msg = status === 'published'
                        ? 'Program akan langsung tampil di website publik.'
                        : 'Program akan disembunyikan dari website publik.';
                    AppConfirm({
                        title: 'Konfirmasi',
                        message: `Yakin ingin ${label} program <b>${p.name}</b>? ${msg}`,
                        confirmText: `Ya, ${label}`,
                        onConfirm: () => {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ url('/admin/programs') }}/' + p.id + '/status';
                            form.innerHTML = '<input type="hidden" name="_token" value="' + this.csrf + '"><input type="hidden" name="status" value="' + status + '">';
                            document.body.appendChild(form); form.submit();
                        }
                    });
                },

                duplicate(p) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ url('/admin/programs') }}/' + p.id + '/duplicate';
                    form.innerHTML = '<input type="hidden" name="_token" value="' + this.csrf + '">';
                    document.body.appendChild(form); form.submit();
                },

                askDelete(p) {
                    AppConfirm({
                        title: 'Hapus Program',
                        message: `Hapus program <b>${p.name}</b>? Tindakan ini tidak dapat dibatalkan.`,
                        danger: true,
                        confirmText: 'Ya, hapus',
                        onConfirm: () => {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ url('/admin/programs') }}/' + p.id;
                            form.innerHTML = '<input type="hidden" name="_token" value="' + this.csrf + '"><input type="hidden" name="_method" value="DELETE">';
                            document.body.appendChild(form); form.submit();
                        }
                    });
                },

                bulk(action) {
                    const ids = this.selectedIds().map(Number);
                    if (ids.length === 0) return;
                    const label = action === 'publish' ? 'menerbitkan' : action === 'archive' ? 'mengarsipkan' : 'menghapus';
                    const danger = action === 'delete';
                    AppConfirm({
                        title: danger ? 'Hapus Massal' : 'Konfirmasi Aksi Massal',
                        message: `<b>${ids.length}</b> program akan di-${label}. ${danger ? 'Tindakan ini tidak dapat dibatalkan.' : ''}`,
                        danger: danger,
                        confirmText: danger ? 'Ya, hapus' : 'Ya, lanjutkan',
                        onConfirm: () => {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ url('/admin/programs/bulk') }}';
                            let html = '<input type="hidden" name="_token" value="' + this.csrf + '"><input type="hidden" name="action" value="' + action + '">';
                            ids.forEach(id => html += '<input type="hidden" name="ids[]" value="' + id + '">');
                            form.innerHTML = html;
                            document.body.appendChild(form); form.submit();
                        }
                    });
                },

                bulkExport() {
                    const ids = new Set(this.selectedIds().map(Number));
                    if (ids.size === 0) return;
                    const rows = this.raw.filter(i => ids.has(i.id));
                    const esc = v => '"' + String(v || '').replace(/"/g, '""') + '"';
                    const csv = '\uFEFF' + [
                        ['ID', 'Nama', 'Status', 'Penerbit', 'Deskripsi'],
                        ...rows.map(r => [r.id, r.name, this.statusLabel(r.status), r.publisher, r.desc])
                    ].map(r => r.map(esc).join(',')).join('\n');
                    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                    const a = document.createElement('a');
                    a.href = URL.createObjectURL(blob);
                    a.download = 'program-sekolah.csv';
                    a.click();
                    URL.revokeObjectURL(a.href);
                    AppToast(`Export ${rows.length} program berhasil.`, 'info');
                },

                init(csrf) { this.csrf = csrf; }
            }));
        });
    </script>
@endpush