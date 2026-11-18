// Tạo QR ngân hàng: ảnh VietQR từ img.vietqr.io theo ngân hàng, số tài khoản, số tiền và nội dung.
const field = id => document.getElementById(id);
const preview = field('bankQrcode');

function generate() {
    const bank = field('bankId').value;
    const account = field('bankAccount').value;
    if (!bank || !account) return;

    const amount = field('bankAmount').value || 0;
    const content = encodeURIComponent(field('bankContent').value || '');
    const name = encodeURIComponent(field('bankName').value || '');
    const url = `https://img.vietqr.io/image/${bank}-${account}-print.png?amount=${amount}&addInfo=${content}&accountName=${name}`;

    preview.innerHTML = '<div class="spinner-border text-success" role="status"><span class="visually-hidden">Đang tải...</span></div>';
    const image = new Image();
    image.onload = () => {
        preview.innerHTML = `<img src="${url}" class="img-fluid border shadow-sm" id="vietqrInfoImg" alt="VietQR" style="max-height:450px;">`;
    };
    image.onerror = () => {
        preview.innerHTML = '<div class="text-danger">Lỗi tạo mã. Vui lòng kiểm tra lại thông tin.</div>';
    };
    image.src = url;
}

function download() {
    const image = field('vietqrInfoImg');
    // Ảnh nằm trên domain khác nên mở tab mới thay vì tải trực tiếp.
    if (image?.src) window.open(image.src, '_blank');
    else alert('Vui lòng tạo mã QR trước!');
}

document.querySelectorAll('#bankId, #bankAccount, #bankName, #bankAmount, #bankContent').forEach(input => input.addEventListener('change', generate));
document.querySelector('[data-action="generate"]').addEventListener('click', generate);
document.querySelector('[data-action="download"]').addEventListener('click', download);
