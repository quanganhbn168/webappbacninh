@extends('layouts.master')

@section('title', 'Giới thiệu công ty thiết kế web Bắc Ninh')
@section('meta_description', 'WebApp Bắc Ninh chuyên thiết kế website cho doanh nghiệp, bán hàng, tuyển sinh...')
@section('meta_image', asset('images/gioi-thieu.jpg'))

@section('content')
@php
$templates = [
  (object)[
    'name' => 'Website Bán Hàng',
    'slug' => 'ban-hang',
    'thumbnail' => 'https://via.placeholder.com/600x400?text=B%C3%A1n+H%C3%A0ng'
  ],
  (object)[
    'name' => 'Website Bất Động Sản',
    'slug' => 'bat-dong-san',
    'thumbnail' => 'https://via.placeholder.com/600x400?text=B%E1%BA%A5t+%C4%90%E1%BB%99ng+S%E1%BA%A3n'
  ],
  (object)[
    'name' => 'Website Tuyển Sinh',
    'slug' => 'tuyen-sinh',
    'thumbnail' => 'https://via.placeholder.com/600x400?text=Tuy%E1%BB%83n+Sinh'
  ],
];
$posts = [
  (object)[
    'title' => 'Vì sao làm website mà vẫn không ra đơn?',
    'slug' => 'vi-sao-lam-website-ma-khong-ra-don',
    'thumbnail' => 'https://via.placeholder.com/600x400?text=Website+kh%C3%B4ng+c%C3%B3+%C4%91%C6%A1n',
    'excerpt' => 'Có website mà không ra đơn là tình trạng phổ biến. Vậy nguyên nhân từ đâu?'
  ],
  (object)[
    'title' => 'Checklist một website bán hàng chuẩn cần có gì?',
    'slug' => 'checklist-website-ban-hang',
    'thumbnail' => 'https://via.placeholder.com/600x400?text=Checklist+Web+B%C3%A1n+H%C3%A0ng',
    'excerpt' => 'Đừng chỉ làm web cho có! Đây là những thứ bắt buộc phải có nếu muốn bán được hàng.'
  ],
  (object)[
    'title' => 'Vì sao làm website mà vẫn không ra đơn?',
    'slug' => 'vi-sao-lam-website-ma-khong-ra-don',
    'thumbnail' => 'https://via.placeholder.com/600x400?text=Website+kh%C3%B4ng+c%C3%B3+%C4%91%C6%A1n',
    'excerpt' => 'Có website mà không ra đơn là tình trạng phổ biến. Vậy nguyên nhân từ đâu?'
  ],
  (object)[
    'title' => 'Checklist một website bán hàng chuẩn cần có gì?',
    'slug' => 'checklist-website-ban-hang',
    'thumbnail' => 'https://via.placeholder.com/600x400?text=Checklist+Web+B%C3%A1n+H%C3%A0ng',
    'excerpt' => 'Đừng chỉ làm web cho có! Đây là những thứ bắt buộc phải có nếu muốn bán được hàng.'
  ],
  (object)[
    'title' => 'Vì sao làm website mà vẫn không ra đơn?',
    'slug' => 'vi-sao-lam-website-ma-khong-ra-don',
    'thumbnail' => 'https://via.placeholder.com/600x400?text=Website+kh%C3%B4ng+c%C3%B3+%C4%91%C6%A1n',
    'excerpt' => 'Có website mà không ra đơn là tình trạng phổ biến. Vậy nguyên nhân từ đâu?'
  ],
  (object)[
    'title' => 'Checklist một website bán hàng chuẩn cần có gì?',
    'slug' => 'checklist-website-ban-hang',
    'thumbnail' => 'https://via.placeholder.com/600x400?text=Checklist+Web+B%C3%A1n+H%C3%A0ng',
    'excerpt' => 'Đừng chỉ làm web cho có! Đây là những thứ bắt buộc phải có nếu muốn bán được hàng.'
  ],
  
];
$posts = collect($posts);
@endphp

  @include('partials.frontend.hero')
  {{-- Giải pháp theo ngành --}}
  <section class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="mb-4">Giải pháp website theo từng lĩnh vực</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <i class="fas fa-store fa-2x text-primary mb-3"></i>
            <h5 class="card-title">Website bán hàng</h5>
            <p class="card-text">Giao diện đẹp, giỏ hàng, thanh toán online, tự quản lý đơn hàng dễ dàng.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <i class="fas fa-building fa-2x text-primary mb-3"></i>
            <h5 class="card-title">Website bất động sản</h5>
            <p class="card-text">Tùy biến theo từng dự án, có chức năng lọc, đăng tin, xem sơ đồ, liên hệ nhanh.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <i class="fas fa-user-graduate fa-2x text-primary mb-3"></i>
            <h5 class="card-title">Website tuyển sinh</h5>
            <p class="card-text">Dành cho trung tâm, trường học – giới thiệu chương trình, nhận hồ sơ online.</p>
          </div>
        </div>
      </div>
    </div>
    <a href="{{ route('home') }}" class="btn btn-outline-primary mt-4">Xem tất cả lĩnh vực</a>
  </div>
</section>
<section class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="mb-4" data-aos="fade-up">Tính năng nổi bật</h2>
    <div class="row g-4">
      <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="100">
        <div class="p-4 border rounded h-100 shadow-sm bg-white">
          <i class="fas fa-laptop-code fa-2x text-primary mb-3"></i>
          <h6 class="fw-bold">Dễ quản trị</h6>
          <p class="small text-muted">Không cần kỹ thuật – thêm, sửa nội dung cực dễ.</p>
        </div>
      </div>
      <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="200">
        <div class="p-4 border rounded h-100 shadow-sm bg-white">
          <i class="fas fa-search-dollar fa-2x text-primary mb-3"></i>
          <h6 class="fw-bold">Chuẩn SEO</h6>
          <p class="small text-muted">Tối ưu Google từ đầu để web có cơ hội lên top.</p>
        </div>
      </div>
      <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="300">
        <div class="p-4 border rounded h-100 shadow-sm bg-white">
          <i class="fas fa-mobile-alt fa-2x text-primary mb-3"></i>
          <h6 class="fw-bold">Hiển thị tốt trên điện thoại</h6>
          <p class="small text-muted">Responsive 100% – phù hợp mọi thiết bị.</p>
        </div>
      </div>
      <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="400">
        <div class="p-4 border rounded h-100 shadow-sm bg-white">
          <i class="fas fa-tags fa-2x text-primary mb-3"></i>
          <h6 class="fw-bold">Giá hợp lý</h6>
          <p class="small text-muted">Không phát sinh chi phí ẩn – báo giá rõ ràng.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="mb-4">Một số giao diện sẵn có</h2>
    <div class="row g-4">
      @foreach($templates as $template)
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <img src="{{ asset($template->thumbnail) }}" class="card-img-top" alt="{{ $template->name }}">
          <div class="card-body">
            <h5 class="card-title">{{ $template->name }}</h5>
            <a href="{{ route('templates.show', $template->slug) }}" class="btn btn-primary btn-sm">Xem giao diện</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <a href="{{ route('templates.index') }}" class="btn btn-outline-primary mt-4">Xem tất cả</a>
  </div>
</section>
<section class="py-5">
  <div class="container text-center">
    <h2 class="mb-4">Khách hàng nói gì?</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <blockquote class="blockquote">
          <p>“Làm web ở đây rất dễ hiểu, được hướng dẫn chi tiết. Nhìn đẹp và chuyên nghiệp.”</p>
          <footer class="blockquote-footer">Anh Minh – Chủ cửa hàng rau sạch</footer>
        </blockquote>
      </div>
      <div class="col-md-4">
        <blockquote class="blockquote">
          <p>“Web chạy nhanh, SEO có lên top luôn. Hỗ trợ cực nhiệt tình.”</p>
          <footer class="blockquote-footer">Chị Hương – Trung tâm tiếng Anh</footer>
        </blockquote>
      </div>
      <div class="col-md-4">
        <blockquote class="blockquote">
          <p>“Lần đầu làm web, mà không thấy bị 'bắt nạt'. Mọi thứ rõ ràng.”</p>
          <footer class="blockquote-footer">Anh Quang – Startup logistics</footer>
        </blockquote>
      </div>
    </div>
  </div>
</section>
@include('partials.frontend.marquee')
<section class="py-5 bg-primary text-white text-center">
  <div class="container">
    <h2 class="mb-3">Sẵn sàng bắt đầu với website của bạn?</h2>
    <p class="mb-4">Chúng tôi sẽ giúp bạn online chuyên nghiệp chỉ sau vài ngày.</p>

    <form action="{{ route('subscribe.email') }}" method="POST" class="row justify-content-center g-2">
      @csrf
      <div class="col-md-4 col-sm-6">
        <input type="email" name="email" class="form-control form-control-lg" placeholder="Nhập email của bạn" required>
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-light btn-lg px-4">
          <i class="fas fa-paper-plane"></i>
        </button>
      </div>
    </form>

    <p class="mt-3 small text-white-50">Chúng tôi sẽ liên hệ tư vấn sớm nhất có thể.</p>
  </div>
</section>

<section class="py-5 bg-light">
  <div class="container">
    <h2 class="mb-4 text-center">Bài viết mới</h2>

    <div class="swiper blog-swiper">
      <div class="swiper-wrapper">
        @foreach($posts->chunk(2) as $chunk)
        <div class="swiper-slide">
          <div class="d-flex flex-wrap gap-4 justify-content-center">
            @foreach($chunk as $post)
            <div class="card shadow-sm">
              <img src="{{ $post->thumbnail }}" class="card-img-top" alt="{{ $post->title }}">
              <div class="card-body">
                <h5 class="card-title">{{ $post->title }}</h5>
                <p class="card-text small text-muted">{{ $post->excerpt }}</p>
                <a href="#" class="btn btn-sm btn-outline-primary">Đọc tiếp</a>
              </div>
            </div>
            @endforeach
          </div>
        </div>
        @endforeach
      </div>
      <div class="swiper-pagination mt-3"></div>
    </div>
  </div>
</section>
@include('partials.frontend.marquee')


@endsection
