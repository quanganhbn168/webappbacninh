<footer class="bg-dark text-light pt-5 pb-3">
  <div class="container">
    <div class="row">
      {{-- Cột 1: Giới thiệu --}}
      <div class="col-md-4 mb-4">
        <h5 class="text-uppercase mb-3">WebApp Bắc Ninh</h5>
        <p>
          Chúng tôi cung cấp giải pháp thiết kế website chuyên nghiệp, chuẩn SEO, phù hợp với doanh nghiệp tại Bắc Ninh và toàn quốc.
        </p>
        <ul class="list-unstyled d-flex gap-3">
          <li><a href="#" class="text-light"><i class="fab fa-facebook fa-lg"></i></a></li>
          <li><a href="#" class="text-light"><i class="fab fa-youtube fa-lg"></i></a></li>
          <li><a href="#" class="text-light"><i class="fab fa-tiktok fa-lg"></i></a></li>
        </ul>
      </div>

      {{-- Cột 2: Dịch vụ --}}
      <div class="col-md-2 mb-4">
        <h6 class="text-uppercase fw-bold">Dịch vụ</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="text-light text-decoration-none">Thiết kế website</a></li>
          <li><a href="#" class="text-light text-decoration-none">Hosting & VPS</a></li>
          <li><a href="#" class="text-light text-decoration-none">Tên miền</a></li>
          <li><a href="#" class="text-light text-decoration-none">SEO & Marketing</a></li>
        </ul>
      </div>

      {{-- Cột 3: Thông tin --}}
      <div class="col-md-3 mb-4">
        <h6 class="text-uppercase fw-bold">Thông tin</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="text-light text-decoration-none">Về chúng tôi</a></li>
          <li><a href="#" class="text-light text-decoration-none">Chính sách bảo mật</a></li>
          <li><a href="#" class="text-light text-decoration-none">Điều khoản sử dụng</a></li>
          <li><a href="#" class="text-light text-decoration-none">Liên hệ</a></li>
        </ul>
      </div>

      {{-- Cột 4: Liên hệ --}}
      <div class="col-md-3 mb-4">
        <h6 class="text-uppercase fw-bold">Liên hệ</h6>
        <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>TP. Bắc Ninh, Việt Nam</p>
        <p class="mb-1"><i class="fas fa-phone-alt me-2"></i>0988.888.888</p>
        <p class="mb-1"><i class="fas fa-envelope me-2"></i>support@webappbacninh.vn</p>
        <form class="mt-2">
          <label for="newsletter" class="form-label small">Nhận thông tin mới:</label>
          <div class="input-group input-group-sm">
            <input type="email" id="newsletter" class="form-control" placeholder="Nhập email...">
            <button class="btn btn-outline-light" type="submit">Gửi</button>
          </div>
        </form>
      </div>
    </div>

    <hr class="border-secondary">

    <div class="d-flex justify-content-between align-items-center small">
      <div>&copy; {{ now()->year }} WebApp Bắc Ninh. All rights reserved.</div>
      <div>Thiết kế bởi <a href="https://webappbacninh.vn" class="text-light text-decoration-underline">WebApp Bắc Ninh</a></div>
    </div>
  </div>
</footer>
