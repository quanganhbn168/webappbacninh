// Vòng quay ăn trưa: vẽ bánh xe trên canvas, quay rồi hiện kết quả trong modal.
const modes = {
    rich: [
        { text: 'Pizza', color: '#FF5733', meme: 'https://media.giphy.com/media/STH1UmoVpxTnXqS9E3/giphy.gif' },
        { text: 'Sushi', color: '#C70039', meme: 'https://media.giphy.com/media/13CoXDiaCcCzdK/giphy.gif' },
        { text: 'Lẩu Haidilao', color: '#900C3F', meme: 'https://media.giphy.com/media/l2Je3qSgD6I9EN0LC/giphy.gif' },
        { text: 'Buffet Sen', color: '#581845', meme: 'https://media.giphy.com/media/wZtxnyxZwRMZi/giphy.gif' },
        { text: 'Steak', color: '#FFC300', meme: 'https://media.giphy.com/media/3o7TKnKXMdlljrThwk/giphy.gif' },
        { text: 'Starbucks', color: '#DAF7A6', meme: 'https://media.giphy.com/media/M4trH1uta8OPu/giphy.gif' },
    ],
    normal: [
        { text: 'Cơm Rang', color: '#FFC300', meme: null },
        { text: 'Bún Chả', color: '#DAF7A6', meme: null },
        { text: 'Phở Bò', color: '#FF5733', meme: null },
        { text: 'Bún Đậu', color: '#33FF57', meme: null },
        { text: 'Cơm Tấm', color: '#3357FF', meme: null },
        { text: 'Bánh Mì', color: '#FF33F6', meme: null },
    ],
    poor: [
        { text: 'Mì Tôm', color: '#FF5733', meme: 'https://media.giphy.com/media/12uXi1GXBibALC/giphy.gif' },
        { text: 'Bánh Mì Không', color: '#C70039', meme: 'https://media.giphy.com/media/3o6Zt44rW4tH9YtCne/giphy.gif' },
        { text: 'Xôi 5k', color: '#900C3F', meme: null },
        { text: 'Nhịn Ăn', color: '#000000', textColor: '#fff', meme: 'https://media.giphy.com/media/26ufcVAp3AiJJsrIs/giphy.gif' },
        { text: 'Uống Nước', color: '#33C4FF', meme: 'https://media.giphy.com/media/3o7TKSjRrfPHj3unx6/giphy.gif' },
        { text: 'Gói Hảo Hảo', color: '#FFC300', meme: null },
    ],
};

const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
const spinButton = document.querySelector('.spin-btn');
const resultDesc = document.getElementById('resultDesc');

let options = modes.rich;
let arc = (2 * Math.PI) / options.length;
let startAngle = 0;
let spinAngleStart = 0;
let spinTime = 0;
let spinTimeTotal = 0;
let spinTimeout = null;

function draw() {
    const outsideRadius = 200;
    const textRadius = 160;
    const insideRadius = 50;

    ctx.clearRect(0, 0, 500, 500);
    ctx.strokeStyle = 'white';
    ctx.lineWidth = 2;
    ctx.font = 'bold 16px Inter, sans-serif';

    options.forEach((option, i) => {
        const angle = startAngle + i * arc;
        ctx.fillStyle = option.color;
        ctx.beginPath();
        ctx.arc(250, 250, outsideRadius, angle, angle + arc, false);
        ctx.arc(250, 250, insideRadius, angle + arc, angle, true);
        ctx.stroke();
        ctx.fill();

        ctx.save();
        ctx.shadowOffsetX = -1;
        ctx.shadowOffsetY = -1;
        ctx.shadowBlur = 0;
        ctx.fillStyle = option.textColor || 'white';
        ctx.translate(250 + Math.cos(angle + arc / 2) * textRadius, 250 + Math.sin(angle + arc / 2) * textRadius);
        ctx.rotate(angle + arc / 2 + Math.PI / 2);
        ctx.fillText(option.text, -ctx.measureText(option.text).width / 2, 0);
        ctx.restore();
    });
}

function setMode(mode) {
    options = modes[mode];
    arc = (2 * Math.PI) / options.length;
    draw();
}

const easeOut = (t, b, c, d) => {
    const ts = (t /= d) * t;
    const tc = ts * t;
    return b + c * (tc + -3 * ts + 3 * t);
};

function rotate() {
    spinTime += 30;
    if (spinTime >= spinTimeTotal) { stop(); return; }
    const spinAngle = spinAngleStart - easeOut(spinTime, 0, spinAngleStart, spinTimeTotal);
    startAngle += (spinAngle * Math.PI) / 180;
    draw();
    spinTimeout = setTimeout(rotate, 30);
}

function spin() {
    if (spinTimeout) return; // đang quay
    spinAngleStart = Math.random() * 10 + 10;
    spinTime = 0;
    spinTimeTotal = Math.random() * 3 + 4 * 1000;
    spinButton.disabled = true;
    rotate();
}

function stop() {
    clearTimeout(spinTimeout);
    spinTimeout = null;
    const degrees = (startAngle * 180) / Math.PI + 90;
    const index = Math.floor((360 - (degrees % 360)) / ((arc * 180) / Math.PI));
    showResult(options[index]);
    spinButton.disabled = false;
}

function showResult(item) {
    document.getElementById('resultName').textContent = item.text;
    const image = document.getElementById('resultImageContainer');
    resultDesc.classList.remove('alert-light', 'alert-success', 'alert-danger');

    if (item.meme) {
        image.innerHTML = `<img src="${item.meme}" class="img-fluid rounded" style="max-height: 200px;" alt="">`;
        resultDesc.textContent = item.text === 'Nhịn Ăn' ? 'Chia buồn cùng bạn! Nghèo thì phải chấp nhận thôi...' : `Chúc mừng bạn đã quay trúng ${item.text}`;
        resultDesc.classList.add(item.text === 'Nhịn Ăn' || item.text === 'Mì Tôm' ? 'alert-danger' : 'alert-success');
    } else {
        // Icon dao dĩa mặc định nằm sẵn trong <template data-fallback-icon> của trang.
        image.replaceChildren(document.querySelector('[data-fallback-icon]').content.cloneNode(true));
        resultDesc.textContent = `Hôm nay ăn ${item.text} nhé!`;
        resultDesc.classList.add('alert-light');
    }

    window.bootstrap.Modal.getOrCreateInstance(document.getElementById('resultModal')).show();
}

document.querySelectorAll('[data-mode]').forEach(label => label.addEventListener('click', () => setMode(label.dataset.mode)));
document.querySelectorAll('[data-action="spin"]').forEach(button => button.addEventListener('click', spin));
document.querySelector('[data-action="share"]').addEventListener('click', () => {
    alert('Đã copy kết quả vào clipboard! Hãy dán lên Facebook ngay!');
});

draw();
