import 'bootstrap';
import '@fortawesome/fontawesome-free/js/all.min.js';

// Toastify hoặc SweetAlert nếu có
import Toastify from 'toastify-js';
window.Toastify = Toastify;

// Livewire support (nếu dùng tương tác JS/DOM)
import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';
Livewire.start();

// Gắn tooltip hoặc component JS
document.addEventListener('DOMContentLoaded', () => {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
});
