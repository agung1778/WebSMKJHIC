@props([
    'paginator' => null,
    'client' => false,
    'showingLabel' => 'Menampilkan',
    'countLabel' => 'data',
])
@if($client)
    <div class="pagination-bar" x-show="!loading && !empty" x-cloak>
        <span class="range-text" x-text="'Menampilkan ' + rangeStart + '–' + rangeEnd + ' dari ' + filtered.length + ' {{ $countLabel }}'"></span>
        <div class="page-btns">
            <button class="page-btn" @click="goPage(page - 1)" :disabled="page <= 1"><i class="fa-solid fa-chevron-left" style="font-size:10px"></i></button>
            <template x-for="n in pageList" :key="n">
                <button class="page-btn" :class="{ 'active': n === page }" @click="goPage(n)" x-text="n"></button>
            </template>
            <button class="page-btn" @click="goPage(page + 1)" :disabled="page >= totalPages"><i class="fa-solid fa-chevron-right" style="font-size:10px"></i></button>
        </div>
        <select class="app-select" style="width:auto" x-model.number="perPage" @change="page = 1">
            <option :value="8">8 per halaman</option>
            <option :value="12">12 per halaman</option>
            <option :value="24">24 per halaman</option>
        </select>
    </div>
@elseif($paginator && $paginator->hasPages())
    <div class="pagination-bar">
        <span class="range-text">
            {{ $showingLabel }} {{ $paginator->firstItem() ?? 0 }} – {{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }}
        </span>
        <div class="page-btns">
            @if($paginator->onFirstPage())
                <span class="page-btn" disabled><i class="fa-solid fa-chevron-left"></i></span>
            @else
                <a class="page-btn" href="{{ $paginator->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
            @endif

            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                <a class="page-btn @if($page === $paginator->currentPage()) active @endif" href="{{ $url }}">{{ $page }}</a>
            @endforeach

            @if($paginator->hasMorePages())
                <a class="page-btn" href="{{ $paginator->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
            @else
                <span class="page-btn" disabled><i class="fa-solid fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
@endif