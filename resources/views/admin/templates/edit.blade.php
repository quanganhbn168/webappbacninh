@extends('layouts.admin')

@section('title', 'Chỉnh sửa Giao Diện')

@section('header_title', 'Chỉnh sửa: ' . $template->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.templates.index') }}">Kho Giao Diện</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa</li>
@stop

@section('admin_content')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('admin.templates.update', $template) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card card-outline card-warning">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            
                            <x-admin.input
                                name="name"
                                label="Tên giao diện"
                                :value="old('name', $template->name)"
                                required="true"
                            />

                            <x-admin.slug-input
                                name="slug"
                                label="Đường dẫn (Slug)"
                                :value="old('slug', $template->slug)"
                                exclude-id="{{ $template->id }}"
                                placeholder="tu-dong-theo-ten-neu-de-trong"
                                model="Template"
                            />

                            <x-admin.select
                                name="template_category_id"
                                label="Danh mục giao diện"
                                :options="$categories->pluck('name', 'id')"
                                :selected="$template->template_category_id"
                                required="true"
                            />

                            <x-admin.select
                                name="tags[]"
                                label="Tags (Thẻ)"
                                :options="$tags"
                                :selected="old('tags', $template->tags->pluck('id')->toArray())"
                                multiple
                                tags
                                help="Chọn tag có sẵn hoặc nhập mới rồi nhấn Enter."
                            />

                            <x-admin.input
                                name="demo_url"
                                label="Link Demo (URL)"
                                :value="$template->demo_url"
                                type="url"
                            />

                            <x-admin.editor
                                name="content"
                                label="Mô tả chi tiết"
                                :value="$template->content"
                            />

                        </div>

                        <div class="col-md-4">
                            <x-admin.image-uploader
                                name="image"
                                label="Ảnh đại diện (Thumbnail)"
                                :value="$template->image"
                            />

                            <div class="mt-3">
                                <x-admin.multi-image-uploader
                                    name="images"
                                    label="Thư viện ảnh (Gallery)"
                                    :value="$template->images"
                                />
                            </div>

                            <div class="mt-4">
                                <x-admin.input
                                    name="price"
                                    label="Giá bán (VNĐ)"
                                    :value="$template->price"
                                    type="number"
                                    min="0"
                                />

                                <x-admin.input
                                    name="sale_price"
                                    label="Giá khuyến mãi (VNĐ)"
                                    :value="$template->sale_price"
                                    type="number"
                                    min="0"
                                />

                                <div class="border-top pt-3 mt-3">
                                    <x-admin.switch
                                        name="is_free"
                                        label="Miễn phí (Free)"
                                        :checked="$template->is_free"
                                    />

                                    <x-admin.switch
                                        name="is_premium"
                                        label="Giao diện Premium"
                                        :checked="$template->is_premium"
                                        help="Đánh dấu nếu đây là giao diện trả phí"
                                    />

                                <x-admin.switch
                                    name="is_active"
                                    label="Hiển thị giao diện này"
                                    :checked="$template->is_active"
                                />
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
