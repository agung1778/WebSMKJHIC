@extends('layouts.admin-app')

@section('title', 'Mitra Industri')

@section('content')
    @php
        $total = $partners->count();
    @endphp

    <div class="fade-up space-y-6">

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

        <div data-filter-root>
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari mitra, sektor, kota…" data-filter-input>
                </div>
                <span class="toolbar-spacer"></span>
                <a class="app-btn app-btn-md" href="{{ route('admin.export', ['resource' => 'partners']) }}" title="Export CSV"><i class="fa-solid fa-file-csv"></i><span class="hide-mob">Export</span></a>
            </div>

            <div class="table-wrap">
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
                        @forelse ($partners as $p)
                            <tr data-row>
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:240px">
                                        <img class="thumb" src="{{ $p->logo ? asset('storage/' . $p->logo) : 'https://placehold.co/64x64/eff9e3/63cd00?text=MTR' }}" alt="{{ $p->name }}" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate">{{ $p->name }}</div>
                                            <div class="cell-sub truncate" style="max-width:300px">{{ \Illuminate\Support\Str::limit(strip_tags($p->description ?? ''), 140) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-info">{{ $p->sector ?? '-' }}</span></td>
                                <td><span style="white-space:nowrap"><i class="fa-solid fa-location-dot mr-1" style="color:var(--text-3)"></i>{{ $p->city ?? '-' }}</span></td>
                                <td><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($p->updated_at)->locale('id')->diffForHumans() }}</span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" href="{{ route('admin.partners.edit', $p) }}"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu" style="display:none">
                                                <a class="dropdown-item" href="{{ route('admin.partners.show', $p) }}"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <form action="{{ route('admin.partners.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mitra ini?')">
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
                                <td colspan="5">
                                    <div class="p-6 text-center">
                                        <div class="empty-state">
                                            <div class="empty-icon"><i class="fa-solid fa-handshake"></i></div>
                                            <h3>Belum ada mitra industri</h3>
                                            <p>Tambahkan mitra industri pertama untuk ditampilkan di halaman website sekolah.</p>
                                            <a class="app-btn app-btn-primary" href="{{ route('admin.partners.create') }}"><i class="fa-solid fa-plus"></i>Tambah Mitra</a>
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