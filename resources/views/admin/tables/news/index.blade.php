@extends('layouts.admin-app')

@section('title', 'Berita')

@section('content')
    @php
        $items = $news->map(function ($n) {
            return [
                'id' => $n->id,
                'name' => $n->title,
                'img' => $n->image ? asset('storage/' . $n->image) : null,
                'sub' => \Illuminate\Support\Str::limit(strip_tags($n->description), 150),
                'by' => $n->publisher,
                'date' => \Carbon\Carbon::parse($n->date_published)->translatedFormat('d M Y'),
                'ts' => $n->created_at->timestamp,
                'updated' => \Carbon\Carbon::parse($n->updated_at)->locale('id')->diffForHumans(),
            ];
        })->values();
        $total = $news->count();
    @endphp

    <div class="fade-up space-y-6" x-data="tableIndex({
        raw: @json($items),
        searchKeys: ['name', 'sub', 'by'],
        perPage: 8,
        exportUrl: @json(route('admin.export', ['resource' => 'news'])),
        emptyHead: 'Belum ada berita',
        emptyBody: 'Mulai tulis berita pertama untuk ditampilkan di halaman website sekolah.'
    })">

        <x-admin-components::page-header
            icon="fa-solid fa-newspaper"
            kicker="Kelola konten website"
            title="Berita"
            subtitle="Kelola berita dan informasi terbaru yang tampil di website sekolah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.news.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tulis Berita</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        {{-- Ringkasan --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Berita" :value="$total" icon="fa-solid fa-newspaper" tone="brand" />
            <x-admin-components::stat-card label="Diterbitkan Bulan Ini" :value="$news->where('date_published','>=', now()->startOfMonth())->count()" icon="fa-solid fa-calendar-check" tone="green" />
            <x-admin-components::stat-card label="Kontributor" :value="$news->pluck('publisher')->unique()->count()" icon="fa-solid fa-user-pen" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$news->max('updated_at') ? \Carbon\Carbon::parse($news->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        {{-- Tabel --}}
        <div class="app-card overflow-hidden">
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari berita, penerbit…" x-model="q">
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

            <div class="table-wrap" x-show="!loading" x-cloak>
                <table class="table-app">
                    <thead>
                        <tr>
                            <th>Berita</th>
                            <th>Tanggal Terbit</th>
                            <th>Penerbit</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="n in paged" :key="n.id">
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:280px">
                                        <img class="thumb" :src="n.img || 'https://placehold.co/64x64/eff9e3/63cd00?text=BRT'" :alt="n.name" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate" x-text="n.name"></div>
                                            <div class="cell-sub truncate" style="max-width:420px" x-text="n.sub"></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="cell-sub" style="white-space:nowrap" x-text="n.date"></span></td>
                                <td><span style="white-space:nowrap"><i class="fa-regular fa-user mr-1" style="color:var(--text-3)"></i><span x-text="n.by"></span></span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" :href="'/admin/news/' + n.id + '/edit'"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" :href="'/admin/news/' + n.id"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <button class="dropdown-item danger" type="button" @click="askDelete(n, '/admin/news/' + n.id)"><i class="fa-solid fa-trash"></i><span>Hapus</span></button>
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

            <div x-show="!loading && empty" x-cloak>
                <x-admin-components::empty-state icon="fa-newspaper" :title="'Belum ada berita'" description="Tulis berita pertama untuk ditampilkan di halaman website sekolah.">
                    <a class="app-btn app-btn-primary" href="{{ route('admin.news.create') }}"><i class="fa-solid fa-plus"></i>Tulis Berita</a>
                </x-admin-components::empty-state>
            </div>

            <x-admin-components::pagination client countLabel="berita" />
        </div>
    </div>
@endsection