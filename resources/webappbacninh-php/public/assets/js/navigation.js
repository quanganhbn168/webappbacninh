(() => {
  'use strict';

  document.querySelectorAll('.desktop-dropdown').forEach((dropdown) => {
    const trigger = dropdown.querySelector(':scope > .dropdown-toggle');
    trigger?.addEventListener('keydown', (event) => {
      if (event.key === 'ArrowDown') {
        event.preventDefault();
        dropdown.classList.add('is-open');
        dropdown.querySelector('.dropdown-item')?.focus();
      }
    });
    dropdown.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        dropdown.classList.remove('is-open');
        trigger?.focus();
      }
    });
    dropdown.addEventListener('focusout', () => {
      window.setTimeout(() => {
        if (!dropdown.contains(document.activeElement)) dropdown.classList.remove('is-open');
      }, 0);
    });
  });

  document.querySelectorAll('.mobile-nav__toggle').forEach((button) => {
    const target = document.querySelector(button.getAttribute('data-bs-target') || '');
    if (!target) return;
    target.addEventListener('show.bs.collapse', () => button.setAttribute('aria-expanded', 'true'));
    target.addEventListener('hide.bs.collapse', () => button.setAttribute('aria-expanded', 'false'));
  });

  const mobileMenu = document.getElementById('mobileMenu');
  mobileMenu?.querySelectorAll('.mobile-nav a').forEach((link) => {
    link.addEventListener('click', () => {
      bootstrap.Offcanvas.getInstance(mobileMenu)?.hide();
    });
  });
})();
