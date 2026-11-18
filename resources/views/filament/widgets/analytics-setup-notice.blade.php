<x-filament-widgets::widget>
    <x-filament::section icon="heroicon-o-link" icon-color="warning" heading="Kết nối Google Analytics 4 và Search Console"
        description="Dashboard đọc số liệu bằng một service account, chỉ có quyền xem.">
        <ol class="flex list-decimal flex-col gap-3 ps-5 text-sm text-gray-700 dark:text-gray-300">
            <li @class(['text-success-600 dark:text-success-400' => $hasKey])>
                Trong <a class="font-medium text-primary-600 underline" href="https://console.cloud.google.com/" target="_blank" rel="noopener">Google Cloud Console</a>:
                tạo project, bật <strong>Google Analytics Data API</strong> và <strong>Google Search Console API</strong>,
                tạo <strong>service account</strong> rồi tải khóa JSON, đặt vào <code class="rounded bg-gray-100 px-1 dark:bg-white/10">{{ $keyPath }}</code>.
                @if ($hasKey) <strong>✓ Đã có khóa</strong> @endif
            </li>
            <li @class(['text-success-600 dark:text-success-400' => $hasGa4])>
                GA4 → Quản trị → Quản lý quyền truy cập property: thêm email của service account với vai trò <strong>Người xem</strong>.
                Ghi <code class="rounded bg-gray-100 px-1 dark:bg-white/10">GA4_PROPERTY_ID=…</code> (mã số property, ở Quản trị → Chi tiết property) vào <code class="rounded bg-gray-100 px-1 dark:bg-white/10">.env</code>.
                @if ($hasGa4) <strong>✓ Đã có mã property</strong> @endif
            </li>
            <li @class(['text-success-600 dark:text-success-400' => $hasGsc])>
                Search Console → Cài đặt → Người dùng và quyền: thêm email service account (quyền <strong>Hạn chế</strong> là đủ).
                Ghi <code class="rounded bg-gray-100 px-1 dark:bg-white/10">GSC_SITE_URL=sc-domain:tenmien.vn</code> (property tên miền) hoặc <code class="rounded bg-gray-100 px-1 dark:bg-white/10">https://tenmien.vn/</code> (property URL) vào <code class="rounded bg-gray-100 px-1 dark:bg-white/10">.env</code>.
                @if ($hasGsc) <strong>✓ Đã có địa chỉ site</strong> @endif
            </li>
            <li>Chạy <code class="rounded bg-gray-100 px-1 dark:bg-white/10">php artisan config:cache</code> nếu server có cache cấu hình, rồi tải lại trang này.</li>
        </ol>
    </x-filament::section>
</x-filament-widgets::widget>
