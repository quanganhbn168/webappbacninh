<!DOCTYPE html>
<html lang="vi">
<head>
    @php
        $utilityTitle = trim($__env->yieldContent('meta_title')) ?: trim($__env->yieldContent('title'));
        $utilityTitle = $utilityTitle ?: ($site_settings['site_name'] ?? 'WebApp Bắc Ninh');
        $utilityDescription = trim($__env->yieldContent('meta_description')) ?: ($site_settings['site_description'] ?? 'Thiết kế web theo ngành nghề, chuẩn SEO, dễ dùng và tối ưu chuyển đổi.');
        $utilityKeywords = trim($__env->yieldContent('meta_keywords')) ?: ($site_settings['meta_keywords'] ?? 'thiết kế web, web Bắc Ninh, website bán hàng, landing page');
        $utilityImage = trim($__env->yieldContent('meta_image')) ?: asset($site_settings['default_og_image'] ?? 'images/default-og.jpg');
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $utilityTitle }}</title>
    <meta name="description" content="{{ $utilityDescription }}">
    <meta name="keywords" content="{{ $utilityKeywords }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $site_settings['site_name'] ?? 'WebApp Bắc Ninh' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $utilityTitle }}">
    <meta property="og:description" content="{{ $utilityDescription }}">
    <meta property="og:image" content="{{ $utilityImage }}">
    <meta property="og:image:alt" content="{{ $utilityTitle }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $utilityTitle }}">
    <meta name="twitter:description" content="{{ $utilityDescription }}">
    <meta name="twitter:image" content="{{ $utilityImage }}">
    <link rel="icon" href="{{ asset($site_settings['site_favicon'] ?? 'images/webapp.svg') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset($site_settings['site_favicon'] ?? 'images/webapp.svg') }}">
    <link rel="stylesheet" href="{{ asset('fonts/filament/filament/inter/index.css') }}">
    <style>body { font-family: 'Inter Variable', 'Inter', sans-serif !important; } .ls-1 { letter-spacing: 0.5px; }</style>
    @vite('resources/scss/app-user.scss')
    @stack('head')
</head>
<body data-toast="{{ session('success') }}">
    @include('partials.frontend.header')
    <main class="min-vh-100">
        @if(session('success')) <div class="alert alert-success text-center m-0">{{ session('success') }}</div> @endif
        @if(session('warning')) <div class="alert alert-warning text-center m-0">{{ session('warning') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger text-center m-0">{{ session('error') }}</div> @endif
        @yield('content')
    </main>
    @include('partials.frontend.footer')
    @vite('resources/js/app-user.js')
    @stack('scripts')
</body>
</html>
