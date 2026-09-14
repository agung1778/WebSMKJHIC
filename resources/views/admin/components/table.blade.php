@props([
    'head' => [],
    'search' => true,
    'searchHint' => 'Cari data…',
    'categoryOptions' => null,
    'exportUrl' => null,
])

<div data-filter-root>
    @if ($search || filled($categoryOptions) || filled($exportUrl))
        <div class="toolbar">
            @if ($search)
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="{{ $searchHint }}" data-filter-input>
                </div>
            @endif

            @if (filled($categoryOptions))
                <select class="app-select" style="width:auto" data-filter-cat>
                    @foreach ($categoryOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            @endif

            <span class="toolbar-spacer"></span>

            @if (filled($exportUrl))
                <a class="app-btn app-btn-md" href="{{ $exportUrl }}" title="Export CSV">
                    <i class="fa-solid fa-file-csv"></i><span class="hide-mob">Export</span>
                </a>
            @endif
        </div>
    @endif

    <div class="table-wrap">
        <table class="table-app">
            <thead>
                <tr>
                    @foreach ($head as $col)
                        @php
                            $label  = is_array($col) ? ($col['label'] ?? '') : $col;
                            $isRight = is_array($col) && !empty($col['right']);
                            $extra  = is_array($col) ? ($col['class'] ?? '') : '';
                        @endphp
                        <th @class([$extra, 'text-right' => $isRight])>{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{ $body }}
            </tbody>
        </table>
    </div>

    @include('admin.tables._filter')
</div>