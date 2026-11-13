@extends('layouts.admin-app')
@section('title', 'Pusat Unduhan')

@section('content')
    <div class="space-y-6 fade-up">
        <x-admin-components::page-header icon="fa-solid fa-download" kicker="Website" title="Pusat Unduhan"
            subtitle="Kelola logo sekolah, logo perusahaan, dan berkas contoh laporan PKL siswa.">
            <x-slot:actions>
                <a href="{{ route('public.downloads.index') }}" target="_blank" rel="noopener" class="app-btn"><i class="fa-solid fa-arrow-up-right-from-square"></i> Halaman Publik</a>
                <a href="{{ route('admin.downloads.create') }}" class="app-btn app-btn-primary"><i class="fa-solid fa-plus"></i> Tambah Berkas</a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-3">
            <x-admin-components::stat-card label="Total Berkas" :value="$downloads->count()" icon="fa-solid fa-folder-open" tone="brand" />
            <x-admin-components::stat-card label="Aktif Ditampilkan" :value="$downloads->where('is_active', true)->where('file_available', true)->count()" icon="fa-solid fa-circle-check" tone="green" />
            <x-admin-components::stat-card label="Laporan PKL" :value="$downloads->where('category', 'pkl')->count()" icon="fa-solid fa-graduation-cap" tone="blue" />
        </div>

        <form action="{{ route('admin.downloads.index') }}" method="GET" class="app-card app-card-pad flex flex-wrap items-center justify-between gap-3 text-sm">
            <div class="flex flex-wrap items-center gap-2.5">
                <i class="fa-solid fa-filter text-gray-400"></i>
                <label for="admin-download-major" class="font-medium text-gray-700 dark:text-gray-300">Filter Jurusan PKL:</label>
                <select name="jurusan" id="admin-download-major" class="app-select !py-1.5 !px-3 !text-sm" onchange="this.form.submit()" style="width:auto;min-width:210px">
                    <option value="">Semua Berkas &amp; Jurusan</option>
                    <option value="umum" @selected($majorFilter === 'umum')>Umum / Semua Jurusan</option>
                    @foreach($majors as $major)
                        <option value="{{ $major->id }}" @selected($majorFilter === (string) $major->id)>{{ $major->name }}</option>
                    @endforeach
                </select>
            </div>
            @if($majorFilter !== null)
                <a href="{{ route('admin.downloads.index') }}" class="app-btn !py-1.5 !px-3 text-xs"><i class="fa-solid fa-rotate-left mr-1"></i> Reset Filter</a>
            @endif
        </form>

        <x-admin-components::table :head="['Berkas', 'Kategori', 'File Asli', 'Status', ['label' => 'Aksi', 'right' => true]]" search-hint="Cari berkas, kategori, atau file…"
            :category-options="['' => 'Semua Kategori'] + \App\Models\SchoolDownload::categories()">
            <x-slot:body>
                @forelse ($downloads as $logo)
                    <tr data-row data-cat="{{ $logo->category }}">
                        <td data-label="Berkas">
                            <div class="table-main">
                                @if($logo->is_image && $logo->file_available)
                                    <img src="{{ route('admin.downloads.preview', $logo) }}" alt="{{ $logo->name }}" loading="lazy" class="thumb"
                                        style="object-fit:contain;padding:4px;background:{{ $logo->dark_preview ? '#202b25' : '#f4f6f4' }};">
                                @else
                                    <span class="initials-avatar" aria-hidden="true" style="width:46px;height:46px;border-radius:12px;background:#eaf4e5;color:var(--brand-deep);font-size:18px;">
                                        <i class="fa-solid {{ $logo->is_image ? 'fa-image' : $logo->document_icon }}"></i>
                                    </span>
                                @endif
                                <div style="min-width:0">
                                    <div class="cell-main truncate" style="max-width:320px;" title="{{ $logo->name }}">{{ $logo->name }}</div>
                                    @if($logo->description)
                                        <div class="cell-sub truncate" style="max-width:320px;" title="{{ $logo->description }}">{{ $logo->description }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td data-label="Kategori">
                            <div>
                                <span class="badge {{ $logo->category === 'pkl' ? 'badge-amber' : ($logo->category === 'company_logo' ? 'badge-teal' : 'badge-brand') }}">
                                    {{ $logo->category_label }}
                                </span>
                                @if($logo->major_label)
                                    <div class="cell-sub" style="margin-top:4px"><i class="fa-solid fa-graduation-cap"></i> {{ $logo->major_label }}</div>
                                @endif
                            </div>
                        </td>
                        <td data-label="File Asli">
                            <div>
                                <span class="cell-main" style="text-transform:uppercase;font-size:12px;font-weight:700;">{{ $logo->extension }}</span>
                                <span class="cell-sub">· {{ $logo->formatted_size }}</span>
                                <div class="cell-sub truncate" style="max-width:180px;" title="{{ $logo->original_name }}">{{ $logo->original_name }}</div>
                                @unless($logo->file_available)
                                    <span class="badge badge-archived" style="font-size:10px;margin-top:3px;">File hilang</span>
                                @endunless
                            </div>
                        </td>
                        <td data-label="Status">
                            <span class="badge {{ $logo->is_active ? 'badge-published' : 'badge-inactive' }}">
                                <span class="dot"></span>
                                {{ $logo->is_active ? 'Ditampilkan' : 'Disembunyikan' }}
                            </span>
                        </td>
                        <td data-label="Aksi" class="text-right">
                            <div class="table-actions justify-end">
                                @if($logo->file_available)
                                    <a href="{{ route('admin.downloads.download', $logo) }}" class="act-btn view" title="Unduh file asli" aria-label="Unduh {{ $logo->name }}"><i class="fa-solid fa-download"></i></a>
                                @endif
                                <a href="{{ route('admin.downloads.edit', $logo) }}" class="act-btn edit" title="Edit berkas" aria-label="Edit {{ $logo->name }}"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.downloads.destroy', $logo) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus berkas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="act-btn delete" aria-label="Hapus {{ $logo->name }}" title="Hapus berkas"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-row data-empty><td colspan="5">
                        <x-admin-components::empty-state icon="fa-solid fa-folder-open" title="Belum ada berkas"
                            :description="$majorFilter !== null ? 'Belum ada laporan PKL untuk jurusan yang dipilih. Pilih filter lain atau tambahkan berkas.' : 'Tambahkan logo sekolah, logo perusahaan, atau contoh laporan PKL.'"
                            action-label="Tambah Berkas" :action-url="route('admin.downloads.create')" />
                    </td></tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection
