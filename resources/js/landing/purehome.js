const menuButton = document.querySelector('.ph-menu');
const navigation = document.querySelector('.ph-nav');

function closeMenu() {
    navigation.classList.remove('is-open');
    menuButton.setAttribute('aria-expanded', 'false');
    menuButton.setAttribute('aria-label', 'Mở menu');
}

menuButton.addEventListener('click', () => {
    const isOpen = navigation.classList.toggle('is-open');
    menuButton.setAttribute('aria-expanded', String(isOpen));
    menuButton.setAttribute('aria-label', isOpen ? 'Đóng menu' : 'Mở menu');
});

navigation.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', closeMenu);
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && navigation.classList.contains('is-open')) {
        closeMenu();
        menuButton.focus();
    }
});

document.addEventListener('click', (event) => {
    if (!event.target.closest('.ph-header')) closeMenu();
});

const navLinks = [...navigation.querySelectorAll('a')];
const sections = navLinks.map((link) => document.querySelector(link.hash));
function updateActiveNavigation() {
    let current = sections[0];
    sections.forEach((section) => {
        if (section.getBoundingClientRect().top <= 120 && section.offsetTop >= current.offsetTop) current = section;
    });
    if (window.scrollY > 0 && window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 2) current = sections.at(-1);
    navLinks.forEach((link) => {
        const active = link.hash === `#${current.id}`;
        link.classList.toggle('is-active', active);
        if (active) link.setAttribute('aria-current', 'location');
        else link.removeAttribute('aria-current');
    });
}

let navigationUpdatePending = false;
window.addEventListener('scroll', () => {
    if (navigationUpdatePending) return;
    navigationUpdatePending = true;
    requestAnimationFrame(() => {
        updateActiveNavigation();
        navigationUpdatePending = false;
    });
}, { passive: true });
window.addEventListener('resize', updateActiveNavigation);
updateActiveNavigation();

document.querySelectorAll('[data-dialog-open]').forEach((button) => {
    button.addEventListener('click', () => {
        document.getElementById(button.dataset.dialogOpen).showModal();
    });
});

document.querySelectorAll('.ph-dialog').forEach((dialog) => {
    dialog.querySelector('[data-dialog-close]').addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', (event) => {
        if (event.target !== dialog) return;
        const bounds = dialog.getBoundingClientRect();
        if (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom) dialog.close();
    });
});

const allReviews = document.querySelector('.ph-dialog-reviews');
document.querySelectorAll('#ph-review-cards > article').forEach((review) => {
    allReviews.append(review.cloneNode(true));
});

const policies = {
    warranty: ['Chính sách bảo hành', 'PureHome A3 được bảo hành chính hãng 24 tháng. Vui lòng giữ hóa đơn hoặc thông tin đặt hàng để được hỗ trợ kiểm tra sản phẩm và hướng dẫn bảo hành.'],
    returns: ['Chính sách đổi trả', 'Hỗ trợ đổi trả trong 7 ngày. Vui lòng liên hệ PureHome trước khi gửi sản phẩm để được xác nhận điều kiện đổi trả và hướng dẫn đóng gói, vận chuyển.'],
    shipping: ['Chính sách giao hàng', 'Miễn phí giao hàng toàn quốc cho PureHome A3. Thời gian giao hàng và địa chỉ nhận hàng được xác nhận trực tiếp khi đặt mua.'],
    privacy: ['Chính sách bảo mật', 'Trang giới thiệu này không thu thập thông tin qua biểu mẫu hoặc xử lý thanh toán trực tuyến. Khi liên hệ qua điện thoại hoặc email, chỉ cung cấp thông tin cần thiết để được tư vấn và hỗ trợ đơn hàng.'],
    terms: ['Điều khoản sử dụng', 'Nội dung trên trang giới thiệu sản phẩm PureHome A3. Vui lòng xác nhận thông số, ưu đãi, chính sách và thông tin giao hàng với nhân viên tư vấn trước khi đặt mua.'],
};

document.querySelectorAll('[data-policy]').forEach((button) => {
    button.addEventListener('click', () => {
        const [title, content] = policies[button.dataset.policy];
        document.getElementById('ph-policy-title').textContent = title;
        document.getElementById('ph-policy-content').textContent = content;
        document.getElementById('ph-policy').showModal();
    });
});
