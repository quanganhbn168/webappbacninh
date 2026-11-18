@extends('layouts.tool')

@section('tool-content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h1 class="h4 mb-0"><x-icon name="banknote" class="me-2" />Tạo Mã QR Chuyển Khoản (VietQR)</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Input Column -->
                        <div class="col-md-7 border-right">
                            <form id="bankForm">
                                <div class="mb-3">
                                    <label class="fw-bold">Ngân hàng thụ hưởng <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="bankId" style="width: 100%;">
                                        <option value="">-- Chọn ngân hàng --</option>
                                        @foreach($banks as $code => $bank)
                                            <option value="{{ $code }}" data-bin="{{ $bank['bin'] }}" data-logo="{{ $bank['logo'] }}">
                                                {{ $bank['short_name'] }} - {{ $bank['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold">Số tài khoản <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="bankAccount" placeholder="Ví dụ: 1903..." required>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold">Tên chủ tài khoản (Viết hoa, không dấu)</label>
                                    <input type="text" class="form-control text-uppercase" id="bankName" placeholder="NGUYEN VAN A">
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold">Số tiền (VNĐ)</label>
                                    <input type="number" class="form-control" id="bankAmount" placeholder="Để trống nếu muốn tự nhập khi quét">
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold">Nội dung chuyển khoản</label>
                                    <input type="text" class="form-control" id="bankContent" placeholder="Ví dụ: Thanh toan tien com">
                                </div>

                                <button type="button" class="btn btn-success w-100 fw-bold mt-4" data-action="generate">
                                    <x-icon name="qr-code" class="me-2" /> TẠO MÃ NGÂN HÀNG
                                </button>
                            </form>
                        </div>

                        <!-- Preview Column -->
                        <div class="col-md-5 text-center d-flex flex-column justify-content-center align-items-center bg-light rounded py-4">
                            <h5 class="mb-3 text-muted">Mã VietQR Của Bạn</h5>
                            <div id="bankQrcode" class="bg-white p-3 shadow-sm rounded mb-3 d-flex align-items-center justify-content-center" style="min-height: 400px; width: 100%; max-width: 350px;">
                                <div class="text-muted text-center">
                                    <x-icon name="landmark" class="icon-xl mb-3 text-secondary" /><br>
                                    Nhập thông tin bên trái để tạo mã
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" data-action="download"><x-icon name="download" class="me-1" /> Tải Mã Về Máy</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <h4><x-icon name="shield-check" class="text-success" /> VietQR Chuẩn Napas</h4>
                <p>Mã QR được tạo ra tuẩn thủ tiêu chuẩn VietQR của Napas. Hỗ trợ quét bằng tất cả các ứng dụng ngân hàng (Mobile Banking) và ví điện tử (MoMo, ZaloPay...) tại Việt Nam.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @vite('resources/js/tools/bank-qr.js')
@endpush
@endsection
