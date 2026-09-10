@props([
    'label',
    'value',
    'icon' => 'fa-solid fa-chart-simple',
    'bg' => '#EFF9E3',
    'fg' => '#3E9B00',
    'sub' => null,
    'pct' => null,
    'trend' => null,
    'trendLabel' => null,
    'tone' => null,
])
@php
    $tones = [
        'brand' => ['#EFF9E3', '#3E9B00'],
        'green' => ['#E7F6EC', '#15803D'],
        'blue' => ['#EAF2FE', '#1D6FD6'],
        'amber' => ['#FFF6E5', '#B45309'],
        'purple' => ['#F3ECFE', '#7C3AED'],
        'rose' => ['#FFECEF', '#BE123C'],
    ];
    $resolved = $tone && isset($tones[$tone]) ? $tones[$tone] : [$bg, $fg];
@endphp
<div class="app-card app-card-hover p-5 stat-card">
    <div>
        <div class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">{{ $label }}</div>
        <div class="text-[26px] lg:text-[30px] font-extrabold text-[#1C1C1D] mt-1 tracking-tight" @if($attributes->has('data-count')) data-count="{{ $attributes->get('data-count') }}" @endif>{{ $value }}</div>
    </div>

    <div class="flex items-center justify-between gap-3">
        <div style="background:{{ $resolved[0] }};color:{{ $resolved[1] }}" class="sc-icon">
            <i class="{{ $icon }}"></i>
        </div>
        @if($pct !== null)
            <div class="progress-track flex-1" style="max-width:120px">
                <div class="progress-fill" style="width:{{ $pct }}%;background:{{ $pct > 85 ? '#E11D48' : '#63CD00' }}"></div>
            </div>
        @endif
        @if($trend)
            <span class="text-xs font-bold {{ $trend === 'up' ? 'text-green-600' : 'text-rose-500' }}">
                <i class="fa-solid {{ $trend === 'up' ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i> {{ $trendLabel ?? '' }}
            </span>
        @endif
    </div>

    @if($sub)
        <div class="text-xs text-gray-400 mt-1">{{ $sub }}</div>
    @endif
</div>