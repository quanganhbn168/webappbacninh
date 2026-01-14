@extends('adminlte::page')

@section('title', 'Sửa Banner')

@section('content_header')
    <h1>Sửa Banner: {{ $adBanner->name }}</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('admin.ad-banners.update', $adBanner) }}" method="POST" enctype="multipart/form-data">
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
                        <label>Tên banner <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $adBanner->name) }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Link khi click</label>
                                <input type="url" name="link" class="form-control" value="{{ old('link', $adBanner->link) }}" placeholder="https://...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alt text (SEO)</label>
                                <input type="text" name="alt_text" class="form-control" value="{{ old('alt_text', $adBanner->alt_text) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Từ ngày</label>
                                <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at', $adBanner->starts_at?->format('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Đến ngày</label>
                                <input type="date" name="ends_at" class="form-control" value="{{ old('ends_at', $adBanner->ends_at?->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <x-admin.image-uploader 
                        name="banner_image" 
                        label="Ảnh Banner"
                        :value="$adBanner->getFirstMediaUrl('banner_image')"
                        ratio="16x9" />
                    
                    <div class="form-group mt-3">
                        <label>Vị trí hiển thị <span class="text-danger">*</span></label>
                        <select name="slot" class="form-control" required>
                            @foreach($slots as $slot)
                                <option value="{{ $slot['value'] }}" {{ old('slot', $adBanner->slot->value) == $slot['value'] ? 'selected' : '' }}>
                                    {{ $slot['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Thứ tự</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', $adBanner->order) }}">
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ $adBanner->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Kích hoạt</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="open_new_tab" name="open_new_tab" value="1" {{ $adBanner->open_new_tab ? 'checked' : '' }}>
                            <label class="custom-control-label" for="open_new_tab">Mở tab mới</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.ad-banners.index') }}" class="btn btn-default">Hủy</a>
        </div>
    </form>
</div>
@stop
