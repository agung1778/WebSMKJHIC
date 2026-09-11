@extends('layouts.admin-app')

@section('title', 'Prestasi')

@section('content')
    @php
        $total = $achievements->count();
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-trophy"
            kicker="Akademik"
            title="Prestasi"
            subtitle="Kelola prestasi siswa dan sekolah yang ditampilkan di website.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.achievements.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Prestasi</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Prestasi" :value="$total" icon="fa-solid fa-trophy" tone="brand" />
            <x-admin-components::stat-card label="Individual" :value="$achievements->where('category','Individual')->count()" icon="fa-solid fa-user" tone="green" />
            <x-admin-components::stat-card label="Institutional" :value="$achievements->where('category','Institutional')->count()" icon="fa-solid fa-building-shield" tone="blue" />
            <x-admin-components::stat-card label="Tingkat Unik" :value="$achievements->pluck('level')->filter()->unique()->count()" icon="fa-solid fa-medal" tone="amber" />
        </div>

        <div data-filter-root>
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari prestasi, juara, tingkat…" data-filter-input>
                </div>
                <select class="app-select" style="width:auto" data-filter-cat>
                    <option value="">Semua kategori</option>
                    <option value="Individual">Individual</option>
                    <option value="Institutional">Institutional</option>
                </select>
                <span class="toolbar-spacer"></span>
                <a class="app-btn app-btn-md" href="{{ route('admin.export', ['resource' => 'achievements']) }}" title="Export CSV"><i class="fa-solid fa-file-csv"></i><span class="hide-mob">Export</span></a>
            </div>

            <div class="table-wrap">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th>Prestasi</th>
                            <th>Kategori</th>
                            <th>Pemenang</th>
                            <th>Tanggal</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($achievements as $a)
                            @php $category = $a->category ?? 'Individual'; @endphp
                            <tr data-row data-cat="{{ $category }}">
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:260px">
                                        <img class="thumb" src="{{ $a->image ? asset('storage/' . $a->image) : 'https://placehold.co/64x64/eff9e3/63cd00?text=PRS' }}" alt="{{ $a->title }}" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate">{{ $a->title }}</div>
                                            <div class="cell-sub truncate" style="max-width:320px">{{ \Illuminate\Support\Str::limit(strip_tags($a->description ?? ''), 140) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge {{ $category === 'Institutional' ? 'badge-published' : 'badge-info' }}">{{ $category }}</span></td>
                                <td>
                                    <div style="min-width:0">
                                        <div class="cell-main truncate" style="max-width:180px">{{ $a->winner ?? '-' }}</div>
                                        <div class="cell-sub">{{ $a->level ?? '-' }}</div>
                                    </div>
                                </td>
                                <td><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($a->date)->translatedFormat('d M Y') }}</span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" href="{{ route('admin.achievements.edit', $a) }}"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu" style="display:none">
                                                <a class="dropdown-item" href="{{ route('admin.achievements.show', $a) }}"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <form action="{{ route('admin.achievements.destroy', $a) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus prestasi ini?')">
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
                                            <div class="empty-icon"><i class="fa-solid fa-trophy"></i></div>
                                            <h3>Belum ada prestasi</h3>
                                            <p>Tambahkan prestasi pertama untuk ditampilkan di website sekolah.</p>
                                            <a class="app-btn app-btn-primary" href="{{ route('admin.achievements.create') }}"><i class="fa-solid fa-plus"></i>Tambah Prestasi</a>
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