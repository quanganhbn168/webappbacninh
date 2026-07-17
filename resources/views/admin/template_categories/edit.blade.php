@extends('layouts.admin')

@section('title', 'Cập nhật Danh Mục Giao Diện')

@section('header_title', 'Cập nhật Danh Mục')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.template-categories.index') }}">Danh mục Giao diện</a></li>
    <li class="breadcrumb-item active">Cập nhật</li>
@stop

@section('admin_content')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('admin.template-categories.update', $templateCategory) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <x-admin.input 
                                        name="name" 
                                        label="Tên danh mục" 
                                        :required="true"
                                        :value="$templateCategory->name"
                                    />

                                    <x-admin.select
                                        name="parent_id"
                                        label="Danh mục cha"
                                        :options="$parents"
                                        :selected="$templateCategory->parent_id"
                                        placeholder="-- Là danh mục cha --"
                                    />
                                    
                                    <x-admin.slug-input 
                                        name="slug" 
                                        label="Đường dẫn (Slug)"
                                        model="TemplateCategory"
                                        :source="'name'"
                                        :value="$templateCategory->slug"
                                        :exclude-id="$templateCategory->id"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-admin.image-uploader 
                                        name="image" 
                                        label="Ảnh đại diện"
                                        :value="$templateCategory->image"
                                        height="150"
                                    />
                                </div>
                            </div>

                            <x-admin.textarea 
                                name="description" 
                                label="Mô tả ngắn" 
                                rows="3"
                                :value="$templateCategory->description"
                            />

                            <x-admin.editor 
                                name="content" 
                                label="Mô tả chi tiết"
                                :value="$templateCategory->content"
                            />

                            <hr>
                            <h5 class="text-success"><i class="fas fa-search mr-1"></i> Cấu hình SEO & Chia sẻ</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-admin.input 
                                        name="meta_title" 
                                        label="SEO Title" 
                                        placeholder="Mặc định lấy tên danh mục..."
                                        :value="$templateCategory->meta_title"
                                    />
                                    
                                    <x-admin.textarea 
                                        name="meta_description" 
                                        label="SEO Description" 
                                        rows="3"
                                        :value="$templateCategory->meta_description"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-admin.image-uploader 
                                        name="og_image" 
                                        label="Ảnh chia sẻ (OG Image)"
                                        :value="$templateCategory->og_image"
                                        height="150"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                             <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Cập nhật</button>
                             <a href="{{ route('admin.template-categories.index') }}" class="btn btn-default float-right">Quay lại</a>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>
@stop
