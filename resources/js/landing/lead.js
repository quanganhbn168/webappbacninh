// Landing forms marked data-landing-lead post to the shared contact endpoint, so every
// request becomes a Lead in the admin and a Telegram alert, with no page reload.
const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function status(form, message, ok) {
    const element = form.querySelector('[data-lead-status]');
    if (!element) return;
    element.textContent = message;
    element.classList.toggle('is-error', !ok);
    element.hidden = false;
}

export function initLandingLeads(onSent = () => {}) {
    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('form[data-landing-lead]');
        if (!form) return;
        event.preventDefault();
        if (!form.reportValidity()) return;

        const data = new FormData(form);
        data.set('need', form.dataset.need ?? '');
        data.set('source', window.location.pathname);

        // Extra answers (for example the room type) travel in the free-text message.
        const notes = [...form.querySelectorAll('[data-lead-note]')].map((field) => `${field.dataset.leadNote}: ${field.value}`);
        if (notes.length) data.set('message', notes.join('\n'));

        const button = form.querySelector('[type="submit"]');
        button.disabled = true;
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: data,
                headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf() },
            });
            const payload = await response.json().catch(() => ({}));
            if (!response.ok) {
                throw new Error(Object.values(payload.errors ?? {}).flat()[0] ?? payload.message ?? 'Không thể gửi yêu cầu. Vui lòng thử lại.');
            }
            form.reset();
            status(form, payload.message, true);
            onSent(payload.message);
        } catch (error) {
            status(form, error.message, false);
        } finally {
            button.disabled = false;
        }
    });
}
