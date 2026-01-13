<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách ảnh đã lưu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-4 md:p-8">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800">Danh sách ảnh đã lưu</h1>
            <a href="{{ route('cover.page') }}" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition">
                &larr; Quay lại trang chính
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 border border-green-300 rounded-lg">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 border border-red-300 rounded-lg">{{ session('error') }}</div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ảnh</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiêu đề</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link Nhúng (URL)</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($logs as $log)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <a href="{{ $log->url ?? $log->original_url }}" class="glightbox">
                                        <img src="{{ asset('storage/' . $log->saved_path) }}" alt="Thumbnail" class="h-12 w-20 object-cover rounded-md transition transform hover:scale-110">
                                    </a>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900 truncate" style="max-width: 250px;" title="{{ $log->title }}">
                                        {{ $log->title ?: 'Không có tiêu đề' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $log->created_at->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div x-data="{ link: '{{ $log->url ?? $log->original_url }}' }" class="flex items-center">
                                        <input type="text" readonly :value="link" class="w-48 p-1 border bg-gray-50 text-xs rounded-l-md">
                                        <button @click="navigator.clipboard.writeText(link); $el.innerText = 'Đã chép!'; setTimeout(() => $el.innerText = 'Chép', 2000)" class="px-2 py-1 bg-gray-200 text-xs rounded-r-md hover:bg-gray-300">Chép</button>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-3">
                                        <a href="{{ asset('storage/' . $log->saved_path) }}" target="_blank" class="text-blue-600 hover:text-blue-900" title="Xem ảnh gốc">Xem</a>
                                        <form action="{{ route('cover.delete', $log) }}" method="POST" onsubmit="return confirm('Anh có chắc chắn muốn xóa ảnh này không?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Xóa vĩnh viễn">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">Chưa có ảnh nào được lưu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <script type="text/javascript">
        const lightbox = GLightbox({ selector: '.glightbox' });
    </script>
</body>
</html>
