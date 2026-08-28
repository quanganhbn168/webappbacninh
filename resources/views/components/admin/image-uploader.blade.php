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
             onclick="document.getElementById('{{ $inputId }}').focus()">
            
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
        
        <input
            type="text"
            name="{{ $name }}"
            id="{{ $inputId }}"
            value="{{ $value }}"
            class="form-control form-control-sm mb-2"
            placeholder="/uploads/duong-dan-anh.jpg"
            data-image-path-input
            data-preview="{{ $previewId }}"
            data-placeholder="{{ $placeholderId }}"
        >
        
        {{-- Buttons --}}
        <div class="d-flex">
            <button type="button"
                    class="btn btn-outline-primary btn-sm flex-grow-1 mr-1"
                    onclick="updatePreviewImg('{{ $inputId }}', '{{ $previewId }}', '{{ $placeholderId }}')">
                <i class="fas fa-image mr-1"></i> Cập nhật xem trước
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-image-path-input]').forEach(function (input) {
            input.addEventListener('input', function () {
                updatePreviewImg(input.id, input.dataset.preview, input.dataset.placeholder);
            });
        });
    });

    function updatePreviewImg(inputId, previewId, placeholderId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);
        const value = input.value.trim();

        if (value) {
            const assetBase = @json(rtrim(asset(''), '/'));
            preview.src = /^https?:\/\//i.test(value)
                ? value
                : assetBase + '/' + value.replace(/^\/+/, '');
            preview.classList.remove('d-none');
            placeholder.classList.add('d-none');
        } else {
            preview.removeAttribute('src');
            preview.classList.add('d-none');
            placeholder.classList.remove('d-none');
        }
    }

    function clearImageField(previewId, inputId, placeholderId) {
        document.getElementById(inputId).value = '';
        updatePreviewImg(inputId, previewId, placeholderId);
    }
</script>
@endpush
@endonce
