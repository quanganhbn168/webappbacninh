@extends('layouts.tool')

@section('tool-content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <h1 class="display-4 fw-bold text-primary mb-3">Vòng Quay "Thần Thánh"</h1>
            <p class="lead text-muted mb-5">Đừng để câu hỏi "Trưa nay ăn gì?" làm chia rẽ tình đồng nghiệp!</p>

            <!-- Controls -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Chọn tình trạng ví tiền của bạn:</h5>
                    <div class="btn-group">
                        <label class="btn btn-outline-success active" data-mode="rich">
                            <input type="radio" name="mode" id="option1" checked> 💰 Đầu tháng (Giàu)
                        </label>
                        <label class="btn btn-outline-secondary" data-mode="normal">
                            <input type="radio" name="mode" id="option2"> 😐 Giữa tháng (Đủ)
                        </label>
                        <label class="btn btn-outline-danger" data-mode="poor">
                            <input type="radio" name="mode" id="option3"> 💸 Cuối tháng (Nghèo)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Wheel Container -->
            <div class="position-relative d-inline-block mb-5" style="max-width: 100%; overflow: hidden;">
                <!-- Arrow -->
                <div class="position-absolute" style="top: -10px; left: 50%; transform: translateX(-50%); z-index: 10; width: 0; height: 0; border-left: 20px solid transparent; border-right: 20px solid transparent; border-top: 40px solid #dc3545;"></div>
                
                <canvas id="canvas" width="500" height="500" style="width: 100%; max-width: 400px; height: auto;"></canvas>
                
                <!-- Spin Button Center (Optional style) -->
                <div class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%); width: 60px; height: 60px; background: #fff; border-radius: 50%; box-shadow: 0 0 10px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; font-weight: bold; cursor: pointer; user-select: none;" data-action="spin" role="button" tabindex="0">
                    QUAY
                </div>
            </div>

            <div class="mt-4">
                <button type="button" class="btn btn-primary btn-lg px-5 fw-bold shadow-lg spin-btn" data-action="spin">
                    <x-icon name="refresh-cw" class="me-2" /> QUAY NGAY
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Result Modal -->
<div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-5">
                <h3 class="text-uppercase text-muted mb-3">Trưa nay bạn sẽ ăn:</h3>
                <h1 class="display-4 fw-bold text-primary mb-4" id="resultName">...</h1>
                
                <div id="resultImageContainer" class="mb-4">
                    <!-- Meme will be inserted here -->
                </div>

                <div class="alert alert-light border border-light shadow-sm" id="resultDesc">
                    Chúc bạn ngon miệng!
                </div>

                <button type="button" class="btn btn-outline-primary mt-3" data-bs-dismiss="modal">Quay lại</button>
                <button type="button" class="btn btn-success mt-3 ms-2" data-action="share">
                    <x-icon name="brand-facebook" /> Khoe ngay
                </button>
            </div>
        </div>
    </div>
</div>

<template data-fallback-icon><x-icon name="utensils" class="icon-2xl text-secondary" /></template>

@push('scripts')
    @vite('resources/js/tools/food-wheel.js')
@endpush
@endsection
