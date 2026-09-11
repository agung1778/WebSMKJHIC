@extends('layouts.admin-app')

@section('title', 'Berita')

@section('content')
    @php
        $total = $news->count();
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-newspaper"
            kicker="Kelola konten website"
            title="Berita"
            subtitle="Kelola berita dan informasi terbaru yang tampil di website sekolah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.news.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tulis Berita</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        {{-- Ringkasan --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Berita" :value="$total" icon="fa-solid fa-newspaper" tone="brand" />
            <x-admin-components::stat-card label="Diterbitkan Bulan Ini" :value="$news->where('date_published','>=', now()->startOfMonth())->count()" icon="fa-solid fa-calendar-check" tone="green" />
            <x-admin-components::stat-card label="Kontributor" :value="$news->pluck('publisher')->unique()->count()" icon="fa-solid fa-user-pen" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$news->max('updated_at') ? \Carbon\Carbon::parse($news->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        {{-- Tabel --}}
        <div data-filter-root>
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari berita, penerbit…" data-filter-input>
                </div>
                <span class="toolbar-spacer"></span>
                <a class="app-btn app-btn-md" href="{{ route('admin.export', ['resource' => 'news']) }}" title="Export CSV"><i class="fa-solid fa-file-csv"></i><span class="hide-mob">Export</span></a>
            </div>

            <div class="table-wrap">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th>Berita</th>
                            <th>Tanggal Terbit</th>
                            <th>Penerbit</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($news as $n)
                            <tr data-row>
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:280px">
                                        <img class="thumb" src="{{ $n->image ? asset('storage/' . $n->image) : 'https://placehold.co/64x64/eff9e3/63cd00?text=BRT' }}" alt="{{ $n->title }}" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate">{{ $n->title }}</div>
                                            <div class="cell-sub truncate" style="max-width:420px">{{ \Illuminate\Support\Str::limit(strip_tags($n->description), 150) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($n->date_published)->translatedFormat('d M Y') }}</span></td>
                                <td><span style="white-space:nowrap"><i class="fa-regular fa-user mr-1" style="color:var(--text-3)"></i>{{ $n->publisher }}</span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" href="{{ route('admin.news.edit', $n) }}"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu" style="display:none">
                                                <a class="dropdown-item" href="{{ route('admin.news.show', $n) }}"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <div class="dropdown-sep"></div>
                                                <form action="{{ route('admin.news.destroy', $n) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
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
                                <td colspan="4">
                                    <div class="p-6 text-center">
                                        <div class="empty-state">
                                            <div class="empty-icon"><i class="fa-solid fa-newspaper"></i></div>
                                            <h3>Belum ada berita</h3>
                                            <p>Tulis berita pertama untuk ditampilkan di halaman website sekolah.</p>
                                            <a class="app-btn app-btn-primary" href="{{ route('admin.news.create') }}"><i class="fa-solid fa-plus"></i>Tulis Berita</a>
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