@extends('layouts.admin-app')

@section('title', 'Tambah Pemimpin Sekolah')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-user-tie"
            kicker="Akademik"
            title="Tambah Pemimpin Sekolah"
            subtitle="Tambahkan pimpinan sekolah baru untuk bagian Get To Know Our School Leaders." />

        <form action="{{ route('admin.leaders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.tables.leader._form', ['leader' => null])
        </form>
    </div>
@endsection