@props([
    'icon' => 'fa-solid fa-folder-open',
    'title' => 'Belum ada data',
    'message' => 'Mulai tambahkan data pertama Anda.',
    'actionLabel' => null,
    'actionUrl' => null,
])
<div class="empty-state">
    <div class="empty-icon"><i class="{{ $icon }}"></i></div>
    <h3>{{ $title }}</h3>
    <p>{{ $message }}</p>
    @if ($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}" class="app-btn app-btn-primary">
            <i class="fa-solid fa-plus"></i>{{ $actionLabel }}
        </a>
    @endif
</div>