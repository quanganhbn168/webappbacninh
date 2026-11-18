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
                                <button type="button" class="btn btn-primary w-100 d-md-none mt-3" data-action="generate">Tạo Mã</button>
                            </form>
                        </div>

                        <!-- Preview Column -->
                        <div class="col-md-5 text-center d-flex flex-column justify-content-center align-items-center bg-light rounded py-4">
                            <h5 class="mb-3 text-muted">Xem trước</h5>
                            <div id="qrcode" class="bg-white p-3 shadow-sm rounded mb-3"></div>
                            
                            <div class="mt-3">
                                <button type="button" class="btn btn-success btn-lg" data-action="download">
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
    <script src="/vendor/easy-qrcode-4.5.0.min.js"></script>
    @vite('resources/js/tools/qr-code.js')
@endpush
@endsection
