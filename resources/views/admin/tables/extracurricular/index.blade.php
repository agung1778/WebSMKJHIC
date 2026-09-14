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

        <x-admin-components::table
            :head="['Ekstrakurikuler', 'Tipe', 'Pembina', 'Diperbarui', ['label' => 'Aksi', 'right' => true]]"
            search-hint="Cari ekskul, pembina…"
            :category-options="['' => 'Semua tipe', 'Wajib' => 'Wajib', 'Pilihan' => 'Pilihan']"
            :export-url="route('admin.export', ['resource' => 'extracurriculars'])">
            <x-slot:body>
                @forelse ($extracurriculars as $e)
                    @php $type = $e->type ?? 'Pilihan'; @endphp
                    <tr data-row data-cat="{{ $type }}">
                        <td data-label="Ekstrakurikuler">
                            <div class="table-main">
                                <img class="thumb" src="{{ $e->image ? asset('storage/' . $e->image) : 'https://placehold.co/64x64/eff9e3/63cd00?text=EKS' }}" alt="{{ $e->name }}" loading="lazy">
                                <div style="min-width:0">
                                    <div class="cell-main truncate">{{ $e->name }}</div>
                                    <div class="cell-sub truncate">{{ \Illuminate\Support\Str::limit(strip_tags($e->description ?? ''), 150) }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Tipe"><span class="badge {{ $type === 'Wajib' ? 'badge-info' : 'badge-warning' }}">{{ $type }}</span></td>
                        <td data-label="Pembina"><span style="white-space:nowrap"><i class="fa-solid fa-whistle mr-1" style="color:var(--text-3)"></i>{{ $e->coach ?? '-' }}</span></td>
                        <td data-label="Diperbarui"><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($e->updated_at)->locale('id')->diffForHumans() }}</span></td>
                        <td data-label="Aksi" class="text-right">
                            <div class="table-actions">
                                <a class="act-btn view" href="{{ route('admin.extracurriculars.show', $e) }}" title="Lihat detail"><i class="fa-regular fa-eye"></i></a>
                                <a class="act-btn edit" href="{{ route('admin.extracurriculars.edit', $e) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.extracurriculars.destroy', $e) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ekstrakurikuler ini?')">
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
                                icon="fa-solid fa-futbol"
                                title="Belum ada ekstrakurikuler"
                                description="Tambahkan ekstrakurikuler pertama untuk ditampilkan di website sekolah."
                                action-label="Tambah Ekskul"
                                action-url="{{ route('admin.extracurriculars.create') }}" />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection