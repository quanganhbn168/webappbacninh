<div class="card card-primary card-outline">
    <div class="card-body">
        <div class="form-group">
            <label for="name">Tên nhóm <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $serviceCategory->name ?? '') }}" required>
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="slug">Đường dẫn</label>
            <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $serviceCategory->slug ?? '') }}" placeholder="Tự tạo từ tên nhóm nếu để trống">
            <small class="form-text text-muted">Slug này dùng cho URL công khai và phải duy nhất trên toàn website.</small>
            @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="description">Mô tả ngắn</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $serviceCategory->description ?? '') }}</textarea>
            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <x-admin.editor name="content" label="Nội dung giới thiệu" :value="old('content', $serviceCategory->content ?? '')" />
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="meta_title">SEO title</label>
                <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', $serviceCategory->meta_title ?? '') }}">
            </div>
            <div class="form-group col-md-6">
                <label for="order">Thứ tự</label>
                <input type="number" min="0" class="form-control" id="order" name="order" value="{{ old('order', $serviceCategory->order ?? 0) }}">
            </div>
        </div>
        <div class="form-group">
            <label for="meta_description">SEO description</label>
            <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $serviceCategory->meta_description ?? '') }}</textarea>
        </div>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $serviceCategory->is_active ?? true))>
            <label class="custom-control-label" for="is_active">Hiển thị nhóm này</label>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Lưu lại</button>
        <a href="{{ route('admin.service-categories.index') }}" class="btn btn-default float-right">Hủy</a>
    </div>
</div>
