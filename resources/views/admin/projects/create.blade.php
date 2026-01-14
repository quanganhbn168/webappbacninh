@extends('adminlte::page')

@section('title', 'Thêm Dự án')

@section('content_header')
    <h1>Thêm Dự án mới</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
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
                        <label>Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Link dự án</label>
                        <input type="url" name="link" class="form-control" value="{{ old('link') }}" placeholder="https://...">
                    </div>
                </div>
                <div class="col-md-4">
                    {{-- Use reusable image uploader component --}}
                    <x-admin.image-uploader 
                        name="featured_image" 
                        label="Ảnh đại diện"
                        ratio="4x3" />
                    
                    <div class="form-group mt-3">
                        <label>Danh mục</label>
                        <select name="category" class="form-control">
                            <option value="">-- Chọn --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category['value'] }}" {{ old('category') == $category['value'] ? 'selected' : '' }}>
                                    {{ $category['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Thứ tự hiển thị</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}">
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1">
                            <label class="custom-control-label" for="is_featured">Đánh dấu Nổi bật</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu
            </button>
            <a href="{{ route('admin.projects.index') }}" class="btn btn-default">Hủy</a>
        </div>
    </form>
</div>
@stop
