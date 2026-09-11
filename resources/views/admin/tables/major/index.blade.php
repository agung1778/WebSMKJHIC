@extends('layouts.admin-app')

@section('title', 'Jurusan')

@section('content')
    @php
        $total = $majors->count();
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-graduation-cap"
            kicker="Akademik"
            title="Jurusan"
            subtitle="Kelola kompetensi keahlian yang tersedia di SMK Amaliah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.majors.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Jurusan</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Jurusan" :value="$total" icon="fa-solid fa-graduation-cap" tone="brand" />
            <x-admin-components::stat-card label="Dengan Gambar" :value="$majors->whereNotNull('image')->count()" icon="fa-solid fa-image" tone="green" />
            <x-admin-components::stat-card label="Dengan Logo" :value="$majors->whereNotNull('logo')->count()" icon="fa-solid fa-fill-drip" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$majors->max('updated_at') ? \Carbon\Carbon::parse($majors->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        <div data-filter-root>
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari jurusan, kepala kompetensi…" data-filter-input>
                </div>
                <span class="toolbar-spacer"></span>
                <a class="app-btn app-btn-md" href="{{ route('admin.export', ['resource' => 'majors']) }}" title="Export CSV"><i class="fa-solid fa-file-csv"></i><span class="hide-mob">Export</span></a>
            </div>

            <div class="table-wrap">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th>Jurusan</th>
                            <th>Kepala Kompetensi</th>
                            <th>Penerbit</th>
                            <th>Diperbarui</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($majors as $m)
                            @php
                                $mImg = $m->image ? asset('storage/' . $m->image) : ($m->logo ? asset('storage/' . $m->logo) : null);
                                $mSub = $m->abbreviation ? 'Singkatan: ' . $m->abbreviation : \Illuminate\Support\Str::limit(strip_tags($m->description ?? ''), 140);
                            @endphp
                            <tr data-row>
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:260px">
                                        <img class="thumb" src="{{ $mImg ?: 'https://placehold.co/64x64/eff9e3/63cd00?text=JRS' }}" alt="{{ $m->name }}" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate">{{ $m->name }}</div>
                                            <div class="cell-sub truncate" style="max-width:320px">{{ $mSub }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span style="white-space:nowrap">{{ $m->competency_head ?? '-' }}</span></td>
                                <td><span style="white-space:nowrap"><i class="fa-regular fa-user mr-1" style="color:var(--text-3)"></i>{{ $m->publisher ?? '-' }}</span></td>
                                <td><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($m->updated_at)->locale('id')->diffForHumans() }}</span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" href="{{ route('admin.majors.edit', $m) }}"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu" style="display:none">
                                                <a class="dropdown-item" href="{{ route('admin.majors.show', $m) }}"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <form action="{{ route('admin.majors.destroy', $m) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jurusan ini?')">
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
                                            <div class="empty-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                                            <h3>Belum ada jurusan</h3>
                                            <p>Tambahkan jurusan pertama untuk ditampilkan di halaman website sekolah.</p>
                                            <a class="app-btn app-btn-primary" href="{{ route('admin.majors.create') }}"><i class="fa-solid fa-plus"></i>Tambah Jurusan</a>
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