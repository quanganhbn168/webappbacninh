@extends('layouts.master')

@section('title', 'Đăng ký - WebApp Bắc Ninh')

@section('content')
<section class="py-5 bg-light d-flex align-items-center" style="min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-primary">Tạo Tài Khoản</h3>
                            <p class="text-muted small">Tham gia cộng đồng WebApp Bắc Ninh ngay hôm nay.</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-4 rounded-3 border-0 bg-danger bg-opacity-10 text-danger small">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Họ và Tên</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-user"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 ps-0" placeholder="Nguyễn Văn A" value="{{ old('name') }}" required autofocus>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="name@example.com" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Min 8 ký tự" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Xác nhận mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0" placeholder="Nhập lại mật khẩu" required>
                                </div>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm fw-bold">Đăng ký ngay</button>
                            </div>

                            <div class="position-relative mb-4 text-center">
                                <hr class="text-secondary opacity-25">
                                <span class="position-absolute top-50 start-50 translate-middle bg-body px-2 small text-muted">Hoặc đăng ký với</span>
                            </div>

                            <div class="d-flex gap-2 mb-3">
                                <a href="{{ route('social.login', 'google') }}" class="btn btn-outline-danger w-50 fw-bold">
                                    <i class="fab fa-google me-2"></i> Google
                                </a>
                                <a href="{{ route('social.login', 'facebook') }}" class="btn btn-outline-primary w-50 fw-bold">
                                    <i class="fab fa-facebook-f me-2"></i> Facebook
                                </a>
                            </div>
                        </form>

                        <div class="text-center">
                            <p class="small text-muted mb-0">
                                Đã có tài khoản? 
                                <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Đăng nhập</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
