(() => {
  'use strict';
  document.querySelectorAll('[data-demo-form]').forEach((form) => {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      event.stopPropagation();
      form.classList.add('was-validated');
      const success = form.querySelector('[data-form-success]');
      if (!form.checkValidity()) {
        success?.classList.add('d-none');
        return;
      }
      success?.classList.remove('d-none');
      success?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
  });
})();