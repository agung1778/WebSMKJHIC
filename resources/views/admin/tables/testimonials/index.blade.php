@extends('layouts.admin-app')

@section('title', 'Testimoni')

@section('content')
    @php
        $items = $testimonials->map(function ($t) {
            return [
                'id' => $t->id,
                'name' => $t->name,
                'sub' => \Illuminate\Support\Str::limit(strip_tags($t->description ?? ''), 140),
                'img' => $t->photo ? asset('storage/' . $t->photo) : null,
                'by' => $t->publisher ?? '-',
                'major' => $t->major ? $t->major->name : '-',
                'year' => $t->alumni_year ?? '-',
                'ts' => $t->created_at->timestamp,
                'updated' => \Carbon\Carbon::parse($t->updated_at)->locale('id')->diffForHumans(),
            ];
        })->values();
        $total = $testimonials->count();
    @endphp

    <div class="fade-up space-y-6" x-data="tableIndex({
        raw: @json($items),
        searchKeys: ['name', 'sub', 'by', 'major', 'year'],
        perPage: 8,
        exportUrl: @json(route('admin.export', ['resource' => 'testimonials'])),
        emptyHead: 'Belum ada testimoni',
        emptyBody: 'Tambahkan testimoni alumni pertama untuk ditampilkan di website sekolah.'
    })">

        <x-admin-components::page-header
            icon="fa-solid fa-comment-dots"
            kicker="Konten"
            title="Testimoni"
            subtitle="Kelola cerita dan kesan alumni yang menjadi testimoni sekolah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.testimonials.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Testimoni</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Testimoni" :value="$total" icon="fa-solid fa-comment-dots" tone="brand" />
            <x-admin-components::stat-card label="Jurusan Terwakili" :value="$testimonials->pluck('major.name')->filter()->unique()->count()" icon="fa-solid fa-graduation-cap" tone="green" />
            <x-admin-components::stat-card label="Alumni Tersubur" :value="$testimonials->pluck('alumni_year')->filter()->unique()->count() . ' angkatan'" icon="fa-solid fa-calendar" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$testimonials->max('updated_at') ? \Carbon\Carbon::parse($testimonials->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        <div>
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari alumni, jurusan, angkatan…" x-model="q">
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
                            <th>Alumni</th>
                            <th>Jurusan</th>
                            <th>Angkatan</th>
                            <th>Diperbarui</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="t in paged" :key="t.id">
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:240px">
                                        <img class="thumb thumb-round" :src="t.img || 'https://placehold.co/64x64/eff9e3/63cd00?text=ALM'" :alt="t.name" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate" x-text="t.name"></div>
                                            <div class="cell-sub truncate" style="max-width:300px" x-text="t.sub"></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-info" x-text="t.major"></span></td>
                                <td><span style="white-space:nowrap" x-text="t.year"></span></td>
                                <td><span class="cell-sub" style="white-space:nowrap" x-text="t.updated"></span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" :href="'/admin/testimonials/' + t.id + '/edit'"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" :href="'/admin/testimonials/' + t.id"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <button class="dropdown-item danger" type="button" @click="askDelete(t, '/admin/testimonials/' + t.id)"><i class="fa-solid fa-trash"></i><span>Hapus</span></button>
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
                <x-admin-components::empty-state icon="fa-comment-dots" :title="'Belum ada testimoni'" description="Tambahkan testimoni alumni pertama untuk ditampilkan di website sekolah.">
                    <a class="app-btn app-btn-primary" href="{{ route('admin.testimonials.create') }}"><i class="fa-solid fa-plus"></i>Tambah Testimoni</a>
                </x-admin-components::empty-state>
            </div>

            <x-admin-components::pagination client countLabel="testimoni" />
        </div>
    </div>
@endsection