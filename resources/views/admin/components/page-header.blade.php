@props([
    'title',
    'subtitle' => null,
    'icon' => null,
    'kicker' => null,
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
                @if($kicker)
                    <span class="inline-flex items-center gap-1.5 text-[10.5px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full mb-1.5" style="background:var(--brand-soft);color:var(--brand-deep)">
                        <span class="w-1.5 h-1.5 rounded-full" style="background:var(--brand)"></span>
                        {{ $kicker }}
                    </span>
                @endif
                <h2 class="text-xl lg:text-2xl font-bold leading-tight" style="color:var(--text)">{{ $title }}</h2>
                @if($subtitle)
                    <p class="text-sm mt-1" style="color:var(--text-3)">{{ $subtitle }}</p>
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