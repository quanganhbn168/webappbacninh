@extends('layouts.master')

@section('title', 'Thanh toán - WebApp Bắc Ninh')
@section('meta_description', 'Chọn phương thức thanh toán phù hợp: VNPay, MoMo, ZaloPay')

@push('head')
<style>
    .payment-method {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent !important;
    }
    .payment-method:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .payment-method.active {
        border-color: #667eea !important;
        background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);
    }
    .payment-method .provider-logo {
        height: 40px;
        object-fit: contain;
    }
    .order-summary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        padding: 30px;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            {{-- Header --}}
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-dark mb-3">
                    <i class="fas fa-credit-card text-primary me-2"></i>
                    Thanh toán
                </h1>
                <p class="text-muted lead">
                    Chọn phương thức thanh toán phù hợp
                </p>
            </div>

            <div class="row g-4">
                {{-- Left: Payment Methods --}}
                <div class="col-lg-7">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="fas fa-wallet text-primary me-2"></i>
                                Phương thức thanh toán
                            </h5>

                            <form action="{{ route('payment.process') }}" method="POST" id="paymentForm">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order['id'] }}">
                                <input type="hidden" name="amount" value="{{ $order['amount'] }}">
                                <input type="hidden" name="description" value="{{ $order['description'] }}">
                                <input type="hidden" name="provider" id="selectedProvider" value="{{ $defaultProvider }}">

                                <div class="row g-3 mb-4">
                                    @foreach($providers as $provider)
                                    <div class="col-md-6">
                                        <div class="payment-method card h-100 p-3 {{ $provider['name'] === $defaultProvider ? 'active' : '' }}"
                                             data-provider="{{ $provider['name'] }}"
                                             onclick="selectProvider('{{ $provider['name'] }}')">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <i class="{{ $provider['icon'] }} fa-2x" style="color: {{ $provider['color'] }}"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{ $provider['display_name'] }}</h6>
                                                    <small class="text-muted">{{ $provider['description'] }}</small>
                                                </div>
                                                <div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" 
                                                               name="provider_radio"
                                                               {{ $provider['name'] === $defaultProvider ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                {{-- Customer info --}}
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-user text-info me-2"></i>
                                    Thông tin khách hàng (tùy chọn)
                                </h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <input type="email" name="customer_email" class="form-control" placeholder="Email">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="tel" name="customer_phone" class="form-control" placeholder="Số điện thoại">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                                    <i class="fas fa-lock me-2"></i>
                                    Thanh toán ngay
                                </button>
                            </form>

                            @if(session('error'))
                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ session('error') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right: Order Summary --}}
                <div class="col-lg-5">
                    <div class="order-summary">
                        <h5 class="fw-bold mb-4">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Thông tin đơn hàng
                        </h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="opacity-75">Mã đơn hàng:</span>
                            <strong>{{ $order['id'] }}</strong>
                        </div>

                        <hr class="opacity-25">

                        <div class="d-flex justify-content-between mb-2">
                            <span class="opacity-75">Mô tả:</span>
                            <span>{{ Str::limit($order['description'], 30) }}</span>
                        </div>

                        <hr class="opacity-25">

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">Tổng tiền:</span>
                            <span class="h3 fw-bold mb-0">{{ number_format($order['amount']) }} đ</span>
                        </div>

                        <div class="mt-4 pt-3 border-top border-white border-opacity-25">
                            <div class="d-flex align-items-center small opacity-75">
                                <i class="fas fa-shield-alt me-2"></i>
                                Giao dịch được bảo mật bởi SSL
                            </div>
                        </div>
                    </div>

                    {{-- Demo note --}}
                    <div class="alert alert-info mt-3 small">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Demo mode:</strong> Đây là trang thanh toán mẫu để demo Payment Gateway Interface Pattern.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectProvider(name) {
    document.getElementById('selectedProvider').value = name;
    
    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('active');
        el.querySelector('input[type="radio"]').checked = false;
    });
    
    const selected = document.querySelector(`[data-provider="${name}"]`);
    selected.classList.add('active');
    selected.querySelector('input[type="radio"]').checked = true;
}
</script>
@endpush
