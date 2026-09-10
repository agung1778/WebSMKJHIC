@extends('layouts.admin-app')

@section('title', 'Fasilitas')

@section('content')
    @php
        $items = $facilities->map(function ($f) {
            return [
                'id' => $f->id,
                'name' => $f->name,
                'sub' => \Illuminate\Support\Str::limit(strip_tags($f->description ?? ''), 140),
                'img' => $f->image ? asset('storage/' . $f->image) : null,
                'by' => $f->publisher ?? '-',
                'type' => $f->type ?? '-',
                'ts' => $f->created_at->timestamp,
                'updated' => \Carbon\Carbon::parse($f->updated_at)->locale('id')->diffForHumans(),
            ];
        })->values();
        $total = $facilities->count();
        $types = $facilities->pluck('type')->filter()->unique()->values();
    @endphp

    <div class="fade-up space-y-6" x-data="tableIndex({
        raw: @json($items),
        searchKeys: ['name', 'sub', 'by', 'type'],
        perPage: 8,
        exportUrl: @json(route('admin.export', ['resource' => 'facilities'])),
        emptyHead: 'Belum ada fasilitas',
        emptyBody: 'Tambahkan fasilitas pertama untuk ditampilkan di halaman website sekolah.'
    })">

        <x-admin-components::page-header
            icon="fa-solid fa-building-columns"
            kicker="Konten"
            title="Fasilitas"
            subtitle="Kelola sarana dan prasarana yang tersedia di sekolah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.facilities.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Fasilitas</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Fasilitas" :value="$total" icon="fa-solid fa-building-columns" tone="brand" />
            <x-admin-components::stat-card label="Jenis Unik" :value="$types->count()" icon="fa-solid fa-shapes" tone="green" />
            <x-admin-components::stat-card label="Dengan Gambar" :value="$facilities->whereNotNull('image')->count()" icon="fa-solid fa-image" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$facilities->max('updated_at') ? \Carbon\Carbon::parse($facilities->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        <div class="app-card overflow-hidden">
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari fasilitas…" x-model="q">
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

            <div class="table-wrap" x-show="!loading">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th>Fasilitas</th>
                            <th>Jenis</th>
                            <th>Penerbit</th>
                            <th>Diperbarui</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="f in paged" :key="f.id">
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:260px">
                                        <img class="thumb" :src="f.img || 'https://placehold.co/64x64/eff9e3/63cd00?text=FSL'" :alt="f.name" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate" x-text="f.name"></div>
                                            <div class="cell-sub truncate" style="max-width:340px" x-text="f.sub"></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-info" x-text="f.type"></span></td>
                                <td><span style="white-space:nowrap"><i class="fa-regular fa-user mr-1" style="color:var(--text-3)"></i><span x-text="f.by"></span></span></td>
                                <td><span class="cell-sub" style="white-space:nowrap" x-text="f.updated"></span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" :href="'/admin/facilities/' + f.id + '/edit'"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" :href="'/admin/facilities/' + f.id"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <button class="dropdown-item danger" type="button" @click="askDelete(f, '/admin/facilities/' + f.id)"><i class="fa-solid fa-trash"></i><span>Hapus</span></button>
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
                <x-admin-components::empty-state icon="fa-building-columns" :title="'Belum ada fasilitas'" description="Tambahkan fasilitas pertama untuk ditampilkan di halaman website sekolah.">
                    <a class="app-btn app-btn-primary" href="{{ route('admin.facilities.create') }}"><i class="fa-solid fa-plus"></i>Tambah Fasilitas</a>
                </x-admin-components::empty-state>
            </div>

            <x-admin-components::pagination client countLabel="fasilitas" />
        </div>
    </div>
@endsection