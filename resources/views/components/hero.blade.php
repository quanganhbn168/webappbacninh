{{--
    Page hero. On a fixed page every text comes from its admin record (Trang → Banner đầu trang);
    pages made from a record (a service, a category) pass their own. The image passed by the view
    is replaced when one is chosen in the admin.
--}}
@props([
    'eyebrow' => null,
    'title' => null,
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
    $title = $banner?->banner_title ?: ($title ?: $banner?->title);
    $highlight = $banner?->banner_title ? $banner->banner_highlight : $highlight;
    $lead = $banner?->banner_subtitle ?: $lead;
    $image = $banner?->bannerImage
        ? app(\App\Domain\Media\Actions\BuildResponsiveImage::class)->execute($banner->bannerImage, [480, 800, 1200])
        : $image;
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
                <h1>{{ $title }}@if ($highlight) <span class="page-hero__highlight">{{ $highlight }}</span>@endif</h1>
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
                    <x-img :src="$image" sizes="(min-width: 992px) 50vw, 100vw" :alt="$imageAlt" width="1448" height="1086" fetchpriority="high" decoding="async" />
                    @if ($note)
                        <span class="page-hero__note text-hand" aria-hidden="true">{{ $note }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
    {{ $after ?? '' }}
</section>
