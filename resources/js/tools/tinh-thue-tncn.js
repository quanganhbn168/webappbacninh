// Tính thuế thu nhập cá nhân.
import { ToolKit } from '../site/toolkit';

const root = document.getElementById('tax-tool');
const error = root.querySelector('[data-tool-error]');
const insuranceInput = document.getElementById('insurance');
const autoInsurance = document.getElementById('auto-insurance');
let timer;

const version = ToolKit.toggleGroup(root.querySelector('[data-toggle-group]'), () => calculate());
const gross = ToolKit.moneyInput(document.getElementById('gross-income'), () => schedule());
const insurance = ToolKit.moneyInput(insuranceInput, () => schedule());
const other = ToolKit.moneyInput(document.getElementById('other-deductions'), () => schedule());

function syncInsurance() {
    insuranceInput.disabled = autoInsurance.checked;
    if (autoInsurance.checked) {
        insuranceInput.value = ToolKit.number(gross() * 0.105);
        insuranceInput.dataset.value = String(Math.round(gross() * 0.105));
    }
}

function schedule() {
    clearTimeout(timer);
    timer = setTimeout(calculate, 300);
}

async function calculate() {
    syncInsurance();
    try {
        const { data } = await ToolKit.postJson(root.dataset.url, {
            gross_income: gross(),
            dependents: Number(document.getElementById('dependents').value),
            insurance: autoInsurance.checked ? null : insurance(),
            other_deductions: other(),
            version: version(),
        });
        error.hidden = true;
        ToolKit.render(root, data);
    } catch (exception) {
        error.textContent = exception.message;
        error.hidden = false;
    }
}

document.getElementById('dependents').addEventListener('change', calculate);
autoInsurance.addEventListener('change', calculate);
calculate();
