@extends('layouts.admin-app')

@section('title', 'Testimoni')

@section('content')
    @php
        $total = $testimonials->count();
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-comment-dots"
            kicker="Konten"
            title="Testimoni"
            subtitle="Kelola cerita dan kesan alumni yang menjadi testimoni sekolah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.testimonials.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Testimoni</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Testimoni" :value="$total" icon="fa-solid fa-comment-dots" tone="brand" />
            <x-admin-components::stat-card label="Jurusan Terwakili" :value="$testimonials->pluck('major.name')->filter()->unique()->count()" icon="fa-solid fa-graduation-cap" tone="green" />
            <x-admin-components::stat-card label="Alumni Tersubur" :value="$testimonials->pluck('alumni_year')->filter()->unique()->count() . ' angkatan'" icon="fa-solid fa-calendar" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$testimonials->max('updated_at') ? \Carbon\Carbon::parse($testimonials->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        <x-admin-components::table
            :head="['Alumni', 'Jurusan', 'Angkatan', 'Diperbarui', ['label' => 'Aksi', 'right' => true]]"
            search-hint="Cari alumni, jurusan, angkatan…"
            :export-url="route('admin.export', ['resource' => 'testimonials'])">
            <x-slot:body>
                @forelse ($testimonials as $t)
                    <tr data-row>
                        <td data-label="Alumni">
                            <div class="table-main">
                                <img class="thumb thumb-round" src="{{ $t->photo ? asset('storage/' . $t->photo) : 'https://placehold.co/64x64/eff9e3/63cd00?text=ALM' }}" alt="{{ $t->name }}" loading="lazy">
                                <div style="min-width:0">
                                    <div class="cell-main truncate">{{ $t->name }}</div>
                                    <div class="cell-sub truncate">{{ \Illuminate\Support\Str::limit(strip_tags($t->description ?? ''), 140) }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Jurusan"><span class="badge badge-info">{{ $t->major ? $t->major->name : '-' }}</span></td>
                        <td data-label="Angkatan"><span style="white-space:nowrap">{{ $t->alumni_year ?? '-' }}</span></td>
                        <td data-label="Diperbarui"><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($t->updated_at)->locale('id')->diffForHumans() }}</span></td>
                        <td data-label="Aksi" class="text-right">
                            <div class="table-actions">
                                <a class="act-btn view" href="{{ route('admin.testimonials.show', $t) }}" title="Lihat detail"><i class="fa-regular fa-eye"></i></a>
                                <a class="act-btn edit" href="{{ route('admin.testimonials.edit', $t) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.testimonials.destroy', $t) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus testimoni ini?')">
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
                                icon="fa-solid fa-comment-dots"
                                title="Belum ada testimoni"
                                description="Tambahkan testimoni alumni pertama untuk ditampilkan di website sekolah."
                                action-label="Tambah Testimoni"
                                action-url="{{ route('admin.testimonials.create') }}" />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection