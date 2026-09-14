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

        <x-admin-components::table
            :head="[['label' => 'Urutan', 'class' => 'w-16'], 'Label Menu', 'URL / Link', 'Posisi', 'Tipe', 'Status', ['label' => 'Aksi', 'right' => true]]"
            search-hint="Cari menu, URL, posisi…">
            <x-slot:body>
                @forelse ($navigations as $n)
                    @php
                        $navType = ucfirst($n->type);
                        $navActive = $n->is_active ? 'active' : 'inactive';
                        $navPosition = str_replace('_', ' ', $n->position);
                    @endphp
                    <tr data-row>
                        <td data-label="Urutan"><span class="badge badge-info">#{{ $n->order }}</span></td>
                        <td data-label="Label Menu"><div class="cell-main">{{ $n->title }}</div></td>
                        <td data-label="URL / Link"><code class="cell-sub" style="font-size:12px">{{ $n->url }}</code></td>
                        <td data-label="Posisi"><span class="badge badge-info">{{ $navPosition }}</span></td>
                        <td data-label="Tipe">
                            <span class="badge {{ $navType === 'Button' ? 'badge-archived' : 'badge-published' }}">{{ $navType }}</span>
                        </td>
                        <td data-label="Status"><span class="badge {{ $navActive === 'active' ? 'badge-published' : 'badge-archived' }}">{{ $navActive === 'active' ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td data-label="Aksi" class="text-right">
                            <div class="table-actions">
                                <a class="act-btn edit" href="{{ route('admin.navigations.edit', $n) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.navigations.destroy', $n) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="act-btn delete" type="submit" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-row data-empty>
                        <td colspan="7">
                            <x-admin-components::empty-state
                                icon="fa-solid fa-bars"
                                title="Belum ada menu"
                                description="Tambahkan menu navigasi pertama untuk website sekolah."
                                action-label="Tambah Menu"
                                action-url="{{ route('admin.navigations.create') }}" />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection