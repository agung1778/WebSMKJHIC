@extends('layouts.admin-app')

@section('title', 'Menu Navigasi')

@section('content')
    @php
        $items = $navigations->map(function ($n) {
            return [
                'id' => $n->id,
                'name' => $n->title,
                'sub' => $n->url,
                'by' => str_replace('_', ' ', $n->position),
                'type' => ucfirst($n->type),
                'target' => $n->target ?? '_self',
                'order' => $n->order,
                'status' => $n->is_active ? 'active' : 'inactive',
                'ts' => $n->created_at->timestamp,
                'updated' => \Carbon\Carbon::parse($n->updated_at)->locale('id')->diffForHumans(),
            ];
        })->values();
        $total = $navigations->count();
    @endphp

    <div class="fade-up space-y-6" x-data="tableIndex({
        raw: @json($items),
        statuses: {
            active: { label: 'Aktif', class: 'badge-published' },
            inactive: { label: 'Nonaktif', class: 'badge-archived' }
        },
        searchKeys: ['name', 'sub', 'by', 'type'],
        perPage: 10,
        emptyHead: 'Belum ada menu',
        emptyBody: 'Tambahkan menu navigasi pertama untuk website sekolah.'
    })">

        <x-admin-components::page-header
            icon="fa-solid fa-bars"
            kicker="Website"
            title="Menu Navigasi"
            subtitle="Kelola menu top bar, menu utama, dan footer website.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.navigations.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Menu</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Menu" :value="$total" icon="fa-solid fa-bars" tone="brand" />
            <x-admin-components::stat-card label="Aktif" :value="$navigations->where('is_active', 1)->count()" icon="fa-solid fa-circle-check" tone="green" />
            <x-admin-components::stat-card label="Button" :value="$navigations->where('type', 'button')->count()" icon="fa-solid fa-square" tone="blue" />
            <x-admin-components::stat-card label="Posisi" :value="$navigations->pluck('position')->unique()->count()" icon="fa-solid fa-layer-group" tone="amber" />
        </div>

        <div class="app-card overflow-hidden">
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari menu, URL, posisi…" x-model="q">
                </div>
                <span class="toolbar-spacer"></span>
                <button class="icon-btn" @click="refresh" title="Muat ulang" aria-label="Muat ulang"><i class="fa-solid fa-rotate-right" :class="{'fa-spin': loading}"></i></button>
            </div>

            <div class="table-wrap" x-show="!loading" x-cloak>
                <table class="table-app">
                    <thead>
                        <tr>
                            <th style="width:64px">Urutan</th>
                            <th>Label Menu</th>
                            <th>URL / Link</th>
                            <th>Posisi</th>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="n in paged" :key="n.id">
                            <tr>
                                <td><span class="badge badge-info" x-text="'#' + n.order"></span></td>
                                <td><div class="cell-main" x-text="n.name"></div></td>
                                <td><code class="cell-sub" style="font-size:12px" x-text="n.sub"></code></td>
                                <td><span class="badge badge-info" x-text="n.by"></span></td>
                                <td>
                                    <span class="badge" :class="n.type === 'Button' ? 'badge-archived' : 'badge-published'" x-text="n.type"></span>
                                </td>
                                <td><span class="badge" :class="badgeClass(n.status)" x-text="statusLabel(n.status)"></span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" :href="'/admin/navigations/' + n.id + '/edit'"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu">
                                                <button class="dropdown-item danger" type="button" @click="askDelete(n, '/admin/navigations/' + n.id)"><i class="fa-solid fa-trash"></i><span>Hapus</span></button>
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
                <x-admin-components::empty-state icon="fa-bars" :title="'Belum ada menu'" description="Tambahkan menu navigasi pertama untuk website sekolah.">
                    <a class="app-btn app-btn-primary" href="{{ route('admin.navigations.create') }}"><i class="fa-solid fa-plus"></i>Tambah Menu</a>
                </x-admin-components::empty-state>
            </div>

            <x-admin-components::pagination client countLabel="menu" />
        </div>
    </div>
@endsection