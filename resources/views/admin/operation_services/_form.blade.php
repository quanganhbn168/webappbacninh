<div class="card-body">
    <div class="form-group"><label for="title">Tên dịch vụ *</label><input id="title" name="title" class="form-control" value="{{ old('title', $service->title) }}" required></div>
    <div class="form-row">
        <div class="form-group col-md-6"><label for="slug">Đường dẫn</label><input id="slug" name="slug" class="form-control" value="{{ old('slug', $service->slug) }}" placeholder="Tự tạo từ tên nếu để trống"></div>
        <div class="form-group col-md-3"><label for="menu_key">Khóa menu</label><input id="menu_key" name="menu_key" class="form-control" value="{{ old('menu_key', $service->menu_key) }}"></div>
        <div class="form-group col-md-3"><label for="order">Thứ tự</label><input id="order" name="order" type="number" min="0" class="form-control" value="{{ old('order', $service->order ?? 0) }}"></div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6"><label for="eyebrow">Nhãn hiển thị</label><input id="eyebrow" name="eyebrow" class="form-control" value="{{ old('eyebrow', $service->eyebrow) }}"></div>
        <div class="form-group col-md-6"><label for="icon">Icon FontAwesome</label><input id="icon" name="icon" class="form-control" value="{{ old('icon', $service->icon) }}" placeholder="fa-solid fa-server"></div>
    </div>
    <div class="form-group"><label for="highlight">Điểm nổi bật</label><input id="highlight" name="highlight" class="form-control" value="{{ old('highlight', $service->highlight) }}"></div>
    <div class="form-group"><label for="description">Mô tả</label><textarea id="description" name="description" rows="3" class="form-control">{{ old('description', $service->description) }}</textarea></div>
    <div class="form-row">
        <div class="form-group col-md-6"><label for="image">Ảnh chính</label><input id="image" name="image" class="form-control" value="{{ old('image', $service->image) }}" placeholder="frontend/assets/images/... hoặc URL"></div>
        <div class="form-group col-md-6"><label for="secondary_image">Ảnh phụ</label><input id="secondary_image" name="secondary_image" class="form-control" value="{{ old('secondary_image', $service->secondary_image) }}" placeholder="frontend/assets/images/... hoặc URL"></div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6"><label for="price_from">Giá tham khảo</label><input id="price_from" name="price_from" class="form-control" value="{{ old('price_from', $service->price_from) }}"></div>
        <div class="form-group col-md-6"><label for="cadence">Hình thức triển khai</label><input id="cadence" name="cadence" class="form-control" value="{{ old('cadence', $service->cadence) }}"></div>
    </div>
    <div class="form-group"><label for="meta_title">SEO title</label><input id="meta_title" name="meta_title" class="form-control" value="{{ old('meta_title', $service->meta_title) }}"></div>
    <div class="form-group"><label for="meta_description">SEO description</label><textarea id="meta_description" name="meta_description" rows="2" class="form-control">{{ old('meta_description', $service->meta_description) }}</textarea></div>
    <div class="form-group"><label for="data">Nội dung chi tiết (JSON)</label><textarea id="data" name="data" rows="16" class="form-control font-monospace">{{ old('data', json_encode($service->data ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}</textarea><small class="form-text text-muted">Các phần đối tượng, công việc, gói giá, FAQ và quy trình hiển thị ở trang chi tiết.</small></div>
    <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $service->is_active ?? true))><label class="custom-control-label" for="is_active">Hiển thị trên frontend</label></div>
</div>
