@extends('layouts.utility')

@section('title', 'Kết quả kiểm tra tên miền - WebAppBacNinh')

@section('content')
<section class="py-5 bg-light" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <a href="{{ route('home') }}" class="text-decoration-none mb-4 d-inline-block">
                    <i class="fas fa-arrow-left me-2"></i> Quay lại trang chủ
                </a>

                <div class="glass-card p-5 text-center">
                    @if($status === 'available')
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Chúc mừng!</h2>
                        <h4 class="text-primary mb-4">{{ $domain }}</h4>
                        <p class="lead mb-4">Tên miền này chưa có chủ sở hữu. Bạn có thể đăng ký ngay.</p>
                        
                        <a href="#" class="btn btn-premium btn-lg w-100 mb-3">
                            Đăng ký ngay
                        </a>
                    @else
                        <div class="mb-4">
                            <i class="fas fa-times-circle text-danger" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Rất tiếc!</h2>
                        <h4 class="text-secondary mb-4">{{ $domain }}</h4>
                        <p class="mb-4">Tên miền này đã được đăng ký. Hãy thử các gợi ý bên dưới.</p>
                        
                        <div class="list-group text-start mb-4 shadow-sm">
                            <div class="list-group-item bg-light fw-bold text-uppercase small text-muted">Gợi ý khả dụng</div>
                            @foreach($suggestions as $suggest)
                                <a href="{{ route('domain.check', ['domain' => $suggest]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    {{ $suggest }}
                                    <span class="badge bg-light text-dark border">Kiểm tra</span>
                                </a>
                            @endforeach
                        </div>

                        <form action="{{ route('domain.check') }}" method="GET" class="d-flex gap-2">
                            <input type="text" name="domain" class="form-control" placeholder="Tìm tên khác..." required>
                            <button type="submit" class="btn btn-secondary">Tìm lại</button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
