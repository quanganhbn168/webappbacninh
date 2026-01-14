@extends('adminlte::page')

@section('title', 'Thêm Dịch Vụ Mới')

@section('content_header')
    <h1>Thêm Dịch Vụ Mới</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('admin.services.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Tên dịch vụ <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="icon">Icon FontAwesome (Ví dụ: fas fa-server)</label>
                        <div class="input-group">
                            <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon') }}" placeholder="fas fa-check">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                            </div>
                        </div>
                        <small class="form-text text-muted">Tra cứu tại <a href="https://fontawesome.com/v5/search" target="_blank">FontAwesome v5</a></small>
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả ngắn <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="content">Nội dung chi tiết (HTML)</label>
                        <textarea name="content" id="content" class="form-control" rows="10">{{ old('content') }}</textarea>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                            <label class="custom-control-label" for="is_active">Hiển thị dịch vụ này</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Lưu lại</button>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-default">Hủy bỏ</a>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@push('js')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('content');
    </script>
@endpush
