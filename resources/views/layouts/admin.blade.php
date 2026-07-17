@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', $title ?? 'Admin - WebApp Bắc Ninh')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>@yield('header_title', 'Dashboard')</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                @yield('breadcrumb')
            </ol>
        </div>
    </div>
@stop

@section('content')
    @yield('admin_content')
@stop

@section('footer')
    <div class="float-right d-none d-sm-block">
        <b>Version</b> 1.0.0
    </div>
    <strong>&copy; {{ now()->year }} <a href="{{ url('/') }}">WebApp Bắc Ninh</a>.</strong> All rights reserved.
@stop

@section('css')
    <style>
        /* Fix Avatar Jumping in Navbar */
        .navbar-nav .user-menu .user-image {
            width: 30px !important;
            height: 30px !important;
            object-fit: cover;
        }
    </style>
    @stack('admin_css')
@stop

@section('js')
    @stack('js')
    @stack('admin_js')

    {{-- Global Scripts --}}
    <script>
        $(document).ready(function() {
            // Global Delete Confirmation
            $('body').on('submit', '.confirm-delete', function(e) {
                e.preventDefault();
                var form = this;
                
                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Dữ liệu sẽ bị xóa vĩnh viễn và không thể khôi phục!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Vâng, xóa nó!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

             // Global Duplicate Confirmation
             $('body').on('click', '.btn-duplicate', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Sao chép dữ liệu?',
                    text: "Bạn sẽ được chuyển sang trang Thêm mới với dữ liệu được điền sẵn.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Bulk Delete Logic
            const $selectAll = $('#selectAll');
            const $bulkBtn = $('#bulkDeleteBtn');
            const $bulkIds = $('#bulkDeleteIds');
            const $countSpan = $('#selectedCount');

            // Handle Select All
            $selectAll.on('change', function() {
                $('.row-checkbox').prop('checked', this.checked).trigger('change');
            });

            // Handle Individual Checkbox
            $('body').on('change', '.row-checkbox', function() {
                const selected = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                // Update Select All state
                if ($('.row-checkbox:checked').length === $('.row-checkbox').length && $('.row-checkbox').length > 0) {
                    $selectAll.prop('checked', true);
                } else {
                    $selectAll.prop('checked', false);
                }

                // Update Button State
                if (selected.length > 0) {
                    $bulkBtn.removeClass('d-none');
                    $countSpan.text(selected.length);
                    $bulkIds.val(JSON.stringify(selected));
                } else {
                    $bulkBtn.addClass('d-none');
                    $bulkIds.val('');
                }
            });

            // Bulk Delete Confirmation
            $('#bulkDeleteForm').on('submit', function(e) {
                e.preventDefault();
                const count = $countSpan.text();
                
                Swal.fire({
                    title: 'Xóa ' + count + ' mục đã chọn?',
                    text: "Hành động này không thể hoàn tác!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Vâng, xóa hết!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@stop
