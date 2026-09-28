// Any element with data-consult opens the consultation modal; its value fills "Nhu cầu".
// data-domain opens the domain checker.
export function initConsult() {
    const consult = document.getElementById('consult-modal');
    const domain = document.getElementById('domain-modal');

    document.addEventListener('click', event => {
        const trigger = event.target.closest('[data-consult], [data-domain]');
        if (!trigger) return;
        event.preventDefault();

        const nav = document.getElementById('site-nav');
        if (nav) window.bootstrap.Offcanvas.getInstance(nav)?.hide();

        if (trigger.hasAttribute('data-domain')) {
            window.bootstrap.Modal.getOrCreateInstance(domain).show(trigger);
            return;
        }

        const form = consult.querySelector('form');
        form.querySelector('[data-lead-need]').value = trigger.dataset.consult || '';
        form.querySelector('[data-lead-status]').hidden = true;
        window.bootstrap.Modal.getOrCreateInstance(consult).show(trigger);
    });
}
