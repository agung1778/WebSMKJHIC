@extends('layouts.admin-app')

@section('title', $pkk->title)

@section('content')
    <div class="fade-up mx-auto max-w-5xl space-y-6">
        <x-admin-components::page-header
            icon="fa-solid fa-lightbulb"
            kicker="Galeri P5/PKK"
            :title="Str::limit($pkk->title, 60)"
            :subtitle="$pkk->major ? $pkk->major->name : '' . ' · ' . $pkk->student_class">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.pkk.edit', $pkk->id) }}"><i class="fa-solid fa-pen"></i> Edit</a>
                <form action="{{ route('admin.pkk.destroy', $pkk->id) }}" method="POST" id="delete-pkk-form" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="app-btn app-btn-lg app-btn-danger" onclick="AppConfirm({ title:'Hapus Proyek', message:'Hapus proyek {{ addslashes($pkk->title) }}? Tindakan ini tidak dapat dibatalkan.', danger:true, confirmText:'Ya, hapus', onConfirm(){ document.getElementById('delete-pkk-form').submit(); } })"><i class="fa-solid fa-trash"></i></button>
                </form>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="form-section md:col-span-2">
                @if($pkk->photo)
                    <div class="rounded-2xl overflow-hidden mb-6 border" style="border-color:var(--border)">
                        <img src="{{ asset('storage/' . $pkk->photo) }}" alt="{{ $pkk->title }}" class="w-full" style="max-height:400px;object-fit:cover">
                    </div>
                @endif

                <h4 class="form-section-title"><i class="fa-solid fa-file-lines" style="color:var(--brand)"></i> Deskripsi</h4>
                <x-admin-components::rich-text :content="$pkk->description" />

                @if($pkk->contact_info || $pkk->social_media)
                    <h4 class="form-section-title mt-6"><i class="fa-solid fa-bullhorn" style="color:var(--brand)"></i> Kontak Pembelian</h4>
                    <div class="flex flex-wrap gap-3">
                        @if($pkk->contact_info)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pkk->contact_info) }}" target="_blank" class="app-btn"><i class="fa-brands fa-whatsapp"></i> {{ $pkk->contact_info }}</a>
                        @endif
                        @if($pkk->social_media)
                            <a href="{{ $pkk->social_media }}" target="_blank" class="app-btn"><i class="fa-solid fa-share-nodes"></i> Lihat Sosmed</a>
                        @endif
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="form-section h-fit">
                    <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Info Produk</h4>
                    <dl class="info-grid">
                        <div><dt>Nama Proyek</dt><dd>{{ $pkk->title }}</dd></div>
                        @if($pkk->brand_name)<div><dt>Merek</dt><dd>{{ $pkk->brand_name }}</dd></div>@endif
                        <div><dt>Jurusan</dt><dd>{{ $pkk->major->name ?? '-' }}</dd></div>
                        <div><dt>Kategori</dt><dd><span class="badge badge-published">{{ $pkk->category }}</span></dd></div>
                        <div><dt>Kelas</dt><dd>{{ $pkk->student_class }}</dd></div>
                        <div><dt>Harga</dt><dd>{{ $pkk->price !== null ? 'Rp ' . number_format($pkk->price, 0, ',', '.') : 'Gratis / Tidak dijual' }}</dd></div>
                    </dl>
                </div>

                <div class="form-section h-fit">
                    <h4 class="form-section-title"><i class="fa-solid fa-users" style="color:var(--brand)"></i> Siswa / Kelompok</h4>
                    <div class="cell-main">{{ $pkk->student_names }}</div>
                </div>

                @if($pkk->logo)
                    <div class="form-section h-fit">
                        <h4 class="form-section-title"><i class="fa-solid fa-trademark" style="color:var(--brand)"></i> Logo Brand</h4>
                        <a href="{{ asset('storage/' . $pkk->logo) }}" target="_blank" class="img-thumb" style="width:140px">
                            <img src="{{ asset('storage/' . $pkk->logo) }}" alt="{{ $pkk->brand_name }}" style="height:140px">
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a class="app-btn" href="{{ route('admin.pkk.index') }}"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
@endsection