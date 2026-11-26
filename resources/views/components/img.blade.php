{{--
    A picture sized for the screen. "src" is either the result of BuildResponsiveImage (a model's
    cover) or a URL; a file in public/images with a "-800" copy beside it is offered in both sizes.
--}}
@props(['src', 'sizes' => '100vw'])
@php($image = is_array($src) ? $src : ['src' => $src, 'srcset' => public_image_srcset($src)])
<img src="{{ $image['src'] }}" @if ($image['srcset']) srcset="{{ $image['srcset'] }}" sizes="{{ $sizes }}" @endif {{ $attributes }}>
