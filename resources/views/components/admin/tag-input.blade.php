@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => [],
    'placeholder' => 'Chọn hoặc nhập để tạo mới...',
    'allowNew' => true,
    'help' => null
])

<div class="form-group mb-3">
    @if($label)
        <label for="tag-select-{{ $name }}" class="font-weight-bold small text-muted mb-1">{{ $label }}</label>
    @endif
    
    <select 
        name="{{ $name }}[]" 
        id="tag-select-{{ $name }}" 
        class="form-control select2bs4 @error($name) is-invalid @enderror"
        multiple="multiple"
        data-placeholder="{{ $placeholder }}"
        style="width: 100%;"
    >
        @php
            $selectedValues = (array) old($name, $selected);
        @endphp

        {{-- Render predefined options --}}
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ in_array($value, $selectedValues) ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach

        {{-- Render custom/new tags that are not in options (e.g. from old input) --}}
        @if($allowNew)
            @foreach($selectedValues as $value)
                @if(!array_key_exists($value, $options))
                    <option value="{{ $value }}" selected>{{ $value }}</option>
                @endif
            @endforeach
        @endif
    </select>
    
    @if($help)
        <small class="text-muted d-block mt-1">{{ $help }}</small>
    @endif
    
    @error($name)
        <span class="invalid-feedback d-block">{{ $message }}</span>
    @enderror
</div>

@push('admin_js')
<script>
    $(document).ready(function() {
        // console.log('Initializing Select2 for #tag-select-{{ $name }}');
        try {
            $('#tag-select-{{ $name }}').select2({
                theme: 'bootstrap4',
                width: '100%',
                tags: {{ $allowNew ? 'true' : 'false' }},
                tokenSeparators: [','],
                placeholder: '{{ $placeholder }}',
                allowClear: true
            });
            // console.log('Select2 initialized success');
        } catch (e) {
            console.error('Select2 init error:', e);
        }
    });
</script>
@endpush
