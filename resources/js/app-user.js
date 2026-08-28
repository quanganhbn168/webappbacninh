import AOS from 'aos';
import 'aos/dist/aos.css';

window.AOS = AOS;

const resolveTarget = (trigger) => {
    const selector = trigger?.getAttribute('data-ui-target') || trigger?.getAttribute('href');

    if (!selector?.startsWith('#')) {
        return null;
    }

    return document.querySelector(selector);
};

const createBackdrop = (element) => {
    const backdrop = document.createElement('div');
    backdrop.className = 'ui-backdrop';
    backdrop.dataset.uiBackdropFor = element.id;
    backdrop.addEventListener('click', () => window.tailwindUi.close(element));
    document.body.append(backdrop);
};

const removeBackdrop = (element) => {
    document.querySelector(`[data-ui-backdrop-for="${element.id}"]`)?.remove();
};

const setOverlayState = (element, isOpen) => {
    element.classList.toggle('show', isOpen);
    element.setAttribute('aria-hidden', isOpen ? 'false' : 'true');

    if (isOpen) {
        createBackdrop(element);
        document.body.classList.add('ui-overlay-open');
        element.querySelector('button, a, input, select, textarea, [tabindex]:not([tabindex="-1"])')?.focus();
    } else {
        removeBackdrop(element);
        if (!document.querySelector('.offcanvas.show, .modal.show')) {
            document.body.classList.remove('ui-overlay-open');
        }
    }
};

window.tailwindUi = {
    open(element) {
        if (!element) return;

        if (element.matches('.offcanvas, .modal')) {
            setOverlayState(element, true);
        }
    },

    close(element) {
        if (!element) return;

        if (element.matches('.offcanvas, .modal')) {
            setOverlayState(element, false);
        }
    },

    toggleCollapse(element, trigger) {
        if (!element) return;

        const willOpen = !element.classList.contains('show');
        const parentSelector = element.getAttribute('data-ui-parent');

        if (willOpen && parentSelector) {
            document.querySelector(parentSelector)?.querySelectorAll('.collapse.show').forEach((sibling) => {
                if (sibling === element) return;
                sibling.classList.remove('show');
                document.querySelectorAll(`[data-ui-target="#${sibling.id}"]`).forEach((control) => {
                    control.classList.add('collapsed');
                    control.setAttribute('aria-expanded', 'false');
                });
                sibling.dispatchEvent(new CustomEvent('ui:hide'));
            });
        }

        element.classList.toggle('show', willOpen);
        trigger?.classList.toggle('collapsed', !willOpen);
        trigger?.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        element.dispatchEvent(new CustomEvent(willOpen ? 'ui:show' : 'ui:hide'));
    },
};

document.addEventListener('click', (event) => {
    const dismiss = event.target.closest('[data-ui-dismiss]');
    if (dismiss) {
        const type = dismiss.getAttribute('data-ui-dismiss');
        window.tailwindUi.close(dismiss.closest(`.${type}`));
        return;
    }

    const trigger = event.target.closest('[data-ui-toggle]');
    if (!trigger) return;

    const type = trigger.getAttribute('data-ui-toggle');
    const target = resolveTarget(trigger);

    if (type === 'collapse') {
        window.tailwindUi.toggleCollapse(target, trigger);
    } else if (type === 'dropdown') {
        event.preventDefault();
        const dropdown = trigger.closest('.dropdown');
        const willOpen = !dropdown?.classList.contains('is-open');
        document.querySelectorAll('.dropdown.is-open').forEach((item) => item.classList.remove('is-open'));
        dropdown?.classList.toggle('is-open', willOpen);
        trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
    } else if (type === 'offcanvas' || type === 'modal') {
        event.preventDefault();
        window.tailwindUi.open(target);
    }
});

document.addEventListener('click', (event) => {
    if (!event.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown.is-open').forEach((item) => item.classList.remove('is-open'));
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;

    const overlay = [...document.querySelectorAll('.offcanvas.show, .modal.show')].at(-1);
    if (overlay) {
        window.tailwindUi.close(overlay);
    }
});

AOS.init({
    duration: 650,
    easing: 'ease-out-cubic',
    once: true,
    offset: 60,
    disable: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
});
