@extends('layouts.tool')

@section('tool-content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-dark">
                    <h1 class="h4 mb-0"><x-icon name="qr-code" class="me-2" />Tạo Mã QR Code (Link/Text/Wifi)</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Input Column -->
                        <div class="col-md-7 border-right">
                            <form id="qrForm">
                                <div class="mb-3">
                                    <label class="fw-bold">Nội dung QR Code</label>
                                    <textarea class="form-control" id="qrText" rows="3" placeholder="Nhập văn bản, đường dẫn website, hoặc nội dung bất kỳ...">https://webappbacninh.vn</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Màu nền (Background)</label>
                                            <input type="color" class="form-control form-control-color w-100" id="qrBgColor" value="#ffffff">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Màu mã (Foreground)</label>
                                            <input type="color" class="form-control form-control-color w-100" id="qrColor" value="#000000">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Kích thước (px)</label>
                                            <select class="form-select" id="qrSize">
                                                <option value="200">200 x 200</option>
                                                <option value="300" selected>300 x 300</option>
                                                <option value="400">400 x 400</option>
                                                <option value="500">500 x 500</option>
                                                <option value="1000">1000 x 1000 (HD)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Logo (Optional)</label>
                                            <label class="form-label visually-hidden" for="qrLogo">Chọn ảnh logo</label>
                                            <input type="file" class="form-control" id="qrLogo" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary w-100 d-md-none mt-3" onclick="generateQR()">Tạo Mã</button>
                            </form>
                        </div>

                        <!-- Preview Column -->
                        <div class="col-md-5 text-center d-flex flex-column justify-content-center align-items-center bg-light rounded py-4">
                            <h5 class="mb-3 text-muted">Xem trước</h5>
                            <div id="qrcode" class="bg-white p-3 shadow-sm rounded mb-3"></div>
                            
                            <div class="mt-3">
                                <button class="btn btn-success btn-lg" onclick="downloadQR()">
                                    <x-icon name="download" class="me-1" /> Tải xuống ảnh PNG
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <h4><x-icon name="info" class="text-primary" /> Thông tin thêm</h4>
                <p>Công cụ này dành cho việc tạo mã QR chứa thông tin văn bản, trang web (URL), email, hoặc Wifi. Nếu bạn muốn tạo mã QR chuyển khoản ngân hàng, vui lòng sử dụng công cụ <a href="{{ route('tools.bank-qr') }}">Tạo QR Ngân Hàng</a>.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- EasyQRCodeJS Library -->
<script src="/vendor/easy-qrcode-4.5.0.min.js"></script>
<script>
    var qrcode = null;
    var logoFile = null;

    document.addEventListener('DOMContentLoaded', function () {
        generateQR();

        ['qrText', 'qrBgColor', 'qrColor', 'qrSize'].forEach(function (id) {
            var input = document.getElementById(id);
            input.addEventListener('input', generateQR);
            input.addEventListener('change', generateQR);
        });

        document.getElementById('qrLogo').addEventListener('change', function (event) {
            var file = event.target.files[0];
            if (!file) {
                logoFile = null;
                generateQR();
                return;
            }
            var reader = new FileReader();
            reader.onload = function (loaded) {
                logoFile = loaded.target.result;
                generateQR();
            };
            reader.readAsDataURL(file);
        });
    });

    function generateQR() {
        document.getElementById('qrcode').replaceChildren();

        var text = document.getElementById('qrText').value || 'https://webappbacninh.vn';
        var size = parseInt(document.getElementById('qrSize').value, 10);
        var colorDark = document.getElementById('qrColor').value;
        var colorLight = document.getElementById('qrBgColor').value;

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
