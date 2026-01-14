@extends('layouts.admin')

@section('title', 'Chỉnh sửa Ứng dụng')
@section('header_title', 'Chỉnh sửa Ứng dụng')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.mini-apps.index') }}">Hệ sinh thái</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa</li>
@endsection

@section('admin_content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Cập nhật thông tin</h3>
                </div>
                <form action="{{ route('admin.mini-apps.update', $miniApp->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Tên ứng dụng <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $miniApp->name) }}" placeholder="Nhập tên ứng dụng">
                            @error('name')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="icon">Icon FontAwesome <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" id="icon" value="{{ old('icon', $miniApp->icon) }}" placeholder="VD: fas fa-layer-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i id="icon-preview" class="{{ $miniApp->icon }}"></i></span>
                                        </div>
                                    </div>
                                    @error('icon')
                                        <span class="error invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Tìm icon tại <a href="https://fontawesome.com/v5/search?m=free" target="_blank">FontAwesome v5</a></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="badge">Badge (Nhãn)</label>
                                    <input type="text" name="badge" class="form-control @error('badge') is-invalid @enderror" id="badge" value="{{ old('badge', $miniApp->badge) }}" placeholder="VD: HOT, Free, New...">
                                    @error('badge')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="link">Đường dẫn (Link)</label>
                            <input type="text" name="link" class="form-control @error('link') is-invalid @enderror" id="link" value="{{ old('link', $miniApp->link) }}" placeholder="URL ứng dụng (https://...) hoặc route ID (#...)">
                            @error('link')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả ngắn</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="3" placeholder="Mô tả chức năng của ứng dụng">{{ old('description', $miniApp->description) }}</textarea>
                            @error('description')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $miniApp->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Kích hoạt hiển thị</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">Cập nhật</button>
                        <a href="{{ route('admin.mini-apps.index') }}" class="btn btn-default float-right">Hủy bỏ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    $('#icon').on('input', function() {
        var iconClass = $(this).val();
        $('#icon-preview').attr('class', iconClass);
    });
</script>
@endpush
