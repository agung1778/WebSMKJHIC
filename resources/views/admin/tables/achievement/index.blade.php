@extends('layouts.admin-app')

@section('title', 'Prestasi')

@section('content')
    @php
        $items = $achievements->map(function ($a) {
            return [
                'id' => $a->id,
                'name' => $a->title,
                'sub' => \Illuminate\Support\Str::limit(strip_tags($a->description ?? ''), 140),
                'img' => $a->image ? asset('storage/' . $a->image) : null,
                'by' => $a->publisher ?? '-',
                'category' => $a->category ?? 'Individual',
                'level' => $a->level ?? '-',
                'winner' => $a->winner ?? '-',
                'date' => \Carbon\Carbon::parse($a->date)->translatedFormat('d M Y'),
                'ts' => $a->created_at->timestamp,
                'updated' => \Carbon\Carbon::parse($a->updated_at)->locale('id')->diffForHumans(),
            ];
        })->values();
        $total = $achievements->count();
    @endphp

    <div class="fade-up space-y-6" x-data="tableIndex({
        raw: @json($items),
        statusKey: 'category',
        statuses: {
            Individual: { label: 'Individual', class: 'badge-info' },
            Institutional: { label: 'Institutional', class: 'badge-published' }
        },
        searchKeys: ['name', 'sub', 'by', 'winner', 'level'],
        perPage: 8,
        exportUrl: @json(route('admin.export', ['resource' => 'achievements'])),
        emptyHead: 'Belum ada prestasi',
        emptyBody: 'Tambahkan prestasi pertama untuk ditampilkan di website sekolah.'
    })">

        <x-admin-components::page-header
            icon="fa-solid fa-trophy"
            kicker="Akademik"
            title="Prestasi"
            subtitle="Kelola prestasi siswa dan sekolah yang ditampilkan di website.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.achievements.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Prestasi</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Prestasi" :value="$total" icon="fa-solid fa-trophy" tone="brand" />
            <x-admin-components::stat-card label="Individual" :value="$achievements->where('category','Individual')->count()" icon="fa-solid fa-user" tone="green" />
            <x-admin-components::stat-card label="Institutional" :value="$achievements->where('category','Institutional')->count()" icon="fa-solid fa-building-shield" tone="blue" />
            <x-admin-components::stat-card label="Tingkat Unik" :value="$achievements->pluck('level')->filter()->unique()->count()" icon="fa-solid fa-medal" tone="amber" />
        </div>

        <div>
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari prestasi, juara, tingkat…" x-model="q">
                </div>
                <select class="app-select" style="width:auto" x-model="statusFilter">
                    <option value="all">Semua kategori</option>
                    <option value="Individual">Individual</option>
                    <option value="Institutional">Institutional</option>
                </select>
                <select class="app-select" style="width:auto" x-model="sortBy">
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="az">Judul A–Z</option>
                    <option value="za">Judul Z–A</option>
                </select>
                <span class="toolbar-spacer"></span>
                <button class="icon-btn" @click="refresh" title="Muat ulang" aria-label="Muat ulang"><i class="fa-solid fa-rotate-right" :class="{'fa-spin': loading}"></i></button>
                <a class="app-btn app-btn-md" :href="exportUrl" title="Export CSV"><i class="fa-solid fa-file-csv"></i><span class="hide-mob">Export</span></a>
            </div>

            <div class="table-wrap" x-show="!loading">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th>Prestasi</th>
                            <th>Kategori</th>
                            <th>Pemenang</th>
                            <th>Tanggal</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="a in paged" :key="a.id">
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:260px">
                                        <img class="thumb" :src="a.img || 'https://placehold.co/64x64/eff9e3/63cd00?text=PRS'" :alt="a.name" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate" x-text="a.name"></div>
                                            <div class="cell-sub truncate" style="max-width:320px" x-text="a.sub"></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge" :class="badgeClass(a.category)" x-text="statusLabel(a.category)"></span></td>
                                <td>
                                    <div style="min-width:0">
                                        <div class="cell-main truncate" style="max-width:180px" x-text="a.winner"></div>
                                        <div class="cell-sub" x-text="a.level"></div>
                                    </div>
                                </td>
                                <td><span class="cell-sub" style="white-space:nowrap" x-text="a.date"></span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" :href="'/admin/achievements/' + a.id + '/edit'"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" :href="'/admin/achievements/' + a.id"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <button class="dropdown-item danger" type="button" @click="askDelete(a, '/admin/achievements/' + a.id)"><i class="fa-solid fa-trash"></i><span>Hapus</span></button>
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
                <x-admin-components::empty-state icon="fa-trophy" :title="'Belum ada prestasi'" description="Tambahkan prestasi pertama untuk ditampilkan di website sekolah.">
                    <a class="app-btn app-btn-primary" href="{{ route('admin.achievements.create') }}"><i class="fa-solid fa-plus"></i>Tambah Prestasi</a>
                </x-admin-components::empty-state>
            </div>

            <x-admin-components::pagination client countLabel="prestasi" />
        </div>
    </div>
@endsection