@props([
    'title',
    'subtitle' => null,
    'icon' => null,
])
<div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-7 fade-up">
    <div>
        <div class="flex items-center gap-3">
            @if($icon)
                <span class="section-hd" style="margin-bottom:0">
                    <span class="sh-icon"><i class="{{ $icon }}"></i></span>
                </span>
            @endif
            <div>
                <h2 class="text-xl lg:text-2xl font-bold text-[#1C1C1D] leading-tight">{{ $title }}</h2>
                @if($subtitle)
                    <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
    </div>
    @isset($actions)
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            {{ $actions }}
        </div>
    @endisset
</div>