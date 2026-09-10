@extends('layouts.admin-app')

@section('title', 'Galeri P5/PKK')

@section('content')
    @php
        $items = $projects->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->title,
                'sub' => \Illuminate\Support\Str::limit(strip_tags($p->description ?? ''), 140),
                'img' => $p->photo ? asset('storage/' . $p->photo) : null,
                'by' => $p->major ? $p->major->name : '-',
                'category' => $p->category ?? 'Produk',
                'class' => $p->student_class ?? '-',
                'price' => $p->price !== null ? number_format($p->price, 0, ',', '.') : 'Gratis',
                'ts' => $p->created_at->timestamp,
                'updated' => \Carbon\Carbon::parse($p->updated_at)->locale('id')->diffForHumans(),
            ];
        })->values();
        $total = $projects->count();
    @endphp

    <div class="fade-up space-y-6" x-data="tableIndex({
        raw: @json($items),
        statusKey: 'category',
        statuses: {
            Makanan: { label: 'Makanan', class: 'badge-warning' },
            Kerajinan: { label: 'Kerajinan', class: 'badge-info' },
            Jasa: { label: 'Jasa', class: 'badge-published' },
            Teknologi: { label: 'Teknologi', class: 'badge-draft' }
        },
        searchKeys: ['name', 'sub', 'by', 'category', 'class'],
        perPage: 8,
        exportUrl: @json(route('admin.export', ['resource' => 'pkk'])),
        emptyHead: 'Belum ada proyek P5/PKK',
        emptyBody: 'Tambahkan proyek P5/PKK pertama untuk ditampilkan di galeri.'
    })">

        <x-admin-components::page-header
            icon="fa-solid fa-lightbulb"
            kicker="Konten"
            title="Galeri P5/PKK"
            subtitle="Kelola hasil proyek P5 dan praktik kewirausahaan siswa.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.pkk.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Proyek</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Proyek" :value="$total" icon="fa-solid fa-lightbulb" tone="brand" />
            <x-admin-components::stat-card label="Kategori" :value="$projects->pluck('category')->unique()->count()" icon="fa-solid fa-shapes" tone="green" />
            <x-admin-components::stat-card label="Kelas Terlibat" :value="$projects->pluck('student_class')->unique()->count()" icon="fa-solid fa-user-graduate" tone="blue" />
            <x-admin-components::stat-card label="Berbayar" :value="$projects->whereNotNull('price')->count()" icon="fa-solid fa-tags" tone="amber" />
        </div>

        <div class="app-card overflow-hidden">
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari proyek, jurusan, kategori…" x-model="q">
                </div>
                <select class="app-select" style="width:auto" x-model="statusFilter">
                    <option value="all">Semua kategori</option>
                    <option value="Makanan">Makanan</option>
                    <option value="Kerajinan">Kerajinan</option>
                    <option value="Jasa">Jasa</option>
                    <option value="Teknologi">Teknologi</option>
                </select>
                <select class="app-select" style="width:auto" x-model="sortBy">
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="az">Nama A–Z</option>
                    <option value="za">Nama Z–A</option>
                </select>
                <span class="toolbar-spacer"></span>
                <button class="icon-btn" @click="refresh" title="Muat ulang" aria-label="Muat ulang"><i class="fa-solid fa-rotate-right" :class="{'fa-spin': loading}"></i></button>
                <a class="app-btn app-btn-md" :href="exportUrl" title="Export CSV"><i class="fa-solid fa-file-csv"></i><span class="hide-mob">Export</span></a>
            </div>

            <div class="table-wrap" x-show="!loading">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th>Proyek</th>
                            <th>Kategori</th>
                            <th>Kelas</th>
                            <th>Harga</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="p in paged" :key="p.id">
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:280px">
                                        <img class="thumb" :src="p.img || 'https://placehold.co/64x64/eff9e3/63cd00?text=PKK'" :alt="p.name" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate" x-text="p.name"></div>
                                            <div class="cell-sub truncate" style="max-width:320px" x-text="p.sub"></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge" :class="badgeClass(p.category)" x-text="statusLabel(p.category)"></span></td>
                                <td><span style="white-space:nowrap" x-text="p.class"></span></td>
                                <td><span style="white-space:nowrap" :style="p.price === 'Gratis' ? 'color:var(--green);font-weight:700' : ''" x-text="p.price"></span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" :href="'/admin/pkk/' + p.id + '/edit'"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" :href="'/admin/pkk/' + p.id"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <button class="dropdown-item danger" type="button" @click="askDelete(p, '/admin/pkk/' + p.id)"><i class="fa-solid fa-trash"></i><span>Hapus</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

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

            <div x-show="!loading && empty" x-cloak>
                <x-admin-components::empty-state icon="fa-lightbulb" :title="'Belum ada proyek P5/PKK'" description="Tambahkan proyek P5/PKK pertama untuk ditampilkan di galeri.">
                    <a class="app-btn app-btn-primary" href="{{ route('admin.pkk.create') }}"><i class="fa-solid fa-plus"></i>Tambah Proyek</a>
                </x-admin-components::empty-state>
            </div>

            <x-admin-components::pagination client countLabel="proyek" />
        </div>
    </div>
@endsection