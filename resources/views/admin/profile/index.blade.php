@extends('layouts.admin')

@section('title', 'Thông tin cá nhân')
@section('header_title', 'Hồ sơ cá nhân')

@section('breadcrumb')
    <li class="breadcrumb-item active">Profile</li>
@stop

@section('admin_content')
    <div class="row">
        <div class="col-md-4">
            {{-- Profile Image --}}
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{ Auth::guard('admin')->user()->adminlte_image() }}"
                             alt="User profile picture"
                             style="width: 100px; height: 100px; object-fit: cover">
                    </div>

                    <h3 class="profile-username text-center">{{ $user->name }}</h3>
                    <p class="text-muted text-center">{{ $user->getRoleNames()->first() ?? 'User' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Cài đặt</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="settings">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <form class="form-horizontal" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Họ tên</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputName" name="name" value="{{ old('name', $user->name) }}">
                                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="inputEmail" name="email" value="{{ old('email', $user->email) }}">
                                        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputAvatar" class="col-sm-2 col-form-label">Avatar</label>
                                    <div class="col-sm-10">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="inputAvatar" name="avatar" accept="image/*">
                                            <label class="custom-file-label" for="inputAvatar">Chọn ảnh...</label>
                                        </div>
                                        @error('avatar') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <hr>
                                        <h6 class="text-primary font-weight-bold">Đổi mật khẩu (Bỏ trống nếu không đổi)</h6>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Mật khẩu mới</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="inputPassword" name="password">
                                        @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputPassword2" class="col-sm-2 col-form-label">Xác nhận</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="inputPassword2" name="password_confirmation">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-danger">Lưu thay đổi</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    // Custom File Input
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
@stop
