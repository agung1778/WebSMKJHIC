@extends('layouts.admin-app')

@section('title', 'Tambah Fasilitas')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-building-columns"
            kicker="Konten"
            title="Tambah Fasilitas"
            subtitle="Tambahkan sarana & prasarana baru untuk website sekolah." />

        <form action="{{ route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.tables.facility._form', ['facility' => null])
        </form>
    </div>
@endsection