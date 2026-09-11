@extends('layouts.admin-app')

@section('title', 'Ekstrakurikuler')

@section('content')
    @php
        $total = $extracurriculars->count();
    @endphp

    <div class="fade-up space-y-6">

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

        <div data-filter-root>
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari ekskul, pembina…" data-filter-input>
                </div>
                <select class="app-select" style="width:auto" data-filter-cat>
                    <option value="">Semua tipe</option>
                    <option value="Wajib">Wajib</option>
                    <option value="Pilihan">Pilihan</option>
                </select>
                <span class="toolbar-spacer"></span>
                <a class="app-btn app-btn-md" href="{{ route('admin.export', ['resource' => 'extracurriculars']) }}" title="Export CSV"><i class="fa-solid fa-file-csv"></i><span class="hide-mob">Export</span></a>
            </div>

            <div class="table-wrap">
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
                        @forelse ($extracurriculars as $e)
                            @php $type = $e->type ?? 'Pilihan'; @endphp
                            <tr data-row data-cat="{{ $type }}">
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:260px">
                                        <img class="thumb" src="{{ $e->image ? asset('storage/' . $e->image) : 'https://placehold.co/64x64/eff9e3/63cd00?text=EKS' }}" alt="{{ $e->name }}" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate">{{ $e->name }}</div>
                                            <div class="cell-sub truncate" style="max-width:320px">{{ \Illuminate\Support\Str::limit(strip_tags($e->description ?? ''), 150) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge {{ $type === 'Wajib' ? 'badge-info' : 'badge-warning' }}">{{ $type }}</span></td>
                                <td><span style="white-space:nowrap"><i class="fa-solid fa-whistle mr-1" style="color:var(--text-3)"></i>{{ $e->coach ?? '-' }}</span></td>
                                <td><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($e->updated_at)->locale('id')->diffForHumans() }}</span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" href="{{ route('admin.extracurriculars.edit', $e) }}"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu" style="display:none">
                                                <a class="dropdown-item" href="{{ route('admin.extracurriculars.show', $e) }}"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <form action="{{ route('admin.extracurriculars.destroy', $e) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ekstrakurikuler ini?')">
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
                                            <div class="empty-icon"><i class="fa-solid fa-futbol"></i></div>
                                            <h3>Belum ada ekstrakurikuler</h3>
                                            <p>Tambahkan ekstrakurikuler pertama untuk ditampilkan di website sekolah.</p>
                                            <a class="app-btn app-btn-primary" href="{{ route('admin.extracurriculars.create') }}"><i class="fa-solid fa-plus"></i>Tambah Ekskul</a>
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