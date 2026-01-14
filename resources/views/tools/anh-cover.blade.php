@extends('layouts.master')

@section('title', 'Công cụ lấy ảnh Cover Video (Thumbnail) - WebApp Bắc Ninh')
@section('meta_description', 'Công cụ miễn phí giúp lấy ảnh cover (thumbnail) chất lượng cao từ video YouTube, TikTok. Hỗ trợ tải về nhanh chóng.')
@section('meta_keywords', 'get thumbnail youtube, lấy ảnh cover tiktok, youtube thumbnail downloader, công cụ mmo')

@push('head')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body p-4 p-md-5" x-data="{
                    url: '',
                    isLoading: false,
                    result: null,
                    error: '',
                    async getInfo() {
                        if (!this.url.trim()) {
                            this.error = 'Vui lòng nhập một đường dẫn hợp lệ.';
                            return;
                        }
                        this.isLoading = true;
                        this.result = null;
                        this.error = '';

                        try {
                            const response = await fetch('/get-info', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                                body: JSON.stringify({ url: this.url })
                            });

                            const data = await response.json();

                            if (data.success) {
                                this.result = data;
                            } else {
                                this.error = data.message || 'Không thể phân tích link này. Vui lòng kiểm tra lại.';
                            }
                        } catch (e) {
                            this.error = 'Đã có lỗi xảy ra. Vui lòng thử lại sau.';
                        } finally {
                            this.isLoading = false;
                        }
                    }
                }">
                    <meta name="csrf-token" content="{{ csrf_token() }}">

                    {{-- Alerts --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Header --}}
                    <div class="text-center mb-5">
                        <h1 class="h2 fw-bold text-dark mb-2">Lấy Ảnh Cover Video</h1>
                        <p class="text-muted">Dán link YouTube hoặc TikTok để lấy ảnh thumbnail chất lượng cao.</p>
                        <div class="mt-3">
                            <a href="{{ route('cover.bulk.page') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                <i class="fas fa-bolt me-1"></i> Chuyển sang chế độ tải hàng loạt (Bulk) &rarr;
                            </a>
                        </div>
                    </div>

                    {{-- Search Box --}}
                    <div class="input-group input-group-lg mb-3 shadow-sm">
                        <input type="text" class="form-control border-primary" 
                               x-model="url" @keydown.enter.prevent="getInfo()" 
                               placeholder="Dán link YouTube, YouTube Shorts hoặc TikTok vào đây..."
                               :disabled="isLoading">
                        <button class="btn btn-primary px-4 fw-bold" type="button" 
                                @click.prevent="getInfo()" :disabled="isLoading">
                            <span x-show="!isLoading">Lấy thông tin</span>
                            <span x-show="isLoading" x-cloak>
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Đang xử lý...
                            </span>
                        </button>
                    </div>

                    {{-- Error Message --}}
                    <div x-show="error" x-cloak class="alert alert-danger mt-4" x-text="error"></div>

                    {{-- Result Area --}}
                    <div x-show="result" x-cloak x-transition class="mt-5 pt-4 border-top">
                        <div class="row g-4">
                            <div class="col-md-5">
                                <div class="position-relative bg-light rounded overflow-hidden">
                                    <img :src="result.thumbnail_url" alt="Video Thumbnail" class="img-fluid w-100 rounded shadow-sm">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <form action="{{ route('cover.download') }}" method="POST" class="h-100 d-flex flex-column justify-content-center">
                                    @csrf
                                    <input type="hidden" name="image_url" :value="result.thumbnail_url">
                                    <input type="hidden" name="filename" :value="result.title">
                                    <input type="hidden" name="provider" :value="result.provider">

                                    <div class="mb-4">
                                        <label for="title" class="form-label fw-bold text-secondary text-uppercase small">Tiêu đề (Tên file ảnh)</label>
                                        <input type="text" id="title" x-model="result.title" class="form-control form-control-lg">
                                    </div>

                                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">
                                        <i class="fas fa-download me-2"></i> Tải ảnh về máy
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
