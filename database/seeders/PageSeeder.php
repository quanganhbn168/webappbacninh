<?php

namespace Database\Seeders;

use App\Domain\Pages\SitePages;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /** The SEO texts written for the main pages; the others use the defaults in SitePages. */
    private const SEO = [
        'terms' => [
            'meta_description' => 'Điều khoản áp dụng khi truy cập website, sử dụng nội dung và gửi yêu cầu dịch vụ tới WebApp Bắc Ninh.',
        ],
        'warranty' => [
            'meta_description' => 'Phạm vi bảo hành, hỗ trợ kỹ thuật và xử lý yêu cầu sau bàn giao website của WebApp Bắc Ninh.',
        ],
        'payment-process' => [
            'meta_description' => 'Các bước xác nhận phạm vi, đặt cọc, nghiệm thu và thanh toán khi triển khai website cùng WebApp Bắc Ninh.',
        ],
        'about' => [
            'meta_title' => 'Giới thiệu WebApp Bắc Ninh',
            'meta_description' => 'WebApp Bắc Ninh tập trung thiết kế website, đồng hành vận hành nội dung và triển khai kỹ thuật phù hợp doanh nghiệp nhỏ và vừa.',
        ],
        'agency' => [
            'meta_title' => 'Hợp tác kỹ thuật và gia công website cho Agency',
            'meta_description' => 'Nhận triển khai website, landing page, bảo trì và module kỹ thuật theo hình thức giới thiệu khách, đồng triển khai hoặc white-label.',
        ],
        'articles' => [
            'meta_title' => 'Kiến thức website, SEO và vận hành',
            'meta_description' => 'Bài viết thực dụng về thiết kế website, SEO nền tảng, hosting, bảo trì, quản trị nội dung và vận hành số cho doanh nghiệp.',
        ],
        'contact' => [
            'meta_title' => 'Liên hệ tư vấn website',
            'meta_description' => 'Liên hệ WebApp Bắc Ninh để tư vấn thiết kế website, kho giao diện, hosting, bảo trì, SEO, nội dung và hợp tác kỹ thuật.',
        ],
        'home' => [
            'meta_title' => 'Thiết kế website tại Bắc Ninh',
            'meta_description' => 'WebApp Bắc Ninh thiết kế website doanh nghiệp, website bán hàng, landing page và đồng hành hosting, bảo trì, đăng bài, SEO, nội dung Facebook sau bàn giao.',
        ],
        'operations' => [
            'meta_title' => 'Dịch vụ vận hành website, SEO và nội dung',
            'meta_description' => 'Hosting, bảo trì, quản trị website, đăng bài, SEO, nội dung Facebook và nâng cấp chức năng theo nhu cầu doanh nghiệp.',
        ],
        'pricing' => [
            'meta_title' => 'Bảng giá thiết kế website và vận hành',
            'meta_description' => 'Tham khảo các gói thiết kế website, hosting, bảo trì, đăng bài, SEO và vận hành nội dung của WebApp Bắc Ninh.',
        ],
        'projects' => [
            'meta_title' => 'Dự án website và phần mềm đã triển khai',
            'meta_description' => 'Tham khảo các dự án website doanh nghiệp, website bán hàng, landing page, website giáo dục, du lịch và hệ thống quản lý của WebApp Bắc Ninh.',
        ],
        'themes' => [
            'meta_title' => 'Kho giao diện website theo ngành',
            'meta_description' => 'Khám phá kho giao diện website doanh nghiệp, bán hàng, landing page và website theo ngành. Lọc nhanh theo nhu cầu, lĩnh vực và mức đầu tư.',
        ],
        'website-design' => [
            'meta_title' => 'Dịch vụ thiết kế website tại Bắc Ninh',
            'meta_description' => 'Thiết kế website doanh nghiệp, website bán hàng, landing page và website theo ngành tại Bắc Ninh. Giao diện phù hợp, dễ quản trị, SEO nền tảng và hỗ trợ lâu dài.',
        ],
    ];

    /** The heading block at the top of each page; the title and highlight together are its H1. */
    private const BANNERS = [
        'home' => [
            'banner_eyebrow' => 'Giải pháp số toàn diện cho doanh nghiệp',
            'banner_title' => 'Thiết kế website tại Bắc Ninh,',
            'banner_highlight' => 'phần mềm và vận hành số',
            'banner_subtitle' => 'Chúng tôi thiết kế website, landing page, phát triển phần mềm, CRM, booking và đồng hành vận hành số cùng doanh nghiệp với giải pháp tối ưu, hiện đại và hỗ trợ lâu dài.',
        ],
        'services' => [
            'banner_eyebrow' => 'Dịch vụ WebApp Bắc Ninh',
            'banner_title' => 'Giải pháp dịch vụ toàn diện',
            'banner_highlight' => 'cho doanh nghiệp',
            'banner_subtitle' => 'Chúng tôi cung cấp dịch vụ thiết kế website, phát triển phần mềm, CRM, booking, SEO, quảng cáo và vận hành số, giúp doanh nghiệp tối ưu quy trình, tăng trưởng bền vững với sự đồng hành lâu dài.',
        ],
        'website-design' => [
            'banner_eyebrow' => 'Dịch vụ thiết kế website',
            'banner_title' => 'Thiết kế website tại Bắc Ninh',
            'banner_highlight' => 'dễ quản trị, hỗ trợ bán hàng',
            'banner_subtitle' => 'WebApp Bắc Ninh thiết kế website theo mục tiêu thực tế của doanh nghiệp: giới thiệu năng lực, nhận khách hàng, bán sản phẩm, chạy chiến dịch hoặc xây nền tảng nội dung lâu dài.',
        ],
        'operations' => [
            'banner_eyebrow' => 'Sau khi website đi vào hoạt động',
            'banner_title' => 'Dịch vụ vận hành website, SEO và nội dung',
            'banner_subtitle' => 'WebApp Bắc Ninh hỗ trợ phần kỹ thuật, nội dung, SEO và cập nhật định kỳ để doanh nghiệp không phải tuyển ngay một đội riêng.',
            'banner_highlight' => 'tại Bắc Ninh',
        ],
        'hosting' => [
            'banner_title' => 'Hosting, tên miền và email doanh nghiệp',
            'banner_highlight' => 'tại Bắc Ninh',
            'banner_subtitle' => 'WebApp Bắc Ninh cung cấp giải pháp Hosting, Domain, Email doanh nghiệp và cấu hình kỹ thuật đồng bộ, giúp website của bạn hoạt động ổn định, nhanh chóng và an toàn.',
        ],
        'solutions' => [
            'banner_title' => 'Giải pháp số phù hợp',
            'banner_highlight' => 'với từng doanh nghiệp',
            'banner_subtitle' => 'Chúng tôi kết nối website, phần mềm và vận hành thành giải pháp tổng thể, phù hợp với đặc thù từng ngành nghề, giúp doanh nghiệp tăng trưởng bền vững trong kỷ nguyên số.',
        ],
        'products' => [
            'banner_title' => 'Sản phẩm & Giải pháp đóng gói',
            'banner_highlight' => 'Sẵn sàng cho doanh nghiệp bạn',
            'banner_subtitle' => 'Các mẫu website theo ngành và phần mềm quản lý được dựng sẵn, dễ dàng tùy biến theo nhu cầu thực tế của doanh nghiệp, giúp bạn triển khai nhanh chóng, tiết kiệm chi phí và vận hành hiệu quả.',
        ],
        'themes' => [
            'banner_eyebrow' => 'Kho giao diện website',
            'banner_title' => 'Thiết kế website theo mẫu',
            'banner_subtitle' => 'Chọn mẫu gần nhu cầu, chúng tôi đổi logo, màu sắc, nội dung và chức năng theo doanh nghiệp.',
        ],
        'projects' => [
            'banner_title' => 'Dự án tiêu biểu',
            'banner_highlight' => 'Từ ý tưởng đến giải pháp thực tế',
            'banner_subtitle' => 'Khám phá các hướng triển khai website và phần mềm cho doanh nghiệp. Từ những ý tưởng ban đầu đến các giải pháp website, phần mềm hiệu quả và bền vững.',
        ],
        'pricing' => [
            'banner_title' => 'Bảng giá dịch vụ',
            'banner_highlight' => 'Minh bạch – Linh hoạt – Phù hợp',
            'banner_subtitle' => 'Lựa chọn giải pháp phù hợp với mục tiêu và ngân sách của doanh nghiệp. Chúng tôi luôn tư vấn chi tiết, tối ưu chi phí và đồng hành lâu dài.',
        ],
        'articles' => [
            'banner_title' => 'Kiến thức',
            'banner_highlight' => 'Đồng hành cùng doanh nghiệp',
            'banner_subtitle' => 'Chia sẻ kiến thức hữu ích về website, phần mềm, marketing và vận hành số, giúp doanh nghiệp bắt kịp xu hướng và phát triển bền vững.',
        ],
        'tools' => [
            'banner_eyebrow' => 'Miễn phí cho doanh nghiệp',
            'banner_title' => 'Công cụ miễn phí',
            'banner_highlight' => 'dùng ngay trên trình duyệt',
            'banner_subtitle' => 'Tính thuế TNCN, thuế hộ kinh doanh, thuế doanh nghiệp, tạo mã QR, xem lịch vạn niên, lấy ảnh cover video… Không cần đăng ký, không cài đặt.',
        ],
        'contact' => [
            'banner_title' => 'Liên hệ với chúng tôi',
            'banner_highlight' => 'Cùng bắt đầu dự án của bạn',
            'banner_subtitle' => 'Chúng tôi luôn sẵn sàng lắng nghe và đồng hành cùng bạn trong việc xây dựng website, phát triển phần mềm và triển khai các giải pháp vận hành số hiệu quả cho doanh nghiệp.',
        ],
        'about' => [
            'banner_eyebrow' => 'WebApp Bắc Ninh',
            'banner_title' => 'Thiết kế website và đồng hành vận hành cho doanh nghiệp',
            'banner_subtitle' => 'Chúng tôi tập trung vào website dễ hiểu, dễ sử dụng và có thể tiếp tục phát triển bằng nội dung, SEO, tích hợp và phần mềm khi doanh nghiệp thực sự cần.',
        ],
        'agency' => [
            'banner_eyebrow' => 'Đối tác kỹ thuật phía sau',
            'banner_title' => 'Agency tập trung khách hàng. Chúng tôi phụ trách phần website.',
            'banner_subtitle' => 'Nhận triển khai website, landing page, bảo trì hoặc từng module theo hình thức giới thiệu khách, đồng triển khai và white-label.',
        ],
        'privacy' => [
            'banner_title' => 'Chính sách bảo mật thông tin',
            'banner_eyebrow' => 'THÔNG TIN VÀ CHÍNH SÁCH',
            'banner_subtitle' => 'WebApp Bắc Ninh tôn trọng quyền riêng tư của khách hàng. Chính sách này giải thích loại thông tin có thể được tiếp nhận, mục đích sử dụng và cách khách hàng yêu cầu kiểm tra hoặc điều chỉnh dữ liệu đã cung cấp.',
        ],
        'terms' => [
            'banner_title' => 'Điều khoản sử dụng website',
            'banner_eyebrow' => 'THÔNG TIN VÀ CHÍNH SÁCH',
            'banner_subtitle' => 'Khi truy cập và sử dụng website WebApp Bắc Ninh, người dùng được hiểu là đã đọc và đồng ý tuân thủ các điều khoản dưới đây. Các nội dung báo giá, phạm vi triển khai và nghĩa vụ cụ thể của từng dự án sẽ được xác nhận riêng.',
        ],
        'warranty' => [
            'banner_title' => 'Chính sách bảo hành và hỗ trợ',
            'banner_eyebrow' => 'THÔNG TIN VÀ CHÍNH SÁCH',
            'banner_subtitle' => 'Chính sách này mô tả nguyên tắc bảo hành đối với website và hạng mục kỹ thuật do WebApp Bắc Ninh trực tiếp triển khai. Thời hạn và phạm vi cụ thể của từng dự án sẽ được ghi trong báo giá hoặc biên bản bàn giao.',
        ],
        'payment-process' => [
            'banner_title' => 'Quy trình thanh toán và triển khai',
            'banner_eyebrow' => 'THÔNG TIN VÀ CHÍNH SÁCH',
            'banner_subtitle' => 'Quy trình thanh toán được chia theo từng giai đoạn nhằm bảo đảm hai bên cùng kiểm soát phạm vi, tiến độ và chất lượng bàn giao. Tỷ lệ cụ thể có thể thay đổi theo quy mô dự án và sẽ được ghi trong báo giá.',
        ],
    ];

    /**
     * One record per fixed page, for SEO and the banner; records edited in the admin are kept.
     */
    public function run(): void
    {
        foreach (array_keys(SitePages::PAGES) as $key) {
            Page::query()->firstOrCreate(['key' => $key], ['title' => SitePages::title($key)] + (self::SEO[$key] ?? []) + (self::BANNERS[$key] ?? []));
        }
    }
}
