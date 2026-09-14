@extends('layouts.admin-app')

@section('title', 'Mitra Industri')

@section('content')
    @php
        $total = $partners->count();
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-handshake"
            kicker="Konten"
            title="Mitra Industri"
            subtitle="Kelola mitra industri dan dunia kerja yang bekerja sama dengan sekolah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.partners.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Mitra</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Mitra" :value="$total" icon="fa-solid fa-handshake" tone="brand" />
            <x-admin-components::stat-card label="Kota Terwakili" :value="$partners->pluck('city')->filter()->unique()->count()" icon="fa-solid fa-location-dot" tone="green" />
            <x-admin-components::stat-card label="Sektor Unik" :value="$partners->pluck('sector')->filter()->unique()->count()" icon="fa-solid fa-industry" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$partners->max('updated_at') ? \Carbon\Carbon::parse($partners->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        <x-admin-components::table
            :head="['Mitra', 'Sektor', 'Kota', 'Diperbarui', ['label' => 'Aksi', 'right' => true]]"
            search-hint="Cari mitra, sektor, kota…"
            :export-url="route('admin.export', ['resource' => 'partners'])">
            <x-slot:body>
                @forelse ($partners as $p)
                    <tr data-row>
                        <td data-label="Mitra">
                            <div class="table-main">
                                <img class="thumb" src="{{ $p->logo ? asset('storage/' . $p->logo) : 'https://placehold.co/64x64/eff9e3/63cd00?text=MTR' }}" alt="{{ $p->name }}" loading="lazy">
                                <div style="min-width:0">
                                    <div class="cell-main truncate">{{ $p->name }}</div>
                                    <div class="cell-sub truncate">{{ \Illuminate\Support\Str::limit(strip_tags($p->description ?? ''), 140) }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Sektor"><span class="badge badge-info">{{ $p->sector ?? '-' }}</span></td>
                        <td data-label="Kota"><span style="white-space:nowrap"><i class="fa-solid fa-location-dot mr-1" style="color:var(--text-3)"></i>{{ $p->city ?? '-' }}</span></td>
                        <td data-label="Diperbarui"><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($p->updated_at)->locale('id')->diffForHumans() }}</span></td>
                        <td data-label="Aksi" class="text-right">
                            <div class="table-actions">
                                <a class="act-btn view" href="{{ route('admin.partners.show', $p) }}" title="Lihat detail"><i class="fa-regular fa-eye"></i></a>
                                <a class="act-btn edit" href="{{ route('admin.partners.edit', $p) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.partners.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mitra ini?')">
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
                                icon="fa-solid fa-handshake"
                                title="Belum ada mitra industri"
                                description="Tambahkan mitra industri pertama untuk ditampilkan di halaman website sekolah."
                                action-label="Tambah Mitra"
                                action-url="{{ route('admin.partners.create') }}" />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection