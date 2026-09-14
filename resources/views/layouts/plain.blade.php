<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WebApp Bắc Ninh')</title>
    <meta name="robots" content="noindex, follow">
    @hasSection('error-page')
        <link rel="icon" type="image/svg+xml" href="/frontend/images/favicon.svg">
    @else
        @include('partials.frontend.favicon')
        {!! tracking_code('head') !!}
    @endif
    @vite('resources/css/app.css')
    @stack('head')
</head>
<body class="min-h-screen bg-white text-gray-900">
    @unless($__env->hasSection('error-page')) {!! tracking_code('body_start') !!} @endunless
    <main>
        @if(session('success')) <p class="p-4 text-center" role="status">{{ session('success') }}</p> @endif
        @if(session('error')) <p class="p-4 text-center" role="alert">{{ session('error') }}</p> @endif
        @yield('content')
    </main>
    @vite('resources/js/app-user.js')
    @stack('scripts')
    @unless($__env->hasSection('error-page')) {!! tracking_code('body_end') !!} @endunless
</body>
</html>
