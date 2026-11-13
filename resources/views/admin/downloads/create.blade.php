@extends('layouts.admin-app')
@section('title', 'Tambah Berkas — Pusat Unduhan')
@section('content')
    <div class="space-y-6 fade-up">
        <x-admin-components::page-header icon="fa-solid fa-cloud-arrow-up" kicker="Pusat Unduhan" title="Tambah Berkas"
            subtitle="Unggah logo sekolah, logo perusahaan, atau contoh laporan PKL untuk diunduh pengunjung." />
        <form action="{{ route('admin.downloads.store') }}" method="POST" enctype="multipart/form-data" x-data="{ submitting: false, category: @js(old('category', 'logo')) }" @submit="submitting = true">
            @csrf
            @include('admin.downloads._form')
        </form>
    </div>
@endsection
