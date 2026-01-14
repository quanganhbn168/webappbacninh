@props([
    'position' => 'default',
    'class' => ''
])

@php
    $slotEnum = \App\Enums\BannerSlot::tryFrom($position);
    $banners = $slotEnum ? \App\Models\AdBanner::forSlot($slotEnum)->get() : collect([]);
@endphp

@if($banners->isNotEmpty())
    <div class="ad-slot ad-slot-{{ $position }} {{ $class }}">
        @foreach($banners as $banner)
            <a href="{{ $banner->link ?? '#' }}" 
               class="d-block text-decoration-none mb-2"
               @if($banner->open_new_tab) target="_blank" rel="noopener" @endif>
                <img src="{{ $banner->image_url }}" 
                     alt="{{ $banner->alt_text ?? $banner->name }}" 
                     class="img-fluid w-100 rounded shadow-sm hover-scale transition"
                     loading="lazy">
            </a>
        @endforeach
    </div>
@endif
