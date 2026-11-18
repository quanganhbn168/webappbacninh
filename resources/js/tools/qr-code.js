// Tạo QR code. Cần /vendor/easy-qrcode-4.5.0.min.js (biến toàn cục QRCode) được nạp trước.
const field = id => document.getElementById(id);
const preview = field('qrcode');
let logo = null;

function generate() {
    preview.replaceChildren();
    const size = parseInt(field('qrSize').value, 10);
    try {
        new window.QRCode(preview, {
            text: field('qrText').value || 'https://webappbacninh.vn',
            width: size,
            height: size,
            colorDark: field('qrColor').value,
            colorLight: field('qrBgColor').value,
            correctLevel: window.QRCode.CorrectLevel.H, // mức sửa lỗi cao để chèn được logo
            logo,
            logoWidth: size * 0.2,
            logoHeight: size * 0.2,
            logoBackgroundColor: '#ffffff',
            logoBackgroundTransparent: false,
            quietZone: 10,
            quietZoneColor: 'rgba(0,0,0,0)',
        });
    } catch (error) {
        console.error(error);
    }
}

function download() {
    const canvas = preview.querySelector('canvas');
    if (!canvas) return;
    const link = document.createElement('a');
    link.download = `qrcode-${Date.now()}.png`;
    link.href = canvas.toDataURL('image/png').replace('image/png', 'image/octet-stream');
    link.click();
}

['qrText', 'qrBgColor', 'qrColor', 'qrSize'].forEach(id => {
    field(id).addEventListener('input', generate);
    field(id).addEventListener('change', generate);
});

field('qrLogo').addEventListener('change', event => {
    const file = event.target.files[0];
    if (!file) { logo = null; generate(); return; }
    const reader = new FileReader();
    reader.onload = loaded => { logo = loaded.target.result; generate(); };
    reader.readAsDataURL(file);
});

document.querySelector('[data-action="generate"]').addEventListener('click', generate);
document.querySelector('[data-action="download"]').addEventListener('click', download);

generate();
