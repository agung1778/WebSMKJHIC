@extends('layouts.admin-app')

@section('title', 'Galeri P5/PKK')

@section('content')
    @php
        $total = $projects->count();
        $categories = [
            'Makanan' => 'badge-warning',
            'Kerajinan' => 'badge-info',
            'Jasa' => 'badge-published',
            'Teknologi' => 'badge-draft',
        ];
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-lightbulb"
            kicker="Konten"
            title="Galeri P5/PKK"
            subtitle="Kelola hasil proyek P5 dan praktik kewirausahaan siswa.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.pkk.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Proyek</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-2 xl:grid-cols-4 stat-grid-2">
            <x-admin-components::stat-card label="Total Proyek" :value="$total" icon="fa-solid fa-lightbulb" tone="brand" />
            <x-admin-components::stat-card label="Kategori" :value="$projects->pluck('category')->unique()->count()" icon="fa-solid fa-shapes" tone="green" />
            <x-admin-components::stat-card label="Kelas Terlibat" :value="$projects->pluck('student_class')->unique()->count()" icon="fa-solid fa-user-graduate" tone="blue" />
            <x-admin-components::stat-card label="Berbayar" :value="$projects->whereNotNull('price')->count()" icon="fa-solid fa-tags" tone="amber" />
        </div>

        <x-admin-components::table
            :head="['Proyek', 'Kategori', ['label' => 'Kelas', 'class' => 'hide-mob'], ['label' => 'Harga', 'class' => 'hide-mob'], ['label' => 'Aksi', 'right' => true, 'class' => 'has-3-actions']]"
            search-hint="Cari proyek, jurusan, kategori…"
            :category-options="['' => 'Semua kategori', 'Makanan' => 'Makanan', 'Kerajinan' => 'Kerajinan', 'Jasa' => 'Jasa', 'Teknologi' => 'Teknologi']"
            :export-url="route('admin.export', ['resource' => 'pkk'])">
            <x-slot:body>
                @forelse ($projects as $p)
                    @php
                        $category = $p->category ?? 'Produk';
                        $priceText = $p->price !== null ? number_format($p->price, 0, ',', '.') : 'Gratis';
                    @endphp
                    <tr data-row data-cat="{{ $category }}">
                        <td data-label="Proyek">
                            <div class="table-main">
                                <img class="thumb" src="{{ $p->photo ? asset('storage/' . $p->photo) : 'https://placehold.co/64x64/eff9e3/63cd00?text=PKK' }}" alt="{{ $p->title }}" loading="lazy">
                                <div style="min-width:0">
                                    <div class="cell-main truncate font-medium">{{ $p->title }}</div>
                                    <div class="cell-sub truncate text-xs">
                                        <span class="sm:hidden font-medium text-[var(--brand)]">{{ $p->student_class ? $p->student_class . ' · ' : '' }}{{ $priceText }} · </span>{{ \Illuminate\Support\Str::limit(strip_tags($p->description ?? ''), 100) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Kategori"><span class="badge {{ $categories[$category] ?? 'badge-info' }}">{{ $category }}</span></td>
                        <td data-label="Kelas" class="hide-mob"><span style="white-space:nowrap">{{ $p->student_class ?? '-' }}</span></td>
                        <td data-label="Harga" class="hide-mob"><span style="white-space:nowrap;{{ $priceText === 'Gratis' ? 'color:var(--green);font-weight:700' : '' }}">{{ $priceText }}</span></td>
                        <td data-label="Aksi" class="has-3-actions text-right">
                            <div class="table-actions">
                                <a class="act-btn view" href="{{ route('admin.pkk.show', $p) }}" title="Lihat detail"><i class="fa-regular fa-eye"></i></a>
                                <a class="act-btn edit" href="{{ route('admin.pkk.edit', $p) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.pkk.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus proyek ini?')" class="inline-block">
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
                                icon="fa-solid fa-lightbulb"
                                title="Belum ada proyek P5/PKK"
                                description="Tambahkan proyek P5/PKK pertama untuk ditampilkan di galeri."
                                action-label="Tambah Proyek"
                                action-url="{{ route('admin.pkk.create') }}" />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection