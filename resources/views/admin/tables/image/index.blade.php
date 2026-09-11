@extends('layouts.admin-app')

@section('title', 'Hero & Media')

@section('content')
    @php
        $total = $images->count();
        $totalSizeMB = $images->sum('size') / 1024 / 1024;
        $allTitles = ['MainImage', 'MajorsImage', 'NewsImage', 'PartnersImage', 'FacilityImage', 'ExtracurricularImage', 'AchievementImage', 'ProgramImage', 'GridImage', 'MajorGrid', 'Main', 'PortraitImage'];
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-images"
            kicker="Website"
            title="Hero & Media"
            subtitle="Unggah dan kelola aset gambar untuk header, galeri, dan media website Anda." />

        {{-- Ringkasan --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Gambar" :value="$total" icon="fa-solid fa-images" tone="brand" />
            <x-admin-components::stat-card label="Total Ukuran" :value="number_format($totalSizeMB, 2) . ' MB'" icon="fa-solid fa-hdd" tone="green" />
            <x-admin-components::stat-card label="Slot Terpakai" :value="collect($allTitles)->filter(fn ($t) => ($imageCounts[$t] ?? 0) > 0)->count() . ' / ' . count($allTitles)" icon="fa-solid fa-sitemap" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$images->max('updated_at') ? \Carbon\Carbon::parse($images->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        <div class="grid gap-6 lg:grid-cols-[340px_1fr]">
            {{-- Form unggah --}}
            <div class="app-card p-0 h-fit lg:sticky lg:top-24">
                <div class="p-5 border-b" style="border-color:var(--border)">
                    <h3 class="card-title"><i class="fa-solid fa-cloud-arrow-up text-[var(--brand)]"></i> Unggah Gambar Baru</h3>
                    <p class="text-[12.5px]" style="color:var(--text-3)">PNG, JPG, WEBP (Maks. 5MB)</p>
                </div>
                <form action="{{ route('admin.image.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="app-label" for="title_option">Judul Gambar <span class="req">*</span></label>
                        <select name="title" id="title_option" class="app-select w-full" onchange="document.getElementById('custom_title_wrapper').classList.toggle('hidden', this.value !== 'custom')" required>
                            <optgroup label="Header Halaman">
                                <option value="MainImage">MainImage - Header Home</option>
                                <option value="MajorsImage">MajorsImage - Header Jurusan</option>
                                <option value="NewsImage">NewsImage - Header Berita</option>
                                <option value="PartnersImage">PartnersImage - Header Mitra</option>
                                <option value="FacilityImage">FacilityImage - Header Fasilitas</option>
                                <option value="AchievementImage">AchievementImage - Header Prestasi</option>
                                <option value="ProgramImage">ProgramImage - Header Program</option>
                            </optgroup>
                            <optgroup label="Tata Letak & Grid">
                                <option value="GridImage">GridImage - Galeri Grid</option>
                                <option value="MajorGrid">MajorGrid - Grid Jurusan</option>
                            </optgroup>
                            <optgroup label="Lainnya">
                                <option value="Main">Main - Gambar Umum</option>
                                <option value="PortraitImage">PortraitImage - Foto Potret</option>
                                <option value="custom">-- Judul Kustom --</option>
                            </optgroup>
                        </select>
                        <div id="custom_title_wrapper" class="hidden mt-2">
                            <input type="text" name="title_custom" class="app-input" placeholder="Contoh: Foto Kegiatan PPLG">
                        </div>
                        @error('title') <p class="input-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="app-label" for="description">Deskripsi <span class="optional">(opsional)</span></label>
                        <textarea name="description" id="description" rows="2" class="app-input" placeholder="Tulis deskripsi singkat gambar...">{{ old('description') }}</textarea>
                        @error('description') <p class="input-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="app-label">File Gambar <span class="req">*</span></label>
                        <x-admin-components::dropzone name="image_file" :required="true" accept=".png,.jpg,.jpeg,.webp" hint="PNG, JPG, WEBP (Maks. 5MB)" />
                        @error('image_file') <p class="input-error">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="app-btn app-btn-primary app-btn-lg w-full justify-center">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Unggah Gambar
                    </button>
                </form>
            </div>

            {{-- Daftar gambar --}}
            <div class="space-y-6">
                {{-- Statistik judul terpakai --}}
                <div class="app-card p-5">
                    <h3 class="card-title mb-3"><i class="fa-solid fa-chart-simple text-[var(--brand)]"></i> Statistik Slot Media</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3">
                        @foreach ($allTitles as $title)
                            @php $count = $imageCounts[$title] ?? 0; @endphp
                            <div class="mini-stat p-3">
                                <div class="flex items-center gap-2">
                                    <span class="status-dot" style="background:{{ $count > 0 ? 'var(--brand)' : 'var(--border)' }}"></span>
                                    <code class="ms-label" style="font-size:12px">{{ $title }}</code>
                                </div>
                                <div class="ms-value" style="color:{{ $count > 0 ? 'var(--brand)' : 'var(--text-3)' }}">{{ $count }} <span class="text-[11px]">gambar</span></div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div data-filter-root>
                    <div class="toolbar">
                        <div class="search-field">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input class="app-input" type="search" placeholder="Cari gambar judul…" data-filter-input>
                        </div>
                        <span class="toolbar-spacer"></span>
                        <a class="app-btn app-btn-md" href="{{ route('admin.export', ['resource' => 'image']) }}" title="Export CSV"><i class="fa-solid fa-file-csv"></i><span class="hide-mob">Export</span></a>
                    </div>

                    <div class="table-wrap">
                        <table class="table-app">
                            <thead>
                                <tr>
                                    <th>Preview</th>
                                    <th>Judul / Path</th>
                                    <th>Ukuran</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($images as $g)
                                    @php
                                        $imgName = $g->title ?? $g->filename;
                                        $imgSub = $g->description ?? ($g->mime_type ?? '');
                                        $imgSize = $g->size ? number_format($g->size / 1024 / 1024, 2) . ' MB' : '-';
                                        $imgUrl = asset('storage/' . $g->path);
                                    @endphp
                                    <tr data-row>
                                        <td>
                                            <img class="thumb cursor-zoom-in" src="{{ $imgUrl }}" alt="{{ $imgName }}" loading="lazy" style="width:72px;height:48px;border-radius:10px" onclick="window.open('{{ $imgUrl }}')">
                                        </td>
                                        <td style="min-width:260px">
                                            <div class="cell-main truncate">{{ $imgName }}</div>
                                            <code class="cell-sub truncate d-block" style="max-width:340px">{{ $imgSub }}</code>
                                        </td>
                                        <td><span class="badge badge-info">{{ $imgSize }}</span></td>
                                        <td>
                                            <div class="flex items-center justify-end gap-2">
                                                <a class="app-btn app-btn-sm app-btn-primary" href="{{ route('admin.image.edit', $g) }}"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                                <div class="dropdown">
                                                    <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                                    <div class="dropdown-menu" style="display:none">
                                                        <a class="dropdown-item" href="{{ route('admin.image.show', $g) }}"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                        <div class="dropdown-sep"></div>
                                                        <form action="{{ route('admin.image.destroy', $g) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus gambar ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item danger" type="submit"><i class="fa-solid fa-trash"></i><span>Hapus</span></button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr data-row data-empty>
                                        <td colspan="4">
                                            <div class="p-6 text-center">
                                                <div class="empty-state">
                                                    <div class="empty-icon"><i class="fa-solid fa-images"></i></div>
                                                    <h3>Belum ada gambar</h3>
                                                    <p>Unggah gambar pertama dari formulir di samping.</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @include('admin.tables._filter')
                </div>
            </div>
        </div>
    </div>
@endsection