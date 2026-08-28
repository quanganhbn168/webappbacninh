@props([
    'name',
    'label' => 'Thư viện ảnh',
    'value' => [], // Array of URLs
])

@php
    $componentId = 'multi-image-' . Str::slug($name) . '-' . Str::random(6);
    $inputId = $componentId . '-input';
    $holderId = $componentId . '-holder';
    $templateId = $componentId . '-template';
@endphp

<div class="form-group">
    @if($label)
        <label class="font-weight-bold">{{ $label }}</label>
    @endif
    
    <div class="input-group mb-3">
        <input id="{{ $inputId }}" class="form-control" type="text" placeholder="/uploads/duong-dan-anh.jpg">
        <div class="input-group-append">
            <button type="button" class="btn btn-primary" data-add-image-path data-input="{{ $inputId }}" data-holder="{{ $holderId }}" data-template="{{ $templateId }}">
                <i class="fas fa-plus"></i> Thêm ảnh
            </button>
        </div>
    </div>

    <div id="{{ $holderId }}" class="d-flex flex-wrap" style="gap: 10px;">
        {{-- Existing Images --}}
        @if($value && count($value) > 0)
            @foreach($value as $img)
                @php
                    $url = is_string($img) ? $img : ($img['url'] ?? $img->url ?? '');
                @endphp
                @if($url)
                <div class="position-relative d-inline-block image-item" style="width: 150px;">
                    <input type="hidden" name="{{ $name }}[]" value="{{ $url }}">
                    <img src="{{ $url }}" style="height: 100px; width: 100%; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                    <button type="button" class="btn btn-danger btn-xs position-absolute" style="top: 5px; right: 5px; border-radius: 50%; padding: 2px 6px;" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endif
            @endforeach
        @endif
    </div>
    <small class="text-muted">Nhập đường dẫn hoặc URL ảnh rồi bấm “Thêm ảnh”.</small>
</div>

{{-- Template for new image --}}
<template id="{{ $templateId }}">
    <div class="position-relative d-inline-block image-item" style="width: 150px;">
        <input type="hidden" name="{{ $name }}[]" value="">
        <img src="" style="height: 100px; width: 100%; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
        <button type="button" class="btn btn-danger btn-xs position-absolute" style="top: 5px; right: 5px; border-radius: 50%; padding: 2px 6px;" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
</template>

@once
@push('admin_js')
    <script>
        document.addEventListener('click', function (event) {
            const button = event.target.closest('[data-add-image-path]');

            if (!button) {
                return;
            }

            const input = document.getElementById(button.dataset.input);
            const holder = document.getElementById(button.dataset.holder);
            const template = document.getElementById(button.dataset.template);
            const value = input.value.trim();

            if (!value) {
                input.focus();
                return;
            }

            const clone = template.content.cloneNode(true);
            const assetBase = @json(rtrim(asset(''), '/'));
            const previewUrl = /^https?:\/\//i.test(value)
                ? value
                : assetBase + '/' + value.replace(/^\/+/, '');

            clone.querySelector('input').value = value;
            clone.querySelector('img').src = previewUrl;
            holder.append(clone);
            input.value = '';
        });
    </script>
@endpush
@endonce
