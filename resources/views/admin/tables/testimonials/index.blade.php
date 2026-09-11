@extends('layouts.admin-app')

@section('title', 'Testimoni')

@section('content')
    @php
        $total = $testimonials->count();
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-comment-dots"
            kicker="Konten"
            title="Testimoni"
            subtitle="Kelola cerita dan kesan alumni yang menjadi testimoni sekolah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.testimonials.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Testimoni</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Testimoni" :value="$total" icon="fa-solid fa-comment-dots" tone="brand" />
            <x-admin-components::stat-card label="Jurusan Terwakili" :value="$testimonials->pluck('major.name')->filter()->unique()->count()" icon="fa-solid fa-graduation-cap" tone="green" />
            <x-admin-components::stat-card label="Alumni Tersubur" :value="$testimonials->pluck('alumni_year')->filter()->unique()->count() . ' angkatan'" icon="fa-solid fa-calendar" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$testimonials->max('updated_at') ? \Carbon\Carbon::parse($testimonials->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        <div data-filter-root>
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari alumni, jurusan, angkatan…" data-filter-input>
                </div>
                <span class="toolbar-spacer"></span>
                <a class="app-btn app-btn-md" href="{{ route('admin.export', ['resource' => 'testimonials']) }}" title="Export CSV"><i class="fa-solid fa-file-csv"></i><span class="hide-mob">Export</span></a>
            </div>

            <div class="table-wrap">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th>Alumni</th>
                            <th>Jurusan</th>
                            <th>Angkatan</th>
                            <th>Diperbarui</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($testimonials as $t)
                            <tr data-row>
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:240px">
                                        <img class="thumb thumb-round" src="{{ $t->photo ? asset('storage/' . $t->photo) : 'https://placehold.co/64x64/eff9e3/63cd00?text=ALM' }}" alt="{{ $t->name }}" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate">{{ $t->name }}</div>
                                            <div class="cell-sub truncate" style="max-width:300px">{{ \Illuminate\Support\Str::limit(strip_tags($t->description ?? ''), 140) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-info">{{ $t->major ? $t->major->name : '-' }}</span></td>
                                <td><span style="white-space:nowrap">{{ $t->alumni_year ?? '-' }}</span></td>
                                <td><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($t->updated_at)->locale('id')->diffForHumans() }}</span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" href="{{ route('admin.testimonials.edit', $t) }}"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu" style="display:none">
                                                <a class="dropdown-item" href="{{ route('admin.testimonials.show', $t) }}"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <form action="{{ route('admin.testimonials.destroy', $t) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus testimoni ini?')">
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
                                            <div class="empty-icon"><i class="fa-solid fa-comment-dots"></i></div>
                                            <h3>Belum ada testimoni</h3>
                                            <p>Tambahkan testimoni alumni pertama untuk ditampilkan di website sekolah.</p>
                                            <a class="app-btn app-btn-primary" href="{{ route('admin.testimonials.create') }}"><i class="fa-solid fa-plus"></i>Tambah Testimoni</a>
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