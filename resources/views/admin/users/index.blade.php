@extends('layouts.admin')

@section('title', 'Quản lý thành viên')
@section('header_title', 'Danh sách thành viên')

@section('breadcrumb')
    <li class="breadcrumb-item active">Users</li>
@stop

@section('admin_content')
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session('error') }}
                </div>
            @endif

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Danh sách</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Thêm mới
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Thành viên</th>
                                <th>Email</th>
                                <th>Vai trò (Role)</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="user-block">
                                            <img class="img-circle img-bordered-sm" src="{{ $user->adminlte_image() }}" alt="User Image" style="object-fit: cover">
                                            <span class="username">
                                                <a href="#">{{ $user->name }}</a>
                                            </span>
                                            <span class="description">Member</span>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-{{ $role->name == 'super_admin' ? 'danger' : 'info' }}">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        @if(!$user->hasRole('super_admin') || \App\Models\User::role('super_admin')->count() > 1)
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@stop
