<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    {{-- SEO cơ bản --}}
    <title>@yield('meta_title', $site_settings['site_name'] ?? 'WebApp Bắc Ninh')</title>
    <meta name="title" content="@yield('meta_title', $site_settings['site_name'] ?? 'WebApp Bắc Ninh')" />
    <meta name="description" content="@yield('meta_description', 'Thiết kế web theo ngành nghề, chuẩn SEO, dễ dùng và tối ưu chuyển đổi.')" />
    <meta name="keywords" content="@yield('meta_keywords', 'thiết kế web, web bắc ninh, website bán hàng, web tuyển sinh, landing page')" />
    <meta name="robots" content="index, follow" />
    <meta name="revisit-after" content="1 days" />
    <meta name="author" content="{{ $site_settings['site_name'] ?? 'WebApp Bắc Ninh' }}" />
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="image_src" href="@yield('meta_image', asset($site_settings['default_og_image'] ?? 'images/default-og.jpg'))" />

    {{-- Open Graph (Facebook) --}}
    <meta property="og:site_name" content="{{ $site_settings['site_name'] ?? 'WebApp Bắc Ninh' }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="@yield('meta_title', $site_settings['site_name'] ?? 'WebApp Bắc Ninh')" />
    <meta property="og:description" content="@yield('meta_description', 'Thiết kế web theo ngành nghề, chuẩn SEO, dễ dùng và tối ưu chuyển đổi.')" />
    <meta property="og:image" content="@yield('meta_image', asset($site_settings['default_og_image'] ?? 'images/default-og.jpg'))" />
    <meta property="og:image:alt" content="@yield('meta_title', $site_settings['site_name'] ?? 'WebApp Bắc Ninh')" />

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('meta_title', $site_settings['site_name'] ?? 'WebApp Bắc Ninh')">
    <meta name="twitter:description" content="@yield('meta_description', 'Thiết kế web theo ngành nghề, chuẩn SEO, dễ dùng và tối ưu chuyển đổi.')">
    <meta name="twitter:image" content="@yield('meta_image', asset($site_settings['default_og_image'] ?? 'images/default-og.jpg'))">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset($site_settings['site_favicon'] ?? 'images/webapp.svg') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset($site_settings['site_favicon'] ?? 'images/webapp.svg') }}" />

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif !important; }
        .ls-1 { letter-spacing: 0.5px; }
    </style>

    {{-- CSS --}}
    @vite('resources/scss/app-user.scss')

    {{-- Custom head thêm --}}
    @stack('head')
</head>
<body data-toast="{{ session('success') }}">

    {{-- Header --}}
    @include('partials.frontend.header')

    {{-- Content --}}
    <main class="min-vh-100">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success text-center m-0">{{ session('success') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning text-center m-0">{{ session('warning') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger text-center m-0">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.frontend.footer')

    {{-- Script --}}
    @vite('resources/js/app-user.js')

    {{-- Script thêm --}}
    @stack('scripts')
</body>
</html>
