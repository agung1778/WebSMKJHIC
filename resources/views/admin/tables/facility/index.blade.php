@extends('layouts.admin-app')

@section('title', 'Fasilitas')

@section('content')
    @php
        $total = $facilities->count();
        $types = $facilities->pluck('type')->filter()->unique()->values();
    @endphp

    <div class="fade-up space-y-6">

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

        <div data-filter-root>
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari fasilitas…" data-filter-input>
                </div>
                <span class="toolbar-spacer"></span>
                <a class="app-btn app-btn-md" href="{{ route('admin.export', ['resource' => 'facilities']) }}" title="Export CSV"><i class="fa-solid fa-file-csv"></i><span class="hide-mob">Export</span></a>
            </div>

            <div class="table-wrap">
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
                        @forelse ($facilities as $f)
                            <tr data-row>
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:260px">
                                        <img class="thumb" src="{{ $f->image ? asset('storage/' . $f->image) : 'https://placehold.co/64x64/eff9e3/63cd00?text=FSL' }}" alt="{{ $f->name }}" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate">{{ $f->name }}</div>
                                            <div class="cell-sub truncate" style="max-width:340px">{{ \Illuminate\Support\Str::limit(strip_tags($f->description ?? ''), 140) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-info">{{ $f->type ?? '-' }}</span></td>
                                <td><span style="white-space:nowrap"><i class="fa-regular fa-user mr-1" style="color:var(--text-3)"></i>{{ $f->publisher ?? '-' }}</span></td>
                                <td><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($f->updated_at)->locale('id')->diffForHumans() }}</span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" href="{{ route('admin.facilities.edit', $f) }}"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu" style="display:none">
                                                <a class="dropdown-item" href="{{ route('admin.facilities.show', $f) }}"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <form action="{{ route('admin.facilities.destroy', $f) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus fasilitas ini?')">
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
                                            <div class="empty-icon"><i class="fa-solid fa-building-columns"></i></div>
                                            <h3>Belum ada fasilitas</h3>
                                            <p>Tambahkan fasilitas pertama untuk ditampilkan di halaman website sekolah.</p>
                                            <a class="app-btn app-btn-primary" href="{{ route('admin.facilities.create') }}"><i class="fa-solid fa-plus"></i>Tambah Fasilitas</a>
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