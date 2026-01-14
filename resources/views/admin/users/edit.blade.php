@extends('layouts.admin')

@section('title', 'Sửa thành viên')
@section('header_title', 'Cập nhật thành viên')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">Edit</li>
@stop

@section('admin_content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Thông tin</h3>
                </div>
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>Họ tên</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu mới (Để trống nếu không đổi)</label>
                            <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới...">
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Xác nhận mật khẩu</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu...">
                        </div>
                        <div class="form-group">
                            <label>Vai trò (Role)</label>
                            <select name="role" class="form-control">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->roles->contains('name', $role->name) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">Cập nhật</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-default float-right">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
