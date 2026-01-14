<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Công cụ lấy ảnh Cover (Thumbnail)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="container mx-auto p-4 md:p-8 max-w-4xl">

        <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg" x-data="{
            url: '',
            isLoading: false,
            result: null,
            error: '',
            async getInfo() {
                if (!this.url.trim()) {
                    this.error = 'Vui lòng nhập một đường dẫn hợp lệ.';
                    return;
                }
                this.isLoading = true;
                this.result = null;
                this.error = '';

                try {
                    const response = await fetch('/get-info', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        },
                        body: JSON.stringify({ url: this.url })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.result = data;
                    } else {
                        this.error = data.message || 'Không thể phân tích link này. Vui lòng kiểm tra lại.';
                    }
                } catch (e) {
                    this.error = 'Đã có lỗi xảy ra. Vui lòng thử lại sau.';
                } finally {
                    this.isLoading = false;
                }
            }
        }">
            <meta name="csrf-token" content="{{ csrf_token() }}">

            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 border border-green-300 rounded-lg"
                    x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-700 border border-red-300 rounded-lg" x-data="{ show: true }"
                    x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition>
                    {{ session('error') }}
                </div>
            @endif

            <div class="text-center mb-6">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800">Lấy Ảnh Cover Video</h1>
                <p class="text-gray-500 mt-2">Dán link YouTube hoặc TikTok để lấy ảnh thumbnail chất lượng cao.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-2">
                <input type="text" x-model="url" @keydown.enter.prevent="getInfo()"
                    placeholder="Dán link YouTube, YouTube Shorts hoặc TikTok vào đây..."
                    class="flex-grow p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    :disabled="isLoading">
                <button @click.prevent="getInfo()" :disabled="isLoading"
                    class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-wait transition-all duration-300">
                    <span x-show="!isLoading">Lấy thông tin</span>
                    <span x-show="isLoading" x-cloak>
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 inline-block text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                fill="currentColor"></path>
                        </svg>
                        Đang phân tích...
                    </span>
                </button>
            </div>

            <div x-show="error" x-cloak class="mt-4 p-3 bg-red-100 text-red-700 border border-red-300 rounded-lg"
                x-text="error"></div>

            <div x-show="result" x-cloak x-transition class="mt-8 pt-6 border-t">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <img :src="result.thumbnail_url" alt="Video Thumbnail" class="rounded-lg shadow-lg w-full">
                    </div>
                    <div class="md:col-span-2">
                        <form action="{{ route('cover.download') }}" method="POST">
                            @csrf
                            <input type="hidden" name="image_url" :value="result.thumbnail_url">
                            <input type="hidden" name="filename" :value="result.title">
                            <input type="hidden" name="provider" :value="result.provider">

                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Tiêu đề (tên file ảnh)</label>
                                <input type="text" id="title" x-model="result.title"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <button type="submit"
                                class="w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition">
                                Tải ảnh về máy
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <footer class="text-center mt-6 text-sm text-gray-500">
            <p>Phát triển bởi WebAppBacNinh</p>
        </footer>
    </div>
</body>

</html>
