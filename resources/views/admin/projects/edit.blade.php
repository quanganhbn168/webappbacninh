@extends('adminlte::page')

@section('title', 'Sửa Dự án')

@section('content_header')
    <h1>Sửa Dự án: {{ $project->title }}</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
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
                        <input type="text" name="title" class="form-control" value="{{ old('title', $project->title) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $project->description) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Link dự án</label>
                        <input type="url" name="link" class="form-control" value="{{ old('link', $project->link) }}" placeholder="https://...">
                    </div>
                </div>
                <div class="col-md-4">
                    {{-- Use reusable image uploader component --}}
                    <x-admin.image-uploader 
                        name="featured_image" 
                        label="Ảnh đại diện"
                        :value="$project->getFirstMediaUrl('featured_image')"
                        ratio="4x3" />

                    <div class="form-group mt-3">
                        <label>Danh mục</label>
                        <select name="category" class="form-control">
                            <option value="">-- Chọn --</option>
                            <option value="E-commerce" {{ $project->category == 'E-commerce' ? 'selected' : '' }}>E-commerce</option>
                            <option value="F&B" {{ $project->category == 'F&B' ? 'selected' : '' }}>F&B</option>
                            <option value="Real Estate" {{ $project->category == 'Real Estate' ? 'selected' : '' }}>Bất động sản</option>
                            <option value="Corporate" {{ $project->category == 'Corporate' ? 'selected' : '' }}>Doanh nghiệp</option>
                            <option value="Other" {{ $project->category == 'Other' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Thứ tự hiển thị</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', $project->order) }}">
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ $project->is_featured ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_featured">Đánh dấu Nổi bật</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.projects.index') }}" class="btn btn-default">Hủy</a>
        </div>
    </form>
</div>
@stop
