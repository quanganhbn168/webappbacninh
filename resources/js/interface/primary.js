/* WebApp Bắc Ninh · Vanilla JavaScript. No dependencies. */
(() => {
  'use strict';
  const $ = (selector, root = document) => root.querySelector(selector);
  const $$ = (selector, root = document) => [...root.querySelectorAll(selector)];
  const config = window.WEBAPP_CONFIG || {};
  $$('[data-contact-phone]').forEach(el => { if (config.contact?.phone) el.textContent = config.contact.phone; });
  $$('[data-contact-email]').forEach(el => { if (config.contact?.email) el.textContent = config.contact.email; });

  const consultModal = $('#consult-modal');
  const previewModal = $('#preview-modal');
  const domainModal = $('#domain-modal');
  const nav = $('#main-nav');
  const menuButton = $('[data-menu-toggle]');
  let modalTrigger = null;
  let toastTimer = null;

  function toast(message) {
    const el = $('#toast');
    el.textContent = message;
    el.hidden = false;
    window.clearTimeout(toastTimer);
    toastTimer = window.setTimeout(() => { el.hidden = true; }, 4200);
  }

  function closeMenu() {
    nav?.classList.remove('is-open');
    menuButton?.setAttribute('aria-expanded', 'false');
    menuButton?.setAttribute('aria-label', 'Mở menu');
  }

  function showModal(dialog, trigger = document.activeElement) {
    if (!dialog) return;
    closeMenu();
    $$('dialog[open]').forEach(item => item.close());
    modalTrigger = trigger;
    dialog.showModal();
    dialog.scrollTop = 0;
  }

  function openConsult(service, trigger) {
    $('#consult-service').value = service || 'Tư vấn dự án';
    $('#form-error').hidden = true;
    $('#form-result').hidden = true;
    showModal(consultModal, trigger);
  }

  function openPreview({ title, description, image }, trigger) {
    $('#preview-title').textContent = title || 'WebApp Bắc Ninh';
    $('#preview-description').textContent = description || '';
    $('#preview-extra').replaceChildren();
    const img = $('#preview-image');
    img.hidden = !image;
    if (image) { img.src = image; img.alt = title || ''; }
    else img.removeAttribute('src');
    $('[data-consult]', previewModal).dataset.consult = title || 'Tư vấn giải pháp';
    showModal(previewModal, trigger);
  }

  menuButton?.addEventListener('click', () => {
    const open = !nav.classList.contains('is-open');
    nav.classList.toggle('is-open', open);
    menuButton.setAttribute('aria-expanded', String(open));
    menuButton.setAttribute('aria-label', open ? 'Đóng menu' : 'Mở menu');
  });

  $$('.dropdown-toggle').forEach(toggle => {
    toggle.addEventListener('click', () => {
      const parent = toggle.closest('.nav-dropdown');
      const open = !parent.classList.contains('is-open');
      parent.classList.toggle('is-open', open);
      toggle.setAttribute('aria-expanded', String(open));
    });
  });

  $$('dialog').forEach(dialog => {
    // Only clicks outside the content bounds dismiss the dialog.
    dialog.addEventListener('click', event => {
      if (event.target !== dialog) return;
      const rect = dialog.getBoundingClientRect();
      if (event.clientX < rect.left || event.clientX > rect.right ||
          event.clientY < rect.top || event.clientY > rect.bottom) dialog.close();
    });
    dialog.addEventListener('close', () => {
      if (!$('dialog[open]') && modalTrigger instanceof HTMLElement) {
        modalTrigger.focus({ preventScroll: true });
      }
    });
  });

  document.addEventListener('keydown', event => {
    if (event.key === 'Escape') {
      closeMenu();
      $$('.nav-dropdown.is-open').forEach(el => el.classList.remove('is-open'));
      $$('.dropdown-toggle').forEach(el => el.setAttribute('aria-expanded', 'false'));
    }
  });

  document.addEventListener('click', event => {
    const target = event.target instanceof Element ? event.target : null;
    if (!target) return;
    const close = target.closest('[data-close]');
    if (close) { close.closest('dialog')?.close(); return; }
    const consult = target.closest('[data-consult]');
    if (consult) { openConsult(consult.dataset.consult, consult); return; }
    const preview = target.closest('[data-preview]');
    if (preview) {
      openPreview({ title: preview.dataset.title, description: preview.dataset.description,
        image: preview.dataset.image }, preview);
      return;
    }
    const domain = target.closest('[data-domain]');
    if (domain) { showModal(domainModal, domain); return; }
    const profile = target.closest('[data-profile]');
    if (profile) {
      if (config.profileUrl && /^https?:\/\//i.test(config.profileUrl)) {
        window.open(config.profileUrl, '_blank', 'noopener,noreferrer');
      } else {
        openPreview({ title: 'Hợp tác cùng WebApp Bắc Ninh',
          description: 'Website – Phần mềm – Vận hành số. Cung cấp giải pháp website, phát triển phần mềm doanh nghiệp, CRM, booking và triển khai hạ tầng. Hợp tác theo mô hình Agency / White Label. Liên hệ để nhận hồ sơ năng lực phù hợp với nhu cầu hợp tác.' }, profile);
      }
      return;
    }
    const social = target.closest('[data-social]');
    if (social) {
      const url = config.socialLinks?.[social.dataset.social];
      if (url && /^https?:\/\//i.test(url)) window.open(url, '_blank', 'noopener,noreferrer');
      else toast(`Kênh ${social.dataset.social} đang được cập nhật.`);
      return;
    }
    const policy = target.closest('[data-policy]');
    if (policy) {
      openPreview({ title: policy.dataset.policy === 'privacy' ? 'Chính sách bảo mật' : 'Điều khoản sử dụng',
        description: 'Xem chính sách bảo mật và điều khoản sử dụng ở cuối trang.' }, policy);
      return;
    }
    const search = target.closest('button[data-search]');
    if (search) {
      openPreview({ title: 'Anh đang tìm dịch vụ nào?',
        description: 'Chọn một nhóm giải pháp để xem nội dung chi tiết.' }, search);
      const list = document.createElement('div'); list.className = 'preview-menu';
      [['Thiết kế Website','/dich-vu#thiet-ke-website'],['Phần mềm doanh nghiệp','/dich-vu#phan-mem'],
        ['CRM & Booking','/dich-vu#crm-booking'],['SEO, quảng cáo & vận hành số','/dich-vu#seo-quang-cao'],
        ['Hosting / Domain / Email','/hosting-domain-email']].forEach(([label, href]) => {
          const a = document.createElement('a'); a.href = href; a.textContent = label; list.append(a);
        });
      $('#preview-extra').append(list);
      return;
    }
    if (target.closest('.main-nav a')) closeMenu();
    if (!target.closest('.site-header')) closeMenu();
    if (!target.closest('.nav-dropdown')) {
      $$('.nav-dropdown.is-open').forEach(el => el.classList.remove('is-open'));
      $$('.dropdown-toggle').forEach(el => el.setAttribute('aria-expanded','false'));
    }
  });

  const form = $('#consult-form');
  form?.addEventListener('submit', async event => {
    event.preventDefault();
    const error = $('#form-error'); error.hidden = true;
    $('#form-result').hidden = true;
    const values = new FormData(form);
    const payload = {
      name: String(values.get('name') || '').trim(),
      phone: String(values.get('phone') || '').trim(),
      email: String(values.get('email') || '').trim(),
      service: String(values.get('service') || '').trim(),
      message: String(values.get('message') || '').trim(),
      consent: values.get('consent') === 'on'
    };
    const digits = payload.phone.replace(/[\s().-]/g, '');
    const fail = (message, field) => {
      error.textContent = message; error.hidden = false;
      $(`[name="${field}"]`, form)?.focus();
    };
    if (payload.name.length < 2) { fail('Vui lòng nhập họ tên tối thiểu 2 ký tự.', 'name'); return; }
    if (!/^\+?\d{8,15}$/.test(digits)) { fail('Vui lòng nhập số điện thoại hợp lệ (8–15 chữ số).', 'phone'); return; }
    if (payload.email && !$('[name="email"]',form).checkValidity()) { fail('Email chưa đúng định dạng.', 'email'); return; }
    if (!payload.consent) { fail('Vui lòng xác nhận đồng ý cung cấp thông tin.', 'consent'); return; }
    const submit = $('.form-submit',form);
    if (typeof config.submitConsultation === 'function') {
      submit.disabled = true;
      try {
        const response = await config.submitConsultation(payload);
        if (!response || response.ok !== true) throw new Error(response?.message || 'Máy chủ chưa xác nhận gửi thành công.');
        toast(response.message || 'Yêu cầu đã được máy chủ tiếp nhận.');
        form.reset(); consultModal.close();
      } catch (e) {
        error.textContent = e instanceof Error ? e.message : 'Không thể kết nối. Vui lòng thử lại.';
        error.hidden = false;
      } finally { submit.disabled = false; }
      return;
    }
    // Demo mode: build a local copy, never claim a lead was delivered.
    $('#request-summary').value = `YÊU CẦU TƯ VẤN WEBAPP BẮC NINH\nHọ tên: ${payload.name}\nĐiện thoại: ${payload.phone}\nEmail: ${payload.email || 'Chưa cung cấp'}\nDịch vụ: ${payload.service}\nNội dung: ${payload.message || 'Cần trao đổi thêm'}`;
    $('#form-result').hidden = false;
    $('#form-result').scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    document.dispatchEvent(new CustomEvent('webapp:consultation-prepared', { detail: payload }));
  });

  $('#copy-request')?.addEventListener('click', async () => {
    const summary = $('#request-summary');
    try {
      if (!navigator.clipboard?.writeText) throw new Error('Clipboard API unavailable');
      await navigator.clipboard.writeText(summary.value);
      toast('Đã sao chép nội dung yêu cầu.');
    } catch (_) {
      summary.focus(); summary.select(); summary.setSelectionRange(0, summary.value.length);
      toast('Nội dung đã được chọn. Nhấn Ctrl+C hoặc giữ để sao chép trên điện thoại.');
    }
  });

  $('#domain-form')?.addEventListener('submit', event => {
    event.preventDefault();
    const raw = String(new FormData(event.currentTarget).get('domain') || '').trim().toLowerCase();
    const result = $('#domain-result'); result.hidden = false;
    let hostname = '';
    try {
      if (!raw || /\s/.test(raw)) throw new Error('invalid');
      const parsed = new URL(raw.includes('://') ? raw : `https://${raw}`);
      if (!['https:','http:'].includes(parsed.protocol) || parsed.port || parsed.username || parsed.password ||
          (parsed.pathname !== '/' && parsed.pathname !== '') || parsed.search || parsed.hash) throw new Error('invalid');
      hostname = parsed.hostname.replace(/\.$/, '');
      const labels = hostname.split('.');
      if (hostname.length > 253 || labels.length < 2 ||
          labels.some(label => !/^[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?$/.test(label)) ||
          !/^(?:[a-z]{2,63}|xn--[a-z0-9-]{2,59})$/.test(labels.at(-1))) throw new Error('invalid');
      result.textContent = `“${hostname}” đúng định dạng tên miền. Tình trạng đăng ký chưa được xác minh. Cần kết nối API nhà đăng ký để kiểm tra còn trống.`;
    } catch (_) {
      result.textContent = 'Tên miền chưa đúng định dạng. Ví dụ: tencongty.vn hoặc tencongty.com.vn. Không nhập đường dẫn, email hoặc khoảng trắng.';
    }
  });

  // Keep a single answer expanded at a time, including on keyboard activation.
  $$('.faq-item').forEach(item => item.addEventListener('toggle', () => {
    if (item.open) $$('.faq-item[open]').forEach(other => { if (other !== item) other.open = false; });
  }));

  window.matchMedia('(min-width: 960px)').addEventListener('change', event => { if (event.matches) closeMenu(); });

})();
