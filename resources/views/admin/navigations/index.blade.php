@extends('layouts.admin-app')

@section('title', 'Menu Navigasi')

@section('content')
    @php
        $total = $navigations->count();
    @endphp

    <div class="fade-up space-y-6">

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

        <div data-filter-root>
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari menu, URL, posisi…" data-filter-input>
                </div>
                <span class="toolbar-spacer"></span>
            </div>

            <div class="table-wrap">
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
                        @forelse ($navigations as $n)
                            @php
                                $navType = ucfirst($n->type);
                                $navActive = $n->is_active ? 'active' : 'inactive';
                                $navPosition = str_replace('_', ' ', $n->position);
                            @endphp
                            <tr data-row>
                                <td><span class="badge badge-info">#{{ $n->order }}</span></td>
                                <td><div class="cell-main">{{ $n->title }}</div></td>
                                <td><code class="cell-sub" style="font-size:12px">{{ $n->url }}</code></td>
                                <td><span class="badge badge-info">{{ $navPosition }}</span></td>
                                <td>
                                    <span class="badge {{ $navType === 'Button' ? 'badge-archived' : 'badge-published' }}">{{ $navType }}</span>
                                </td>
                                <td><span class="badge {{ $navActive === 'active' ? 'badge-published' : 'badge-archived' }}">{{ $navActive === 'active' ? 'Aktif' : 'Nonaktif' }}</span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" href="{{ route('admin.navigations.edit', $n) }}"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu" style="display:none">
                                                <form action="{{ route('admin.navigations.destroy', $n) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item danger" type="submit"><i class="fa-solid fa-trash"></i><span>Hapus</span></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr data-row data-empty>
                                <td colspan="7">
                                    <div class="p-6 text-center">
                                        <div class="empty-state">
                                            <div class="empty-icon"><i class="fa-solid fa-bars"></i></div>
                                            <h3>Belum ada menu</h3>
                                            <p>Tambahkan menu navigasi pertama untuk website sekolah.</p>
                                            <a class="app-btn app-btn-primary" href="{{ route('admin.navigations.create') }}"><i class="fa-solid fa-plus"></i>Tambah Menu</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('admin.tables._filter')
        </div>
    </div>
@endsection