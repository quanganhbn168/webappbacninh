// Tính thuế hộ kinh doanh.
import { ToolKit } from '../site/toolkit';

const root = document.getElementById('tax-tool');
const error = root.querySelector('[data-tool-error]');
let timer;

const version = ToolKit.toggleGroup(root.querySelector('[data-toggle-group]'), () => calculate());
const revenue = ToolKit.moneyInput(document.getElementById('revenue'), () => {
    clearTimeout(timer);
    timer = setTimeout(calculate, 300);
});

async function calculate() {
    document.querySelectorAll('[data-threshold]').forEach(el => { el.textContent = version() === '2026' ? '500 triệu' : '100 triệu'; });
    try {
        const { data } = await ToolKit.postJson(root.dataset.url, {
            revenue: revenue(),
            sector: root.querySelector('input[name="sector"]:checked').value,
            version: version(),
        });
        error.hidden = true;
        root.querySelector('[data-result]').hidden = false;
        root.querySelector('[data-exempt]').hidden = data.taxable_amount > 0;
        root.querySelector('[data-taxed]').hidden = !(data.taxable_amount > 0);
        ToolKit.render(root, data);
    } catch (exception) {
        error.textContent = exception.message;
        error.hidden = false;
    }
}

root.querySelectorAll('input[name="sector"]').forEach(input => input.addEventListener('change', calculate));
calculate();
