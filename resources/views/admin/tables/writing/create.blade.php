@extends('layouts.admin-app')

@section('title', 'Tulis Baru')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-pen-nib"
            kicker="Website"
            title="Tulis Baru"
            subtitle="Buat tulisan untuk halaman home website." />

        <form action="{{ route('admin.writings.store') }}" method="POST">
            @csrf
            @include('admin.tables.writing._form', ['writing' => null])
        </form>
    </div>
@endsection