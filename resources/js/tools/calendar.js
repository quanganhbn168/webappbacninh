// Lịch vạn niên. Cần /vendor/lunar-1.7.7.js (biến toàn cục Solar) được nạp trước.
const today = new Date();
let month = today.getMonth();
let year = today.getFullYear();

const weekDays = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
const can = ['Canh', 'Tân', 'Nhâm', 'Quý', 'Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ'];
const chi = ['Thân', 'Dậu', 'Tuất', 'Hợi', 'Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi'];

const text = (id, value) => { document.getElementById(id).textContent = value; };

function lunarOf(date) {
    const lunar = window.Solar.fromDate(date).getLunar();
    return { day: lunar.getDay(), month: Math.abs(lunar.getMonth()), leap: lunar.getMonth() < 0, year: lunar.getYear() };
}

function renderToday() {
    text('todayDay', today.getDate());
    text('todayMonthYear', `Tháng ${today.getMonth() + 1}/${today.getFullYear()}`);
    text('todayWeekday', weekDays[today.getDay()]);

    const lunar = lunarOf(today);
    text('lunarDay', lunar.day);
    text('lunarMonthYear', `Tháng ${lunar.month}${lunar.leap ? ' (nhuận)' : ''} Năm ${lunar.year}`);
    text('canChi', `Năm ${can[lunar.year % 10]} ${chi[lunar.year % 12]}`);
}

function renderCalendar() {
    text('calHeader', `Tháng ${month + 1} Năm ${year}`);

    const cells = [];
    for (let i = 0; i < new Date(year, month, 1).getDay(); i++) {
        cells.push('<div class="cal-day empty"></div>');
    }
    for (let d = 1; d <= new Date(year, month + 1, 0).getDate(); d++) {
        const date = new Date(year, month, d);
        const lunar = lunarOf(date);
        const classes = ['cal-day'];
        if (date.toDateString() === today.toDateString()) classes.push('today');
        if (date.getDay() === 0) classes.push('sunday');
        const lunarLabel = lunar.day === 1 ? `${lunar.day}/${lunar.month}` : lunar.day;
        cells.push(`<div class="${classes.join(' ')}"><div class="solar">${d}</div><div class="lunar">${lunarLabel}</div></div>`);
    }
    document.getElementById('calendarDays').innerHTML = cells.join('');
}

function shift(step) {
    month += step;
    if (month < 0) { month = 11; year--; }
    if (month > 11) { month = 0; year++; }
    renderCalendar();
}

document.querySelector('[data-action="prev"]').addEventListener('click', () => shift(-1));
document.querySelector('[data-action="next"]').addEventListener('click', () => shift(1));
document.querySelector('[data-action="today"]').addEventListener('click', () => {
    month = today.getMonth();
    year = today.getFullYear();
    renderCalendar();
});

renderToday();
renderCalendar();
