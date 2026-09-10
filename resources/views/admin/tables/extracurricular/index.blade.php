@extends('layouts.admin-app')

@section('title', 'Ekstrakurikuler')

@section('content')
    @php
        $items = $extracurriculars->map(function ($e) {
            return [
                'id' => $e->id,
                'name' => $e->name,
                'sub' => \Illuminate\Support\Str::limit(strip_tags($e->description ?? ''), 150),
                'img' => $e->image ? asset('storage/' . $e->image) : null,
                'by' => $e->publisher ?? '-',
                'type' => $e->type ?? 'Pilihan',
                'coach' => $e->coach ?? '-',
                'contact' => $e->contact ?? '-',
                'ts' => $e->created_at->timestamp,
                'updated' => \Carbon\Carbon::parse($e->updated_at)->locale('id')->diffForHumans(),
            ];
        })->values();
        $total = $extracurriculars->count();
    @endphp

    <div class="fade-up space-y-6" x-data="tableIndex({
        raw: @json($items),
        statusKey: 'type',
        statuses: {
            Wajib: { label: 'Wajib', class: 'badge-info' },
            Pilihan: { label: 'Pilihan', class: 'badge-warning' }
        },
        searchKeys: ['name', 'sub', 'by', 'coach', 'type'],
        perPage: 8,
        exportUrl: @json(route('admin.export', ['resource' => 'extracurriculars'])),
        emptyHead: 'Belum ada ekstrakurikuler',
        emptyBody: 'Tambahkan ekstrakurikuler pertama untuk ditampilkan di website sekolah.'
    })">

        <x-admin-components::page-header
            icon="fa-solid fa-futbol"
            kicker="Akademik"
            title="Ekstrakurikuler"
            subtitle="Kelola kegiatan ekstrakurikuler siswa SMK Amaliah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.extracurriculars.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Ekskul</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Ekskul" :value="$total" icon="fa-solid fa-futbol" tone="brand" />
            <x-admin-components::stat-card label="Wajib" :value="$extracurriculars->where('type','Wajib')->count()" icon="fa-solid fa-list-check" tone="green" />
            <x-admin-components::stat-card label="Pilihan" :value="$extracurriculars->where('type','Pilihan')->count()" icon="fa-solid fa-hand-pointer" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$extracurriculars->max('updated_at') ? \Carbon\Carbon::parse($extracurriculars->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        <div class="app-card overflow-hidden">
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari ekskul, pembina…" x-model="q">
                </div>
                <select class="app-select" style="width:auto" x-model="statusFilter">
                    <option value="all">Semua tipe</option>
                    <option value="Wajib">Wajib</option>
                    <option value="Pilihan">Pilihan</option>
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

            <div class="table-wrap" x-show="!loading" x-cloak>
                <table class="table-app">
                    <thead>
                        <tr>
                            <th>Ekstrakurikuler</th>
                            <th>Tipe</th>
                            <th>Pembina</th>
                            <th>Diperbarui</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="e in paged" :key="e.id">
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:260px">
                                        <img class="thumb" :src="e.img || 'https://placehold.co/64x64/eff9e3/63cd00?text=EKS'" :alt="e.name" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate" x-text="e.name"></div>
                                            <div class="cell-sub truncate" style="max-width:320px" x-text="e.sub"></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge" :class="badgeClass(e.type)" x-text="statusLabel(e.type)"></span></td>
                                <td><span style="white-space:nowrap"><i class="fa-solid fa-whistle mr-1" style="color:var(--text-3)"></i><span x-text="e.coach"></span></span></td>
                                <td><span class="cell-sub" style="white-space:nowrap" x-text="e.updated"></span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" :href="'/admin/extracurriculars/' + e.id + '/edit'"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" :href="'/admin/extracurriculars/' + e.id"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <button class="dropdown-item danger" type="button" @click="askDelete(e, '/admin/extracurriculars/' + e.id)"><i class="fa-solid fa-trash"></i><span>Hapus</span></button>
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
                <x-admin-components::empty-state icon="fa-futbol" :title="'Belum ada ekstrakurikuler'" description="Tambahkan ekstrakurikuler pertama untuk ditampilkan di website sekolah.">
                    <a class="app-btn app-btn-primary" href="{{ route('admin.extracurriculars.create') }}"><i class="fa-solid fa-plus"></i>Tambah Ekskul</a>
                </x-admin-components::empty-state>
            </div>

            <x-admin-components::pagination client countLabel="ekskul" />
        </div>
    </div>
@endsection