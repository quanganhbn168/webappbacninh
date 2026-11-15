<div class="modal fade site-modal site-modal--consult" id="consult-modal" tabindex="-1" aria-labelledby="consult-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <button type="button" class="btn-close site-modal__close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            <div class="site-modal__layout">
                <aside class="site-modal__aside">
                    <p class="site-modal__eyebrow">{{ site_config('name') }}</p>
                    <h2 class="site-modal__title" id="consult-modal-title">Cùng trao đổi về dự án của bạn</h2>
                    <p class="site-modal__lead">Để lại nhu cầu, chúng tôi sẽ liên hệ và tư vấn giải pháp phù hợp.</p>
                    <ul class="site-modal__points">
                        <li><x-icon name="check" /> Tư vấn miễn phí, không ràng buộc</li>
                        <li><x-icon name="check" /> Phản hồi trong giờ làm việc</li>
                        <li><x-icon name="check" /> Báo giá rõ ràng theo nhu cầu</li>
                    </ul>
                    <div class="site-modal__contact">
                        <a href="tel:{{ site_config('phone_href') }}"><x-icon name="phone" /> {{ site_config('phone') }}</a>
                        @if (site_config('working_time'))
                            <span>{{ site_config('working_time') }}</span>
                        @endif
                    </div>
                </aside>
                <div class="site-modal__form">
                    <x-lead-form id="consult" :fields="['email', 'message']" />
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade site-modal" id="domain-modal" tabindex="-1" aria-labelledby="domain-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <p class="eyebrow mb-1">Tên miền doanh nghiệp</p>
                    <h2 class="modal-title h3" id="domain-modal-title">Kiểm tra tên miền</h2>
                </div>
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
