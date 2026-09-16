@extends('layouts.admin-app')

@section('title', 'Pimpinan Sekolah')

@section('content')
    @php
        $activeCount = $leaders->where('is_active', true)->count();
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-user-tie"
            kicker="Akademik"
            title="Pimpinan Sekolah"
            subtitle="Kelola profil pemimpin untuk bagian Get To Know Our School Leaders di halaman depan."
            >
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.leaders.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Pemimpin</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Pemimpin" :value="$leaders->count()" icon="fa-solid fa-user-tie" tone="brand" />
            <x-admin-components::stat-card label="Aktif di Website" :value="$activeCount" icon="fa-solid fa-circle-check" tone="green" />
            <x-admin-components::stat-card label="Amaliah 1" :value="$leaders->where('school','Amaliah 1')->count()" icon="fa-solid fa-school" tone="blue" />
            <x-admin-components::stat-card label="Amaliah 2" :value="$leaders->where('school','Amaliah 2')->count()" icon="fa-solid fa-school" tone="amber" />
        </div>

        <x-admin-components::table
            :head="['Pimpinan', 'Sekolah', 'Urutan', 'Status', ['label' => 'Aksi', 'right' => true]]"
            search-hint="Cari nama, jabatan, sekolah…">
            <x-slot:body>
                @forelse ($leaders as $l)
                    @php
                        $imgUrl = $l->image
                            ? (str_starts_with($l->image, 'uploads/') ? asset('storage/' . $l->image) : asset($l->image))
                            : null;
                    @endphp
                    <tr data-row>
                        <td data-label="Pimpinan">
                            <div class="table-main">
                                @if ($imgUrl)
                                    <img class="thumb thumb-round" src="{{ $imgUrl }}" alt="{{ $l->name }}" loading="lazy">
                                @else
                                    <div class="thumb thumb-round" style="background:var(--soft);display:flex;align-items:center;justify-content:center;color:var(--brand)"><i class="fa-solid fa-user-tie"></i></div>
                                @endif
                                <div style="min-width:0">
                                    <div class="cell-main truncate">{{ $l->name }}</div>
                                    <div class="cell-sub truncate">{{ $l->position }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Sekolah"><span class="badge badge-info">{{ $l->school }}</span></td>
                        <td data-label="Urutan"><span class="cell-sub" style="white-space:nowrap">#{{ $l->order_column ?? '-' }}</span></td>
                        <td data-label="Status">
                            <span class="badge {{ $l->is_active ? 'badge-published' : 'badge-archived' }}">{{ $l->is_active ? 'Aktif' : 'Tersembunyi' }}</span>
                        </td>
                        <td data-label="Aksi" class="text-right">
                            <div class="table-actions">
                                <a class="act-btn view" href="{{ route('admin.leaders.show', $l) }}" title="Lihat detail"><i class="fa-regular fa-eye"></i></a>
                                <a class="act-btn edit" href="{{ route('admin.leaders.edit', $l) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.leaders.destroy', $l) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus {{ addslashes($l->name) }}?')">
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
                                icon="fa-solid fa-user-tie"
                                title="Belum ada pemimpin"
                                description="Tambahkan pimpinan sekolah pertama untuk ditampilkan di bagian Get To Know Our School Leaders."
                                action-label="Tambah Pemimpin"
                                action-url="{{ route('admin.leaders.create') }}" />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection