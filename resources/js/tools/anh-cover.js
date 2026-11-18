// Lấy ảnh cover video YouTube/TikTok.
import { ToolKit } from '../site/toolkit';

const root = document.getElementById('cover-tool');
const form = root.querySelector('[data-cover-form]');
const button = form.querySelector('button');
const error = root.querySelector('[data-cover-error]');
const result = root.querySelector('[data-cover-result]');
const $ = selector => root.querySelector(selector);

function busy(state) {
    button.disabled = state;
    form.querySelector('input').disabled = state;
    $('[data-idle]').hidden = state;
    $('[data-busy]').hidden = !state;
}

form.addEventListener('submit', async event => {
    event.preventDefault();
    busy(true);
    error.hidden = true;
    result.hidden = true;
    try {
        const data = await ToolKit.postJson(root.dataset.url, { url: $('#cover-url').value.trim() });
        $('[data-thumbnail]').src = data.thumbnail_url;
        $('[data-image-url]').value = data.thumbnail_url;
        $('[data-provider]').value = data.provider ?? '';
        $('[data-title]').value = data.title ?? '';
        $('[data-video-url]').value = data.video_url ?? '';
        $('[data-video-form]').hidden = !data.video_url;
        $('[data-no-video]').hidden = Boolean(data.video_url) || data.provider !== 'tiktok';
        result.hidden = false;
    } catch (exception) {
        error.textContent = exception.message || 'Không thể phân tích link này. Vui lòng kiểm tra lại.';
        error.hidden = false;
    } finally {
        busy(false);
    }
});

// The video file keeps the (editable) title as its name.
$('[data-video-form]').addEventListener('submit', () => { $('[data-video-title]').value = $('[data-title]').value; });
