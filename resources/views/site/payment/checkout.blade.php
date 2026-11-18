@extends('layouts.basic')

@section('title', 'Thanh toán - WebApp Bắc Ninh')
@section('meta_description', 'Chọn phương thức thanh toán phù hợp: VNPay, MoMo, ZaloPay')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            {{-- Header --}}
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-dark mb-3">
                    <x-icon name="credit-card" class="text-primary me-2" />
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
                                <x-icon name="wallet" class="text-primary me-2" />
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
                                             data-provider="{{ $provider['name'] }}">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <x-icon name="circle-help" class="{{ $provider['icon'] }} icon-lg" style="color: {{ $provider['color'] }}" />
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
                                    <x-icon name="user" class="text-info me-2" />
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
                                    <x-icon name="lock" class="me-2" />
                                    Thanh toán ngay
                                </button>
                            </form>

                            @if(session('error'))
                            <div class="alert alert-danger mt-3">
                                <x-icon name="triangle-alert" class="me-2" />
                                {{ session('error') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right: Order Summary --}}
                <div class="col-lg-5">
                    <div class="panel-dark">
                        <h5 class="fw-bold mb-4">
                            <x-icon name="shopping-cart" class="me-2" />
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
                                <x-icon name="shield-check" class="me-2" />
                                Giao dịch được bảo mật bởi SSL
                            </div>
                        </div>
                    </div>

                    {{-- Demo note --}}
                    <div class="alert alert-info mt-3 small">
                        <x-icon name="info" class="me-2" />
                        <strong>Demo mode:</strong> Đây là trang thanh toán mẫu để demo Payment Gateway Interface Pattern.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/payment/checkout.js')
@endpush
