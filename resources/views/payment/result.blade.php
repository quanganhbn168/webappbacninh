@extends('layouts.master')

@section('title', $result['success'] ? 'Thanh toán thành công' : 'Thanh toán thất bại')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-body p-5 text-center">
                    
                    @if($result['success'])
                        {{-- Success --}}
                        <div class="mb-4">
                            <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold text-success mb-3">Thanh toán thành công!</h2>
                        <p class="text-muted mb-4">
                            Cảm ơn bạn đã thanh toán. Giao dịch đã được xử lý thành công.
                        </p>
                    @else
                        {{-- Failed --}}
                        <div class="mb-4">
                            <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                <i class="fas fa-times-circle text-danger" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold text-danger mb-3">Thanh toán thất bại</h2>
                        <p class="text-muted mb-4">
                            {{ $result['message'] ?? 'Đã có lỗi xảy ra. Vui lòng thử lại sau.' }}
                        </p>
                    @endif

                    {{-- Transaction Details --}}
                    @if(!empty($result['transaction_id']) || !empty($result['order_id']))
                    <div class="bg-light rounded-3 p-4 mb-4 text-start">
                        @if(!empty($result['order_id']))
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Mã đơn hàng:</span>
                            <strong>{{ $result['order_id'] }}</strong>
                        </div>
                        @endif

                        @if(!empty($result['transaction_id']))
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Mã giao dịch:</span>
                            <strong>{{ $result['transaction_id'] }}</strong>
                        </div>
                        @endif

                        @if(!empty($result['amount']))
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Số tiền:</span>
                            <strong class="text-success">{{ number_format($result['amount']) }} đ</strong>
                        </div>
                        @endif

                        @if(!empty($result['bank_code']))
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Ngân hàng:</span>
                            <strong>{{ $result['bank_code'] }}</strong>
                        </div>
                        @endif

                        @if(!empty($result['status_label']))
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Trạng thái:</span>
                            <span class="badge bg-{{ $result['success'] ? 'success' : 'danger' }}">
                                {{ $result['status_label'] }}
                            </span>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Actions --}}
                    <div class="d-grid gap-2">
                        <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-home me-2"></i>
                            Về trang chủ
                        </a>
                        @if(!$result['success'])
                        <a href="{{ route('payment.checkout') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-2"></i>
                            Thử lại
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
