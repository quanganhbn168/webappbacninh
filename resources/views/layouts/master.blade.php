<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    {{-- SEO cơ bản --}}
    <title>@yield('title', 'WebApp Bắc Ninh - Thiết kế website chuyên nghiệp')</title>
    <meta name="description" content="@yield('meta_description', 'Thiết kế web theo ngành nghề, chuẩn SEO, dễ dùng và tối ưu chuyển đổi.')">
    <meta name="keywords" content="@yield('meta_keywords', 'thiết kế web, web bắc ninh, website bán hàng, web tuyển sinh, landing page')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}" />

    {{-- Open Graph (Facebook) --}}
    <meta property="og:title" content="@yield('title', 'WebApp Bắc Ninh')" />
    <meta property="og:description" content="@yield('meta_description', 'Thiết kế web theo ngành nghề, chuẩn SEO')" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="@yield('meta_image', asset('images/og-image.jpg'))" />

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('images/webapp.svg') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset('images/webapp.svg') }}" />

    {{-- Preload fonts nếu có --}}
    {{-- <link rel="preload" as="font" href="{{ asset('fonts/your-font.woff2') }}" type="font/woff2" crossorigin="anonymous"> --}}

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
