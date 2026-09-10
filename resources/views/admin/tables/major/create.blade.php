@extends('layouts.admin-app')

@section('title', 'Tambah Jurusan')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-5xl">
        <x-admin-components::page-header
            icon="fa-solid fa-graduation-cap"
            kicker="Akademik"
            title="Tambah Jurusan"
            subtitle="Tambahkan kompetensi keahlian baru untuk ditampilkan di website." />

        <form action="{{ route('admin.majors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.tables.major._form', ['major' => null])
        </form>
    </div>
@endsection