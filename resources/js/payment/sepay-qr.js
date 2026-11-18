// Trang QR SePay: nút sao chép, và hỏi trạng thái đơn mỗi 5 giây.
document.querySelectorAll('[data-copy]').forEach(button => button.addEventListener('click', () => {
    navigator.clipboard.writeText(button.dataset.copy).then(() => {
        button.classList.add('text-success');
        button.title = 'Đã sao chép';
        setTimeout(() => button.classList.remove('text-success'), 2000);
    });
}));

const root = document.querySelector('[data-status-url]');
if (root) {
    const timer = setInterval(() => {
        fetch(root.dataset.statusUrl)
            .then(response => response.json())
            .then(data => {
                if (data.status !== 'completed') return;
                clearInterval(timer);
                window.location.href = root.dataset.successUrl;
            })
            .catch(() => {});
    }, 5000);
}
