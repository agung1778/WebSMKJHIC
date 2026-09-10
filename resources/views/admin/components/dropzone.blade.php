@props([
    'name' => null,
    'label' => null,
    'accept' => 'image/*',
    'preview' => null,
    'current' => null,
    'hint' => null,
    'required' => false,
    'max' => null,
    'storage' => null,
])
@php
    $dropId = 'dz-' . str_replace(['[', ']', '.'], ['-', '', '_'], (string) $name);
    $previewId = $preview ?? 'preview-' . str_replace(['[', ']', '.'], ['-', '', '_'], (string) $name);
    $hasError = $errors->has($name);
    $imgUrl = $current ? (str_starts_with($current, 'http') ? $current : asset('storage/' . $current)) : null;
@endphp

<div class="field">
    @if($label)
        <label class="field-label">{{ $label }} @if($required)<span class="req">*</span>@endif</label>
    @endif

    <div class="dropzone" id="{{ $dropId }}">
        <input type="file" name="{{ $name }}" id="{{ $dropId }}-input" accept="{{ $accept }}"
            data-preview="{{ $previewId }}" class="@if($hasError) !border-rose-500 @endif" />
        <div class="dz-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
        <div class="dz-title">Tarik &amp; letakkan file di sini</div>
        <div class="dz-sub">atau klik untuk memilih · <span data-file-label="{{ $dropId }}-input">@if($imgUrl)file terpasang saat ini@else belum ada file@endif</span></div>
    </div>

    @if($hasError)
        <div class="field-error">
            <i class="fa-solid fa-circle-exclamation"></i>{{ $errors->first($name) }}
        </div>
    @endif

    @if($hint)
        <div class="field-hint">{{ $hint }}</div>
    @endif

    <div class="img-preview @if($imgUrl) visible @endif" id="{{ $previewId }}">
        <img data-role="img" src="{{ $imgUrl }}" alt="Preview" onerror="this.parentElement.classList.remove('visible')" />
    </div>
</div>