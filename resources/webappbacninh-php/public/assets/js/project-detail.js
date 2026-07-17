(() => {
  'use strict';

  const mainImage = document.getElementById('projectMainImage');
  document.querySelectorAll('[data-gallery-image]').forEach(button => {
    button.addEventListener('click', () => {
      if (mainImage) mainImage.src = button.dataset.galleryImage || mainImage.src;
      document.querySelectorAll('[data-gallery-image]').forEach(item => item.classList.toggle('is-active', item === button));
    });
  });

  document.getElementById('detailProjectForm')?.addEventListener('submit', event => {
    event.preventDefault();
    const form = event.currentTarget;
    if (!form.checkValidity()) {
      form.classList.add('was-validated');
      return;
    }
    form.classList.add('was-validated');
    document.getElementById('detailProjectSuccess')?.classList.remove('d-none');
  });
})();
