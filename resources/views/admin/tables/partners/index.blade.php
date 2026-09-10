@extends('layouts.admin-app')

@section('title', 'Mitra Industri')

@section('content')
    @php
        $items = $partners->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'sub' => \Illuminate\Support\Str::limit(strip_tags($p->description ?? ''), 140),
                'img' => $p->logo ? asset('storage/' . $p->logo) : null,
                'by' => $p->publisher ?? '-',
                'sector' => $p->sector ?? '-',
                'city' => $p->city ?? '-',
                'ts' => $p->created_at->timestamp,
                'updated' => \Carbon\Carbon::parse($p->updated_at)->locale('id')->diffForHumans(),
            ];
        })->values();
        $total = $partners->count();
    @endphp

    <div class="fade-up space-y-6" x-data="tableIndex({
        raw: @json($items),
        searchKeys: ['name', 'sub', 'by', 'sector', 'city'],
        perPage: 8,
        exportUrl: @json(route('admin.export', ['resource' => 'partners'])),
        emptyHead: 'Belum ada mitra industri',
        emptyBody: 'Tambahkan mitra industri pertama untuk ditampilkan di halaman website sekolah.'
    })">

        <x-admin-components::page-header
            icon="fa-solid fa-handshake"
            kicker="Konten"
            title="Mitra Industri"
            subtitle="Kelola mitra industri dan dunia kerja yang bekerja sama dengan sekolah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.partners.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Mitra</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Mitra" :value="$total" icon="fa-solid fa-handshake" tone="brand" />
            <x-admin-components::stat-card label="Kota Terwakili" :value="$partners->pluck('city')->filter()->unique()->count()" icon="fa-solid fa-location-dot" tone="green" />
            <x-admin-components::stat-card label="Sektor Unik" :value="$partners->pluck('sector')->filter()->unique()->count()" icon="fa-solid fa-industry" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$partners->max('updated_at') ? \Carbon\Carbon::parse($partners->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        <div class="app-card overflow-hidden">
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari mitra, sektor, kota…" x-model="q">
                </div>
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

            <div class="table-wrap" x-show="!loading" x-cloak>
                <table class="table-app">
                    <thead>
                        <tr>
                            <th>Mitra</th>
                            <th>Sektor</th>
                            <th>Kota</th>
                            <th>Diperbarui</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="p in paged" :key="p.id">
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:240px">
                                        <img class="thumb" :src="p.img || 'https://placehold.co/64x64/eff9e3/63cd00?text=MTR'" :alt="p.name" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate" x-text="p.name"></div>
                                            <div class="cell-sub truncate" style="max-width:300px" x-text="p.sub"></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-info" x-text="p.sector"></span></td>
                                <td><span style="white-space:nowrap"><i class="fa-solid fa-location-dot mr-1" style="color:var(--text-3)"></i><span x-text="p.city"></span></span></td>
                                <td><span class="cell-sub" style="white-space:nowrap" x-text="p.updated"></span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" :href="'/admin/partners/' + p.id + '/edit'"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" :href="'/admin/partners/' + p.id"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <button class="dropdown-item danger" type="button" @click="askDelete(p, '/admin/partners/' + p.id)"><i class="fa-solid fa-trash"></i><span>Hapus</span></button>
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
                <x-admin-components::empty-state icon="fa-handshake" :title="'Belum ada mitra industri'" description="Tambahkan mitra industri pertama untuk ditampilkan di halaman website sekolah.">
                    <a class="app-btn app-btn-primary" href="{{ route('admin.partners.create') }}"><i class="fa-solid fa-plus"></i>Tambah Mitra</a>
                </x-admin-components::empty-state>
            </div>

            <x-admin-components::pagination client countLabel="mitra" />
        </div>
    </div>
@endsection