/*
 * Shared helpers for the tool pages (tax calculators, QR, cover images…).
 * Bootstrap handles components; pages call ToolKit for number inputs,
 * JSON requests and filling results into [data-field] elements.
 */
const moneyFormat = new Intl.NumberFormat('vi-VN');

const get = (object, path) => path.split('.').reduce((value, key) => (value == null ? undefined : value[key]), object);

function format(value, type) {
    if (value === undefined || value === null) value = type === 'text' ? '' : 0;
    switch (type) {
        case 'money': return moneyFormat.format(Math.round(Number(value) || 0)) + ' đ';
        case 'number': return moneyFormat.format(Math.round(Number(value) || 0));
        case 'percent': return (Number(value) || 0) + '%';
        default: return String(value);
    }
}

export const ToolKit = {
    money: value => format(value, 'money'),
    number: value => format(value, 'number'),

    parseNumber(text) {
        return parseFloat(String(text ?? '').replace(/[^\d]/g, '')) || 0;
    },

    /** Formats a money input with thousand separators while typing; the raw value is in dataset.value. */
    moneyInput(input, onChange) {
        const sync = () => {
            const value = ToolKit.parseNumber(input.value);
            input.dataset.value = String(value);
            input.value = value ? format(value, 'number') : '';
        };
        sync();
        input.addEventListener('input', () => { sync(); onChange?.(); });
        return () => Number(input.dataset.value || 0);
    },

    async postJson(url, payload) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            },
            body: JSON.stringify(payload),
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok || data.success === false) {
            throw new Error(data.message || Object.values(data.errors || {}).flat()[0] || 'Không thể xử lý yêu cầu. Vui lòng thử lại.');
        }
        return data;
    },

    /**
     * Fills [data-field="a.b"] (optional data-format: money|number|percent|text),
     * shows/hides [data-show-if="a.b"] by truthiness and repeats the <template>
     * inside [data-rows="list"] for each item (fields inside are item-relative).
     */
    render(root, data) {
        root.querySelectorAll('[data-rows]').forEach(container => {
            const template = container.querySelector('template');
            container.querySelectorAll('[data-row]').forEach(row => row.remove());
            (get(data, container.dataset.rows) || []).forEach(item => {
                const fragment = template.content.cloneNode(true);
                fragment.querySelectorAll('[data-item]').forEach(el => {
                    el.textContent = format(get(item, el.dataset.item), el.dataset.format);
                });
                fragment.querySelectorAll('[data-item-show]').forEach(el => { el.hidden = !get(item, el.dataset.itemShow); });
                [...fragment.children].forEach(child => child.setAttribute('data-row', ''));
                container.appendChild(fragment);
            });
        });
        root.querySelectorAll('[data-field]').forEach(el => {
            el.textContent = format(get(data, el.dataset.field), el.dataset.format);
        });
        root.querySelectorAll('[data-show-if]').forEach(el => {
            const value = get(data, el.dataset.showIf);
            el.hidden = !(Array.isArray(value) ? value.length : value);
        });
    },

    /** Marks one button of a [data-toggle-group] as active and returns its value on click. */
    toggleGroup(group, onChange) {
        group.addEventListener('click', event => {
            const button = event.target.closest('[data-value]');
            if (!button) return;
            group.querySelectorAll('[data-value]').forEach(el => {
                const active = el === button;
                el.classList.toggle('active', active);
                el.setAttribute('aria-pressed', String(active));
            });
            onChange(button.dataset.value);
        });
        return () => group.querySelector('[data-value].active')?.dataset.value;
    },
};

window.ToolKit = ToolKit;
