@extends('layouts.admin-app')

@section('title', $teacher->name)

@section('content')
    <div class="fade-up mx-auto max-w-3xl space-y-6">
        <x-admin-components::page-header
            icon="fa-solid fa-chalkboard-user"
            kicker="Guru & Staf"
            :title="Str::limit($teacher->name, 60)"
            :subtitle="$teacher->position">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.teachers.edit', $teacher->id) }}"><i class="fa-solid fa-pen"></i> Edit</a>
                <button class="app-btn app-btn-lg app-btn-danger" type="button" x-data="{}" @click="AppConfirm({ title:'Hapus Data', message:'Hapus data <b>{{ addslashes($teacher->name) }}</b>? Tindakan ini tidak dapat dibatalkan.', danger:true, confirmText:'Ya, hapus', onConfirm(){ const f=document.createElement('form'); f.method='POST'; f.action='{{ route('admin.teachers.destroy', $teacher->id) }}'; f.innerHTML='<input type=\"hidden\" name=\"_token\" value=\"{{ csrf_token() }}\"><input type=\"hidden\" name=\"_method\" value=\"DELETE\">'; document.body.appendChild(f); f.submit(); } })"><i class="fa-solid fa-trash"></i></button>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="app-card p-6 flex flex-col sm:flex-row items-center gap-5">
            @if($teacher->photo)
                <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->name }}" class="rounded-2xl" style="width:96px;height:96px;object-fit:cover">
            @else
                <div class="initials-avatar" style="width:96px;height:96px;border-radius:20px;font-size:28px">{{ collect(explode(' ', $teacher->name))->map(fn ($w) => strtoupper(Str::substr($w, 0, 1)))->take(2)->implode('') }}</div>
            @endif
            <div class="flex-1 text-center sm:text-left">
                <div class="cell-main" style="font-size:19px">{{ $teacher->name }}</div>
                <div class="cell-sub">{{ $teacher->position }}</div>
                <div class="mt-2 flex flex-wrap gap-2 justify-center sm:justify-start">
                    <span class="badge badge-info">{{ $teacher->school }}</span>
                    <span class="badge badge-published">{{ $teacher->category }}</span>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Detail</h4>
            <dl class="info-grid">
                <div><dt>Nama Lengkap</dt><dd>{{ $teacher->name }}</dd></div>
                <div><dt>Jabatan</dt><dd>{{ $teacher->position }}</dd></div>
                <div><dt>Mata Pelajaran</dt><dd>{{ $teacher->subject ?? '-' }}</dd></div>
                <div><dt>Sekolah</dt><dd>{{ $teacher->school }}</dd></div>
                <div><dt>Kategori</dt><dd>{{ $teacher->category ?? '-' }}</dd></div>
            </dl>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a class="app-btn" href="{{ route('admin.teachers.index') }}"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
@endsection