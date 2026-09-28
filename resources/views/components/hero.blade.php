{{--
    Page hero. Texts and image can be overridden per page from the admin (Page banner fields);
    the values passed here are the defaults written in the view.
--}}
@props([
    'eyebrow' => null,
    'title',
    'highlight' => null,
    'lead' => null,
    'image' => null,
    'imageAlt' => '',
    'note' => null,
    'breadcrumbs' => null,
    'variant' => 'default',
])
@php
    $banner = $seoPage ?? null;
    $eyebrow = $banner?->banner_eyebrow ?: $eyebrow;
    $title = $banner?->banner_title ?: $title;
    $highlight = $banner?->banner_title ? $banner->banner_highlight : $highlight;
    $lead = $banner?->banner_subtitle ?: $lead;
    $image = $banner?->banner_image_url ?: $image;
    $breadcrumbs ??= $pageBreadcrumbs ?? [];
    $hasAside = isset($aside) && $aside->isNotEmpty();
@endphp
<section {{ $attributes->class(['page-hero', 'page-hero--'.$variant, 'page-hero--text' => ! $image && ! $hasAside]) }}>
    <div class="container">
        <div class="page-hero__grid">
            <div class="page-hero__copy">
                @if ($variant !== 'home')
                    <x-breadcrumbs :items="$breadcrumbs" />
                @endif
                @if ($eyebrow)
                    <p class="eyebrow">{{ $eyebrow }}</p>
                @endif
                <h1>{{ $title }}@if ($highlight)<span class="page-hero__highlight">{{ $highlight }}</span>@endif</h1>
                @if ($lead)
                    <p class="page-hero__lead">{{ $lead }}</p>
                @endif
                @if ($slot->isNotEmpty())
                    <div class="btn-row">{{ $slot }}</div>
                @endif
                {{ $meta ?? '' }}
            </div>
            @if ($hasAside)
                <div class="page-hero__aside">{{ $aside }}</div>
            @elseif ($image)
                <div class="page-hero__art">
                    <img src="{{ $image }}" alt="{{ $imageAlt }}" width="1448" height="1086" fetchpriority="high" decoding="async">
                    @if ($note)
                        <span class="page-hero__note text-hand" aria-hidden="true">{{ $note }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
    {{ $after ?? '' }}
</section>
