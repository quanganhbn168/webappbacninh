@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => '-- Chọn --',
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
    
    <select 
        name="{{ $name }}" 
        id="{{ $name }}" 
        class="form-control select2bs4 @error($name) is-invalid @enderror"
        data-placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
        style="width: 100%;"
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>
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
        $('#{{ $name }}').select2({
            theme: 'bootstrap4',
            width: '100%',
            allowClear: true
        });
    });
</script>
@endpush


