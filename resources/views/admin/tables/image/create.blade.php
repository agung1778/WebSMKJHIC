@extends('layouts.admin-app')

@section('content')

    <script>
        window.location.href = "{{ route('admin.image.index') }}";
    </script>

    <div class="main-content flex-1 p-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-center text-gray-500">Redirecting to Media Gallery...</p>
        </div>
    </div>

@endsection