@extends('layouts.admin-app')

@section('title', 'Tambah Ekstrakurikuler')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-futbol"
            kicker="Akademik"
            title="Tambah Ekstrakurikuler"
            subtitle="Tambahkan kegiatan ekstrakurikuler baru." />

        <form action="{{ route('admin.extracurriculars.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.tables.extracurricular._form', ['extracurricular' => null])
        </form>
    </div>
@endsection