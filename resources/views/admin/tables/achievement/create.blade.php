@extends('layouts.admin-app')

@section('title', 'Tambah Prestasi')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-trophy"
            kicker="Akademik"
            title="Tambah Prestasi"
            subtitle="Tambahkan pencapaian siswa atau sekolah." />

        <form action="{{ route('admin.achievements.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.tables.achievement._form', ['achievement' => null])
        </form>
    </div>
@endsection