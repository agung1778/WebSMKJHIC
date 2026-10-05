@extends('layouts.admin-app')

@section('title', 'Daftar Info SPMB')

@php
    use App\Models\SpmbSetting;
@endphp

@section('content')
    @php
        $total = $settings->count();
        $open = $settings->where('status', 'Buka')->count();
        $freqLabels = SpmbSetting::popupFrequencies();
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-file-circle-check"
            kicker="Website"
            title="Info SPMB"
            subtitle="Kelola data pendaftaran murinew: gelombang, periode, kuota, dan brosur.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.spmb_settings.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Data SPMB</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Data" :value="$total" icon="fa-solid fa-file-circle-check" tone="brand" />
            <x-admin-components::stat-card label="Aktif Ditampilkan" :value="$settings->where('is_active', true)->count()" icon="fa-solid fa-circle-check" tone="green" />
            <x-admin-components::stat-card label="Status Buka" :value="$open" icon="fa-solid fa-lock-open" tone="blue" />
            <x-admin-components::stat-card label="Sudah Ada Brosur" :value="$settings->filter(fn ($s) => $s->brochure_image_1 || $s->brochure_file)->count()" icon="fa-solid fa-file-image" tone="amber" />
        </div>

        <x-admin-components::table
            :head="[['label' => 'Data', 'class' => 'w-16'], 'Gelombang / Periode', 'Status', 'Brosur', 'Ditampilkan', 'Popup', ['label' => 'Aksi', 'right' => true]]"
            search-hint="C gelombang, periode, kuota…">
            <x-slot:body>
                @forelse ($settings as $s)
                    <tr data-row>
                        <td data-label="Data"><span class="badge badge-info">#{{ $s->id }}</span></td>
                        <td data-label="Gelombang / Periode">
                            <div class="cell-main">
                                {{ $s->wave_name ?: 'Tanpa nama gelombang' }}
                                @if ($s->wave_category)
                                    <span class="badge badge-info ms-1">{{ $s->wave_category }}</span>
                                @endif
                            </div>
                            <div class="cell-sub">
                                <i class="fa-solid fa-calendar-days"></i> {{ $s->period_date ?: 'Belum ada periode' }}
                                @if ($s->quota_note)
                                    · {{ $s->quota_note }}
                                @endif
                            </div>
                        </td>
                        <td data-label="Status">
                            <span class="badge {{ $s->status === 'Buka' ? 'badge-published' : 'badge-archived' }}">{{ $s->status }}</span>
                        </td>
                        <td data-label="Brosur">
                            @if ($s->brochure_image_1 || $s->brochure_file)
                                <span class="badge badge-published"><i class="fa-solid fa-check"></i> Ada</span>
                            @else
                                <span class="badge badge-archived"><i class="fa-solid fa-minus"></i> Belum</span>
                            @endif
                        </td>
                        <td data-label="Ditampilkan">
                            @if ($s->is_active)
                                <span class="badge badge-published"><i class="fa-solid fa-eye"></i> Aktif</span>
                            @else
                                <span class="badge badge-archived"><i class="fa-solid fa-eye-slash"></i> Nonaktif</span>
                            @endif
                        </td>
                        <td data-label="Popup">
                            @if ($s->popup_enabled)
                                <span class="badge badge-published" title="{{ $freqLabels[$s->popup_frequency] ?? $s->popup_frequency }}">
                                    <i class="fa-solid fa-window-maximize"></i> Aktif
                                </span>
                            @else
                                <span class="badge badge-archived"><i class="fa-solid fa-ban"></i> Nonaktif</span>
                            @endif
                        </td>
                        <td data-label="Aksi" class="text-right">
                            <div class="table-actions">
                                <a class="act-btn edit" href="{{ route('admin.spmb_settings.edit', $s) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.spmb_settings.destroy', $s) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data SPMB ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="act-btn delete" type="submit" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-row data-empty>
                        <td colspan="7">
                            <x-admin-components::empty-state
                                icon="fa-solid fa-file-circle-check"
                                title="Belum ada data SPMB"
                                description="Tambahkan data pendaftaran pertama (gelombang, periode, kuota, dan brosur) untuk halaman Info SPMB."
                                action-label="Tambah Data SPMB"
                                action-url="{{ route('admin.spmb_settings.create') }}" />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection