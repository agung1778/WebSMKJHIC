@props([
    'icon' => 'fa-solid fa-inbox',
    'title' => 'Belum ada data',
    'message' => null,
    'description' => null,
    'actionLabel' => null,
    'actionUrl' => null,
])
@php $desc = $description ?? $message ?? 'Mulai tambahkan data pertama Anda di sini.'; @endphp
<div class="empty-state fade-up">
    <div class="empty-icon"><i class="{{ $icon }}"></i></div>
    <h3>{{ $title }}</h3>
    <p>{{ $desc }}</p>
    @if ($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}" class="app-btn app-btn-primary">
            <i class="fa-solid fa-plus"></i>{{ $actionLabel }}
        </a>
    @endif
</div>