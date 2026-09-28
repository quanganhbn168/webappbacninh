@extends('layouts.basic')
@section('error-page', '1')
@section('title', 'Website đang bảo trì | WebApp Bắc Ninh')
@section('content')
<section class="min-vh-100 d-flex flex-column align-items-center justify-content-center px-4 py-5 text-center">
    <a href="/" class="mb-5 fs-5 fw-bold text-dark">WEBAPP <span class="text-primary">BẮC NINH</span></a>
    <x-icon name="wrench" class="icon-2xl text-primary" />
    <h1 class="mt-4 h2 fw-bold">Website đang được nâng cấp</h1>
    <p class="mt-3 text-secondary" style="max-width: 32rem">Chúng tôi đang cập nhật hệ thống để phục vụ tốt hơn. Vui lòng quay lại sau ít phút.</p>
    <a href="/" class="btn btn-primary btn-lg mt-4">Tải lại trang</a>
</section>
@endsection
