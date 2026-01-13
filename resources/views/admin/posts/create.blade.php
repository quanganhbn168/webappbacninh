@extends('layouts.admin')

@section('title', 'Tạo bài viết mới')

@section('header_title', 'Tạo bài viết mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">Blog</a></li>
    <li class="breadcrumb-item active">Tạo mới</li>
@stop

@section('admin_content')
    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            {{-- Main Content Column --}}
            <div class="col-md-8">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Nội dung bài viết</h3>
                    </div>
                    <div class="card-body">
                        <x-admin.input 
                            name="title" 
                            label="Tiêu đề bài viết" 
                            placeholder="Nhập tiêu đề hấp dẫn..." 
                            size="lg"
                            :required="true"
                        />

                        <x-admin.slug-input 
                            name="slug" 
                            label="Đường dẫn (Slug)"
                            :prefix="url('blog') . '/'"
                            placeholder="de-trong-de-tu-dong-tao"
                            help="Để trống nếu anh muốn hệ thống tự tạo từ tiêu đề."
                            size="sm"
                            :checkUrl="route('admin.blog.check-slug')"
                        />

                        <x-admin.textarea 
                            name="summary" 
                            label="Tóm tắt ngắn"
                            placeholder="Mô tả ngắn gọn về bài viết (hiển thị ở danh sách)..."
                            :rows="3"
                        />

                        <x-admin.editor 
                            name="content" 
                            label="Nội dung chi tiết"
                            :required="true"
                        />
                    </div>
                </div>

                {{-- SEO SECTION --}}
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-search mr-1"></i> Tối ưu SEO (Meta Tags)
                        </h3>
                    </div>
                    <div class="card-body">
                        <x-admin.input 
                            name="meta_title" 
                            label="SEO Title"
                            placeholder="Theo mặc định sẽ dùng tiêu đề bài viết"
                            help="Độ dài tối ưu: 50-60 ký tự."
                            size="sm"
                        />

                        <x-admin.textarea 
                            name="meta_description" 
                            label="SEO Description"
                            placeholder="Mô tả cho công cụ tìm kiếm..."
                            help="Độ dài tối ưu: 150-160 ký tự."
                            :rows="3"
                        />

                        <x-admin.input 
                            name="meta_keywords" 
                            label="SEO Keywords"
                            placeholder="công nghệ, thiết kế web, bắc ninh..."
                            help="Các từ khóa cách nhau bởi dấu phẩy."
                            size="sm"
                        />
                    </div>
                </div>
            </div>

            {{-- Sidebar Column --}}
            <div class="col-md-4">
                {{-- Category & Tags Box --}}
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-folder mr-1"></i> Phân loại
                        </h3>
                    </div>
                    <div class="card-body">
                        <x-admin.select 
                            name="category_id" 
                            label="Danh mục"
                            :options="$categories"
                            placeholder="-- Chọn danh mục --"
                        />

                        <x-admin.tag-input 
                            name="tags" 
                            label="Tags (Thẻ)"
                            :options="$allTags"
                            help="Nhập tag và nhấn Enter để tạo mới."
                        />
                    </div>
                </div>

                {{-- Publish Box --}}
                <div class="card card-outline card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Trạng thái & Công bố</h3>
                    </div>
                    <div class="card-body">
                        <x-admin.switch 
                            name="is_published" 
                            label="Xuất bản ngay"
                            :checked="true"
                            help="Nếu tắt, bài viết sẽ được lưu dưới dạng bản nháp."
                        />
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Lưu bài viết</button>
                        <a href="{{ route('admin.blog.index') }}" class="btn btn-default float-right">Hủy</a>
                    </div>
                </div>

                {{-- Featured Image Box --}}
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-image mr-1"></i> Ảnh đại diện
                        </h3>
                    </div>
                    <div class="card-body">
                        <x-admin.image-uploader 
                            name="featured_image" 
                            label="Ảnh hiển thị trong danh sách"
                        />
                    </div>
                </div>

                {{-- OG Image Box --}}
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-share-alt mr-1"></i> Ảnh chia sẻ (OG Image)
                        </h3>
                    </div>
                    <div class="card-body">
                        <x-admin.image-uploader 
                            name="og_image" 
                            label="Ảnh khi chia sẻ lên Facebook/Zalo"
                        />
                        <small class="text-muted d-block mt-2">
                            Kích thước tối ưu: 1200x630px. Nếu không chọn, hệ thống sẽ dùng ảnh đại diện.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

