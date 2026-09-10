@props([
    'type' => 'published',
    'label' => null,
    'icon' => null,
])
@php
    $map = [
        'published'  => ['label' => 'Published',  'cls' => 'badge-published', 'icon' => 'fa-solid fa-circle-check'],
        'draft'      => ['label' => 'Draft',      'cls' => 'badge-draft',     'icon' => 'fa-solid fa-pen'],
        'archived'   => ['label' => 'Archived',   'cls' => 'badge-archived',  'icon' => 'fa-solid fa-box-archive'],
        'scheduled'  => ['label' => 'Scheduled',  'cls' => 'badge-scheduled', 'icon' => 'fa-regular fa-clock'],
        'active'     => ['label' => 'Aktif',      'cls' => 'badge-active',    'icon' => 'fa-solid fa-circle-check'],
        'inactive'   => ['label' => 'Nonaktif',   'cls' => 'badge-inactive',  'icon' => 'fa-solid fa-circle-xmark'],
        'buka'       => ['label' => 'Buka',       'cls' => 'badge-buka',      'icon' => 'fa-solid fa-door-open'],
        'tutup'      => ['label' => 'Tutup',      'cls' => 'badge-tutup',     'icon' => 'fa-solid fa-door-closed'],
        'wajib'      => ['label' => 'Wajib',      'cls' => 'badge-brand',     'icon' => 'fa-solid fa-check'],
        'pilihan'    => ['label' => 'Pilihan',    'cls' => 'badge-info',      'icon' => 'fa-solid fa-star'],
        'individual' => ['label' => 'Individual', 'cls' => 'badge-violet',    'icon' => 'fa-solid fa-user'],
        'institutional' => ['label' => 'Institusi','cls' => 'badge-tech',     'icon' => 'fa-solid fa-school'],
        'link'       => ['label' => 'Link',       'cls' => 'badge-info',      'icon' => 'fa-solid fa-link'],
        'button'     => ['label' => 'Tombol',     'cls' => 'badge-brand',     'icon' => 'fa-solid fa-hand-pointer'],
        'yes'        => ['label' => 'Aktif',      'cls' => 'badge-active',    'icon' => 'fa-solid fa-check'],
        'no'         => ['label' => 'Nonaktif',   'cls' => 'badge-inactive',  'icon' => 'fa-solid fa-xmark'],
    ];
    $t = strtolower((string) ($type ?? 'published'));
    $b = $map[$t] ?? ['label' => ucfirst($type ?? ''), 'cls' => 'badge-archived', 'icon' => 'fa-solid fa-box-archive'];
@endphp
<span class="badge {{ $b['cls'] }}">
    @if($icon || $b['icon'])
        <i class="{{ $icon ?? $b['icon'] }}"></i>
    @endif
    {{ $label ?? $b['label'] }}
</span>