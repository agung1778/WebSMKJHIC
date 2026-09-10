@extends('layouts.admin-app')

@section('title', 'Tambah Guru & Staf')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-chalkboard-user"
            kicker="Akademik"
            title="Tambah Guru & Staf"
            subtitle="Tambahkan tenaga pendidik & kependidikan baru." />

        <form action="{{ route('admin.teachers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.tables.teacher._form', ['teacher' => null])
        </form>
    </div>
@endsection