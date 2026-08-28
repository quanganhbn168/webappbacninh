@extends('layouts.utility')

@section('title', 'Thanh toán SePay - WebApp Bắc Ninh')
@section('meta_description', 'Thanh toán qua chuyển khoản ngân hàng với SePay')

@push('head')
<style>
    .qr-container {
        background: linear-gradient(135deg, #00a651 0%, #007a3d 100%);
        border-radius: 20px;
        padding: 30px;
        color: white;
    }
    .qr-code {
        background: white;
        border-radius: 15px;
        padding: 20px;
        display: inline-block;
    }
    .bank-info {
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 15px;
        margin-top: 20px;
    }
    .copy-btn {
        cursor: pointer;
        transition: all 0.2s;
    }
    .copy-btn:hover {
        opacity: 0.8;
    }
    .status-checking {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            {{-- Header --}}
            <div class="text-center mb-4">
                <h1 class="display-6 fw-bold text-dark mb-2">
                    <i class="fas fa-qrcode text-success me-2"></i>
                    Thanh toán SePay
                </h1>
                <p class="text-muted">Quét mã QR hoặc chuyển khoản thủ công</p>
            </div>

            {{-- QR Container --}}
            <div class="qr-container text-center">
                {{-- QR Code --}}
                <div class="qr-code mb-4">
                    <img src="https://img.vietqr.io/image/{{ $bank }}-{{ $account }}-compact2.png?amount={{ $amount }}&addInfo={{ urlencode($code) }}&accountName={{ urlencode($name) }}" 
                         alt="QR Code" 
                         style="max-width: 250px; height: auto;">
                </div>

                {{-- Amount --}}
                <div class="mb-3">
                    <small class="opacity-75">Số tiền thanh toán</small>
                    <h2 class="fw-bold mb-0">{{ number_format($amount) }} đ</h2>
                </div>

                {{-- Bank Info --}}
                <div class="bank-info text-start">
                    <div class="row g-2">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="opacity-75">Ngân hàng:</span>
                                <strong>{{ $bank }}</strong>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="opacity-75">Số tài khoản:</span>
                                <div>
                                    <strong id="accountNumber">{{ $account }}</strong>
                                    <i class="fas fa-copy ms-2 copy-btn" onclick="copyToClipboard('{{ $account }}', this)"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="opacity-75">Chủ tài khoản:</span>
                                <strong>{{ $name }}</strong>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="opacity-75">Nội dung CK:</span>
                                <div>
                                    <strong class="text-warning" id="paymentCode">{{ $code }}</strong>
                                    <i class="fas fa-copy ms-2 copy-btn" onclick="copyToClipboard('{{ $code }}', this)"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Warning --}}
                <div class="alert alert-warning mt-3 mb-0 text-dark small">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    <strong>Lưu ý:</strong> Nhập đúng nội dung chuyển khoản <strong>{{ $code }}</strong> để hệ thống tự động xác nhận.
                </div>
            </div>

            {{-- Status Check --}}
            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="status-checking mb-2">
                        <i class="fas fa-sync-alt fa-spin text-primary fa-2x"></i>
                    </div>
                    <p class="mb-1 fw-bold">Đang chờ thanh toán...</p>
                    <small class="text-muted">Hệ thống sẽ tự động xác nhận khi nhận được tiền</small>
                    
                    <div class="mt-3">
                        <a href="{{ $return_url ?? url('/') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="mt-4">
                <h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-info me-2"></i>Hướng dẫn thanh toán</h6>
                <ol class="text-muted small">
                    <li>Mở app ngân hàng trên điện thoại</li>
                    <li>Chọn "Quét QR" hoặc "Chuyển khoản"</li>
                    <li>Quét mã QR hoặc nhập thông tin tài khoản</li>
                    <li>Nhập <strong>đúng nội dung CK: {{ $code }}</strong></li>
                    <li>Xác nhận chuyển khoản</li>
                    <li>Hệ thống sẽ tự động xác nhận trong vài giây</li>
                </ol>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const originalClass = btn.className;
        btn.className = 'fas fa-check ms-2 text-success';
        setTimeout(() => {
            btn.className = originalClass;
        }, 2000);
    });
}

// Poll for payment status (optional - you can implement WebSocket for real-time)
let checkInterval;
function checkPaymentStatus() {
    fetch('/api/payment/sepay/status/{{ $order_id }}')
        .then(r => r.json())
        .then(data => {
            if (data.status === 'completed') {
                clearInterval(checkInterval);
                window.location.href = '{{ $return_url ?? url("/payment/result") }}?status=success&order_id={{ $order_id }}';
            }
        })
        .catch(() => {});
}

// Check every 5 seconds
checkInterval = setInterval(checkPaymentStatus, 5000);
</script>
@endpush
