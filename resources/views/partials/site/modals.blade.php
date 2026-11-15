<div class="modal fade" id="consult-modal" tabindex="-1" aria-labelledby="consult-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="consult-modal-title">Đăng ký tư vấn</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <x-lead-form id="consult" :fields="['email', 'message']" :show-need="false" />
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="domain-modal" tabindex="-1" aria-labelledby="domain-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="domain-modal-title">Kiểm tra tên miền</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('domain.check') }}" method="get" class="d-flex flex-column gap-3">
                    <label class="form-label mb-0" for="domain-input">Nhập tên miền anh/chị muốn dùng</label>
                    <input class="form-control" id="domain-input" name="domain" placeholder="tencongty.vn" autocapitalize="none" spellcheck="false" maxlength="253" required>
                    <button class="btn btn-primary" type="submit"><x-icon name="search" /> Kiểm tra</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="toast-container position-fixed bottom-0 start-50 translate-middle-x p-3">
    <div class="toast site-toast" id="site-toast" role="status" aria-live="polite" aria-atomic="true">
        <div class="toast-body"></div>
    </div>
</div>
