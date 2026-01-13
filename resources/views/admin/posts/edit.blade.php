@extends('layouts.admin')

@section('title', 'Chỉnh sửa bài viết')

@section('header_title', 'Chỉnh sửa bài viết')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">Blog</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa</li>
@stop

@section('admin_content')
    <form action="{{ route('admin.blog.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
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
                            :value="$post->title"
                            placeholder="Nhập tiêu đề hấp dẫn..." 
                            size="lg"
                            :required="true"
                        />

                        <x-admin.slug-input 
                            name="slug" 
                            label="Đường dẫn (Slug)"
                            :prefix="url('blog') . '/'"
                            :value="$post->slug"
                            placeholder="de-trong-de-tu-dong-tao"
                            size="sm"
                            :checkUrl="route('admin.blog.check-slug')"
                            :excludeId="$post->id"
                        />

                        <x-admin.textarea 
                            name="summary" 
                            label="Tóm tắt ngắn"
                            :value="$post->summary"
                            placeholder="Mô tả ngắn gọn về bài viết (hiển thị ở danh sách)..."
                            :rows="3"
                        />

                        <x-admin.editor 
                            name="content" 
                            label="Nội dung chi tiết"
                            :value="$post->content"
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
                            :value="$post->meta_title"
                            placeholder="Theo mặc định sẽ dùng tiêu đề bài viết"
                            size="sm"
                        />

                        <x-admin.textarea 
                            name="meta_description" 
                            label="SEO Description"
                            :value="$post->meta_description"
                            placeholder="Mô tả cho công cụ tìm kiếm..."
                            :rows="3"
                        />

                        <x-admin.input 
                            name="meta_keywords" 
                            label="SEO Keywords"
                            :value="$post->meta_keywords"
                            placeholder="công nghệ, thiết kế web, bắc ninh..."
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
                            :selected="$post->category_id"
                            placeholder="-- Chọn danh mục --"
                        />

                        <x-admin.tag-input 
                            name="tags" 
                            label="Tags (Thẻ)"
                            :options="$allTags"
                            :selected="$selectedTags"
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
                            label="Xuất bản"
                            :checked="$post->is_published"
                        />
                        <p class="small text-muted mb-0">
                            <i class="fas fa-calendar-alt mr-1"></i> 
                            Ngày đăng: {{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : 'Chưa xuất bản' }}
                        </p>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.blog.index') }}" class="btn btn-default float-right">Quay lại</a>
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
                            :value="$post->featured_image" 
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
                            :value="$post->og_image" 
                            label="Ảnh khi chia sẻ lên Facebook/Zalo"
                        />
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop
