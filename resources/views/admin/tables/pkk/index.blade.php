@extends('layouts.admin-app')

@section('title', 'Galeri P5/PKK')

@section('content')
    @php
        $total = $projects->count();
        $categories = [
            'Makanan' => 'badge-warning',
            'Kerajinan' => 'badge-info',
            'Jasa' => 'badge-published',
            'Teknologi' => 'badge-draft',
        ];
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-lightbulb"
            kicker="Konten"
            title="Galeri P5/PKK"
            subtitle="Kelola hasil proyek P5 dan praktik kewirausahaan siswa.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.pkk.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Proyek</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Proyek" :value="$total" icon="fa-solid fa-lightbulb" tone="brand" />
            <x-admin-components::stat-card label="Kategori" :value="$projects->pluck('category')->unique()->count()" icon="fa-solid fa-shapes" tone="green" />
            <x-admin-components::stat-card label="Kelas Terlibat" :value="$projects->pluck('student_class')->unique()->count()" icon="fa-solid fa-user-graduate" tone="blue" />
            <x-admin-components::stat-card label="Berbayar" :value="$projects->whereNotNull('price')->count()" icon="fa-solid fa-tags" tone="amber" />
        </div>

        <div data-filter-root>
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari proyek, jurusan, kategori…" data-filter-input>
                </div>
                <select class="app-select" style="width:auto" data-filter-cat>
                    <option value="">Semua kategori</option>
                    <option value="Makanan">Makanan</option>
                    <option value="Kerajinan">Kerajinan</option>
                    <option value="Jasa">Jasa</option>
                    <option value="Teknologi">Teknologi</option>
                </select>
                <span class="toolbar-spacer"></span>
                <a class="app-btn app-btn-md" href="{{ route('admin.export', ['resource' => 'pkk']) }}" title="Export CSV"><i class="fa-solid fa-file-csv"></i><span class="hide-mob">Export</span></a>
            </div>

            <div class="table-wrap">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th>Proyek</th>
                            <th>Kategori</th>
                            <th>Kelas</th>
                            <th>Harga</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $p)
                            @php
                                $category = $p->category ?? 'Produk';
                                $priceText = $p->price !== null ? number_format($p->price, 0, ',', '.') : 'Gratis';
                            @endphp
                            <tr data-row data-cat="{{ $category }}">
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:280px">
                                        <img class="thumb" src="{{ $p->photo ? asset('storage/' . $p->photo) : 'https://placehold.co/64x64/eff9e3/63cd00?text=PKK' }}" alt="{{ $p->title }}" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate">{{ $p->title }}</div>
                                            <div class="cell-sub truncate" style="max-width:320px">{{ \Illuminate\Support\Str::limit(strip_tags($p->description ?? ''), 140) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge {{ $categories[$category] ?? 'badge-info' }}">{{ $category }}</span></td>
                                <td><span style="white-space:nowrap">{{ $p->student_class ?? '-' }}</span></td>
                                <td><span style="white-space:nowrap;{{ $priceText === 'Gratis' ? 'color:var(--green);font-weight:700' : '' }}">{{ $priceText }}</span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" href="{{ route('admin.pkk.edit', $p) }}"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu" style="display:none">
                                                <a class="dropdown-item" href="{{ route('admin.pkk.show', $p) }}"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <form action="{{ route('admin.pkk.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus proyek ini?')">
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
                                            <div class="empty-icon"><i class="fa-solid fa-lightbulb"></i></div>
                                            <h3>Belum ada proyek P5/PKK</h3>
                                            <p>Tambahkan proyek P5/PKK pertama untuk ditampilkan di galeri.</p>
                                            <a class="app-btn app-btn-primary" href="{{ route('admin.pkk.create') }}"><i class="fa-solid fa-plus"></i>Tambah Proyek</a>
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