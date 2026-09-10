@php
    $map = [
        'published' => ['label' => 'Published', 'cls' => 'badge-published', 'icon' => 'fa-solid fa-circle-check'],
        'draft'     => ['label' => 'Draft',     'cls' => 'badge-draft',     'icon' => 'fa-solid fa-pen'],
        'archived'  => ['label' => 'Archived',  'cls' => 'badge-archived',  'icon' => 'fa-solid fa-box-archive'],
        'scheduled' => ['label' => 'Scheduled', 'cls' => 'badge-scheduled', 'icon' => 'fa-regular fa-clock'],
    ];
    $b = $map[$status ?? 'published'] ?? ['label' => ucfirst($status ?? ''), 'cls' => 'badge-archived', 'icon' => 'fa-solid fa-box-archive'];
@endphp
<span class="badge {{ $b['cls'] }}">
    <i class="{{ $b['icon'] }}"></i>
    {{ $b['label'] }}
</span>