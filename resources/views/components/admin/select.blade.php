@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => '-- Chọn --',
    'required' => false,
    'help' => null
])

@php
    $id = $attributes->get('id') ?? str_replace(['[', ']'], '', $name) . '_' . uniqid();
@endphp

<div class="form-group mb-3">
    @if($label)
        <label for="{{ $id }}" class="font-weight-bold small text-muted mb-1">
            {{ $label }}
            @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif
    
    <select 
        name="{{ $name }}" 
        id="{{ $id }}" 
        class="form-control select2bs4 @error($name) is-invalid @enderror"
        data-placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
        style="width: 100%;"
    >
        @if(!$attributes->has('multiple'))
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $text)
            <option value="{{ $value }}" 
                @if(is_array(old($name, $selected)))
                    {{ in_array($value, old($name, $selected)) ? 'selected' : '' }}
                @else
                    {{ old($name, $selected) == $value ? 'selected' : '' }}
                @endif
            >
                {{ $text }}
            </option>
        @endforeach
    </select>
    
    @if($help)
        <small class="text-muted">{{ $help }}</small>
    @endif
    
    @error($name)
        <span class="invalid-feedback d-block">{{ $message }}</span>
    @enderror
</div>

@push('admin_js')
<script>
    $(document).ready(function() {
        $('#{{ $id }}').select2({
            theme: 'bootstrap4',
            width: '100%',
            allowClear: true,
            @if($attributes->has('tags'))
            tags: true,
            tokenSeparators: [',']
            @endif
        });
    });
</script>
@endpush


