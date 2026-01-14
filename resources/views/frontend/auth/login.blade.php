@extends('layouts.master')

@section('title', 'Đăng nhập - WebApp Bắc Ninh')

@section('content')
<section class="py-5 bg-light d-flex align-items-center" style="min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-primary">Đăng Nhập</h3>
                            <p class="text-muted small">Chào mừng trở lại! Vui lòng đăng nhập để tiếp tục.</p>
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

                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Email đăng nhập</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label small fw-bold text-muted mb-0">Mật khẩu</label>
                                    <a href="#" class="small text-primary text-decoration-none">Quên mật khẩu?</a>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="••••••••" required>
                                </div>
                            </div>
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label small text-muted" for="remember">Ghi nhớ đăng nhập</label>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm fw-bold">Đăng nhập</button>
                            </div>

                            <div class="position-relative mb-4 text-center">
                                <hr class="text-secondary opacity-25">
                                <span class="position-absolute top-50 start-50 translate-middle bg-body px-2 small text-muted">Hoặc đăng nhập với</span>
                            </div>

                            <div class="d-flex gap-2 mb-3">
                                <a href="{{ route('social.login', 'google') }}" class="btn btn-outline-danger w-50 fw-bold">
                                    <i class="fab fa-google me-2"></i> Google
                                </a>
                                {{-- Custom Style for Facebook Blue --}}
                                <a href="{{ route('social.login', 'facebook') }}" class="btn w-50 fw-bold" style="color: #1877F2; border-color: #1877F2; background-color: transparent;" onmouseover="this.style.backgroundColor='#1877F2'; this.style.color='white'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#1877F2'">
                                    <i class="fab fa-facebook-f me-2"></i> Facebook
                                </a>
                            </div>
                        </form>

                        <div class="text-center">
                            <p class="small text-muted mb-0">
                                Chưa có tài khoản? 
                                <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Đăng ký ngay</a>
                            </p>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0 py-3 text-center">
                        <small class="text-muted">&copy; {{ date('Y') }} WebApp Bắc Ninh. All rights reserved.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
