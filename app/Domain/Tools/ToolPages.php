<?php

namespace App\Domain\Tools;

/**
 * SEO data of the free tool pages (resources/views/tools). The /cong-cu list itself
 * comes from the mini apps managed in the admin.
 */
final class ToolPages
{
    /** @var array<string, array{name: string, title: string, description: string, keywords: string}> */
    public const PAGES = [
        'anh-cover' => ['name' => 'Lấy ảnh cover video', 'title' => 'Công cụ lấy ảnh Cover Video (Thumbnail)', 'description' => 'Công cụ miễn phí giúp lấy ảnh cover (thumbnail) chất lượng cao từ video YouTube, TikTok. Hỗ trợ tải về nhanh chóng.', 'keywords' => 'get thumbnail youtube, lấy ảnh cover tiktok, youtube thumbnail downloader, công cụ mmo'],
        'bank-qr' => ['name' => 'Tạo QR ngân hàng', 'title' => 'Tạo mã QR Ngân Hàng - VietQR Chuyển Khoản Nhanh', 'description' => 'Công cụ tạo mã QR chuyển khoản ngân hàng VietQR tự động. Hỗ trợ tất cả ngân hàng Việt Nam (VCB, MB, Tech... - VietQR). Chính xác, an toàn, có logo.', 'keywords' => ''],
        'bulk-anh-cover' => ['name' => 'Lấy ảnh cover hàng loạt', 'title' => 'Lấy Ảnh Cover Hàng Loạt (Bulk Thumbnail)', 'description' => 'Lấy ảnh thumbnail hàng loạt từ nhiều link YouTube, TikTok và tải về một lần, miễn phí.', 'keywords' => ''],
        'calendar' => ['name' => 'Lịch vạn niên', 'title' => 'Lịch Vạn Niên 2026 - Xem Lịch Âm Dương, Ngày Tốt Xấu', 'description' => 'Xem lịch vạn niên, lịch âm dương hôm nay, đổi ngày âm dương, xem ngày tốt xấu, giờ hoàng đạo chuẩn xác nhất.', 'keywords' => ''],
        'food-wheel' => ['name' => 'Vòng quay ăn trưa', 'title' => 'Vòng Quay Ăn Trưa - Hôm nay ăn gì?', 'description' => 'Vòng quay may mắn chọn món ăn trưa. Chế độ "Đầu tháng sang chảnh" và "Cuối tháng bần hàn". Quay ngay để biết trưa nay ăn gì!', 'keywords' => ''],
        'qr-code' => ['name' => 'Tạo QR code', 'title' => 'Tạo mã QR Code Online Miễn Phí', 'description' => 'Công cụ tạo mã QR Code online miễn phí. Tạo QR Wifi, URL, Văn bản nhanh chóng, hỗ trợ chèn logo và tùy chỉnh màu sắc.', 'keywords' => ''],
        'tinh-thue-doanh-nghiep' => ['name' => 'Tính thuế doanh nghiệp', 'title' => 'Tính thuế Doanh nghiệp nhỏ và vừa 2026', 'description' => 'Công cụ tính thuế TNDN cho doanh nghiệp nhỏ và vừa theo Luật mới. Thuế suất 15%, 17%, 20% theo doanh thu, miễn thuế 3 năm đầu cho doanh nghiệp mới.', 'keywords' => 'thuế TNDN, thuế doanh nghiệp nhỏ và vừa, DNNVV, thuế suất 15%, miễn thuế 3 năm'],
        'tinh-thue-ho-kinh-doanh' => ['name' => 'Tính thuế hộ kinh doanh', 'title' => 'Tính thuế Hộ kinh doanh 2026', 'description' => 'Công cụ tính thuế hộ kinh doanh miễn phí theo Luật mới 2026. Ngưỡng miễn thuế 500 triệu/năm, tính thuế GTGT và TNCN theo ngành nghề.', 'keywords' => 'thuế hộ kinh doanh, thuế khoán, ngưỡng 500 triệu, thuế GTGT, thuế TNCN, kinh doanh cá nhân'],
        'tinh-thue-tncn' => ['name' => 'Tính thuế TNCN', 'title' => 'Tính thuế Thu nhập Cá nhân (TNCN) 2026', 'description' => 'Công cụ tính thuế thu nhập cá nhân (TNCN) miễn phí theo Luật mới 109/2025/QH15. Hỗ trợ biểu thuế 2025 và 2026, tính tự động giảm trừ gia cảnh.', 'keywords' => 'tính thuế tncn, thuế thu nhập cá nhân 2026, biểu thuế lũy tiến, giảm trừ gia cảnh, công cụ tính thuế'],
    ];
}
