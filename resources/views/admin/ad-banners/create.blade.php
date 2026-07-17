@extends('layouts.admin')

@section('title', 'Thêm Banner')

@section('header_title', 'Thêm Banner Mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.ad-banners.index') }}">Banner</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@stop

@section('admin_content')
<div class="card">
    <form action="{{ route('admin.ad-banners.store') }}" method="POST" enctype="multipart/form-data">
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
                        <label>Tên banner <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Link khi click</label>
                                <input type="url" name="link" class="form-control" value="{{ old('link') }}" placeholder="https://...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alt text (SEO)</label>
                                <input type="text" name="alt_text" class="form-control" value="{{ old('alt_text') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Từ ngày</label>
                                <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Đến ngày</label>
                                <input type="date" name="ends_at" class="form-control" value="{{ old('ends_at') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <x-admin.image-uploader 
                        name="banner_image" 
                        label="Ảnh Banner"
                        ratio="16x9" />
                    
                    <div class="form-group mt-3">
                        <label>Vị trí hiển thị <span class="text-danger">*</span></label>
                        <select name="slot" class="form-control" required>
                            @foreach($slots as $slot)
                                <option value="{{ $slot['value'] }}" {{ old('slot') == $slot['value'] ? 'selected' : '' }}>
                                    {{ $slot['label'] }}
                                </option>
                            @endforeach
                        </select>
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
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="open_new_tab" name="open_new_tab" value="1" checked>
                            <label class="custom-control-label" for="open_new_tab">Mở tab mới</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu
            </button>
            <a href="{{ route('admin.ad-banners.index') }}" class="btn btn-default">Hủy</a>
        </div>
    </form>
</div>
@stop
