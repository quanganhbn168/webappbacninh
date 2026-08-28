@extends('layouts.utility')

@section('title', 'Tạo mã QR Code Online Miễn Phí - WebApp Bắc Ninh')
@section('meta_description', 'Công cụ tạo mã QR Code online miễn phí. Tạo QR Wifi, URL, Văn bản nhanh chóng, hỗ trợ chèn logo và tùy chỉnh màu sắc.')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h1 class="h4 mb-0"><i class="fas fa-qrcode mr-2"></i>Tạo Mã QR Code (Link/Text/Wifi)</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Input Column -->
                        <div class="col-md-7 border-right">
                            <form id="qrForm">
                                <div class="form-group">
                                    <label class="font-weight-bold">Nội dung QR Code</label>
                                    <textarea class="form-control" id="qrText" rows="3" placeholder="Nhập văn bản, đường dẫn website, hoặc nội dung bất kỳ...">https://webappbacninh.vn</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Màu nền (Background)</label>
                                            <input type="color" class="form-control" id="qrBgColor" value="#ffffff">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Màu mã (Foreground)</label>
                                            <input type="color" class="form-control" id="qrColor" value="#000000">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kích thước (px)</label>
                                            <select class="form-control" id="qrSize">
                                                <option value="200">200 x 200</option>
                                                <option value="300" selected>300 x 300</option>
                                                <option value="400">400 x 400</option>
                                                <option value="500">500 x 500</option>
                                                <option value="1000">1000 x 1000 (HD)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Logo (Optional)</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="qrLogo" accept="image/*">
                                                <label class="custom-file-label" for="qrLogo">Chọn ảnh logo...</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary btn-block d-md-none mt-3" onclick="generateQR()">Tạo Mã</button>
                            </form>
                        </div>

                        <!-- Preview Column -->
                        <div class="col-md-5 text-center d-flex flex-column justify-content-center align-items-center bg-light rounded py-4">
                            <h5 class="mb-3 text-muted">Xem trước</h5>
                            <div id="qrcode" class="bg-white p-3 shadow-sm rounded mb-3"></div>
                            
                            <div class="mt-3">
                                <button class="btn btn-success btn-lg" onclick="downloadQR()">
                                    <i class="fas fa-download mr-1"></i> Tải xuống ảnh PNG
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <h4><i class="fas fa-info-circle text-primary"></i> Thông tin thêm</h4>
                <p>Công cụ này dành cho việc tạo mã QR chứa thông tin văn bản, trang web (URL), email, hoặc Wifi. Nếu bạn muốn tạo mã QR chuyển khoản ngân hàng, vui lòng sử dụng công cụ <a href="{{ route('tools.bank-qr') }}">Tạo QR Ngân Hàng</a>.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- EasyQRCodeJS Library -->
<script src="https://cdn.jsdelivr.net/npm/easyqrcodejs@4.5.0/dist/easy.qrcode.min.js"></script>
<script>
    var qrcode = null;
    var logoFile = null;

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ === 'undefined') return;

        $(document).ready(function() {
            generateQR();

            // Event Listeners
            $('#qrText, #qrBgColor, #qrColor, #qrSize').on('input change', function() {
                generateQR();
            });

            // File Input Change
            $('#qrLogo').on('change', function(e) {
                var file = e.target.files[0];
                if (file) {
                    $(this).next('.custom-file-label').html(file.name);
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        logoFile = e.target.result;
                        generateQR();
                    }
                    reader.readAsDataURL(file);
                } else {
                    logoFile = null;
                    $(this).next('.custom-file-label').html('Chọn ảnh logo...');
                    generateQR();
                }
            });
        });
    });

    function generateQR() {
        // Clear previous
        $('#qrcode').html('');

        var text = $('#qrText').val() || 'https://webappbacninh.vn';
        var size = parseInt($('#qrSize').val());
        var colorDark = $('#qrColor').val();
        var colorLight = $('#qrBgColor').val();

        // Options
        var options = {
            text: text,
            width: size,
            height: size,
            colorDark : colorDark,
            colorLight : colorLight,
            correctLevel : QRCode.CorrectLevel.H, // High error correction for logo
            
            logo: logoFile,
            logoWidth: size * 0.2, // 20% of QR size
            logoHeight: size * 0.2,
            logoBackgroundColor: '#ffffff',
            logoBackgroundTransparent: false,
            
            quietZone: 10,
            quietZoneColor: "rgba(0,0,0,0)"
        };

        // Create
        try {
            qrcode = new QRCode(document.getElementById("qrcode"), options);
        } catch (e) {
            console.error(e);
        }
    }

    function downloadQR() {
        if (qrcode) {
            // Find canvas
            var canvas = document.querySelector('#qrcode canvas');
            if(canvas) {
                var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
                var link = document.createElement('a');
                link.download = 'qrcode-' + Date.now() + '.png';
                link.href = image;
                link.click();
            }
        }
    }
</script>
@endpush
@endsection
