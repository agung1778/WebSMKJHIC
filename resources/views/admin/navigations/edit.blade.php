@extends('layouts.admin-app')

@section('title', 'Edit Menu Navigasi')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-bars"
            kicker="Website"
            title="Edit Menu Navigasi"
            :subtitle="'Perbarui menu: ' . Str::limit($navigation->title, 60)" />

        <form action="{{ route('admin.navigations.update', $navigation->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.navigations._form', ['navigation' => $navigation])
        </form>
    </div>
@endsection