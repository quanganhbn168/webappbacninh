@extends('adminlte::page')

@section('title', 'Chỉnh sửa Giao Diện')

@section('content_header')
    <h1>Chỉnh sửa: {{ $template->name }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('admin.templates.update', $template) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name">Tên giao diện <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $template->name) }}" required>
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="category">Danh mục</label>
                                <input type="text" name="category" id="category" class="form-control" value="{{ old('category', $template->category) }}" placeholder="Ví dụ: Bán hàng, Doanh nghiệp">
                            </div>

                            <div class="form-group">
                                <label for="demo_url">Link Demo (URL)</label>
                                <input type="url" name="demo_url" id="demo_url" class="form-control" value="{{ old('demo_url', $template->demo_url) }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="image_file">Ảnh đại diện (Thumbnail)</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image_file" name="image_file" accept="image/*">
                                    <label class="custom-file-label" for="image_file">Chọn file mới nếu muốn thay đổi...</label>
                                </div>
                                <div class="mt-2">
                                    <p class="text-muted small mb-1">Ảnh hiện tại:</p>
                                    <img src="{{ $template->image_url }}" class="img-fluid rounded border mb-2" style="max-height: 150px;">
                                    
                                    <img id="previewHelper" src="#" alt="Preview New" class="img-fluid rounded border d-none" style="max-height: 150px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch mb-2">
                                    <input type="checkbox" class="custom-control-input" id="is_premium" name="is_premium" {{ $template->is_premium ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_premium">
                                        <i class="fas fa-crown text-warning mr-1"></i> Giao diện Premium
                                    </label>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ $template->is_active ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Hiển thị giao diện này</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Cập nhật</button>
                    <a href="{{ route('admin.templates.index') }}" class="btn btn-default">Hủy bỏ</a>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@push('js')
<script>
    // Preview image
    $('#image_file').change(function(){
        const file = this.files[0];
        if (file){
            let reader = new FileReader();
            reader.onload = function(event){
                $('#previewHelper').attr('src', event.target.result).removeClass('d-none');
            }
            reader.readAsDataURL(file);
            // Update label
            $(this).next('.custom-file-label').html(file.name);
        }
    });
</script>
@endpush
