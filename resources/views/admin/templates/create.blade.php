@extends('layouts.admin')

@section('title', 'Thêm mới Giao Diện')

@section('header_title', 'Thêm mới Giao Diện')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.templates.index') }}">Kho Giao Diện</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@stop

@section('admin_content')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            
                            <x-admin.input
                                name="name"
                                label="Tên giao diện"
                                :value="old('name')"
                                required="true"
                            />

                            <x-admin.slug-input
                                name="slug"
                                label="Đường dẫn (Slug)"
                                :value="old('slug')"
                                placeholder="tu-dong-theo-ten-neu-de-trong"
                                model="Template"
                            />

                            <x-admin.select
                                name="template_category_id"
                                label="Danh mục giao diện"
                                :options="$categories->pluck('name', 'id')"
                                :selected="old('template_category_id')"
                                required="true"
                            />

                            <x-admin.select
                                name="tags[]"
                                label="Tags (Thẻ)"
                                :options="$tags"
                                :selected="old('tags')"
                                multiple
                                tags
                                help="Chọn tag có sẵn hoặc nhập mới rồi nhấn Enter."
                            />

                            <x-admin.input
                                name="demo_url"
                                label="Link Demo (URL)"
                                :value="old('demo_url')"
                                type="url"
                            />

                            <x-admin.editor
                                name="content"
                                label="Mô tả chi tiết"
                                :value="old('content')"
                            />

                        </div>

                        <div class="col-md-4">
                            <x-admin.image-uploader
                                name="image"
                                label="Ảnh đại diện (Thumbnail)"
                                :value="old('image')"
                            />

                            <div class="mt-3">
                                <x-admin.multi-image-uploader
                                    name="images"
                                    label="Thư viện ảnh (Gallery)"
                                    :value="old('images')"
                                />
                            </div>

                            <div class="mt-4">
                                <x-admin.input
                                    name="price"
                                    label="Giá bán (VNĐ)"
                                    :value="old('price')"
                                    type="number"
                                    min="0"
                                />

                                <x-admin.input
                                    name="sale_price"
                                    label="Giá khuyến mãi (VNĐ)"
                                    :value="old('sale_price')"
                                    type="number"
                                    min="0"
                                />

                                <div class="border-top pt-3 mt-3">
                                    <x-admin.switch
                                        name="is_free"
                                        label="Miễn phí (Free)"
                                        :checked="old('is_free')"
                                    />

                                    <x-admin.switch
                                        name="is_premium"
                                        label="Giao diện Premium"
                                        :checked="old('is_premium')"
                                        help="Đánh dấu nếu đây là giao diện trả phí"
                                    />

                                <x-admin.switch
                                    name="is_active"
                                    label="Hiển thị giao diện này"
                                    :checked="old('is_active', true)"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Lưu lại</button>
                    <a href="{{ route('admin.templates.index') }}" class="btn btn-default">Hủy bỏ</a>
                </div>
            </div>
        </form>
    </div>
</div>
@stop
