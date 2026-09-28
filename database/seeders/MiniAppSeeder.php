<?php

namespace Database\Seeders;

use App\Models\MiniApp;
use Illuminate\Database\Seeder;

class MiniAppSeeder extends Seeder
{
    /**
     * The free tools listed on /cong-cu; entries edited in the admin are kept.
     */
    public function run(): void
    {
        foreach ($this->tools() as $order => $tool) {
            MiniApp::query()->firstOrCreate(['link' => $tool['link']], $tool + ['is_active' => true, 'order' => $order + 1]);
        }
    }

    /**
     * @return array<int, array<string, string|null>>
     */
    private function tools(): array
    {
        return [
            ['link' => '/anh-cover', 'name' => 'Lấy ảnh cover video', 'icon' => 'image', 'description' => 'Lấy ảnh thumbnail chất lượng cao từ video YouTube, TikTok, hỗ trợ tải hàng loạt.', 'badge' => 'Miễn phí'],
            ['link' => '/tinh-thue-tncn', 'name' => 'Tính thuế TNCN', 'icon' => 'calculator', 'description' => 'Tính thuế thu nhập cá nhân theo biểu 5 bậc mới (2026) và 7 bậc (2025).', 'badge' => null],
            ['link' => '/tinh-thue-ho-kinh-doanh', 'name' => 'Tính thuế hộ kinh doanh', 'icon' => 'store', 'description' => 'Tính thuế GTGT và TNCN cho hộ kinh doanh theo ngành nghề, ngưỡng miễn thuế 2026.', 'badge' => 'Mới'],
            ['link' => '/tinh-thue-doanh-nghiep', 'name' => 'Tính thuế doanh nghiệp', 'icon' => 'building-2', 'description' => 'Tính thuế TNDN cho doanh nghiệp nhỏ và vừa (15% / 17% / 20%).', 'badge' => 'Mới'],
            ['link' => '/tools/ngan-hang', 'name' => 'Tạo QR ngân hàng', 'icon' => 'landmark', 'description' => 'Tạo mã QR chuyển khoản VietQR kèm số tiền và nội dung.', 'badge' => 'Hot'],
            ['link' => '/tools/qr-code', 'name' => 'Tạo QR code', 'icon' => 'qr-code', 'description' => 'Tạo mã QR cho đường link, văn bản, WiFi và tải về ảnh PNG.', 'badge' => null],
            ['link' => '/tools/calendar', 'name' => 'Lịch vạn niên', 'icon' => 'calendar-days', 'description' => 'Xem lịch âm dương, đổi ngày âm – dương và năm can chi.', 'badge' => null],
            ['link' => '/tools/vong-quay-an-trua', 'name' => 'Vòng quay ăn trưa', 'icon' => 'utensils', 'description' => 'Không biết trưa nay ăn gì? Để vòng quay chọn giúp bạn.', 'badge' => 'Vui'],
        ];
    }
}
