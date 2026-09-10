@extends('layouts.admin-app')

@section('title', 'Edit Gambar')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-3xl">
        <x-admin-components::page-header
            icon="fa-solid fa-images"
            kicker="Hero & Media"
            title="Edit Gambar"
            :subtitle="'Perbarui gambar: ' . Str::limit($image->title ?? $image->filename, 60)">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.image.show', $image->id) }}"><i class="fa-regular fa-eye"></i> Lihat</a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <form action="{{ route('admin.image.update', $image->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid gap-6 lg:grid-cols-3 items-start">
                <div class="space-y-6 lg:col-span-2">
                    <div class="form-section space-y-5">
                        <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Detail Media</h4>
                        <x-admin-components::field name="title" label="Judul Gambar" :value="$image->title ?? null"
                            placeholder="Contoh: MainImage - Header Home" icon="fa-solid fa-heading" />
                        <x-admin-components::field name="description" label="Deskripsi" type="textarea" :rows="3" :value="$image->description ?? null"
                            placeholder="Tulis deskripsi singkat gambar..." />
                    </div>
                </div>

                <div class="form-section space-y-4 h-fit">
                    <h4 class="form-section-title"><i class="fa-solid fa-rotate" style="color:var(--brand)"></i> Ganti File</h4>
                    <x-admin-components::dropzone name="image_file" label="Gambar Baru (opsional)" :current="$image->path"
                        accept=".jpg,.jpeg,.png,.gif,.svg,.webp" hint="Kosongkan untuk tetap memakai gambar saat ini." />
                </div>
            </div>

            <div class="form-actions">
                <a class="app-btn app-btn-lg" href="{{ route('admin.image.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
                <button type="submit" class="app-btn app-btn-lg app-btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection