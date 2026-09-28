<!doctype html>
<html lang="{{ $language ?? 'vi' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    @if (filled($pageKeywords ?? null))
        <meta name="keywords" content="{{ $pageKeywords }}">
    @endif
    <meta name="robots" content="{{ $robots }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @include('partials.site.favicon')
    @if (site_config('google_site_verification'))
        <meta name="google-site-verification" content="{{ site_config('google_site_verification') }}">
    @endif
    <meta property="og:site_name" content="{{ site_config('name') }}">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:alt" content="{{ $ogImageAlt }}">
    <meta name="twitter:card" content="summary_large_image">
    @if (! empty($jsonLd))
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    @endif
    @vite(['resources/scss/bootstrap.scss', 'resources/css/site.css', 'resources/js/site.js'])
    {!! tracking_code('head') !!}
</head>
<body @class([$bodyClass ?? null])>
    {!! tracking_code('body_start') !!}
    <a class="skip-link" href="#main-content">Bỏ qua menu, đến nội dung</a>
    @include('partials.site.header')
    <main id="main-content" tabindex="-1">
        @if (session('success'))
            <div class="container pt-4">
                <div class="alert alert-success mb-0" role="status">{{ session('success') }}</div>
            </div>
        @endif
        @yield('content')
    </main>
    @include('partials.site.footer')
    @include('partials.site.floating-actions')
    @include('partials.site.modals')
    @stack('scripts')
    {!! tracking_code('body_end') !!}
</body>
</html>
