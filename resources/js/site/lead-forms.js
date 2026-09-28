import { toast } from './toast';

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function showStatus(form, message, ok) {
    const status = form.querySelector('[data-lead-status]');
    if (!status) return;
    status.className = `alert mt-3 mb-0 ${ok ? 'alert-success' : 'alert-danger'}`;
    status.textContent = message;
    status.hidden = false;
}

// Forms marked data-lead-form post to the lead endpoint without leaving the page.
// Without JavaScript they still submit normally and the server redirects back.
async function submit(event) {
    const form = event.target.closest('form[data-lead-form]');
    if (!form) return;
    event.preventDefault();

    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        form.querySelector(':invalid')?.focus();
        return;
    }

    const button = form.querySelector('[type="submit"]');
    const data = new FormData(form);
    data.set('source', window.location.pathname);
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
        form.classList.remove('was-validated');
        showStatus(form, payload.message, true);
        const modal = form.closest('.modal');
        if (modal) {
            window.bootstrap.Modal.getInstance(modal)?.hide();
            toast(payload.message);
        }
    } catch (error) {
        showStatus(form, error.message, false);
    } finally {
        button.disabled = false;
    }
}

export function initLeadForms() {
    document.addEventListener('submit', submit);
}
