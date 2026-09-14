@extends('layouts.admin-app')

@section('title', 'Jurusan')

@section('content')
    @php
        $total = $majors->count();
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-graduation-cap"
            kicker="Akademik"
            title="Jurusan"
            subtitle="Kelola kompetensi keahlian yang tersedia di SMK Amaliah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.majors.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Jurusan</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Jurusan" :value="$total" icon="fa-solid fa-graduation-cap" tone="brand" />
            <x-admin-components::stat-card label="Dengan Gambar" :value="$majors->whereNotNull('image')->count()" icon="fa-solid fa-image" tone="green" />
            <x-admin-components::stat-card label="Dengan Logo" :value="$majors->whereNotNull('logo')->count()" icon="fa-solid fa-fill-drip" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$majors->max('updated_at') ? \Carbon\Carbon::parse($majors->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        <x-admin-components::table
            :head="['Jurusan', 'Kepala Kompetensi', 'Penerbit', 'Diperbarui', ['label' => 'Aksi', 'right' => true]]"
            search-hint="Cari jurusan, kepala kompetensi…"
            :export-url="route('admin.export', ['resource' => 'majors'])">
            <x-slot:body>
                @forelse ($majors as $m)
                    @php
                        $mImg = $m->image ? asset('storage/' . $m->image) : ($m->logo ? asset('storage/' . $m->logo) : null);
                        $mSub = $m->abbreviation ? 'Singkatan: ' . $m->abbreviation : \Illuminate\Support\Str::limit(strip_tags($m->description ?? ''), 140);
                    @endphp
                    <tr data-row>
                        <td data-label="Jurusan">
                            <div class="table-main">
                                <img class="thumb" src="{{ $mImg ?: 'https://placehold.co/64x64/eff9e3/63cd00?text=JRS' }}" alt="{{ $m->name }}" loading="lazy">
                                <div style="min-width:0">
                                    <div class="cell-main truncate">{{ $m->name }}</div>
                                    <div class="cell-sub truncate">{{ $mSub }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Kepala Kompetensi"><span style="white-space:nowrap">{{ $m->competency_head ?? '-' }}</span></td>
                        <td data-label="Penerbit"><span style="white-space:nowrap"><i class="fa-regular fa-user mr-1" style="color:var(--text-3)"></i>{{ $m->publisher ?? '-' }}</span></td>
                        <td data-label="Diperbarui"><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($m->updated_at)->locale('id')->diffForHumans() }}</span></td>
                        <td data-label="Aksi" class="text-right">
                            <div class="table-actions">
                                <a class="act-btn view" href="{{ route('admin.majors.show', $m) }}" title="Lihat detail"><i class="fa-regular fa-eye"></i></a>
                                <a class="act-btn edit" href="{{ route('admin.majors.edit', $m) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.majors.destroy', $m) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jurusan ini?')">
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
                                icon="fa-solid fa-graduation-cap"
                                title="Belum ada jurusan"
                                description="Tambahkan jurusan pertama untuk ditampilkan di halaman website sekolah."
                                action-label="Tambah Jurusan"
                                action-url="{{ route('admin.majors.create') }}" />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection