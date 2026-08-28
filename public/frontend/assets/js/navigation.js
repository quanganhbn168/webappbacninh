(() => {
  'use strict';

  document.querySelectorAll('.desktop-dropdown').forEach((dropdown) => {
    const trigger = dropdown.querySelector(':scope > .dropdown-toggle');
    const menu = dropdown.querySelector(':scope > .dropdown-menu');
    let closeTimer;

    const supportsHover = () => window.matchMedia('(min-width: 1200px) and (hover: hover)').matches;
    const cancelClose = () => window.clearTimeout(closeTimer);

    const open = () => {
      cancelClose();
      dropdown.classList.add('is-open');
      trigger?.setAttribute('aria-expanded', 'true');
    };
    const close = () => {
      cancelClose();
      dropdown.classList.remove('is-open');
      trigger?.setAttribute('aria-expanded', 'false');
    };
    const scheduleClose = () => {
      if (!supportsHover()) return;

      cancelClose();
      closeTimer = window.setTimeout(() => {
        if (!trigger?.matches(':hover') && !menu?.matches(':hover')) close();
      }, 50);
    };

    trigger?.addEventListener('pointerenter', () => {
      if (supportsHover()) open();
    });
    trigger?.addEventListener('pointerleave', scheduleClose);
    menu?.addEventListener('pointerenter', () => {
      if (supportsHover()) open();
    });
    menu?.addEventListener('pointerleave', scheduleClose);

    trigger?.addEventListener('click', (event) => {
      if (!supportsHover() || dropdown.classList.contains('is-open')) return;

      event.preventDefault();
      open();
    });

    trigger?.addEventListener('focus', open);
    trigger?.addEventListener('keydown', (event) => {
      if (event.key === 'ArrowDown') {
        event.preventDefault();
        open();
        dropdown.querySelector('.dropdown-item, .mega-menu__link')?.focus();
      }
    });
    dropdown.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        close();
        trigger?.focus();
      }
    });
    dropdown.addEventListener('focusout', () => {
      window.setTimeout(() => {
        if (!dropdown.contains(document.activeElement)) close();
      }, 0);
    });
  });

  document.querySelectorAll('.mobile-nav__toggle').forEach((button) => {
    const target = document.querySelector(button.getAttribute('data-ui-target') || '');
    if (!target) return;
    target.addEventListener('ui:show', () => button.setAttribute('aria-expanded', 'true'));
    target.addEventListener('ui:hide', () => button.setAttribute('aria-expanded', 'false'));
  });

  const mobileMenu = document.getElementById('mobileMenu');
  mobileMenu?.querySelectorAll('.mobile-nav a').forEach((link) => {
    link.addEventListener('click', () => {
      window.tailwindUi?.close(mobileMenu);
    });
  });
})();
