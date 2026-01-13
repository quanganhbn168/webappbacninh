@props([
    'name',
    'label' => null,
    'checked' => false,
    'help' => null
])

<div class="form-group mb-3">
    <div class="custom-control custom-switch">
        <input 
            type="checkbox" 
            class="custom-control-input" 
            name="{{ $name }}" 
            id="{{ $name }}"
            {{ $checked ? 'checked' : '' }}
            {{ $attributes }}
        >
        <label class="custom-control-label font-weight-bold" for="{{ $name }}">
            {{ $label }}
        </label>
    </div>
    
    @if($help)
        <small class="text-muted d-block mt-1">{{ $help }}</small>
    @endif
</div>
