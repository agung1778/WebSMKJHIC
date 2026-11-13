@extends('layouts.admin-app')
@section('title', 'Edit Berkas — Pusat Unduhan')
@section('content')
    <div class="space-y-6 fade-up">
        <x-admin-components::page-header icon="fa-solid fa-pen" kicker="Pusat Unduhan" title="Edit Berkas"
            subtitle="Perbarui kategori, informasi, file, dan status publikasi berkas." />
        <form action="{{ route('admin.downloads.update', $download) }}" method="POST" enctype="multipart/form-data" x-data="{ submitting: false, category: @js(old('category', $download->category)) }" @submit="submitting = true">
            @csrf
            @method('PUT')
            @include('admin.downloads._form')
        </form>
    </div>
@endsection
