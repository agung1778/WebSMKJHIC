@extends('layouts.admin-app')

@section('title', $major->name)

@section('content')
    <div class="fade-up mx-auto max-w-5xl space-y-6">
        <x-admin-components::page-header
            icon="fa-solid fa-graduation-cap"
            kicker="Jurusan"
            :title="Str::limit($major->name, 60)"
            :subtitle="'Kepala Kompetensi: ' . $major->competency_head">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.majors.edit', $major->id) }}"><i class="fa-solid fa-pen"></i> Edit</a>
                <button class="app-btn app-btn-lg app-btn-danger" type="button" x-data="{}" @click="AppConfirm({ title:'Hapus Jurusan', message:'Hapus jurusan <b>{{ addslashes($major->name) }}</b>? Prestasi, testimoni & proyek PKK terkait ikut terhapus.', danger:true, confirmText:'Ya, hapus', onConfirm(){ const f=document.createElement('form'); f.method='POST'; f.action='{{ route('admin.majors.destroy', $major->id) }}'; f.innerHTML='<input type=\"hidden\" name=\"_token\" value=\"{{ csrf_token() }}\"><input type=\"hidden\" name=\"_method\" value=\"DELETE\">'; document.body.appendChild(f); f.submit(); } })"><i class="fa-solid fa-trash"></i></button>
            </x-slot:actions>
        </x-admin-components::page-header>

        @if($major->image)
            <div class="app-card overflow-hidden">
                <img src="{{ asset('storage/' . $major->image) }}" alt="{{ $major->name }}" class="w-full" style="max-height:400px;object-fit:cover">
            </div>
        @endif

        <div class="grid gap-6 md:grid-cols-3">
            <div class="form-section md:col-span-2">
                <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Info</h4>
                <dl class="info-grid">
                    <div><dt>Nama Jurusan</dt><dd>{{ $major->name }}</dd></div>
                    @if($major->abbreviation)<div><dt>Singkatan</dt><dd>{{ $major->abbreviation }}</dd></div>@endif
                    <div><dt>Kepala Kompetensi</dt><dd>{{ $major->competency_head }}</dd></div>
                    <div><dt>Penerbit</dt><dd>{{ $major->publisher ?? '-' }}</dd></div>
                </dl>

                <h4 class="form-section-title mt-6"><i class="fa-solid fa-file-lines" style="color:var(--brand)"></i> Deskripsi</h4>
                <x-admin-components::rich-text :content="$major->description" />
            </div>

            <div class="space-y-6">
                <div class="form-section">
                    <h4 class="form-section-title"><i class="fa-solid fa-star" style="color:var(--brand)"></i> Keunggulan</h4>
                    @if($major->advantage)
                        <ul class="rich-content list-none" style="list-style:none;margin:0;padding:0">
                            @foreach (preg_split('/\\r?\\n/', $major->advantage) as $point)
                                @if(trim($point))
                                    <li class="flex items-start gap-2 mb-2"><i class="fa-solid fa-circle-check mt-1 text-[var(--green)]"></i><span>{{ $point }}</span></li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p class="cell-sub">Belum ada data keunggulan.</p>
                    @endif
                </div>

                <div class="form-section">
                    <h4 class="form-section-title"><i class="fa-solid fa-tag" style="color:var(--brand)"></i> Tag</h4>
                    <div class="flex flex-wrap gap-2">
                        @forelse ($major->tag ? preg_split('/\\r?\\n/', $major->tag) : [] as $tag)
                            @if(trim($tag))
                                <span class="badge badge-info">{{ $tag }}</span>
                            @endif
                        @empty
                            <span class="cell-sub">Belum ada tag.</span>
                        @endforelse
                    </div>
                </div>

                @if($major->logo || $major->competency_head_photo)
                    <div class="form-section">
                        <h4 class="form-section-title"><i class="fa-solid fa-image" style="color:var(--brand)"></i> Media</h4>
                        <div class="grid grid-cols-2 gap-3">
                            @if($major->logo)
                                <a href="{{ asset('storage/' . $major->logo) }}" target="_blank" class="img-thumb" title="Logo">
                                    <img src="{{ asset('storage/' . $major->logo) }}" alt="Logo">
                                </a>
                            @endif
                            @if($major->competency_head_photo)
                                <a href="{{ asset('storage/' . $major->competency_head_photo) }}" target="_blank" class="img-thumb" title="Foto Kepala Kompetensi">
                                    <img src="{{ asset('storage/' . $major->competency_head_photo) }}" alt="Foto">
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a class="app-btn" href="{{ route('admin.majors.index') }}"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
@endsection