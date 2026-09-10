@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'type' => 'text',
    'value' => null,
    'options' => [],
    'required' => false,
    'hint' => null,
    'icon' => null,
    'rows' => 5,
    'placeholder' => null,
    'autocomplete' => null,
    'step' => null,
    'min' => null,
    'max' => null,
    'class' => null,
])
@php
    $fieldId = $id ?? 'field-' . str_replace(['[', ']', '.'], ['-', '', '_'], (string) $name);
    $val = old($name, $value !== null ? $value : '');
    $hasError = $errors->has($name);
@endphp

<div class="field {{ $class }}">
    @if($label)
        <label for="{{ $fieldId }}" class="field-label">
            {{ $label }}
            @if($required)<span class="req">*</span>@endif
        </label>
    @endif

    @if($type === 'select')
        <select name="{{ $name }}" id="{{ $fieldId }}" class="app-select @if($hasError) has-error @endif" @if($required) required @endif>
            @if(!$required)
                <option value="">— Pilih —</option>
            @endif
            @foreach($options as $optValue => $optLabel)
                <option value="{{ $optValue }}" @selected((string) $val === (string) $optValue)>{{ $optLabel }}</option>
            @endforeach
        </select>

    @elseif($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $fieldId }}" rows="{{ $rows }}" class="app-textarea @if($hasError) has-error @endif"
            @if($required) required @endif placeholder="{{ $placeholder }}">{{ $val }}</textarea>

    @elseif($type === 'trix')
        <input id="{{ $fieldId }}" type="hidden" name="{{ $name }}" value="{{ $val }}">
        <trix-editor input="{{ $fieldId }}" class="@if($hasError) is-invalid @endif"></trix-editor>

    @elseif($type === 'checkbox')
        <label class="flex items-center gap-3 cursor-pointer select-none w-fit">
            <span class="toggle-switch">
                <input type="checkbox" name="{{ $name }}" id="{{ $fieldId }}" value="1" @checked((bool) $val)>
                <span class="track"></span>
            </span>
            @if($label)<span class="text-sm font-medium text-gray-600">{{ $label }}</span>@endif
        </label>

    @else
        <div class="relative">
            @if($icon)
                <i class="{{ $icon }}" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);font-size:12px;color:var(--text-3)"></i>
            @endif
            <input type="{{ $type }}" name="{{ $name }}" id="{{ $fieldId }}"
                value="{{ $type === 'password' ? '' : $val }}"
                class="app-input @if($icon) !pl-10 @endif @if($hasError) has-error @endif"
                @if($required) required @endif
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
                @if($step !== null) step="{{ $step }}" @endif
                @if($min !== null) min="{{ $min }}" @endif
                @if($max !== null) max="{{ $max }}" @endif />
        </div>
    @endif

    @if($hasError)
        <div class="field-error">
            <i class="fa-solid fa-circle-exclamation"></i>{{ $errors->first($name) }}
        </div>
    @endif

    @if($hint)
        <div class="field-hint">{{ $hint }}</div>
    @endif
</div>