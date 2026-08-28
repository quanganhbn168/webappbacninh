@extends('layouts.utility')

@section('title', 'Tạo mã QR Ngân Hàng - VietQR Chuyển Khoản Nhanh - WebApp Bắc Ninh')
@section('meta_description', 'Công cụ tạo mã QR chuyển khoản ngân hàng VietQR tự động. Hỗ trợ tất cả ngân hàng Việt Nam (VCB, MB, Tech... - VietQR). Chính xác, an toàn, có logo.')

@section('content')
@push('head')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
<style>
    .select2-container .select2-selection--single { height: 38px; }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered { line-height: 36px; }
</style>
@endpush

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h1 class="h4 mb-0"><i class="fas fa-money-bill-wave mr-2"></i>Tạo Mã QR Chuyển Khoản (VietQR)</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Input Column -->
                        <div class="col-md-7 border-right">
                            <form id="bankForm">
                                <div class="form-group">
                                    <label class="font-weight-bold">Ngân hàng thụ hưởng <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="bankId" style="width: 100%;">
                                        <option value="">-- Chọn ngân hàng --</option>
                                        @foreach($banks as $code => $bank)
                                            <option value="{{ $code }}" data-bin="{{ $bank['bin'] }}" data-logo="{{ $bank['logo'] }}">
                                                {{ $bank['short_name'] }} - {{ $bank['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Số tài khoản <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="bankAccount" placeholder="Ví dụ: 1903..." required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Tên chủ tài khoản (Viết hoa, không dấu)</label>
                                    <input type="text" class="form-control text-uppercase" id="bankName" placeholder="NGUYEN VAN A">
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Số tiền (VNĐ)</label>
                                    <input type="number" class="form-control" id="bankAmount" placeholder="Để trống nếu muốn tự nhập khi quét">
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Nội dung chuyển khoản</label>
                                    <input type="text" class="form-control" id="bankContent" placeholder="Ví dụ: Thanh toan tien com">
                                </div>

                                <button type="button" class="btn btn-success btn-block font-weight-bold mt-4" onclick="generateBankQR()">
                                    <i class="fas fa-qrcode mr-2"></i> TẠO MÃ NGÂN HÀNG
                                </button>
                            </form>
                        </div>

                        <!-- Preview Column -->
                        <div class="col-md-5 text-center d-flex flex-column justify-content-center align-items-center bg-light rounded py-4">
                            <h5 class="mb-3 text-muted">Mã VietQR Của Bạn</h5>
                            <div id="bankQrcode" class="bg-white p-3 shadow-sm rounded mb-3 d-flex align-items-center justify-content-center" style="min-height: 400px; width: 100%; max-width: 350px;">
                                <div class="text-muted text-center">
                                    <i class="fas fa-university fa-3x mb-3 text-secondary"></i><br>
                                    Nhập thông tin bên trái để tạo mã
                                </div>
                            </div>
                            <button class="btn btn-primary" onclick="downloadBankQR()"><i class="fas fa-download mr-1"></i> Tải Mã Về Máy</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <h4><i class="fas fa-shield-alt text-success"></i> VietQR Chuẩn Napas</h4>
                <p>Mã QR được tạo ra tuẩn thủ tiêu chuẩn VietQR của Napas. Hỗ trợ quét bằng tất cả các ứng dụng ngân hàng (Mobile Banking) và ví điện tử (MoMo, ZaloPay...) tại Việt Nam.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ === 'undefined') return;

        $(document).ready(function() {
            // Init Select2
            if($.fn.select2) {
                $('#bankId').select2({
                    theme: 'bootstrap4',
                    templateResult: formatBankOption,
                    templateSelection: formatBankOption
                });
            }

            // Events
            $('#bankId, #bankAccount, #bankName, #bankAmount, #bankContent').on('change select2:select', function() {
                // Auto update if enough info
                if($('#bankId').val() && $('#bankAccount').val()) {
                    generateBankQR();
                }
            });
        });
    });

    function formatBankOption(state) {
        if (!state.id) return state.text;
        var logo = $(state.element).data('logo');
        if(!logo) return state.text;
        return $('<span><img src="' + logo + '" style="height:20px; width:auto; margin-right:10px; object-fit: contain;" /> ' + state.text + '</span>');
    }

    function generateBankQR() {
        var bankCode = $('#bankId').val();
        var account = $('#bankAccount').val();
        var template = 'print'; // compact2, compact, qr_only, print
        var amount = $('#bankAmount').val() || 0;
        var content = $('#bankContent').val() || '';
        var name = $('#bankName').val() || '';

        if(!bankCode || !account) {
            // alert('Vui lòng chọn ngân hàng và nhập số tiêu khoản');
            return;
        }

        $('#bankQrcode').html('<div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div>');

        // URL format: https://img.vietqr.io/image/<BANK_CODE>-<ACCOUNT_NO>-<TEMPLATE>.png
        var url = `https://img.vietqr.io/image/${bankCode}-${account}-${template}.png?amount=${amount}&addInfo=${encodeURIComponent(content)}&accountName=${encodeURIComponent(name)}`;
        
        // Use Image object to preload
        var img = new Image();
        img.onload = function() {
            $('#bankQrcode').html(`<img src="${url}" class="img-fluid border shadow-sm" id="vietqrInfoImg" alt="VietQR" style="max-height:450px;">`);
        };
        img.onerror = function() {
            $('#bankQrcode').html('<div class="text-danger">Lỗi tạo mã. Vui lòng kiểm tra lại thông tin.</div>');
        };
        img.src = url;
    }

    function downloadBankQR() {
        var img = document.getElementById('vietqrInfoImg');
        if(img && img.src) {
            // Open in new tab is safest for cross-origin images without proxy
            window.open(img.src, '_blank');
        } else {
             alert('Vui lòng tạo mã QR trước!');
        }
    }
</script>
@endpush
@endsection
