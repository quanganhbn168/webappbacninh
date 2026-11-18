// Trang thanh toán: bấm vào thẻ phương thức để chọn nhà cung cấp.
const input = document.getElementById('selectedProvider');
const methods = document.querySelectorAll('.payment-method');

methods.forEach(method => method.addEventListener('click', () => {
    input.value = method.dataset.provider;
    methods.forEach(other => {
        other.classList.toggle('active', other === method);
        other.querySelector('input[type="radio"]').checked = other === method;
    });
}));
