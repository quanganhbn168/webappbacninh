
  <!-- Shared consultation and preview dialogs. -->
  <dialog aria-labelledby="consult-title" class="modal" id="consult-modal">
   <div class="modal-shell">
    <button aria-label="Đóng" class="modal-close" data-close="" type="button">
     <svg aria-hidden="true" class="icon" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" viewBox="0 0 24 24" width="24">
      <path d="m5 5 14 14M5 19 19 5">
      </path>
     </svg>
    </button>
    <p class="eyebrow">
     WEBAPP BẮC NINH
    </p>
    <h2 id="consult-title">
     Cùng trao đổi về dự án của bạn
    </h2>
    <p class="modal-intro">
     Để lại nhu cầu, chúng tôi sẽ liên hệ và tư vấn giải pháp phù hợp.
    </p>
    <form id="consult-form" novalidate="">
     <div class="form-grid">
      <label>
       Họ và tên
       <span>
        *
       </span>
       <input autocomplete="name" maxlength="100" minlength="2" name="name" placeholder="Họ và tên của anh/chị" required=""/>
      </label>
      <label>
       Số điện thoại
       <span>
        *
       </span>
       <input autocomplete="tel" inputmode="tel" maxlength="22" name="phone" placeholder="Số điện thoại liên hệ" required=""/>
      </label>
     </div>
     <label>
      Email
      <input autocomplete="email" maxlength="200" name="email" placeholder="Email của anh/chị (không bắt buộc)" type="email"/>
     </label>
     <label>
      Dịch vụ quan tâm
      <input id="consult-service" maxlength="200" name="service"/>
     </label>
     <label>
      Nhu cầu của anh/chị
      <textarea maxlength="3000" name="message" placeholder="Mô tả ngắn về website, phần mềm hoặc vấn đề cần hỗ trợ..." rows="3"></textarea>
     </label>
     <label class="consent">
      <input name="consent" required="" type="checkbox"/>
      <span>
       Tôi đồng ý cung cấp thông tin để được liên hệ tư vấn.
      </span>
     </label>
     <p class="form-error" hidden="" id="form-error" role="alert">
     </p>
     <button class="btn btn--primary form-submit" type="submit">
      Gửi yêu cầu tư vấn
      <svg aria-hidden="true" class="icon" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" viewBox="0 0 24 24" width="24">
       <path d="M5 12h14m-5-5 5 5-5 5">
       </path>
      </svg>
     </button>
     <p class="form-note">Thông tin chỉ được sử dụng để liên hệ và tư vấn theo nhu cầu của bạn.</p>
     <div class="form-result" hidden="" id="form-result">
      <strong>
       Nội dung yêu cầu đã sẵn sàng
      </strong>
      <p>
       Chưa gửi dữ liệu tới máy chủ. Anh có thể sao chép nội dung bên dưới.
      </p>
      <textarea aria-label="Nội dung yêu cầu" id="request-summary" readonly="" rows="7"></textarea>
      <button class="btn btn--outline" id="copy-request" type="button">
       Sao chép nội dung
      </button>
     </div>
    </form>
   </div>
  </dialog>
  <dialog aria-labelledby="preview-title" class="modal modal--preview" id="preview-modal">
   <div class="modal-shell">
    <button aria-label="Đóng" class="modal-close" data-close="" type="button">
     <svg aria-hidden="true" class="icon" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" viewBox="0 0 24 24" width="24">
      <path d="m5 5 14 14M5 19 19 5">
      </path>
     </svg>
    </button>
    <img alt="" hidden="" id="preview-image"/>
    <p class="eyebrow">
     WEBAPP BẮC NINH
    </p>
    <h2 id="preview-title">
    </h2>
    <p id="preview-description">
    </p>
    <div id="preview-extra">
    </div>
    <button class="btn btn--primary" data-consult="Tư vấn giải pháp" type="button">
     Trao đổi về giải pháp này
    </button>
   </div>
  </dialog>
  <dialog aria-labelledby="domain-title" class="modal" id="domain-modal">
   <div class="modal-shell">
    <button aria-label="Đóng" class="modal-close" data-close="" type="button">
     <svg aria-hidden="true" class="icon" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" viewBox="0 0 24 24" width="24">
      <path d="m5 5 14 14M5 19 19 5">
      </path>
     </svg>
    </button>
    <p class="eyebrow">
     TÊN MIỀN DOANH NGHIỆP
    </p>
    <h2 id="domain-title">
     Kiểm tra tên miền
    </h2>
    <p>
     Nhập tên miền anh muốn sử dụng cho website.
    </p>
    <form id="domain-form">
     <label>
      Tên miền
      <input autocapitalize="none" maxlength="253" name="domain" placeholder="tencongty.vn" required="" spellcheck="false"/>
     </label>
     <button class="btn btn--primary" type="submit">
      <svg aria-hidden="true" class="icon" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" viewBox="0 0 24 24" width="24">
       <circle cx="10.5" cy="10.5" r="7.5">
       </circle>
       <path d="m16 16 6 6">
       </path>
      </svg>
      Kiểm tra định dạng
     </button>
    </form>
    <p class="domain-result" hidden="" id="domain-result" role="status">
    </p>
    <p class="form-note">Thông tin chỉ được sử dụng để liên hệ và tư vấn theo nhu cầu của bạn.</p>
   </div>
  </dialog>
  <div aria-live="polite" class="toast" hidden="" id="toast" role="status">
  </div>
