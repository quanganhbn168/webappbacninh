const price = 3590000;
const money = (amount) => `${new Intl.NumberFormat('vi-VN').format(amount)}đ`;
const quantityInput = document.getElementById('fa-quantity');
const quantity = () => Math.max(1, Math.min(10, Math.trunc(Number(quantityInput.value)) || 1));
const cartKey = 'freshair-s8-demo-cart-v1';
let cartQuantity = 0;
try { cartQuantity = Math.max(0, Math.min(10, Math.trunc(Number(localStorage.getItem(cartKey))) || 0)); } catch { /* Private browsing can disable storage. */ }

const toast = document.querySelector('.fa-toast');
let toastTimer;
function notify(message) {
    clearTimeout(toastTimer);
    toast.textContent = message;
    toast.hidden = false;
    toastTimer = setTimeout(() => { toast.hidden = true; }, 4000);
}

function renderCart() {
    document.querySelectorAll('[data-cart-count]').forEach((element) => { element.textContent = cartQuantity; });
    document.querySelector('[data-cart-quantity]').textContent = cartQuantity;
    document.querySelector('[data-cart-total]').textContent = money(price * cartQuantity);
    document.querySelector('.fa-cart-empty').hidden = cartQuantity > 0;
    document.querySelector('.fa-cart-content').hidden = cartQuantity === 0;
    const body = `Tôi muốn đặt mua máy lọc không khí FreshAir S8.\nSố lượng: ${cartQuantity}\nĐơn giá: ${money(price)}\nTạm tính: ${money(price * cartQuantity)}\nVui lòng tư vấn ưu đãi và thông tin giao hàng.`;
    document.querySelector('[data-order-email]').href = `mailto:cskh@freshair.vn?subject=${encodeURIComponent('Đặt mua FreshAir S8')}&body=${encodeURIComponent(body)}`;
}
function saveCart() {
    try { localStorage.setItem(cartKey, String(cartQuantity)); } catch { /* The cart still works for the current visit. */ }
    renderCart();
}
renderCart();

function normalizeQuantity() {
    quantityInput.value = quantity();
    document.querySelector('[data-quantity-step="-1"]').disabled = quantity() <= 1;
    document.querySelector('[data-quantity-step="1"]').disabled = quantity() >= 10;
}
quantityInput.addEventListener('change', normalizeQuantity);
document.querySelectorAll('[data-quantity-step]').forEach((button) => {
    button.addEventListener('click', () => {
        quantityInput.value = quantity() + Number(button.dataset.quantityStep);
        normalizeQuantity();
    });
});
normalizeQuantity();

document.querySelectorAll('[data-add-cart]').forEach((button) => {
    button.addEventListener('click', () => {
        cartQuantity = Math.min(10, cartQuantity + quantity());
        saveCart();
        notify(`Đã thêm FreshAir S8. Giỏ hàng có ${cartQuantity} sản phẩm.`);
    });
});
document.querySelectorAll('[data-buy]').forEach((button) => {
    button.addEventListener('click', () => {
        cartQuantity = quantity();
        saveCart();
        document.getElementById('fa-cart-dialog').showModal();
    });
});
document.querySelector('[data-open-cart]').addEventListener('click', () => document.getElementById('fa-cart-dialog').showModal());
document.querySelector('[data-clear-cart]').addEventListener('click', () => { cartQuantity = 0; saveCart(); });

document.addEventListener('click', (event) => {
    const close = event.target.closest('[data-close-dialog]');
    if (close) close.closest('dialog').close();
});
document.querySelectorAll('dialog').forEach((dialog) => {
    dialog.addEventListener('click', (event) => {
        if (event.target !== dialog) return;
        const bounds = dialog.getBoundingClientRect();
        if (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom) dialog.close();
    });
});

const sliderSpeed = matchMedia('(prefers-reduced-motion: reduce)').matches ? 0 : 350;
const sliderA11y = {
    prevSlideMessage: 'Ảnh trước',
    nextSlideMessage: 'Ảnh tiếp theo',
    firstSlideMessage: 'Đây là ảnh đầu tiên',
    lastSlideMessage: 'Đây là ảnh cuối cùng',
    paginationBulletMessage: 'Xem nhóm ảnh {{index}}',
    slideLabelMessage: 'Ảnh {{index}} / {{slidesLength}}',
};
const thumbnails = new window.Swiper('.fa-thumbnails', {
    slidesPerView: 4.5,
    spaceBetween: 8,
    watchSlidesProgress: true,
    speed: sliderSpeed,
    a11y: sliderA11y,
});
function updateSelectedThumbnail(swiper) {
    swiper.slides.forEach((slide, index) => { slide.inert = index !== swiper.activeIndex; });
    document.querySelectorAll('[data-photo-index]').forEach((button) => {
        button.setAttribute('aria-pressed', String(Number(button.dataset.photoIndex) === swiper.activeIndex));
    });
}
const productSlider = new window.Swiper('.fa-product-slider', {
    speed: sliderSpeed,
    rewind: true,
    grabCursor: true,
    keyboard: { enabled: true, onlyInViewport: true, pageUpDown: false },
    navigation: { prevEl: '.fa-product-prev', nextEl: '.fa-product-next', addIcons: false },
    pagination: { el: '.fa-product-pagination', type: 'fraction' },
    thumbs: { swiper: thumbnails, multipleActiveThumbs: false },
    a11y: { ...sliderA11y, prevSlideMessage: 'Ảnh sản phẩm trước', nextSlideMessage: 'Ảnh sản phẩm tiếp theo' },
    on: { init: updateSelectedThumbnail, slideChange: updateSelectedThumbnail },
});
document.querySelectorAll('[data-photo-index]').forEach((button) => {
    button.addEventListener('click', () => productSlider.slideTo(Number(button.dataset.photoIndex)));
});
new window.Swiper('.fa-gallery-grid', {
    slidesPerView: 1.8,
    spaceBetween: 12,
    speed: sliderSpeed,
    grabCursor: true,
    watchOverflow: true,
    pagination: { el: '.fa-gallery-pagination', clickable: true },
    breakpoints: { 701: { slidesPerView: 3.2 }, 1100: { slidesPerView: 6 } },
    a11y: sliderA11y,
});

const galleryDialog = document.getElementById('fa-gallery-dialog');
function updateGalleryCaption(swiper) {
    swiper.slides.forEach((slide, index) => { slide.inert = index !== swiper.activeIndex; });
    const slide = swiper.slides[swiper.activeIndex];
    document.getElementById('fa-gallery-caption').textContent = `${slide.dataset.caption} · ${swiper.activeIndex + 1}/${swiper.slides.length}`;
    productSlider.slideTo(swiper.activeIndex, 0);
}
const lightboxSlider = new window.Swiper('.fa-lightbox-slider', {
    init: false,
    speed: sliderSpeed,
    rewind: true,
    grabCursor: true,
    keyboard: { enabled: false, onlyInViewport: true, pageUpDown: false },
    navigation: { prevEl: '.fa-lightbox-prev', nextEl: '.fa-lightbox-next', addIcons: false },
    a11y: sliderA11y,
    on: { slideChange: updateGalleryCaption },
});
document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-gallery-open]');
    if (!trigger || trigger.closest('.swiper')?.swiper?.allowClick === false) return;
    const index = trigger.dataset.galleryOpen === 'product' ? 0 : Number(trigger.dataset.galleryOpen) + 1;
    if (!galleryDialog.open) galleryDialog.showModal();
    if (!lightboxSlider.initialized) lightboxSlider.init();
    lightboxSlider.update();
    lightboxSlider.slideTo(index, 0);
    updateGalleryCaption(lightboxSlider);
    productSlider.keyboard.disable();
    lightboxSlider.keyboard.enable();
});
galleryDialog.addEventListener('close', () => {
    lightboxSlider.keyboard.disable();
    productSlider.keyboard.enable();
});

document.querySelectorAll('[data-voucher]').forEach((button) => {
    button.addEventListener('click', async () => {
        const code = button.dataset.voucher;
        try { await navigator.clipboard.writeText(code); notify(`Đã sao chép mã ${code}. Xác nhận điều kiện ưu đãi khi đặt hàng.`); }
        catch { notify(`Mã ưu đãi của bạn: ${code}. Cung cấp mã khi đặt hàng.`); }
    });
});

const normalizeText = (text) => text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'd').trim();
document.querySelector('.fa-search').addEventListener('submit', (event) => {
    event.preventDefault();
    const query = normalizeText(document.getElementById('fa-search-input').value);
    if (!query) { notify('Nhập thông tin bạn muốn tìm: giá, màng lọc, bảo hành…'); return; }
    const section = [...document.querySelectorAll('[data-search-section]')].find((element) => normalizeText(`${element.dataset.searchSection} ${element.textContent}`).includes(query));
    if (!section) { notify('Chưa tìm thấy nội dung. Bạn có thể xem Hỏi đáp hoặc gọi 1900 636 888.'); return; }
    section.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth', block: 'start' });
    const heading = section.querySelector('h2');
    heading.setAttribute('tabindex', '-1');
    heading.focus({ preventScroll: true });
    notify(`Đã tìm thấy: ${heading.textContent}`);
});

const reviewsDialog = document.getElementById('fa-reviews-dialog');
const dialogReviews = document.querySelector('.fa-reviews-dialog-list');
document.querySelectorAll('#fa-reviews-list > article').forEach((review) => dialogReviews.append(review.cloneNode(true)));
document.querySelector('[data-open-reviews]').addEventListener('click', () => reviewsDialog.showModal());

document.getElementById('fa-consult-form').addEventListener('submit', (event) => {
    event.preventDefault();
    const form = event.currentTarget;
    if (!form.reportValidity()) return;
    const data = new FormData(form);
    const values = [['Họ và tên', String(data.get('name')).trim()], ['Điện thoại', String(data.get('phone')).trim()], ['Email', String(data.get('email')).trim() || 'Không cung cấp'], ['Nhu cầu', String(data.get('room'))]];
    if (!values[0][1]) { form.elements.name.setCustomValidity('Vui lòng nhập họ và tên.'); form.elements.name.reportValidity(); return; }
    const summary = document.querySelector('.fa-consult-summary');
    summary.replaceChildren();
    values.forEach(([label, value]) => {
        const row = document.createElement('div');
        const term = document.createElement('dt');
        const description = document.createElement('dd');
        term.textContent = label;
        description.textContent = value;
        row.append(term, description);
        summary.append(row);
    });
    const body = `Tôi muốn được tư vấn máy lọc không khí FreshAir S8.\n\n${values.map(([label, value]) => `${label}: ${value}`).join('\n')}`;
    document.querySelector('[data-consult-email]').href = `mailto:cskh@freshair.vn?subject=${encodeURIComponent('Yêu cầu tư vấn FreshAir S8')}&body=${encodeURIComponent(body)}`;
    document.getElementById('fa-consult-dialog').showModal();
});
document.querySelector('#fa-consult-form [name="name"]').addEventListener('input', (event) => event.currentTarget.setCustomValidity(''));

const shareUrl = document.querySelector('link[rel="canonical"]').href;
const shareDialog = document.getElementById('fa-share-dialog');
document.querySelectorAll('[data-share]').forEach((button) => button.addEventListener('click', () => shareDialog.showModal()));
document.querySelector('[data-copy-share]').addEventListener('click', async (event) => {
    const button = event.currentTarget;
    try { await navigator.clipboard.writeText(shareUrl); button.textContent = 'Đã sao chép ✓'; }
    catch { document.querySelector('.fa-share-url input').select(); button.textContent = 'Nhấn Ctrl/Cmd + C để sao chép'; }
    window.setTimeout(() => { button.textContent = 'Sao chép đường dẫn'; }, 3000);
});
const nativeShare = document.querySelector('[data-native-share]');
if (typeof navigator.share === 'function') {
    nativeShare.hidden = false;
    nativeShare.addEventListener('click', async () => {
        try { await navigator.share({ title: 'FreshAir S8 — Không khí sạch, cuộc sống tốt đẹp hơn', text: 'Máy lọc không khí FreshAir S8, giá ưu đãi 3.590.000đ.', url: shareUrl }); }
        catch (error) { if (error.name !== 'AbortError') notify('Bạn có thể sao chép đường dẫn để chia sẻ.'); }
    });
}
