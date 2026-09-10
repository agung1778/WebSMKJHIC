@extends('layouts.admin-app')

@section('title', $facility->name)

@section('content')
    <div class="fade-up mx-auto max-w-4xl space-y-6">
        <x-admin-components::page-header
            icon="fa-solid fa-building-columns"
            kicker="Fasilitas"
            :title="Str::limit($facility->name, 60)"
            :subtitle="'Jenis: ' . $facility->type">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.facilities.edit', $facility->id) }}"><i class="fa-solid fa-pen"></i> Edit</a>
                <button class="app-btn app-btn-lg app-btn-danger" type="button" x-data="{}" @click="AppConfirm({ title:'Hapus Fasilitas', message:'Hapus fasilitas <b>{{ addslashes($facility->name) }}</b>? Tindakan ini tidak dapat dibatalkan.', danger:true, confirmText:'Ya, hapus', onConfirm(){ const f=document.createElement('form'); f.method='POST'; f.action='{{ route('admin.facilities.destroy', $facility->id) }}'; f.innerHTML='<input type=\"hidden\" name=\"_token\" value=\"{{ csrf_token() }}\"><input type=\"hidden\" name=\"_method\" value=\"DELETE\">'; document.body.appendChild(f); f.submit(); } })"><i class="fa-solid fa-trash"></i></button>
            </x-slot:actions>
        </x-admin-components::page-header>

        @if($facility->image)
            <div class="app-card overflow-hidden">
                <img src="{{ asset('storage/' . $facility->image) }}" alt="{{ $facility->name }}" class="w-full" style="max-height:380px;object-fit:cover">
            </div>
        @endif

        <div class="grid gap-6 md:grid-cols-3">
            <div class="form-section md:col-span-2">
                <h4 class="form-section-title"><i class="fa-solid fa-file-lines" style="color:var(--brand)"></i> Deskripsi</h4>
                <x-admin-components::rich-text :content="$facility->description" />
            </div>

            <div class="form-section h-fit">
                <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Info</h4>
                <dl class="info-grid">
                    <div><dt>Nama</dt><dd>{{ $facility->name }}</dd></div>
                    <div><dt>Jenis</dt><dd><span class="badge badge-info">{{ $facility->type }}</span></dd></div>
                    <div><dt>Penerbit</dt><dd>{{ $facility->publisher ?? '-' }}</dd></div>
                </dl>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a class="app-btn" href="{{ route('admin.facilities.index') }}"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
@endsection