<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách ảnh đã lưu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
</head>
<body class="bg-light">
    <main class="container py-4 py-md-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <h1 class="h2 mb-0">Danh sách ảnh đã lưu</h1>
            <a href="{{ route('cover.page') }}" class="btn btn-primary">&larr; Quay lại trang chính</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ảnh</th>
                                <th>Tiêu đề</th>
                                <th>Link nhúng (URL)</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                @php($url = $log->url ?? $log->original_url)
                                <tr>
                                    <td style="width: 110px;">
                                        <a href="{{ $url }}" class="glightbox">
                                            <img src="{{ asset('storage/'.$log->saved_path) }}" alt="Thumbnail" class="img-fluid rounded" style="width: 80px; height: 48px; object-fit: cover;">
                                        </a>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-truncate" style="max-width: 250px;" title="{{ $log->title }}">{{ $log->title ?: 'Không có tiêu đề' }}</div>
                                        <small class="text-muted">{{ $log->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm" style="min-width: 260px;">
                                            <input type="text" readonly value="{{ $url }}" class="form-control">
                                            <button type="button" class="btn btn-outline-secondary js-copy-link" data-copy="{{ $url }}">Chép</button>
                                        </div>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a href="{{ asset('storage/'.$log->saved_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">Xem</a>
                                        <form action="{{ route('cover.delete', $log) }}" method="POST" class="d-inline" onsubmit="return confirm('Anh có chắc chắn muốn xóa ảnh này không?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">Chưa có ảnh nào được lưu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($logs->hasPages())
                <div class="card-footer">{{ $logs->links() }}</div>
            @endif
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        GLightbox({ selector: '.glightbox' });
        document.querySelectorAll('.js-copy-link').forEach((button) => {
            button.addEventListener('click', async () => {
                await navigator.clipboard.writeText(button.dataset.copy);
                const original = button.textContent;
                button.textContent = 'Đã chép';
                window.setTimeout(() => { button.textContent = original; }, 2000);
            });
        });
    </script>
</body>
</html>
