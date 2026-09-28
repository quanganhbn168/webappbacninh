<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', site_config('name', 'WebApp Bắc Ninh'))</title>
    <meta name="robots" content="@yield('robots', 'noindex, follow')">
    @hasSection('meta_description')<meta name="description" content="@yield('meta_description')">@endif
    @hasSection('meta_keywords')<meta name="keywords" content="@yield('meta_keywords')">@endif
    @hasSection('error-page')
        <link rel="icon" type="image/svg+xml" href="/frontend/images/favicon.svg">
    @else
        @include('partials.site.favicon')
        {!! tracking_code('head') !!}
    @endif
    @vite(['resources/scss/bootstrap.scss', 'resources/css/basic.css', 'resources/js/basic.js'])
    @stack('head')
</head>
<body class="bg-light">
    @unless($__env->hasSection('error-page')) {!! tracking_code('body_start') !!} @endunless
    <main>
        @if(session('success')) <div class="alert alert-success rounded-0 mb-0 text-center" role="status">{{ session('success') }}</div> @endif
        @yield('content')
    </main>
    @stack('scripts')
    @unless($__env->hasSection('error-page')) {!! tracking_code('body_end') !!} @endunless
</body>
</html>
