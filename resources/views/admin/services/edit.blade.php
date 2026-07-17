@extends('layouts.admin')

@section('title', 'Chỉnh sửa Dịch Vụ')

@section('header_title', 'Chỉnh sửa Dịch Vụ')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Dịch vụ</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa</li>
@stop

@section('admin_content')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('admin.services.update', $service) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Tên dịch vụ <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $service->title) }}" required>
                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="slug">Đường dẫn</label>
                            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $service->slug) }}" required>
                            @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="service_category_id">Nhóm dịch vụ</label>
                            <select name="service_category_id" id="service_category_id" class="form-control">
                                <option value="">-- Chưa phân nhóm / landing riêng --</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" @selected(old('service_category_id', $service->service_category_id) == $id)>{{ $name }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Dịch vụ chưa có landing riêng sẽ hiển thị theo nhóm này.</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="icon">Icon FontAwesome</label>
                        <div class="input-group">
                            <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon', $service->icon) }}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="{{ $service->icon ?? 'fas fa-info' }}"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả ngắn <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description', $service->description) }}</textarea>
                    </div>

                            <x-admin.editor
                                name="content"
                                label="Nội dung chi tiết (HTML)"
                                :value="old('content', $service->content)"
                            />
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ $service->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Hiển thị dịch vụ này</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Cập nhật</button>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-default">Hủy bỏ</a>
                </div>
            </div>
        </form>
    </div>
</div>
@stop
