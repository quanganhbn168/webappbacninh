@extends('adminlte::page')

@section('title', 'Thêm Banner')

@section('content_header')
    <h1>Thêm Banner mới</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Tiêu đề chính <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Tiêu đề phụ</label>
                        <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}">
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nút CTA (Text)</label>
                                <input type="text" name="cta_text" class="form-control" value="{{ old('cta_text') }}" placeholder="VD: Nhận ưu đãi ngay">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Link CTA</label>
                                <input type="url" name="cta_link" class="form-control" value="{{ old('cta_link') }}" placeholder="https://...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Ảnh Banner</label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="imageInput" accept="image/*">
                            <label class="custom-file-label" for="imageInput">Chọn file...</label>
                        </div>
                        <small class="text-muted">Đề xuất: 1920x600px</small>
                    </div>
                    <div class="form-group">
                        <label>Vị trí hiển thị <span class="text-danger">*</span></label>
                        <select name="position" class="form-control" required>
                            <option value="homepage_promo">Trang chủ - Promo</option>
                            <option value="homepage_hero">Trang chủ - Hero</option>
                            <option value="sidebar">Sidebar</option>
                            <option value="popup">Popup</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Từ ngày</label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Đến ngày</label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Thứ tự</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}">
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                            <label class="custom-control-label" for="is_active">Kích hoạt</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu
            </button>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-default">Hủy</a>
        </div>
    </form>
</div>
@stop

@push('js')
<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        var fileName = e.target.files[0]?.name || 'Chọn file...';
        e.target.nextElementSibling.innerText = fileName;
    });
</script>
@endpush
