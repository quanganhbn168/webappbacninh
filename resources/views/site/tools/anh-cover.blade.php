@extends('layouts.tool')

@section('tool-content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5" id="cover-tool" data-url="{{ route('cover.getInfo') }}">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                        </div>
                    @endif

                    <div class="text-center mb-5">
                        <h1 class="h2 fw-bold mb-2">Lấy Ảnh Cover Video</h1>
                        <p class="text-secondary">Dán link YouTube hoặc TikTok để lấy ảnh thumbnail chất lượng cao.</p>
                        <a href="{{ route('cover.bulk.page') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 mt-2">
                            <x-icon name="zap" class="me-1" /> Chuyển sang chế độ tải hàng loạt (Bulk) &rarr;
                        </a>
                    </div>

                    <form class="input-group input-group-lg mb-3 shadow-sm" data-cover-form>
                        <label class="visually-hidden" for="cover-url">Link video</label>
                        <input type="url" class="form-control border-primary" id="cover-url" required placeholder="Dán link YouTube, YouTube Shorts hoặc TikTok vào đây...">
                        <button class="btn btn-primary px-4 fw-bold" type="submit">
                            <span data-idle>Lấy thông tin</span>
                            <span data-busy hidden><span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Đang xử lý...</span>
                        </button>
                    </form>

                    <div class="alert alert-danger mt-4" data-cover-error hidden role="alert"></div>

                    <div class="mt-5 pt-4 border-top" data-cover-result hidden>
                        <div class="row g-4">
                            <div class="col-md-5">
                                <img data-thumbnail alt="Ảnh thumbnail của video" class="img-fluid w-100 rounded shadow-sm bg-light">
                            </div>
                            <div class="col-md-7">
                                <form action="{{ route('cover.download') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="image_url" data-image-url>
                                    <input type="hidden" name="provider" data-provider>
                                    <div class="mb-3">
                                        <label for="cover-title" class="form-label fw-bold text-secondary text-uppercase small">Tiêu đề</label>
                                        <input type="text" id="cover-title" name="filename" class="form-control form-control-lg" data-title>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm mb-3">
                                        <x-icon name="image" class="me-2" /> Tải Ảnh Cover (JPG)
                                    </button>
                                </form>

                                <form action="{{ route('cover.download.video') }}" method="POST" data-video-form hidden>
                                    @csrf
                                    <input type="hidden" name="video_url" data-video-url>
                                    <input type="hidden" name="filename" data-video-title>
                                    <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold shadow-sm">
                                        <x-icon name="video" class="me-2" /> Tải Video (No Watermark)
                                    </button>
                                </form>

                                <div class="alert alert-warning small mt-2" data-no-video hidden>
                                    <x-icon name="triangle-alert" class="me-1" /> Không tìm thấy link video không logo.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    const { ToolKit } = window;
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
</script>
@endpush
