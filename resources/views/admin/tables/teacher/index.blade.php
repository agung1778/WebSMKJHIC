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

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-2 xl:grid-cols-5 stat-grid-2">
            <x-admin-components::stat-card label="Total Guru & Staf" :value="$total" icon="fa-solid fa-chalkboard-user" tone="brand" />
            <x-admin-components::stat-card label="Amaliah 1" :value="$teachers->where('school','Amaliah 1')->count()" icon="fa-solid fa-school" tone="green" />
            <x-admin-components::stat-card label="Amaliah 2" :value="$teachers->where('school','Amaliah 2')->count()" icon="fa-solid fa-school" tone="blue" />
            <x-admin-components::stat-card label="Gabungan" :value="$teachers->where('school','Amaliah 1 & 2')->count()" icon="fa-solid fa-school-circle-check" tone="amber" />
            <div class="col-span-2 sm:col-span-1">
                <x-admin-components::stat-card label="Staff" :value="$teachers->where('school','Staff')->count()" icon="fa-solid fa-users-gear" tone="dark" />
            </div>
        </div>

        <x-admin-components::table
            :head="['Nama', ['label' => 'Sekolah', 'class' => 'hide-mob'], 'Kategori', ['label' => 'Diperbarui', 'class' => 'hide-mob'], ['label' => 'Aksi', 'right' => true, 'class' => 'has-3-actions']]"
            search-hint="Cari nama, jabatan, mapel…"
            :export-url="route('admin.export', ['resource' => 'teachers'])">
            <x-slot:body>
                @forelse ($teachers as $t)
                    @php $isStaff = ($t->school ?? '') === 'Staff'; @endphp
                    <tr data-row>
                        <td data-label="Nama">
                            <div class="table-main">
                                <img class="thumb thumb-round" src="{{ $t->photo ? asset('storage/' . $t->photo) : 'https://placehold.co/64x64/eff9e3/63cd00?text=GRU' }}" alt="{{ $t->name }}" loading="lazy">
                                <div style="min-width:0">
                                    <div class="cell-main truncate font-medium">{{ $t->name }}</div>
                                    <div class="cell-sub truncate text-xs">
                                        <span class="sm:hidden font-medium text-[var(--brand)]">{{ $t->school ? $t->school . ' · ' : '' }}</span>{{ trim(($t->subject ?? '') . (($t->subject && $t->position) ? ' · ' : '') . ($t->position ?? '')) ?: $t->position }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Sekolah" class="hide-mob"><span class="badge badge-info">{{ $t->school ?? '-' }}</span></td>
                        <td data-label="Kategori">
                            <span class="badge {{ $isStaff ? 'badge-warning' : 'badge-published' }}">{{ $t->category ?? ($isStaff ? 'Staf' : 'Guru') }}</span>
                        </td>
                        <td data-label="Diperbarui" class="hide-mob"><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($t->updated_at)->locale('id')->diffForHumans() }}</span></td>
                        <td data-label="Aksi" class="has-3-actions text-right">
                            <div class="table-actions">
                                <a class="act-btn view" href="{{ route('admin.teachers.show', $t) }}" title="Lihat detail"><i class="fa-regular fa-eye"></i></a>
                                <a class="act-btn edit" href="{{ route('admin.teachers.edit', $t) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.teachers.destroy', $t) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus guru ini?')" class="inline-block">
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