@props([
    'name',
    'label' => null,
    'value' => null,
    'ratio' => '16x9', // Options: 16x9, 4x3, 1x1 (square), 3x4 (portrait), auto
    'height' => null   // Custom height in px, overrides ratio
])

@php
    $componentId = 'img-uploader-' . Str::slug($name) . '-' . Str::random(6);
    $previewId = $componentId . '-preview';
    $inputId = $componentId . '-input';
    $lfmId = $componentId . '-lfm';
    $placeholderId = $componentId . '-placeholder';
    
    // Calculate padding-top for aspect ratio
    $paddingTop = match($ratio) {
        '16x9' => '56.25%',
        '4x3' => '75%',
        '1x1' => '100%',
        '3x4' => '133.33%',
        'auto' => '60%',
        default => '56.25%'
    };
    
    if ($height) {
        $paddingTop = null;
    }
    
    $hasValue = !empty($value);
@endphp

<div class="form-group mb-0">
    @if($label)
        <label class="font-weight-bold small text-muted mb-2 d-block">{{ $label }}</label>
    @endif
    
    <div class="image-uploader-wrapper">
        {{-- Preview Area --}}
        <div class="mb-2 rounded overflow-hidden bg-light border" 
             style="{{ $height ? "height: {$height}px;" : "padding-top: {$paddingTop};" }} position: relative; cursor: pointer;"
             onclick="document.getElementById('{{ $lfmId }}').click()">
            
            {{-- Image Preview --}}
            <img src="{{ $hasValue ? asset($value) : '' }}" 
                 id="{{ $previewId }}"
                 class="{{ $hasValue ? '' : 'd-none' }}"
                 style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; background: #f8f9fa;"
                 alt="Preview">
            
            {{-- Placeholder --}}
            <div id="{{ $placeholderId }}" 
                 class="text-center text-muted {{ $hasValue ? 'd-none' : '' }}"
                 style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                <p class="small mb-0">Click để chọn ảnh</p>
            </div>
        </div>
        
        {{-- Hidden Input --}}
        <input type="hidden" name="{{ $name }}" id="{{ $inputId }}" value="{{ $value }}">
        
        {{-- Buttons --}}
        <div class="d-flex">
            <button type="button" id="{{ $lfmId }}" 
                    data-input="{{ $inputId }}" 
                    data-preview="{{ $previewId }}" 
                    class="btn btn-outline-primary btn-sm flex-grow-1 mr-1">
                <i class="fas fa-folder-open mr-1"></i> Chọn từ thư viện
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm" 
                    onclick="clearImageField('{{ $previewId }}', '{{ $inputId }}', '{{ $placeholderId }}')"
                    title="Xóa ảnh">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

@once
@push('admin_js')
<script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
<script>
    // Initialize all LFM buttons
    $(document).ready(function() {
        $('[id$="-lfm"]').each(function() {
            var $btn = $(this);
            var inputId = $btn.data('input');
            var previewId = $btn.data('preview');
            
            $btn.filemanager('image', {
                prefix: '',
            });
            
            // Watch for input value changes
            var $input = $('#' + inputId);
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                        updatePreviewImg(inputId, previewId);
                    }
                });
            });
            observer.observe($input[0], { attributes: true });
            
            // Also handle direct value changes
            $input.on('change', function() {
                updatePreviewImg(inputId, previewId);
            });
        });
    });
    
    function updatePreviewImg(inputId, previewId) {
        var $input = $('#' + inputId);
        var $preview = $('#' + previewId);
        var placeholderId = inputId.replace('-input', '-placeholder');
        var $placeholder = $('#' + placeholderId);
        
        if ($input.val()) {
            $preview.attr('src', $input.val()).removeClass('d-none');
            $placeholder.addClass('d-none');
        } else {
            $preview.addClass('d-none');
            $placeholder.removeClass('d-none');
        }
    }
    
    function clearImageField(previewId, inputId, placeholderId) {
        $('#' + inputId).val('');
        $('#' + previewId).attr('src', '').addClass('d-none');
        $('#' + placeholderId).removeClass('d-none');
    }
</script>
@endpush
@endonce
