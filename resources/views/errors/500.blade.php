@extends('layouts.basic')
@section('error-page', '1')
@section('title', 'Hệ thống đang gặp sự cố | WebApp Bắc Ninh')
@section('content')
<section class="min-vh-100 d-flex flex-column align-items-center justify-content-center px-4 py-5 text-center">
    <a href="/" class="mb-5 fs-5 fw-bold text-dark">WEBAPP BẮC NINH</a>
    <p class="error-page__code fw-bold text-primary mb-0">500</p>
    <h1 class="mt-4 h2 fw-bold">Hệ thống đang gặp sự cố</h1>
    <p class="mt-3 text-secondary" style="max-width: 32rem">Vui lòng thử lại sau ít phút. Cảm ơn bạn đã kiên nhẫn.</p>
    <a href="/" class="btn btn-primary btn-lg mt-4">Về trang chủ →</a>
</section>
@endsection
