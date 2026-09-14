@extends('layouts.admin-app')

@section('title', 'Guru & Staf')

@section('content')
    @php
        $total = $teachers->count();
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-chalkboard-user"
            kicker="Akademik"
            title="Guru & Staf"
            subtitle="Kelola tenaga pendidik dan kependidikan SMK Amaliah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.teachers.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Guru</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Guru & Staf" :value="$total" icon="fa-solid fa-chalkboard-user" tone="brand" />
            <x-admin-components::stat-card label="Amaliah 1" :value="$teachers->where('school','Amaliah 1')->count()" icon="fa-solid fa-school" tone="green" />
            <x-admin-components::stat-card label="Amaliah 2" :value="$teachers->where('school','Amaliah 2')->count()" icon="fa-solid fa-school" tone="blue" />
            <x-admin-components::stat-card label="Gabungan" :value="$teachers->where('school','Amaliah 1 & 2')->count()" icon="fa-solid fa-school-circle-check" tone="amber" />
        </div>

        <x-admin-components::table
            :head="['Nama', 'Sekolah', 'Kategori', 'Diperbarui', ['label' => 'Aksi', 'right' => true]]"
            search-hint="Cari nama, jabatan, mapel…"
            :export-url="route('admin.export', ['resource' => 'teachers'])">
            <x-slot:body>
                @forelse ($teachers as $t)
                    @php $category = $t->category ?? 'guru'; @endphp
                    <tr data-row>
                        <td data-label="Nama">
                            <div class="table-main">
                                <img class="thumb thumb-round" src="{{ $t->photo ? asset('storage/' . $t->photo) : 'https://placehold.co/64x64/eff9e3/63cd00?text=GRU' }}" alt="{{ $t->name }}" loading="lazy">
                                <div style="min-width:0">
                                    <div class="cell-main truncate">{{ $t->name }}</div>
                                    <div class="cell-sub truncate">{{ trim(($t->subject ?? '') . (($t->subject && $t->position) ? ' · ' : '') . ($t->position ?? '')) ?: $t->position }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Sekolah"><span class="badge badge-info">{{ $t->school ?? '-' }}</span></td>
                        <td data-label="Kategori">
                            <span class="badge {{ $category === 'staff' ? 'badge-warning' : 'badge-published' }}">{{ $category }}</span>
                        </td>
                        <td data-label="Diperbarui"><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($t->updated_at)->locale('id')->diffForHumans() }}</span></td>
                        <td data-label="Aksi" class="text-right">
                            <div class="table-actions">
                                <a class="act-btn view" href="{{ route('admin.teachers.show', $t) }}" title="Lihat detail"><i class="fa-regular fa-eye"></i></a>
                                <a class="act-btn edit" href="{{ route('admin.teachers.edit', $t) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.teachers.destroy', $t) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus guru ini?')">
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
                                icon="fa-solid fa-chalkboard-user"
                                title="Belum ada guru"
                                description="Tambahkan data guru & staf pertama untuk ditampilkan di website sekolah."
                                action-label="Tambah Guru"
                                action-url="{{ route('admin.teachers.create') }}" />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection