@extends('layouts.utility')

@section('title', 'Đã hủy thanh toán')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-body p-5 text-center">
                    
                    <div class="mb-4">
                        <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                        </div>
                    </div>

                    <h2 class="fw-bold text-warning mb-3">Đã hủy thanh toán</h2>
                    <p class="text-muted mb-4">
                        Bạn đã hủy giao dịch thanh toán. Đơn hàng của bạn vẫn được lưu lại.
                    </p>

                    <div class="d-grid gap-2">
                        <a href="{{ route('payment.checkout') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-redo me-2"></i>
                            Thử lại
                        </a>
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>
                            Về trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
