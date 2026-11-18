@extends('layouts.basic')

@section('title', 'Thanh toán SePay - WebApp Bắc Ninh')
@section('meta_description', 'Thanh toán qua chuyển khoản ngân hàng với SePay')

@section('content')
<div class="container py-5" data-status-url="{{ route('payment.sepay.status', $order_id) }}" data-success-url="{{ $return_url.(str_contains($return_url, '?') ? '&' : '?').'order_id='.urlencode($order_id) }}">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            {{-- Header --}}
            <div class="text-center mb-4">
                <h1 class="display-6 fw-bold text-dark mb-2">
                    <x-icon name="qr-code" class="text-success me-2" />
                    Thanh toán SePay
                </h1>
                <p class="text-muted">Quét mã QR hoặc chuyển khoản thủ công</p>
            </div>

            {{-- QR Container --}}
            <div class="panel-dark text-center">
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
                                    <button type="button" class="btn btn-link btn-sm p-0 ms-2 copy-btn" data-copy="{{ $account }}" aria-label="Sao chép số tài khoản"><x-icon name="copy" class="icon-sm" /></button>
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
                                    <button type="button" class="btn btn-link btn-sm p-0 ms-2 copy-btn" data-copy="{{ $code }}" aria-label="Sao chép nội dung chuyển khoản"><x-icon name="copy" class="icon-sm" /></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Warning --}}
                <div class="alert alert-warning mt-3 mb-0 text-dark small">
                    <x-icon name="triangle-alert" class="me-1" />
                    <strong>Lưu ý:</strong> Nhập đúng nội dung chuyển khoản <strong>{{ $code }}</strong> để hệ thống tự động xác nhận.
                </div>
            </div>

            {{-- Status Check --}}
            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="status-checking mb-2">
                        <x-icon name="refresh-cw" class="icon-spin text-primary icon-lg" />
                    </div>
                    <p class="mb-1 fw-bold">Đang chờ thanh toán...</p>
                    <small class="text-muted">Hệ thống sẽ tự động xác nhận khi nhận được tiền</small>
                    
                    <div class="mt-3">
                        <a href="{{ $return_url ?? url('/') }}" class="btn btn-outline-secondary btn-sm">
                            <x-icon name="arrow-left" class="me-1" /> Quay lại
                        </a>
                    </div>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="mt-4">
                <h6 class="fw-bold mb-3"><x-icon name="info" class="text-info me-2" />Hướng dẫn thanh toán</h6>
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
    @vite('resources/js/payment/sepay-qr.js')
@endpush
