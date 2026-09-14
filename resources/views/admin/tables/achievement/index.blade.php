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

        <x-admin-components::table
            :head="['Prestasi', 'Kategori', 'Pemenang', 'Tanggal', ['label' => 'Aksi', 'right' => true]]"
            search-hint="Cari prestasi, juara, tingkat…"
            :category-options="['' => 'Semua kategori', 'Individual' => 'Individual', 'Institutional' => 'Institutional']"
            :export-url="route('admin.export', ['resource' => 'achievements'])">
            <x-slot:body>
                @forelse ($achievements as $a)
                    @php $category = $a->category ?? 'Individual'; @endphp
                    <tr data-row data-cat="{{ $category }}">
                        <td data-label="Prestasi">
                            <div class="table-main">
                                <img class="thumb" src="{{ $a->image ? asset('storage/' . $a->image) : 'https://placehold.co/64x64/eff9e3/63cd00?text=PRS' }}" alt="{{ $a->title }}" loading="lazy">
                                <div style="min-width:0">
                                    <div class="cell-main truncate">{{ $a->title }}</div>
                                    <div class="cell-sub truncate">{{ \Illuminate\Support\Str::limit(strip_tags($a->description ?? ''), 140) }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Kategori"><span class="badge {{ $category === 'Institutional' ? 'badge-published' : 'badge-info' }}">{{ $category }}</span></td>
                        <td data-label="Pemenang">
                            <div style="min-width:0">
                                <div class="cell-main truncate">{{ $a->winner ?? '-' }}</div>
                                <div class="cell-sub">{{ $a->level ?? '-' }}</div>
                            </div>
                        </td>
                        <td data-label="Tanggal"><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($a->date)->translatedFormat('d M Y') }}</span></td>
                        <td data-label="Aksi" class="text-right">
                            <div class="table-actions">
                                <a class="act-btn view" href="{{ route('admin.achievements.show', $a) }}" title="Lihat detail"><i class="fa-regular fa-eye"></i></a>
                                <a class="act-btn edit" href="{{ route('admin.achievements.edit', $a) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.achievements.destroy', $a) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus prestasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="act-btn delete" type="submit" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-row data-empty>
                        <td colspan="5">
                            <x-admin-components::empty-state
                                icon="fa-solid fa-trophy"
                                title="Belum ada prestasi"
                                description="Tambahkan prestasi pertama untuk ditampilkan di website sekolah."
                                action-label="Tambah Prestasi"
                                action-url="{{ route('admin.achievements.create') }}" />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection