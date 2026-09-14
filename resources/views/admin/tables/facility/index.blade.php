@extends('layouts.admin-app')

@section('title', 'Fasilitas')

@section('content')
    @php
        $total = $facilities->count();
        $types = $facilities->pluck('type')->filter()->unique()->values();
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-building-columns"
            kicker="Konten"
            title="Fasilitas"
            subtitle="Kelola sarana dan prasarana yang tersedia di sekolah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.facilities.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Fasilitas</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Fasilitas" :value="$total" icon="fa-solid fa-building-columns" tone="brand" />
            <x-admin-components::stat-card label="Jenis Unik" :value="$types->count()" icon="fa-solid fa-shapes" tone="green" />
            <x-admin-components::stat-card label="Dengan Gambar" :value="$facilities->whereNotNull('image')->count()" icon="fa-solid fa-image" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$facilities->max('updated_at') ? \Carbon\Carbon::parse($facilities->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        <x-admin-components::table
            :head="['Fasilitas', 'Jenis', 'Penerbit', 'Diperbarui', ['label' => 'Aksi', 'right' => true]]"
            search-hint="Cari fasilitas…"
            :export-url="route('admin.export', ['resource' => 'facilities'])">
            <x-slot:body>
                @forelse ($facilities as $f)
                    <tr data-row>
                        <td data-label="Fasilitas">
                            <div class="table-main">
                                <img class="thumb" src="{{ $f->image ? asset('storage/' . $f->image) : 'https://placehold.co/64x64/eff9e3/63cd00?text=FSL' }}" alt="{{ $f->name }}" loading="lazy">
                                <div style="min-width:0">
                                    <div class="cell-main truncate">{{ $f->name }}</div>
                                    <div class="cell-sub truncate">{{ \Illuminate\Support\Str::limit(strip_tags($f->description ?? ''), 140) }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Jenis"><span class="badge badge-info">{{ $f->type ?? '-' }}</span></td>
                        <td data-label="Penerbit"><span style="white-space:nowrap"><i class="fa-regular fa-user mr-1" style="color:var(--text-3)"></i>{{ $f->publisher ?? '-' }}</span></td>
                        <td data-label="Diperbarui"><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($f->updated_at)->locale('id')->diffForHumans() }}</span></td>
                        <td data-label="Aksi" class="text-right">
                            <div class="table-actions">
                                <a class="act-btn view" href="{{ route('admin.facilities.show', $f) }}" title="Lihat detail"><i class="fa-regular fa-eye"></i></a>
                                <a class="act-btn edit" href="{{ route('admin.facilities.edit', $f) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.facilities.destroy', $f) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus fasilitas ini?')">
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
                                icon="fa-solid fa-building-columns"
                                title="Belum ada fasilitas"
                                description="Tambahkan fasilitas pertama untuk ditampilkan di halaman website sekolah."
                                action-label="Tambah Fasilitas"
                                action-url="{{ route('admin.facilities.create') }}" />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection