@php($faviconAssets = app(\App\Domain\Site\Actions\ResolveFaviconAssets::class)->execute())
<meta name="application-name" content="{{ $faviconAssets->applicationName }}">
<meta name="theme-color" content="{{ $faviconAssets->themeColor }}">
<meta name="msapplication-TileColor" content="{{ $faviconAssets->themeColor }}">
<meta name="apple-mobile-web-app-title" content="{{ $faviconAssets->shortName }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
@if($faviconAssets->generated)
<link rel="icon" type="image/x-icon" sizes="16x16 32x32 48x48" href="{{ $faviconAssets->url('favicon.ico') }}">
@foreach([16, 32, 48, 96, 192] as $faviconSize)
<link rel="icon" type="image/png" sizes="{{ $faviconSize }}x{{ $faviconSize }}" href="{{ $faviconAssets->url("favicon-{$faviconSize}x{$faviconSize}.png") }}">
@endforeach
@foreach([120, 152, 167, 180] as $appleSize)
<link rel="apple-touch-icon" type="image/png" sizes="{{ $appleSize }}x{{ $appleSize }}" href="{{ $faviconAssets->url("apple-touch-icon-{$appleSize}x{$appleSize}.png") }}">
@endforeach
<meta name="msapplication-TileImage" content="{{ $faviconAssets->url('favicon-144x144.png') }}">
@else
<link rel="icon" type="{{ $faviconAssets->sourceMime }}" @if($faviconAssets->sourceMime === 'image/svg+xml') sizes="any" @endif href="{{ $faviconAssets->sourceUrl }}">
<link rel="apple-touch-icon" href="{{ $faviconAssets->sourceUrl }}">
@endif
@if($faviconAssets->safariMaskIconUrl !== '')
<link rel="mask-icon" href="{{ $faviconAssets->safariMaskIconUrl }}" color="{{ $faviconAssets->safariMaskColor }}">
@endif
<link rel="manifest" href="{{ route('site.manifest', $faviconAssets->version === '' ? [] : ['v' => $faviconAssets->version]) }}">
