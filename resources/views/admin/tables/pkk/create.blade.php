@extends('layouts.admin-app')

@section('title', 'Tambah Proyek P5/PKK')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-5xl">
        <x-admin-components::page-header
            icon="fa-solid fa-lightbulb"
            kicker="Konten"
            title="Tambah Proyek P5/PKK"
            subtitle="Tambahkan hasil proyek P5 / praktik kewirausahaan." />

        <form action="{{ route('admin.pkk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.tables.pkk._form', ['pkk' => null])
        </form>
    </div>
@endsection