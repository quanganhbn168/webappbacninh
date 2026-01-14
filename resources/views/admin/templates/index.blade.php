@extends('adminlte::page')

@section('title', 'Quản lý Kho Giao Diện')

@section('content_header')
    <h1>Quản lý Kho Giao Diện</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách giao diện</h3>
        <div class="card-tools">
            <form action="{{ route('admin.templates.bulkDestroy') }}" method="POST" id="bulkDeleteForm" class="d-inline">
                @csrf
                <input type="hidden" name="ids" id="bulkDeleteIds">
                <button type="submit" class="btn btn-danger btn-sm d-none" id="bulkDeleteBtn" onclick="return confirm('Xóa các mục đã chọn?')">
                    <i class="fas fa-trash"></i> Xóa đã chọn (<span id="selectedCount">0</span>)
                </button>
            </form>
            <a href="{{ route('admin.templates.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th style="width: 30px"><input type="checkbox" id="selectAll"></th>
                    <th style="width: 30px"><i class="fas fa-grip-vertical text-muted"></i></th>
                    <th style="width: 80px">Ảnh</th>
                    <th>Tên giao diện</th>
                    <th>Danh mục</th>
                    <th>Loại</th>
                    <th>Trạng thái</th>
                    <th style="width: 120px">Thao tác</th>
                </tr>
            </thead>
            <tbody id="sortable-templates">
                @forelse($templates as $template)
                <tr data-id="{{ $template->id }}">
                    <td><input type="checkbox" class="row-checkbox" value="{{ $template->id }}"></td>
                    <td class="handle" style="cursor: grab;"><i class="fas fa-grip-vertical text-muted"></i></td>
                    <td>
                        @if($template->image)
                            <img src="{{ $template->image_url }}" alt="" style="width: 60px; height: 40px; object-fit: cover;" class="rounded border">
                        @else
                            <span class="badge bg-secondary">No Image</span>
                        @endif
                    </td>
                    <td>
                        {{ $template->name }}
                        @if($template->demo_url)
                            <a href="{{ $template->demo_url }}" target="_blank" class="text-xs ml-1"><i class="fas fa-external-link-alt"></i> Demo</a>
                        @endif
                    </td>
                    <td>{{ $template->category ?? '---' }}</td>
                    <td>
                        @if($template->is_premium)
                            <span class="badge bg-warning"><i class="fas fa-crown"></i> Premium</span>
                        @else
                            <span class="badge bg-secondary">Free</span>
                        @endif
                    </td>
                    <td>
                        @if($template->is_active)
                            <span class="badge bg-success">Hiển thị</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.templates.edit', $template) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.templates.destroy', $template) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Chưa có giao diện nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{ $templates->links() }}
    </div>
</div>
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
$(function() {
    // SortableJS for drag-to-sort
    var el = document.getElementById('sortable-templates');
    if (el) {
        Sortable.create(el, {
            handle: '.handle',
            animation: 150,
            onEnd: function() {
                var order = [];
                $('#sortable-templates tr[data-id]').each(function() {
                    order.push($(this).data('id'));
                });
                
                $.ajax({
                    url: '{{ route("admin.templates.updateOrder") }}',
                    method: 'POST',
                    data: { order: order, _token: '{{ csrf_token() }}' },
                    success: function() {
                        toastr.success('Đã cập nhật thứ tự!');
                    }
                });
            }
        });
    }

    // Select all checkbox
    $('#selectAll').on('change', function() {
        $('.row-checkbox').prop('checked', $(this).is(':checked'));
        updateBulkDeleteBtn();
    });

    // Individual checkbox
    $('.row-checkbox').on('change', updateBulkDeleteBtn);

    function updateBulkDeleteBtn() {
        var checked = $('.row-checkbox:checked');
        var count = checked.length;
        $('#selectedCount').text(count);
        
        if (count > 0) {
            $('#bulkDeleteBtn').removeClass('d-none');
            var ids = checked.map(function() { return $(this).val(); }).get();
            $('#bulkDeleteIds').val(JSON.stringify(ids));
        } else {
            $('#bulkDeleteBtn').addClass('d-none');
        }
    }
});
</script>
@endpush
