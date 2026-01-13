@props([
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => '',
    'type' => 'text',
    'required' => false,
    'help' => null,
    'size' => 'default' // default, lg, sm
])

@php
    $sizeClass = match($size) {
        'lg' => 'form-control-lg',
        'sm' => 'form-control-sm',
        default => ''
    };
@endphp

<div class="form-group mb-3">
    @if($label)
        <label for="{{ $name }}" class="font-weight-bold small text-muted mb-1">
            {{ $label }}
            @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        class="form-control {{ $sizeClass }} @error($name) is-invalid @enderror"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
    >
    
    @if($help)
        <small class="text-muted">{{ $help }}</small>
    @endif
    
    @error($name)
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>
