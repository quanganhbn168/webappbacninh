@props([
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => '',
    'rows' => 3,
    'required' => false,
    'help' => null
])

<div class="form-group mb-3">
    @if($label)
        <label for="{{ $name }}" class="font-weight-bold small text-muted mb-1">
            {{ $label }}
            @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif
    
    <textarea 
        name="{{ $name }}" 
        id="{{ $name }}" 
        class="form-control @error($name) is-invalid @enderror"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
    >{{ old($name, $value) }}</textarea>
    
    @if($help)
        <small class="text-muted">{{ $help }}</small>
    @endif
    
    @error($name)
        <span class="invalid-feedback d-block">{{ $message }}</span>
    @enderror
</div>
