@extends('layouts.admin-app')

@section('title', 'Tambah Mitra')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-handshake"
            kicker="Konten"
            title="Tambah Mitra Industri"
            subtitle="Tambahkan mitra industri & dunia kerja baru." />

        <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.tables.partners._form', ['partner' => null])
        </form>
    </div>
@endsection