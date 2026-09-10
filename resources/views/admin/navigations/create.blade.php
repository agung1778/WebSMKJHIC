@extends('layouts.admin-app')

@section('title', 'Tambah Menu Navigasi')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-bars"
            kicker="Website"
            title="Tambah Menu Navigasi"
            subtitle="Tambahkan item menu untuk top bar, menu utama, atau footer." />

        <form action="{{ route('admin.navigations.store') }}" method="POST">
            @csrf
            @include('admin.navigations._form', ['navigation' => null])
        </form>
    </div>
@endsection