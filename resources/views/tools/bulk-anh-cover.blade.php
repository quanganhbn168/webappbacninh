<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Công cụ lấy ảnh Cover (Thumbnail) — Hàng loạt</title>

    {{-- Tailwind + Alpine (nếu layout đã có thì bỏ 2 dòng dưới) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-4 md:p-8 max-w-5xl">

    <div class="text-right mb-4">
        <a href="{{ route('cover.list') }}"
           class="px-4 py-2 bg-gray-700 text-white font-semibold rounded-lg shadow-md hover:bg-gray-800 transition">
            Xem danh sách đã lưu &rarr;
        </a>
    </div>

    <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg"
         x-data="BulkThumb()"
         x-init="init()">

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 border border-green-300 rounded-lg"
                 x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)" x-transition>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 border border-red-300 rounded-lg"
                 x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)" x-transition>
                {{ session('error') }}
            </div>
        @endif

        <div class="text-center mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800">Lấy Ảnh Cover Video — Hàng loạt</h1>
            <p class="text-gray-500 mt-2">Dán nhiều link YouTube/TikTok, lấy thumbnail, sửa tiêu đề và lưu tất cả.</p>
        </div>

        {{-- Khu dán link --}}
        <div class="grid gap-3">
            <label class="text-sm font-medium text-gray-700">Dán mỗi link 1 dòng (TikTok/YouTube)</label>
            <textarea x-model="urlsText" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                      rows="8" placeholder="https://www.tiktok.com/@phantrongg/video/7544718877503081746&#10;https://www.tiktok.com/@phantrongg/video/7539834301647490322&#10;..."></textarea>

            <div class="flex flex-wrap gap-2 items-center">
                <button @click="parseUrls()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        :disabled="isLoading">
                    <span x-show="!isLoading">Phân tích & lấy thông tin</span>
                    <span x-show="isLoading">Đang lấy...</span>
                </button>

                <button @click="clearAll()" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Xoá tất cả</button>

                <div class="ml-auto flex items-center gap-2">
                    <label class="text-sm text-gray-700">Sắp xếp:</label>
                    <select x-model="sortBy" @change="applySort()"
                            class="p-2 border border-gray-300 rounded-lg">
                        <option value="none">Không sắp xếp</option>
                        <option value="title_asc">Tiêu đề A→Z</option>
                        <option value="title_desc">Tiêu đề Z→A</option>
                        <option value="provider">Theo nền tảng</option>
                    </select>
                </div>
            </div>

            <template x-if="error">
                <div class="p-3 bg-red-100 text-red-700 border border-red-300 rounded-lg" x-text="error"></div>
            </template>
        </div>

        {{-- Danh sách kết quả --}}
        <div class="mt-6" x-show="items.length" x-cloak>
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="h-4 w-4" @change="toggleSelectAll($event)">
                        <span class="text-sm">Chọn tất cả (<span x-text="selectedCount()"></span>/<span x-text="items.length"></span>)</span>
                    </label>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="h-4 w-4" x-model="downloadZip">
                        <span class="text-sm">Tải ZIP về máy sau khi lưu</span>
                    </label>
                </div>

                <div class="flex gap-2">
                    <button @click="downloadSelected()"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Lưu các mục đã chọn
                    </button>
                    <button @click="selectAll(); downloadSelected()"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                        Lưu tất cả
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <template x-for="(it, idx) in items" :key="it.uid">
                    <div class="border rounded-xl overflow-hidden shadow-sm">
                        <div class="flex gap-3 p-3">
                            <div class="w-32 shrink-0">
                                <img :src="it.thumbnail_url" class="w-full h-24 object-cover rounded-md" loading="lazy" alt="">
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="px-2 py-0.5 text-xs rounded-full"
                                          :class="it.provider==='tiktok' ? 'bg-black text-white':'bg-red-600 text-white'"
                                          x-text="it.provider.toUpperCase()"></span>
                                    <label class="inline-flex items-center gap-1">
                                        <input type="checkbox" x-model="it.selected" class="h-4 w-4">
                                        <span class="text-xs text-gray-600">Chọn</span>
                                    </label>
                                </div>

                                <div class="mt-2">
                                    <label class="text-xs text-gray-500">Tiêu đề (sẽ là tên file)</label>
                                    <input type="text" x-model="it.title"
                                           class="mt-1 w-full p-2 border rounded-lg focus:ring-1 focus:ring-blue-500">
                                </div>

                                <div class="mt-2 text-xs text-gray-500 break-all">
                                    <div><span class="font-medium">Original:</span> <span x-text="it.original_url"></span></div>
                                    <div><span class="font-medium">URL player:</span> <span x-text="it.url"></span></div>
                                </div>

                                <div class="mt-2 flex items-center gap-2">
                                    <button @click="moveUp(idx)" class="px-2 py-1 text-sm border rounded">↑</button>
                                    <button @click="moveDown(idx)" class="px-2 py-1 text-sm border rounded">↓</button>
                                    <a :href="it.original_url" target="_blank" class="ml-auto text-sm text-blue-600 hover:underline">Mở</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Form submit bulk (ẩn) --}}
        <form id="bulkForm" class="hidden" method="POST" action="{{ route('cover.download.bulk') }}">
            @csrf
            <input type="hidden" name="download_zip" x-model="downloadZip">
            <div id="bulkPayload"></div>
        </form>
    </div>

    <footer class="text-center mt-6 text-sm text-gray-500">
        <p>Phát triển bởi WebAppBacNinh</p>
    </footer>
</div>


    <script src="{{ asset('js/tools/bulk-anh-cover.js') }}"></script> --}}

</body>
</html>
