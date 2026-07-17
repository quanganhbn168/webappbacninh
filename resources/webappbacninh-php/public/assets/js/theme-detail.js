(() => {
  'use strict';
  const mainImage = document.getElementById('themeMainImage');
  document.querySelectorAll('[data-gallery-image]').forEach(button => {
    button.addEventListener('click', () => {
      if (mainImage) mainImage.src = button.dataset.galleryImage;
      document.querySelectorAll('[data-gallery-image]').forEach(item => item.classList.remove('is-active'));
      button.classList.add('is-active');
    });
  });

  document.querySelectorAll('[data-select-theme]').forEach(button => {
    button.addEventListener('click', () => {
      const input = document.getElementById('selectedThemeCode');
      if (input) input.value = button.dataset.selectTheme || '';
    });
  });

  document.getElementById('themeDetailForm')?.addEventListener('submit', event => {
    event.preventDefault();
    const form = event.currentTarget;
    if (!form.checkValidity()) {
      form.classList.add('was-validated');
      return;
    }
    document.getElementById('themeDetailSuccess')?.classList.remove('d-none');
  });
})();
