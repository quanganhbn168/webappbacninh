(() => {
  const token = document.querySelector('meta[name="csrf-token"]')?.content || '';

  document.addEventListener('submit', async (event) => {
    const form = event.target.closest('form[data-lead-form]');
    if (!form) return;

    event.preventDefault();
    event.stopImmediatePropagation();

    if (!form.checkValidity()) {
      form.classList.add('was-validated');
      return;
    }

    const button = form.querySelector('[type="submit"]');
    const originalText = button?.innerHTML;
    if (button) {
      button.disabled = true;
      button.textContent = 'Đang gửi...';
    }

    const data = new FormData(form);
    data.set('source', window.location.pathname);

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: data,
        headers: {
          Accept: 'application/json',
          'X-CSRF-TOKEN': token,
        },
      });
      const payload = await response.json();
      if (!response.ok) throw new Error(payload.message || 'Thông tin chưa hợp lệ.');

      let alert = form.querySelector('[data-form-success], [id$="Success"]');
      if (!alert) {
        alert = document.createElement('div');
        alert.className = 'alert alert-success mt-3';
        form.appendChild(alert);
      }
      alert.textContent = payload.message;
      alert.classList.remove('d-none');
      form.reset();
      form.classList.remove('was-validated');
    } catch (error) {
      let alert = form.querySelector('[data-form-error]');
      if (!alert) {
        alert = document.createElement('div');
        alert.className = 'alert alert-danger mt-3';
        alert.dataset.formError = '';
        form.appendChild(alert);
      }
      alert.textContent = error.message;
    } finally {
      if (button) {
        button.disabled = false;
        button.innerHTML = originalText;
      }
    }
  }, true);
})();
