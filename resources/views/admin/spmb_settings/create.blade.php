@extends('layouts.admin-app')

@section('title', 'Tambah Data Info SPMB')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-5xl">
        <x-admin-components::page-header
            icon="fa-solid fa-file-circle-check"
            kicker="Website"
            title="Tambah Data SPMB"
            subtitle="Tambahkan baru gelombang, periode, atau info pendaftaran lainnya." />

        <form action="{{ route('admin.spmb_settings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.spmb_settings._form', ['spmbSetting' => $spmbSetting])
        </form>
    </div>
@endsection