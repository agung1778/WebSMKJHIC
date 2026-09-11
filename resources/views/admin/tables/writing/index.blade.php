@extends('layouts.admin-app')

@section('title', 'Home & Tulisan')

@section('content')
    @php
        $total = $writings->count();
        $wordCount = $writings->sum(fn ($w) => str_word_count(strip_tags($w->content ?? '')));
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-pen-nib"
            kicker="Website"
            title="Home & Tulisan"
            subtitle="Kelola tulisan dan konten halaman beranda website sekolah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.writings.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tulis Baru</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Tulisan" :value="$total" icon="fa-solid fa-pen-nib" tone="brand" />
            <x-admin-components::stat-card label="Total Kata" :value="number_format($wordCount)" icon="fa-solid fa-font" tone="green" />
            <x-admin-components::stat-card label="Kontributor" :value="$writings->pluck('publisher')->unique()->count()" icon="fa-solid fa-user-pen" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$writings->max('updated_at') ? \Carbon\Carbon::parse($writings->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        <div data-filter-root>
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari judul, penerbit…" data-filter-input>
                </div>
                <span class="toolbar-spacer"></span>
                <a class="app-btn app-btn-md" href="{{ route('admin.export', ['resource' => 'writings']) }}" title="Export CSV"><i class="fa-solid fa-file-csv"></i><span class="hide-mob">Export</span></a>
            </div>

            <div class="table-wrap">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Terbit</th>
                            <th>Penerbit</th>
                            <th>Diperbarui</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($writings as $w)
                            <tr data-row>
                                <td style="min-width:320px">
                                    <div class="cell-main truncate">{{ $w->title }}</div>
                                    <div class="cell-sub truncate" style="max-width:440px">{{ \Illuminate\Support\Str::limit(strip_tags($w->content ?? ''), 160) }}</div>
                                </td>
                                <td><span class="badge badge-published" style="white-space:nowrap">{{ $w->release_date ? \Carbon\Carbon::parse($w->release_date)->translatedFormat('d M Y') : '-' }}</span></td>
                                <td><span style="white-space:nowrap"><i class="fa-regular fa-user mr-1" style="color:var(--text-3)"></i>{{ $w->publisher ?? '-' }}</span></td>
                                <td><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($w->updated_at)->locale('id')->diffForHumans() }}</span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" href="{{ route('admin.writings.edit', $w) }}"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu" style="display:none">
                                                <a class="dropdown-item" href="{{ route('admin.writings.show', $w) }}"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <form action="{{ route('admin.writings.destroy', $w) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tulisan ini?')">
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
                                            <div class="empty-icon"><i class="fa-solid fa-pen-nib"></i></div>
                                            <h3>Belum ada tulisan</h3>
                                            <p>Mulai tulis konten pertama untuk halaman home & tulisan website.</p>
                                            <a class="app-btn app-btn-primary" href="{{ route('admin.writings.create') }}"><i class="fa-solid fa-plus"></i>Tulis Baru</a>
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