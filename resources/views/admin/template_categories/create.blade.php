@extends('layouts.admin')

@section('title', 'Thêm Danh Mục Giao Diện')

@section('header_title', 'Thêm Danh Mục Mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.template-categories.index') }}">Danh mục Giao diện</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@stop

@section('admin_content')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('admin.template-categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <x-admin.input 
                                        name="name" 
                                        label="Tên danh mục" 
                                        :required="true"
                                    />

                                    <x-admin.select
                                        name="parent_id"
                                        label="Danh mục cha"
                                        :options="$parents"
                                        placeholder="-- Là danh mục cha --"
                                    />
                                    
                                    <x-admin.slug-input 
                                        name="slug" 
                                        label="Đường dẫn (Slug)"
                                        model="TemplateCategory"
                                        :source="'name'"
                                        placeholder="tu-dong-tao-tu-ten"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-admin.image-uploader 
                                        name="image" 
                                        label="Ảnh đại diện"
                                        height="150"
                                    />
                                </div>
                            </div>

                            <x-admin.textarea 
                                name="description" 
                                label="Mô tả ngắn" 
                                rows="3"
                            />

                            <x-admin.editor 
                                name="content" 
                                label="Mô tả chi tiết" 
                            />

                            <hr>
                            <h5 class="text-success"><i class="fas fa-search mr-1"></i> Cấu hình SEO & Chia sẻ</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-admin.input 
                                        name="meta_title" 
                                        label="SEO Title" 
                                        placeholder="Mặc định lấy tên danh mục..."
                                    />
                                    
                                    <x-admin.textarea 
                                        name="meta_description" 
                                        label="SEO Description" 
                                        rows="3"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-admin.image-uploader 
                                        name="og_image" 
                                        label="Ảnh chia sẻ (OG Image)"
                                        height="150"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                             <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Lưu lại</button>
                             <a href="{{ route('admin.template-categories.index') }}" class="btn btn-default float-right">Quay lại</a>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>
@stop


