@props([
    'name',
    'label' => 'Thư viện ảnh',
    'value' => [], // Array of URLs
])

<div class="form-group">
    @if($label)
        <label class="font-weight-bold">{{ $label }}</label>
    @endif
    
    <div class="input-group mb-3">
        <span class="input-group-btn">
            <a id="lfm-{{ $name }}" data-input="thumbnail-{{ $name }}" data-preview="holder-{{ $name }}" class="btn btn-primary text-white">
                <i class="fas fa-images"></i> Chọn ảnh
            </a>
        </span>
        <input id="thumbnail-{{ $name }}" class="form-control" type="hidden" name="{{ $name }}" value="">
    </div>

    <div id="holder-{{ $name }}" class="d-flex flex-wrap" style="gap: 10px;">
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
    <small class="text-muted">Kéo thả để sắp xếp lại thứ tự ảnh.</small>
</div>

{{-- Template for new image --}}
<template id="template-image-item-{{ $name }}">
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
        $(document).ready(function() {
            var route_prefix = "{{ route('unisharp.lfm.show') }}";
            
            // Define custom callback for LFM
            $('#lfm-{{ $name }}').filemanager('image', {
                prefix: route_prefix,
                multiple: true
            });

            // Override the button click to open window manually to control callback
            $('#lfm-{{ $name }}').off('click').on('click', function(e) {
                e.preventDefault();
                var fullUrl = route_prefix + '?type=Images';
                
                window.open(fullUrl, 'FileManager', 'width=900,height=600');
                
                window.SetUrl = function (items) {
                    var target_preview = $('#holder-{{ $name }}');
                    var template = document.getElementById('template-image-item-{{ $name }}');

                    // Ensure items is array
                    if (!Array.isArray(items)) {
                        items = [items];
                    }

                    items.forEach(function (item) {
                        var url = item.url;
                        
                        // Clone template
                        var clone = template.content.cloneNode(true);
                        
                        // Populate data
                        clone.querySelector('input').value = url;
                        clone.querySelector('img').src = url;
                        
                        // Append to holder
                        target_preview.append(clone);
                    });
                };
            });

            // Sortable
            if (typeof $.fn.sortable !== 'undefined') {
                $("#holder-{{ $name }}").sortable();
            }
        });
    </script>
@endpush
@endonce
