@extends('layouts.admin')

@section('title', 'Danh mục Giao diện')

@section('header_title')
    Danh mục Giao diện
@stop

@section('breadcrumb')
    <li class="breadcrumb-item active">Danh mục Giao diện</li>
@stop

@section('admin_content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"></h3>
            <div class="card-tools">
                <div class="d-inline-block">
                    <x-admin.bulk-action model="TemplateCategory" />
                </div>
                <a href="{{ route('admin.template-categories.create') }}" class="btn btn-primary btn-sm mr-2">
                    <i class="fas fa-plus mr-1"></i> Thêm mới
                </a>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th style="width: 30px"><input type="checkbox" id="selectAll"></th>
                    <th style="width: 80px">Hình ảnh</th>
                    <th>Tên danh mục</th>
                    <th>Số lượng mẫu</th>
                    <th style="width: 150px">Hành động</th>
                </tr>
            </thead>
            <tbody id="sortable-categories">
                    @forelse($categories as $category)
                    <tr data-id="{{ $category->id }}">
                        <td><input type="checkbox" class="row-checkbox" value="{{ $category->id }}"></td>
                        <td>
                            @if($category->image)
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" style="height: 50px; object-fit: cover; border-radius: 4px;">
                            @else
                                <span class="text-muted small">No Image</span>
                            @endif
                        </td>
                        <td>
                            <div class="font-weight-bold">{{ $category->name }}</div>
                            <small class="text-muted">/{{ $category->slug }}</small>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $category->templates_count ?? $category->templates->count() }} mẫu</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.template-categories.edit', $category) }}" class="btn btn-sm btn-info" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <x-admin.duplicate-button 
                                model="TemplateCategory" 
                                :id="$category->id" 
                                create-route="admin.template-categories.create" 
                            />

                            <x-admin.delete-button :action="route('admin.template-categories.destroy', $category)" />
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Chưa có danh mục nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $categories->links() }}
        </div>
    </div>
@stop

@section('plugins.Sweetalert2', true)
