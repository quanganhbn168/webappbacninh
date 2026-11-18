// Tính thuế doanh nghiệp nhỏ và vừa.
import { ToolKit } from '../site/toolkit';

const root = document.getElementById('tax-tool');
const error = root.querySelector('[data-tool-error]');
const expensesInput = document.getElementById('expenses');
const newBusiness = document.getElementById('new-business');
const years = document.getElementById('years');
let timer;

const schedule = () => { clearTimeout(timer); timer = setTimeout(calculate, 300); };
const expenses = ToolKit.moneyInput(expensesInput, schedule);
const revenue = ToolKit.moneyInput(document.getElementById('revenue'), () => {
    // Expenses follow revenue at 70% until the user types their own figure.
    expensesInput.value = ToolKit.number(revenue() * 0.7);
    expensesInput.dataset.value = String(Math.round(revenue() * 0.7));
    schedule();
});

async function calculate() {
    document.getElementById('years-wrapper').hidden = !newBusiness.checked;
    document.getElementById('exempt-hint').hidden = Number(years.value) >= 3;
    try {
        const { data } = await ToolKit.postJson(root.dataset.url, {
            revenue: revenue(),
            expenses: expenses(),
            is_new_business: newBusiness.checked,
            years_in_business: Number(years.value),
            version: '2026',
        });
        error.hidden = true;
        root.querySelector('[data-result]').hidden = false;
        root.querySelector('[data-exempt-badge]').hidden = !(data.meta?.is_new_business && data.meta?.exempt_years_remaining > 0);
        ToolKit.render(root, data);
    } catch (exception) {
        error.textContent = exception.message;
        error.hidden = false;
    }
}

newBusiness.addEventListener('change', calculate);
years.addEventListener('change', calculate);
calculate();
