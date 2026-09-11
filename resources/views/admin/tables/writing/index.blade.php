@extends('layouts.admin-app')

@section('title', 'Home & Tulisan')

@section('content')
    @php
        $items = $writings->map(function ($w) {
            return [
                'id' => $w->id,
                'name' => $w->title,
                'sub' => \Illuminate\Support\Str::limit(strip_tags($w->content ?? ''), 160),
                'img' => null,
                'by' => $w->publisher ?? '-',
                'date' => $w->release_date ? \Carbon\Carbon::parse($w->release_date)->translatedFormat('d M Y') : '-',
                'ts' => $w->created_at->timestamp,
                'updated' => \Carbon\Carbon::parse($w->updated_at)->locale('id')->diffForHumans(),
            ];
        })->values();
        $total = $writings->count();
        $wordCount = $writings->sum(fn ($w) => str_word_count(strip_tags($w->content ?? '')));
    @endphp

    <div class="fade-up space-y-6" x-data="tableIndex({
        raw: @json($items),
        searchKeys: ['name', 'sub', 'by'],
        perPage: 8,
        exportUrl: @json(route('admin.export', ['resource' => 'writings'])),
        emptyHead: 'Belum ada tulisan',
        emptyBody: 'Mulai tulis konten pertama untuk halaman home & tulisan website.'
    })">

        <x-admin-components::page-header
            icon="fa-solid fa-pen-nib"
            kicker="Website"
            title="Home & Tulisan"
            subtitle="Kelola tulisan dan konten halaman beranda website sekolah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.writings.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tulis Baru</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Tulisan" :value="$total" icon="fa-solid fa-pen-nib" tone="brand" />
            <x-admin-components::stat-card label="Total Kata" :value="number_format($wordCount)" icon="fa-solid fa-font" tone="green" />
            <x-admin-components::stat-card label="Kontributor" :value="$writings->pluck('publisher')->unique()->count()" icon="fa-solid fa-user-pen" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$writings->max('updated_at') ? \Carbon\Carbon::parse($writings->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        <div>
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari judul, penerbit…" x-model="q">
                </div>
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
                            <th>Judul</th>
                            <th>Terbit</th>
                            <th>Penerbit</th>
                            <th>Diperbarui</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="w in paged" :key="w.id">
                            <tr>
                                <td style="min-width:320px">
                                    <div class="cell-main truncate" x-text="w.name"></div>
                                    <div class="cell-sub truncate" style="max-width:440px" x-text="w.sub"></div>
                                </td>
                                <td><span class="badge badge-published" style="white-space:nowrap" x-text="w.date"></span></td>
                                <td><span style="white-space:nowrap"><i class="fa-regular fa-user mr-1" style="color:var(--text-3)"></i><span x-text="w.by"></span></span></td>
                                <td><span class="cell-sub" style="white-space:nowrap" x-text="w.updated"></span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" :href="'/admin/writings/' + w.id + '/edit'"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" :href="'/admin/writings/' + w.id"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <button class="dropdown-item danger" type="button" @click="askDelete(w, '/admin/writings/' + w.id)"><i class="fa-solid fa-trash"></i><span>Hapus</span></button>
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
                <x-admin-components::empty-state icon="fa-pen-nib" :title="'Belum ada tulisan'" description="Mulai tulis konten pertama untuk halaman home & tulisan website.">
                    <a class="app-btn app-btn-primary" href="{{ route('admin.writings.create') }}"><i class="fa-solid fa-plus"></i>Tulis Baru</a>
                </x-admin-components::empty-state>
            </div>

            <x-admin-components::pagination client countLabel="tulisan" />
        </div>
    </div>
@endsection