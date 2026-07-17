@extends('layouts.admin')

@section('title', 'Quản lý Hệ sinh thái')
@section('header_title', 'Danh sách Mini Apps')

@section('breadcrumb')
    <li class="breadcrumb-item active">Hệ sinh thái</li>
@endsection

@section('admin_content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Danh sách ứng dụng</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.mini-apps.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Thêm mới
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <x-admin.bulk-action 
                        model="MiniApp"
                    />
                        <table class="table table-hover table-striped projects">
                            <thead>
                                <tr>
                                    <th style="width: 1%">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="checkAll">
                                            <label for="checkAll" class="custom-control-label"></label>
                                        </div>
                                    </th>
                                    <th style="width: 5%">Order</th>
                                    <th style="width: 25%">Tên ứng dụng</th>
                                    <th style="width: 30%">Mô tả</th>
                                    <th style="width: 10%" class="text-center">Link</th>
                                    <th style="width: 10%" class="text-center">Active</th>
                                    <th style="width: 20%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-list">
                                @forelse($miniApps as $app)
                                    <tr data-id="{{ $app->id }}">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input check-item" type="checkbox" id="check_{{ $app->id }}" name="ids[]" value="{{ $app->id }}">
                                                <label for="check_{{ $app->id }}" class="custom-control-label"></label>
                                            </div>
                                        </td>
                                        <td class="handle" style="cursor: move">
                                            <i class="fas fa-arrows-alt text-muted mr-2"></i>
                                            {{ $app->order }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-2 mr-2 text-center" style="width: 40px; height: 40px;">
                                                    <i class="{{ $app->icon }} fa-lg text-primary"></i>
                                                </div>
                                                <div>
                                                    <a href="{{ route('admin.mini-apps.edit', $app->id) }}" class="font-weight-bold text-dark">
                                                        {{ $app->name }}
                                                    </a>
                                                    @if($app->badge)
                                                        <span class="badge badge-warning ml-1">{{ $app->badge }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="small text-muted">{{ Str::limit($app->description, 80) }}</td>
                                        <td class="text-center">
                                            @if($app->link)
                                                <a href="{{ $app->link }}" target="_blank" class="btn btn-default btn-xs" title="Xem link">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @else
                                                <i class="fas fa-minus text-muted"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $app->is_active ? 'success' : 'secondary' }}">
                                                {{ $app->is_active ? 'Active' : 'Hidden' }}
                                            </span>
                                        </td>
                                        <td class="project-actions text-right">
                                            <a class="btn btn-info btn-sm" href="{{ route('admin.mini-apps.edit', $app->id) }}">
                                                <i class="fas fa-pencil-alt"></i> Sửa
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ route('admin.mini-apps.destroy', $app->id) }}')">
                                                <i class="fas fa-trash"></i> Xóa
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                                <p>Chưa có ứng dụng nào. <a href="{{ route('admin.mini-apps.create') }}">Thêm mới ngay</a></p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="card-footer clearfix">

                    <div class="float-right">
                        {{ $miniApps->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Form --}}
    <form id="delete-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('admin_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        // Sortable
        new Sortable(document.getElementById('sortable-list'), {
            handle: '.handle',
            animation: 150,
            onEnd: function (evt) {
                var order = [];
                $('#sortable-list tr').each(function(index) {
                    order.push({
                        id: $(this).data('id'),
                        order: index
                    });
                });
                
                $.ajax({
                    url: '{{ route("admin.mini-apps.update-order") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order: order
                    },
                    success: function(response) {
                        toastr.success('Cập nhật thứ tự thành công!');
                    }
                });
            }
        });

        // Check All
        $('#checkAll').click(function() {
            $('.check-item').prop('checked', this.checked);
            updateBulkDeleteState();
        });

        $('.check-item').change(function() {
            updateBulkDeleteState();
            // Update CheckAll state
            var allChecked = $('.check-item:checked').length == $('.check-item').length;
            $('#checkAll').prop('checked', allChecked);
        });

        function updateBulkDeleteState() {
            var checked = $('.check-item:checked');
            var count = checked.length;
            $('#selectedCount').text(count); // Expects component to have #selectedCount, check component def
            
            if (count > 0) {
                $('#bulkDeleteBtn').removeClass('d-none');
                var ids = checked.map(function() { return $(this).val(); }).get();
                $('#bulkDeleteIds').val(JSON.stringify(ids));
            } else {
                $('#bulkDeleteBtn').addClass('d-none');
            }
        }
        
        // Remove old click handler since component handles confirm
        // $('#btn-bulk-delete').click(...) -> Removed

        // Delete Confirmation
        function confirmDelete(url) {
            if (confirm('Bạn có chắc chắn muốn xóa ứng dụng này?')) {
                $('#delete-form').attr('action', url);
                $('#delete-form').submit();
            }
        }
    </script>
@endpush
