<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="{{ $robots ?? 'index, follow' }}">
    <meta name="theme-color" content="#ffac00">
    <link rel="canonical" href="{{ $canonicalUrl ?? request()->url() }}">
    @include('partials.frontend.favicon')
    @if (site_config('google_site_verification'))
        <meta name="google-site-verification" content="{{ site_config('google_site_verification') }}">
    @endif
    <meta property="og:site_name" content="{{ site_config('name') }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl ?? request()->url() }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('frontend/interface/images/hero-home.webp') }}">
    <meta name="twitter:card" content="summary_large_image">
    @if (!empty($jsonLd))
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    @endif
    @vite(['resources/css/interface.css', 'resources/js/interface.js'])
    @if (!str_starts_with($contentView, 'frontend.interface.'))
        @vite('resources/css/managed-content.css')
    @endif
    {!! tracking_code('head') !!}
</head>
<body class="{{ $bodyClass ?? '' }}">
    {!! tracking_code('body_start') !!}
    <a class="skip-link" href="#main-content">Bỏ qua menu, đến nội dung</a>
    @include('frontend.interface.header')
    <div id="main-content" tabindex="-1" @class(['managed-content' => !str_starts_with($contentView, 'frontend.interface.')])>
        @if (session('success'))
            <div class="container py-4" role="status">{{ session('success') }}</div>
        @endif
        @include($contentView)
    </div>
    @include('frontend.interface.footer')
    @include('frontend.interface.social-channels')
    @include('frontend.interface.dialogs')
    @include('frontend.interface.detail-dialog')
    @if (!str_starts_with($contentView, 'frontend.interface.'))
        <script type="module" src="{{ frontend_asset('assets/js/lead-forms.js') }}"></script>
        @foreach ($extraScripts ?? [] as $script)
            <script type="module" src="{{ frontend_asset('assets/js/'.basename($script)) }}"></script>
        @endforeach
    @endif
    <script type="application/json" id="interface-settings">{!! json_encode($interfaceSettings, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    {!! tracking_code('body_end') !!}
</body>
</html>
