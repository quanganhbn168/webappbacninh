@extends('layouts.plain')
@section('error-page', '1')
@section('title', 'Không tìm thấy trang | WebApp Bắc Ninh')
@section('content')
<section class="flex min-h-screen flex-col items-center justify-center px-6 py-16 text-center">
    <a href="/" class="mb-12 text-lg font-bold">WEBAPP BẮC NINH</a>
    <p class="text-8xl font-bold text-amber-500">404</p>
    <h1 class="mt-6 text-3xl font-bold">Không tìm thấy trang</h1>
    <p class="mt-4 max-w-lg text-gray-600">Trang bạn tìm có thể đã được chuyển hoặc không còn tồn tại.</p>
    <a href="/" class="mt-8 rounded-xl bg-amber-400 px-6 py-3 font-semibold text-gray-900">Về trang chủ →</a>
</section>
@endsection
