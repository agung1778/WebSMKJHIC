@extends('layouts.admin-app')

@section('title', $extracurricular->name)

@section('content')
    <div class="fade-up mx-auto max-w-4xl space-y-6">
        <x-admin-components::page-header
            icon="fa-solid fa-futbol"
            kicker="Ekstrakurikuler"
            :title="Str::limit($extracurricular->name, 60)"
            :subtitle="'Pembina: ' . $extracurricular->coach">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.extracurriculars.edit', $extracurricular->id) }}"><i class="fa-solid fa-pen"></i> Edit</a>
                <button class="app-btn app-btn-lg app-btn-danger" type="button" x-data="{}" @click="AppConfirm({ title:'Hapus Ekskul', message:'Hapus ekstrakurikuler <b>{{ addslashes($extracurricular->name) }}</b>? Tindakan ini tidak dapat dibatalkan.', danger:true, confirmText:'Ya, hapus', onConfirm(){ const f=document.createElement('form'); f.method='POST'; f.action='{{ route('admin.extracurriculars.destroy', $extracurricular->id) }}'; f.innerHTML='<input type=\"hidden\" name=\"_token\" value=\"{{ csrf_token() }}\"><input type=\"hidden\" name=\"_method\" value=\"DELETE\">'; document.body.appendChild(f); f.submit(); } })"><i class="fa-solid fa-trash"></i></button>
            </x-slot:actions>
        </x-admin-components::page-header>

        @if($extracurricular->image)
            <div class="app-card overflow-hidden">
                <img src="{{ asset('storage/' . $extracurricular->image) }}" alt="{{ $extracurricular->name }}" class="w-full" style="max-height:360px;object-fit:cover">
            </div>
        @endif

        <div class="grid gap-6 md:grid-cols-3">
            <div class="form-section md:col-span-2">
                <h4 class="form-section-title"><i class="fa-solid fa-file-lines" style="color:var(--brand)"></i> Deskripsi</h4>
                <x-admin-components::rich-text :content="$extracurricular->description" />
            </div>

            <div class="form-section h-fit">
                <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Info</h4>
                <dl class="info-grid">
                    <div><dt>Nama</dt><dd>{{ $extracurricular->name }}</dd></div>
                    <div><dt>Tipe</dt><dd>
                        <span class="badge {{ $extracurricular->type === 'Wajib' ? 'badge-info' : 'badge-warning' }}">{{ $extracurricular->type }}</span>
                    </dd></div>
                    <div><dt>Pembina</dt><dd>{{ $extracurricular->coach }}</dd></div>
                    <div><dt>Kontak</dt><dd>{{ $extracurricular->contact ?? '-' }}</dd></div>
                    <div><dt>Penerbit</dt><dd>{{ $extracurricular->publisher ?? '-' }}</dd></div>
                </dl>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a class="app-btn" href="{{ route('admin.extracurriculars.index') }}"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
@endsection