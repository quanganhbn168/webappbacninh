@props([
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => '',
    'prefix' => null,
    'suffix' => null,
    'help' => null,
    'size' => 'default', // default, lg, sm
    'checkUrl' => null, // URL to check slug uniqueness
    'excludeId' => null // ID to exclude from check (for editing)
])

@php
    $sizeClass = match($size) {
        'lg' => 'input-group-lg',
        'sm' => 'input-group-sm',
        default => ''
    };
@endphp

<div class="form-group mb-3">
    @if($label)
        <label for="{{ $name }}" class="font-weight-bold small text-muted mb-1">{{ $label }}</label>
    @endif
    
    <div class="input-group {{ $sizeClass }}">
        @if($prefix)
            <div class="input-group-prepend">
                <span class="input-group-text bg-light border-right-0 small">{{ $prefix }}</span>
            </div>
        @endif
        
        <input 
            type="text" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="form-control {{ $prefix ? 'border-left-0' : '' }} {{ $suffix ? 'border-right-0' : '' }} @error($name) is-invalid @enderror"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes }}
        >
        
        @if($suffix)
            <div class="input-group-append">
                <span class="input-group-text bg-light border-left-0 small">{{ $suffix }}</span>
            </div>
        @endif
        
        @if($checkUrl)
            <div class="input-group-append">
                <span class="input-group-text bg-white border-left-0" id="slug-status-{{ $name }}">
                    <i class="fas fa-circle-notch fa-spin text-muted d-none" id="slug-loading-{{ $name }}"></i>
                    <i class="fas fa-check text-success d-none" id="slug-valid-{{ $name }}"></i>
                    <i class="fas fa-times text-danger d-none" id="slug-invalid-{{ $name }}"></i>
                </span>
            </div>
        @endif
    </div>
    
    @if($help)
        <small class="text-muted" id="help-{{ $name }}">{{ $help }}</small>
    @endif
    
    <small class="text-danger d-none" id="error-{{ $name }}"></small>
    
    @error($name)
        <span class="invalid-feedback d-block">{{ $message }}</span>
    @enderror
</div>

@if($checkUrl)
@push('admin_js')
<script>
    (function() {
        let slugTimer;
        const slugInput = document.getElementById('{{ $name }}');
        const loadingIcon = document.getElementById('slug-loading-{{ $name }}');
        const validIcon = document.getElementById('slug-valid-{{ $name }}');
        const invalidIcon = document.getElementById('slug-invalid-{{ $name }}');
        const errorText = document.getElementById('error-{{ $name }}');
        const helpText = document.getElementById('help-{{ $name }}');
        
        function hideAllIcons() {
            loadingIcon.classList.add('d-none');
            validIcon.classList.add('d-none');
            invalidIcon.classList.add('d-none');
            errorText.classList.add('d-none');
        }
        
        slugInput.addEventListener('input', function() {
            clearTimeout(slugTimer);
            hideAllIcons();
            
            if (!this.value.trim()) return;
            
            loadingIcon.classList.remove('d-none');
            
            slugTimer = setTimeout(function() {
                fetch('{{ $checkUrl }}?slug=' + encodeURIComponent(slugInput.value) + '{{ $excludeId ? "&exclude_id=" . $excludeId : "" }}')
                    .then(res => res.json())
                    .then(data => {
                        hideAllIcons();
                        if (data.exists) {
                            invalidIcon.classList.remove('d-none');
                            errorText.textContent = data.message;
                            errorText.classList.remove('d-none');
                            if (helpText) helpText.classList.add('d-none');
                        } else {
                            validIcon.classList.remove('d-none');
                            slugInput.value = data.slug; // Auto-format slug
                            if (helpText) helpText.classList.remove('d-none');
                        }
                    })
                    .catch(() => {
                        hideAllIcons();
                    });
            }, 500);
        });
    })();
</script>
@endpush
@endif
