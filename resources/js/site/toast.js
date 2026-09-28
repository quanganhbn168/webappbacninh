export function toast(message) {
    const element = document.getElementById('site-toast');
    if (!element) return;
    element.querySelector('.toast-body').textContent = message;
    window.bootstrap.Toast.getOrCreateInstance(element, { delay: 5000 }).show();
}
