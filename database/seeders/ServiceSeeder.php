<?php

namespace Database\Seeders;

use App\Domain\Media\Actions\ImportLocalImage;
use App\Domain\Pricing\SeedServicePrices;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

class ServiceSeeder extends Seeder
{
    /** Short wording of the service cards shown from the start. */
    public const CARDS = [
        'landing-page' => ['card_title' => 'Landing Page', 'card_text' => 'Trang đích tối ưu chuyển đổi'],
        'phan-mem' => ['card_title' => 'Phần mềm doanh nghiệp', 'card_text' => 'Phát triển phần mềm theo nghiệp vụ riêng'],
        'crm-booking' => ['card_title' => 'CRM & Booking', 'card_text' => 'Quản lý khách hàng, đặt lịch, tự động hóa'],
        'seo-quang-cao' => ['card_title' => 'SEO, Quảng cáo & Tracking', 'card_text' => 'Tối ưu SEO, chạy quảng cáo, đo lường hiệu quả'],
    ];

    /**
     * Creates the sample services once; services edited in the admin are never overwritten.
     */
    public function run(ImportLocalImage $images, ?array $only = null): void
    {
        foreach ($this->services() as $row) {
            if (($only !== null && ! in_array($row['slug'], $only, true)) || Service::query()->where('slug', $row['slug'])->exists()) {
                continue;
            }

            $image = fn (string $path): ?int => $images->execute('images/'.$path, 'services', $row['title'])?->id;

            $service = Service::query()->create(Arr::except($row, ['image', 'secondary_image', 'price_from', 'packages']) + [
                'image_id' => $image($row['image']),
                'secondary_image_id' => $image($row['secondary_image']),
                'is_landing' => true,
                'is_active' => true,
            ]);

            app(SeedServicePrices::class)->execute($service, $row);

            // An earlier migration runs this seeder before the card columns exist.
            if (isset(self::CARDS[$row['slug']]) && Schema::hasColumn('services', 'show_in_grid')) {
                $service->update(self::CARDS[$row['slug']] + ['show_in_grid' => true]);
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function services(): array
    {
        return
        [
            [
                'slug' => 'website-doanh-nghiep',
                'title' => 'Website doanh nghiệp chuyên nghiệp để khách hàng tin và liên hệ',
                'menu_key' => 'business',
                'eyebrow' => 'WEBSITE DOANH NGHIỆP',
                'highlight' => 'Nền tảng giới thiệu năng lực, sản phẩm và dự án rõ ràng.',
                'description' => 'Dành cho công ty sản xuất, thương mại và dịch vụ cần một website đủ tin cậy để gửi khách hàng, đối tác, đại lý và ứng viên.',
                'icon' => 'building-2',
                'price_from' => 'Từ 8 triệu',
                'timeline' => '2 - 4 tuần',
                'cta' => 'Nhận cấu trúc website doanh nghiệp',
                'need_value' => 'Thiết kế website doanh nghiệp',
                'meta_title' => 'Thiết kế website doanh nghiệp tại Bắc Ninh',
                'meta_description' => 'Thiết kế website doanh nghiệp tại Bắc Ninh: giới thiệu năng lực, sản phẩm, dịch vụ, dự án, tuyển dụng và form nhận khách hàng. Dễ quản trị, SEO nền tảng.',
                'order' => 0,
                'image' => 'project-corporate.webp',
                'secondary_image' => 'about-bacninh.webp',
                'audiences' => [
                    'Doanh nghiệp sản xuất và nhà máy trong khu công nghiệp',
                    'Công ty thương mại, phân phối và xuất nhập khẩu',
                    'Doanh nghiệp dịch vụ cần hồ sơ năng lực trực tuyến',
                    'Công ty đang dùng website cũ, thiếu chuyên nghiệp hoặc khó cập nhật',
                ],
                'problems' => [
                    [
                        'icon' => 'file-question-mark',
                        'title' => 'Nội dung rời rạc',
                        'text' => 'Khách hàng không thấy rõ năng lực, sản phẩm chủ lực và lý do nên lựa chọn doanh nghiệp.',
                    ],
                    [
                        'icon' => 'smartphone',
                        'title' => 'Hiển thị mobile kém',
                        'text' => 'Website cũ khó đọc trên điện thoại, nút liên hệ nhỏ và tốc độ tải chậm.',
                    ],
                    [
                        'icon' => 'user-x',
                        'title' => 'Không tạo được lead',
                        'text' => 'Có lượt truy cập nhưng thiếu form, CTA, hotline và luồng chuyển đổi rõ ràng.',
                    ],
                ],
                'pages' => [
                    'Trang chủ',
                    'Giới thiệu',
                    'Sản phẩm / Dịch vụ',
                    'Năng lực / Dự án',
                    'Tin tức',
                    'Tuyển dụng',
                    'Liên hệ',
                ],
                'features' => [
                    [
                        'icon' => 'palette',
                        'title' => 'Theo nhận diện thương hiệu',
                        'text' => 'Màu sắc, font, hình ảnh và bố cục phù hợp doanh nghiệp.',
                    ],
                    [
                        'icon' => 'layers',
                        'title' => 'Cấu trúc rõ ràng',
                        'text' => 'Sắp xếp nội dung theo cách khách hàng dễ hiểu và dễ tìm.',
                    ],
                    [
                        'icon' => 'languages',
                        'title' => 'Có thể đa ngôn ngữ',
                        'text' => 'Mở rộng tiếng Anh, Trung hoặc ngôn ngữ khác khi cần.',
                    ],
                    [
                        'icon' => 'chart-line',
                        'title' => 'SEO nền tảng',
                        'text' => 'Heading, schema, sitemap, tốc độ và cấu trúc URL được chuẩn bị từ đầu.',
                    ],
                    [
                        'icon' => 'user-cog',
                        'title' => 'Quản trị dễ dùng',
                        'text' => 'Tự cập nhật bài viết, dịch vụ, dự án và thông tin liên hệ.',
                    ],
                    [
                        'icon' => 'headset',
                        'title' => 'Hỗ trợ sau bàn giao',
                        'text' => 'Có gói hosting, bảo trì, đăng bài và SEO định kỳ.',
                    ],
                ],
                'packages' => [
                    [
                        'name' => 'Khởi đầu',
                        'price' => 'Từ 8 triệu',
                        'desc' => 'Website giới thiệu gọn, đủ trang cơ bản.',
                        'items' => [
                            'Giao diện tùy chỉnh',
                            'Trang nội dung cơ bản',
                            'Form và bản đồ',
                            'SEO kỹ thuật nền tảng',
                        ],
                    ],
                    [
                        'name' => 'Tăng trưởng',
                        'price' => 'Từ 15 triệu',
                        'desc' => 'Cấu trúc chuyên sâu cho bán hàng và nội dung.',
                        'items' => [
                            'Thiết kế riêng theo ngành',
                            'Dự án và hồ sơ năng lực',
                            'Landing page dịch vụ',
                            'Tracking và kế hoạch nội dung',
                        ],
                        'featured' => true,
                    ],
                    [
                        'name' => 'Đa ngôn ngữ',
                        'price' => 'Báo giá riêng',
                        'desc' => 'Dành cho doanh nghiệp xuất khẩu hoặc nhiều thị trường.',
                        'items' => [
                            'Nhiều ngôn ngữ',
                            'Danh mục sản phẩm lớn',
                            'Phân quyền nội dung',
                            'Tích hợp theo nhu cầu',
                        ],
                    ],
                ],
                'faqs' => [
                    [
                        'q' => 'Website doanh nghiệp cần chuẩn bị những nội dung gì?',
                        'a' => 'Tối thiểu gồm thông tin giới thiệu, sản phẩm hoặc dịch vụ, năng lực nổi bật, hình ảnh thực tế, dự án và thông tin liên hệ. WebApp Bắc Ninh sẽ gửi danh sách dữ liệu cần chuẩn bị theo từng trang.',
                    ],
                    [
                        'q' => 'Có thể làm trước tiếng Việt rồi bổ sung tiếng Anh sau không?',
                        'a' => 'Có. Cấu trúc có thể được chuẩn bị sẵn để bổ sung ngôn ngữ ở giai đoạn sau mà không phải làm lại toàn bộ giao diện.',
                    ],
                    [
                        'q' => 'Website có dùng để gửi hồ sơ năng lực cho khách hàng được không?',
                        'a' => 'Có. Website được tổ chức để đội kinh doanh có thể gửi từng đường dẫn sản phẩm, dự án hoặc năng lực thay cho việc gửi nhiều file rời.',
                    ],
                ],
            ],
            [
                'slug' => 'website-ban-hang',
                'title' => 'Website bán hàng dễ quản lý, thuận tiện cho khách đặt mua',
                'menu_key' => 'ecommerce',
                'eyebrow' => 'WEBSITE BÁN HÀNG',
                'highlight' => 'Tập trung sản phẩm, đơn hàng và dữ liệu khách hàng về một nơi.',
                'description' => 'Phù hợp cửa hàng, nhà phân phối và doanh nghiệp muốn chủ động kênh bán hàng thay vì phụ thuộc hoàn toàn vào mạng xã hội hoặc sàn thương mại điện tử.',
                'icon' => 'shopping-cart',
                'price_from' => 'Từ 15 triệu',
                'timeline' => '3 - 6 tuần',
                'cta' => 'Nhận tư vấn website bán hàng',
                'need_value' => 'Thiết kế website bán hàng',
                'meta_title' => 'Thiết kế website bán hàng tại Bắc Ninh',
                'meta_description' => 'Thiết kế website bán hàng tại Bắc Ninh với danh mục sản phẩm, giỏ hàng, đơn hàng, thanh toán, vận chuyển và quản trị dễ sử dụng.',
                'order' => 1,
                'image' => 'project-ecommerce.webp',
                'secondary_image' => 'seo-operation.webp',
                'audiences' => [
                    'Cửa hàng muốn nhận đơn trực tiếp trên website',
                    'Nhà phân phối có nhiều nhóm sản phẩm và bảng giá',
                    'Doanh nghiệp đang bán trên Facebook, Zalo hoặc sàn',
                    'Thương hiệu cần quản lý dữ liệu khách hàng và nội dung riêng',
                ],
                'problems' => [
                    [
                        'icon' => 'hand-coins',
                        'title' => 'Chốt đơn thủ công',
                        'text' => 'Nhân viên phải hỏi lại mã hàng, giá, số lượng và thông tin giao hàng qua tin nhắn.',
                    ],
                    [
                        'icon' => 'boxes',
                        'title' => 'Sản phẩm khó tìm',
                        'text' => 'Danh mục lớn nhưng thiếu bộ lọc, tìm kiếm và cách trình bày thông tin rõ ràng.',
                    ],
                    [
                        'icon' => 'database',
                        'title' => 'Dữ liệu phân tán',
                        'text' => 'Đơn hàng và khách hàng nằm ở nhiều kênh, khó theo dõi và chăm sóc lại.',
                    ],
                ],
                'pages' => [
                    'Trang chủ bán hàng',
                    'Danh mục sản phẩm',
                    'Chi tiết sản phẩm',
                    'Giỏ hàng',
                    'Đặt hàng',
                    'Tra cứu đơn',
                    'Khuyến mại / Tin tức',
                    'Liên hệ',
                ],
                'features' => [
                    [
                        'icon' => 'package-open',
                        'title' => 'Quản lý sản phẩm',
                        'text' => 'Danh mục, thuộc tính, giá, hình ảnh và trạng thái sản phẩm.',
                    ],
                    [
                        'icon' => 'shopping-cart',
                        'title' => 'Giỏ hàng và đặt hàng',
                        'text' => 'Quy trình mua hàng gọn trên cả điện thoại và máy tính.',
                    ],
                    [
                        'icon' => 'credit-card',
                        'title' => 'Thanh toán',
                        'text' => 'Có thể tích hợp QR, chuyển khoản hoặc cổng thanh toán.',
                    ],
                    [
                        'icon' => 'truck',
                        'title' => 'Vận chuyển',
                        'text' => 'Cấu hình phí giao hàng hoặc tích hợp đơn vị vận chuyển khi cần.',
                    ],
                    [
                        'icon' => 'receipt',
                        'title' => 'Quản lý đơn',
                        'text' => 'Theo dõi trạng thái, thông tin khách và lịch sử xử lý đơn hàng.',
                    ],
                    [
                        'icon' => 'chart-column',
                        'title' => 'Theo dõi chuyển đổi',
                        'text' => 'Gắn Analytics, Pixel và các sự kiện mua hàng quan trọng.',
                    ],
                ],
                'packages' => [
                    [
                        'name' => 'Catalog',
                        'price' => 'Từ 12 triệu',
                        'desc' => 'Trưng bày sản phẩm, nhận yêu cầu báo giá.',
                        'items' => [
                            'Danh mục sản phẩm',
                            'Tìm kiếm cơ bản',
                            'Form báo giá',
                            'Quản trị nội dung',
                        ],
                    ],
                    [
                        'name' => 'Bán hàng',
                        'price' => 'Từ 18 triệu',
                        'desc' => 'Đủ giỏ hàng, đặt hàng và quản lý đơn.',
                        'items' => [
                            'Giỏ hàng và checkout',
                            'Quản lý đơn hàng',
                            'Mã giảm giá cơ bản',
                            'Thông báo đơn mới',
                        ],
                        'featured' => true,
                    ],
                    [
                        'name' => 'Theo nghiệp vụ',
                        'price' => 'Báo giá riêng',
                        'desc' => 'Tích hợp kho, thanh toán, vận chuyển hoặc hệ thống khác.',
                        'items' => [
                            'API và đồng bộ',
                            'Phân quyền nâng cao',
                            'Nhiều kho / chi nhánh',
                            'Báo cáo theo yêu cầu',
                        ],
                    ],
                ],
                'faqs' => [
                    [
                        'q' => 'Website bán hàng có bắt buộc tích hợp thanh toán online không?',
                        'a' => 'Không. Có thể bắt đầu bằng đặt hàng và chuyển khoản, sau đó bổ sung cổng thanh toán khi lượng đơn và quy trình đã ổn định.',
                    ],
                    [
                        'q' => 'Có nhập sản phẩm từ Excel được không?',
                        'a' => 'Có thể xây chức năng nhập dữ liệu tùy theo cấu trúc sản phẩm. Cần xem trước file Excel để xác định trường dữ liệu và cách xử lý ảnh.',
                    ],
                    [
                        'q' => 'Website có kết nối phần mềm bán hàng hoặc kho không?',
                        'a' => 'Có thể tích hợp nếu phần mềm hiện tại có API hoặc cơ chế xuất nhập dữ liệu phù hợp. Phần này được khảo sát và báo giá riêng.',
                    ],
                ],
            ],
            [
                'slug' => 'landing-page',
                'title' => 'Landing page tập trung một mục tiêu và một hành động chính',
                'menu_key' => 'landing',
                'eyebrow' => 'LANDING PAGE CHUYỂN ĐỔI',
                'highlight' => 'Dùng cho quảng cáo, tuyển sinh, sự kiện và dịch vụ trọng điểm.',
                'description' => 'Một trang đích được xây theo luồng thuyết phục rõ ràng, giúp khách hàng hiểu lợi ích, xem bằng chứng và để lại thông tin hoặc liên hệ ngay.',
                'icon' => 'target',
                'price_from' => 'Từ 6,5 triệu',
                'timeline' => '7 - 15 ngày',
                'cta' => 'Nhận bố cục landing page',
                'need_value' => 'Thiết kế landing page',
                'meta_title' => 'Thiết kế landing page tại Bắc Ninh',
                'meta_description' => 'Thiết kế landing page tại Bắc Ninh cho quảng cáo, tuyển sinh, sự kiện, ra mắt sản phẩm và dịch vụ. Tối ưu mobile, form lead và tracking.',
                'order' => 2,
                'image' => 'project-landing.webp',
                'secondary_image' => 'project-tour.webp',
                'audiences' => [
                    'Chiến dịch chạy quảng cáo Facebook hoặc Google',
                    'Sự kiện, hội thảo, tuyển sinh và đăng ký tham dự',
                    'Ra mắt sản phẩm hoặc dịch vụ mới',
                    'Doanh nghiệp cần thử nghiệm thị trường trước khi làm website lớn',
                ],
                'problems' => [
                    [
                        'icon' => 'chart-no-axes-combined',
                        'title' => 'Chi phí quảng cáo cao',
                        'text' => 'Khách bấm quảng cáo nhưng trang đích không đúng thông điệp hoặc quá nhiều lựa chọn.',
                    ],
                    [
                        'icon' => 'route',
                        'title' => 'Luồng nội dung yếu',
                        'text' => 'Thông tin thiếu thứ tự, không xử lý phản đối và chưa tạo động lực hành động.',
                    ],
                    [
                        'icon' => 'funnel-x',
                        'title' => 'Không đo được lead',
                        'text' => 'Thiếu form, sự kiện tracking và nguồn chiến dịch để đánh giá hiệu quả.',
                    ],
                ],
                'pages' => [
                    'Hero và CTA',
                    'Vấn đề khách hàng',
                    'Giải pháp / Lợi ích',
                    'Dịch vụ / Sản phẩm',
                    'Bằng chứng / Dự án',
                    'Bảng giá',
                    'FAQ',
                    'Form đăng ký',
                ],
                'features' => [
                    [
                        'icon' => 'pen-tool',
                        'title' => 'Bố cục bán hàng',
                        'text' => 'Sắp xếp nội dung theo hành trình ra quyết định của khách hàng.',
                    ],
                    [
                        'icon' => 'smartphone',
                        'title' => 'Mobile-first',
                        'text' => 'Ưu tiên tốc độ đọc, nút CTA và form trên điện thoại.',
                    ],
                    [
                        'icon' => 'wand-sparkles',
                        'title' => 'Hiệu ứng vừa đủ',
                        'text' => 'Chuyển động hỗ trợ trải nghiệm, không làm trang nặng hoặc rối.',
                    ],
                    [
                        'icon' => 'list-checks',
                        'title' => 'Form thu lead',
                        'text' => 'Biểu mẫu ngắn, rõ và có thể lưu hoặc gửi thông báo.',
                    ],
                    [
                        'icon' => 'chart-column',
                        'title' => 'Tracking chiến dịch',
                        'text' => 'Gắn Analytics, Pixel, UTM và sự kiện chuyển đổi.',
                    ],
                    [
                        'icon' => 'flask-conical',
                        'title' => 'Dễ thử nghiệm',
                        'text' => 'Có thể thay CTA, tiêu đề hoặc gói giá để tối ưu sau khi chạy.',
                    ],
                ],
                'packages' => [
                    [
                        'name' => 'Landing cơ bản',
                        'price' => 'Từ 6,5 triệu',
                        'desc' => 'Một trang đích rõ thông tin và form đăng ký.',
                        'items' => [
                            'Thiết kế responsive',
                            'Tối đa 8 section',
                            'Form lead',
                            'SEO và chia sẻ cơ bản',
                        ],
                    ],
                    [
                        'name' => 'Landing quảng cáo',
                        'price' => 'Từ 9 triệu',
                        'desc' => 'Tập trung chuyển đổi và đo lường chiến dịch.',
                        'items' => [
                            'Nội dung theo hành trình',
                            'Tracking và UTM',
                            'CTA cố định',
                            'Tối ưu tốc độ',
                        ],
                        'featured' => true,
                    ],
                    [
                        'name' => 'Landing nâng cao',
                        'price' => 'Báo giá riêng',
                        'desc' => 'Có tính toán, booking, thanh toán hoặc chức năng riêng.',
                        'items' => [
                            'Logic biểu mẫu',
                            'Tích hợp API',
                            'Thanh toán / đặt lịch',
                            'Dashboard dữ liệu',
                        ],
                    ],
                ],
                'faqs' => [
                    [
                        'q' => 'Landing page khác website thông thường ở điểm nào?',
                        'a' => 'Landing page tập trung vào một chiến dịch và một hành động chính, trong khi website có nhiều nhóm nội dung và phục vụ hoạt động dài hạn.',
                    ],
                    [
                        'q' => 'Có hỗ trợ viết nội dung landing page không?',
                        'a' => 'Có thể hỗ trợ xây khung nội dung, biên tập thông điệp và CTA. Mức độ viết mới hoàn toàn sẽ được xác định theo dữ liệu doanh nghiệp cung cấp.',
                    ],
                    [
                        'q' => 'Landing page có chạy độc lập với website hiện tại được không?',
                        'a' => 'Có. Landing page có thể đặt trên tên miền riêng, tên miền phụ hoặc một đường dẫn trong website hiện tại.',
                    ],
                ],
            ],
            [
                'slug' => 'thiet-ke-lai-website',
                'title' => 'Làm lại website cũ nhưng giữ phần dữ liệu còn giá trị',
                'menu_key' => 'redesign',
                'eyebrow' => 'THIẾT KẾ LẠI WEBSITE CŨ',
                'highlight' => 'Cải thiện giao diện, tốc độ, quản trị và khả năng tạo khách hàng.',
                'description' => 'Không phải lúc nào cũng cần bỏ toàn bộ website. WebApp Bắc Ninh kiểm tra cấu trúc, nội dung, dữ liệu và hệ thống hiện tại để đề xuất phần nên giữ, phần cần thay và cách chuyển đổi an toàn.',
                'icon' => 'refresh-cw',
                'price_from' => 'Khảo sát trước',
                'timeline' => '2 - 6 tuần',
                'cta' => 'Yêu cầu kiểm tra website cũ',
                'need_value' => 'Thiết kế lại website cũ',
                'meta_title' => 'Thiết kế lại website cũ tại Bắc Ninh',
                'meta_description' => 'Dịch vụ thiết kế lại website cũ tại Bắc Ninh: nâng cấp giao diện, mobile, tốc độ, SEO, quản trị và chuyển dữ liệu an toàn.',
                'order' => 3,
                'image' => 'project-software.webp',
                'secondary_image' => 'hero-industrial.webp',
                'audiences' => [
                    'Website đã dùng nhiều năm và giao diện lỗi thời',
                    'Trang tải chậm, lỗi mobile hoặc thường xuyên phát sinh lỗi',
                    'Khu vực quản trị khó dùng, không cập nhật được nội dung',
                    'Website có dữ liệu SEO hoặc bài viết cần giữ lại khi nâng cấp',
                ],
                'problems' => [
                    [
                        'icon' => 'gauge',
                        'title' => 'Tốc độ và trải nghiệm kém',
                        'text' => 'Mã nguồn cũ, ảnh nặng, plugin dư thừa hoặc bố cục không còn phù hợp thiết bị mới.',
                    ],
                    [
                        'icon' => 'triangle-alert',
                        'title' => 'Rủi ro mất dữ liệu',
                        'text' => 'Làm mới thiếu kế hoạch có thể mất bài viết, URL cũ, thứ hạng và thông tin khách hàng.',
                    ],
                    [
                        'icon' => 'wrench',
                        'title' => 'Khó bảo trì',
                        'text' => 'Không rõ đơn vị cũ, source thiếu hoặc hệ thống phụ thuộc nhiều thành phần lỗi thời.',
                    ],
                ],
                'pages' => [
                    'Kiểm tra hiện trạng',
                    'Sao lưu dữ liệu',
                    'Thiết kế giao diện mới',
                    'Chuyển nội dung',
                    'Redirect URL cũ',
                    'Kiểm thử form và chức năng',
                    'Theo dõi sau chuyển đổi',
                ],
                'features' => [
                    [
                        'icon' => 'search',
                        'title' => 'Audit website cũ',
                        'text' => 'Kiểm tra giao diện, nội dung, tốc độ, SEO, hosting và mã nguồn.',
                    ],
                    [
                        'icon' => 'database',
                        'title' => 'Giữ dữ liệu quan trọng',
                        'text' => 'Lập kế hoạch sao lưu và chuyển bài viết, sản phẩm, khách hàng khi có thể.',
                    ],
                    [
                        'icon' => 'link',
                        'title' => 'Bảo toàn URL',
                        'text' => 'Giữ đường dẫn có giá trị hoặc thiết lập redirect phù hợp.',
                    ],
                    [
                        'icon' => 'smartphone',
                        'title' => 'Giao diện responsive mới',
                        'text' => 'Thiết kế lại theo nội dung và mục tiêu kinh doanh hiện tại.',
                    ],
                    [
                        'icon' => 'shield-check',
                        'title' => 'Kiểm thử trước khi chuyển',
                        'text' => 'Chạy bản mới trên môi trường thử nghiệm và có phương án quay lại.',
                    ],
                    [
                        'icon' => 'chart-line',
                        'title' => 'Theo dõi sau nâng cấp',
                        'text' => 'Kiểm tra lỗi 404, Search Console, form và tốc độ sau khi đưa vào sử dụng.',
                    ],
                ],
                'packages' => [
                    [
                        'name' => 'Làm mới giao diện',
                        'price' => 'Từ 10 triệu',
                        'desc' => 'Giữ nền tảng hiện tại nếu mã nguồn còn phù hợp.',
                        'items' => [
                            'Thiết kế giao diện mới',
                            'Responsive',
                            'Tối ưu trang chính',
                            'Kiểm tra chức năng cũ',
                        ],
                    ],
                    [
                        'name' => 'Xây lại có chuyển dữ liệu',
                        'price' => 'Từ 18 triệu',
                        'desc' => 'Dùng nền tảng mới và chuyển phần dữ liệu còn giá trị.',
                        'items' => [
                            'Audit và backup',
                            'Phát triển website mới',
                            'Chuyển dữ liệu',
                            'Redirect và kiểm thử',
                        ],
                        'featured' => true,
                    ],
                    [
                        'name' => 'Hệ thống phức tạp',
                        'price' => 'Báo giá riêng',
                        'desc' => 'Website bán hàng, thành viên hoặc có tích hợp nghiệp vụ.',
                        'items' => [
                            'Phân tích database',
                            'Chuyển đổi theo giai đoạn',
                            'API và tích hợp',
                            'Kế hoạch downtime',
                        ],
                    ],
                ],
                'faqs' => [
                    [
                        'q' => 'Làm lại website có mất thứ hạng Google không?',
                        'a' => 'Có rủi ro nếu thay URL và nội dung thiếu kế hoạch. Vì vậy cần lập danh sách URL cũ, giữ nội dung có giá trị, thiết lập redirect và theo dõi Search Console sau khi chuyển.',
                    ],
                    [
                        'q' => 'Có giữ lại bài viết và sản phẩm cũ được không?',
                        'a' => 'Thông thường có thể, nhưng phụ thuộc mã nguồn, cấu trúc database và chất lượng dữ liệu hiện tại. Cần kiểm tra trước khi cam kết phạm vi chuyển.',
                    ],
                    [
                        'q' => 'Website cũ vẫn hoạt động trong lúc làm bản mới chứ?',
                        'a' => 'Có. Bản mới nên được phát triển trên môi trường riêng. Chỉ chuyển sang bản mới sau khi đã kiểm thử và có bản sao lưu đầy đủ.',
                    ],
                ],
            ],
            [
                'slug' => 'phan-mem',
                'section' => 'general',
                'title' => 'Phần mềm doanh nghiệp theo quy trình riêng của bạn',
                'menu_key' => 'software',
                'eyebrow' => 'PHẦN MỀM THEO YÊU CẦU',
                'highlight' => 'Từ quản lý nội bộ đến hệ thống nghiệp vụ, xây đúng theo cách doanh nghiệp đang vận hành.',
                'description' => 'Phát triển phần mềm quản lý, hệ thống nội bộ và công cụ nghiệp vụ theo quy trình riêng, giúp giảm việc thủ công, thống nhất dữ liệu và tăng hiệu suất đội ngũ.',
                'icon' => 'brain',
                'price_from' => 'Báo giá theo phạm vi',
                'timeline' => '4 - 12 tuần',
                'cta' => 'Nhận tư vấn phần mềm',
                'need_value' => 'Phần mềm doanh nghiệp theo yêu cầu',
                'meta_title' => 'Phần mềm doanh nghiệp theo yêu cầu tại Bắc Ninh',
                'meta_description' => 'Phát triển phần mềm quản lý, hệ thống nội bộ theo quy trình riêng của doanh nghiệp tại Bắc Ninh: khảo sát, thiết kế, lập trình, tích hợp, đào tạo và bảo trì lâu dài.',
                'order' => 10,
                'image' => 'service-software.webp',
                'secondary_image' => 'project-software.webp',
                'audiences' => [
                    'Doanh nghiệp đang quản lý bằng Excel, Zalo và giấy tờ rời rạc',
                    'Nhà máy, kho, đơn vị phân phối cần theo dõi đơn hàng và tồn kho',
                    'Công ty dịch vụ cần quản lý hợp đồng, công việc và doanh thu',
                    'Đơn vị đang dùng phần mềm đóng gói nhưng không khớp quy trình',
                ],
                'problems' => [
                    ['icon' => 'files', 'title' => 'Dữ liệu nằm nhiều nơi', 'text' => 'Mỗi bộ phận giữ một file riêng, số liệu lệch nhau và mất thời gian tổng hợp báo cáo.'],
                    ['icon' => 'git-branch', 'title' => 'Quy trình phụ thuộc con người', 'text' => 'Công việc đi qua nhiều người bằng tin nhắn, dễ sót bước và khó biết đang chậm ở đâu.'],
                    ['icon' => 'puzzle', 'title' => 'Phần mềm có sẵn không khớp', 'text' => 'Phải đổi cách làm để chiều theo phần mềm, trong khi nghiệp vụ riêng vẫn không xử lý được.'],
                ],
                'pages' => [
                    'Khảo sát và mô tả quy trình',
                    'Phân quyền người dùng',
                    'Quản lý dữ liệu và danh mục',
                    'Luồng xử lý và phê duyệt',
                    'Báo cáo và dashboard',
                    'Tích hợp hệ thống hiện có',
                    'Hướng dẫn và đào tạo sử dụng',
                ],
                'features' => [
                    ['icon' => 'clipboard-list', 'title' => 'Thiết kế theo nghiệp vụ', 'text' => 'Phân tích quy trình thực tế trước khi viết dòng code đầu tiên.'],
                    ['icon' => 'shield-check', 'title' => 'Phân quyền và bảo mật', 'text' => 'Mỗi vai trò chỉ thấy và làm đúng phần việc của mình.'],
                    ['icon' => 'plug', 'title' => 'Tích hợp linh hoạt', 'text' => 'Kết nối kế toán, Zalo, email, cổng thanh toán hoặc phần mềm đang dùng.'],
                    ['icon' => 'chart-column', 'title' => 'Báo cáo theo thời gian thực', 'text' => 'Số liệu cập nhật ngay, xem trên máy tính và điện thoại.'],
                    ['icon' => 'layers', 'title' => 'Mở rộng theo quy mô', 'text' => 'Kiến trúc cho phép thêm chức năng mà không phải làm lại từ đầu.'],
                    ['icon' => 'headset', 'title' => 'Bảo trì lâu dài', 'text' => 'Sao lưu, vá lỗi, nâng cấp và hỗ trợ kỹ thuật sau bàn giao.'],
                ],
                'packages' => [
                    ['name' => 'Công cụ nhỏ', 'price' => 'Báo giá theo phạm vi', 'desc' => 'Một nghiệp vụ cụ thể như quản lý đơn, công việc hoặc báo giá.', 'items' => ['Khảo sát nhanh', 'Một luồng nghiệp vụ chính', 'Phân quyền cơ bản', 'Hướng dẫn sử dụng']],
                    ['name' => 'Hệ thống quản lý', 'price' => 'Báo giá theo phạm vi', 'desc' => 'Nhiều phân hệ liên thông cho một bộ phận hoặc cả doanh nghiệp.', 'items' => ['Nhiều phân hệ liên thông', 'Báo cáo và dashboard', 'Tích hợp hệ thống', 'Đào tạo người dùng'], 'featured' => true],
                    ['name' => 'Nền tảng riêng', 'price' => 'Báo giá riêng', 'desc' => 'Hệ thống lớn, nhiều chi nhánh hoặc mô hình SaaS.', 'items' => ['Kiến trúc mở rộng', 'Nhiều chi nhánh / đơn vị', 'API cho bên thứ ba', 'Vận hành và bảo trì dài hạn']],
                ],
                'faqs' => [
                    ['q' => 'Làm phần mềm theo yêu cầu mất bao lâu?', 'a' => 'Công cụ nhỏ thường 4 đến 6 tuần, hệ thống nhiều phân hệ từ 2 đến 3 tháng. Sau khảo sát sẽ có lộ trình và mốc bàn giao từng giai đoạn.'],
                    ['q' => 'Chi phí được tính như thế nào?', 'a' => 'Chi phí phụ thuộc phạm vi nghiệp vụ, số phân hệ và mức tích hợp. Sau buổi khảo sát, WebApp Bắc Ninh gửi báo giá chi tiết theo từng hạng mục để doanh nghiệp chủ động lựa chọn.'],
                    ['q' => 'Có thể làm từng giai đoạn không?', 'a' => 'Có. Nên bắt đầu với phần mang lại hiệu quả rõ nhất, chạy thực tế rồi mới mở rộng, thay vì làm toàn bộ cùng lúc.'],
                    ['q' => 'Dữ liệu và mã nguồn thuộc về ai?', 'a' => 'Dữ liệu thuộc về doanh nghiệp. Quyền sử dụng và bàn giao mã nguồn được thống nhất rõ trong hợp đồng.'],
                ],
            ],
            [
                'slug' => 'crm-booking',
                'section' => 'general',
                'title' => 'CRM & Booking giúp quản lý khách hàng và đặt lịch tự động',
                'menu_key' => 'crm',
                'eyebrow' => 'CRM & BOOKING',
                'highlight' => 'Một nơi để biết khách là ai, đã đặt gì và cần chăm sóc lúc nào.',
                'description' => 'Giải pháp quản lý khách hàng và đặt lịch cho spa, phòng khám, giáo dục và doanh nghiệp dịch vụ: gom dữ liệu về một chỗ, nhắc lịch tự động và theo dõi doanh thu.',
                'icon' => 'calendar-check',
                'price_from' => 'Báo giá theo phạm vi',
                'timeline' => '3 - 8 tuần',
                'cta' => 'Nhận tư vấn CRM & Booking',
                'need_value' => 'CRM / Booking cho doanh nghiệp dịch vụ',
                'meta_title' => 'CRM & Booking cho doanh nghiệp dịch vụ tại Bắc Ninh',
                'meta_description' => 'CRM quản lý khách hàng và hệ thống đặt lịch online cho spa, phòng khám, giáo dục, dịch vụ tại Bắc Ninh: nhắc lịch tự động, tích hợp Zalo, Email, SMS, báo cáo doanh thu.',
                'order' => 11,
                'image' => 'service-crm.webp',
                'secondary_image' => 'crm.webp',
                'audiences' => [
                    'Spa, thẩm mỹ viện, salon và phòng khám',
                    'Trung tâm đào tạo, giáo dục và tư vấn',
                    'Doanh nghiệp dịch vụ có nhiều nhân viên chăm sóc khách',
                    'Đơn vị muốn chuyển khách từ Zalo, Facebook về một hệ thống',
                ],
                'problems' => [
                    ['icon' => 'message-circle', 'title' => 'Khách nằm rải rác', 'text' => 'Thông tin khách ở Zalo, Facebook, sổ tay và điện thoại từng nhân viên, nghỉ việc là mất.'],
                    ['icon' => 'clock', 'title' => 'Đặt lịch thủ công', 'text' => 'Nhận lịch qua tin nhắn, dễ trùng giờ, quên nhắc và khách bỏ hẹn không báo.'],
                    ['icon' => 'chart-line', 'title' => 'Không đo được hiệu quả', 'text' => 'Khó biết khách quay lại bao nhiêu, nhân viên nào hiệu quả, dịch vụ nào đem lại doanh thu.'],
                ],
                'pages' => [
                    'Hồ sơ và lịch sử khách hàng',
                    'Đặt lịch online',
                    'Lịch làm việc nhân viên',
                    'Nhắc lịch qua Zalo, Email, SMS',
                    'Gói dịch vụ và thẻ liệu trình',
                    'Báo cáo doanh thu và hiệu suất',
                ],
                'features' => [
                    ['icon' => 'users', 'title' => 'Quản lý khách tập trung', 'text' => 'Một hồ sơ cho mỗi khách với lịch sử sử dụng, ghi chú và nguồn đến.'],
                    ['icon' => 'calendar-check', 'title' => 'Đặt lịch và nhắc lịch', 'text' => 'Khách tự chọn giờ trống, hệ thống tự nhắc để giảm tình trạng bỏ hẹn.'],
                    ['icon' => 'message-circle', 'title' => 'Tích hợp Zalo, Email, SMS', 'text' => 'Gửi xác nhận, nhắc lịch và chăm sóc sau dịch vụ ngay trên kênh khách đang dùng.'],
                    ['icon' => 'user-cog', 'title' => 'Phân ca nhân viên', 'text' => 'Quản lý lịch, hoa hồng và hiệu suất theo từng nhân viên.'],
                    ['icon' => 'chart-column', 'title' => 'Báo cáo doanh thu', 'text' => 'Theo dõi doanh thu, khách mới, khách quay lại theo ngày, tuần, tháng.'],
                    ['icon' => 'headset', 'title' => 'Hỗ trợ triển khai', 'text' => 'Nhập dữ liệu khách cũ, đào tạo nhân viên và hỗ trợ trong giai đoạn đầu.'],
                ],
                'packages' => [
                    ['name' => 'Đặt lịch', 'price' => 'Báo giá theo phạm vi', 'desc' => 'Website kèm đặt lịch online cho một cơ sở.', 'items' => ['Đặt lịch online', 'Lịch nhân viên', 'Nhắc lịch tự động', 'Quản lý khách cơ bản']],
                    ['name' => 'CRM dịch vụ', 'price' => 'Báo giá theo phạm vi', 'desc' => 'Quản lý khách, lịch và doanh thu cho cơ sở dịch vụ.', 'items' => ['Hồ sơ khách hàng', 'Gói và liệu trình', 'Tích hợp Zalo, Email, SMS', 'Báo cáo doanh thu'], 'featured' => true],
                    ['name' => 'Nhiều chi nhánh', 'price' => 'Báo giá riêng', 'desc' => 'Chuỗi cơ sở cần quản lý tập trung.', 'items' => ['Nhiều chi nhánh', 'Phân quyền theo cơ sở', 'Báo cáo tổng hợp', 'Tích hợp theo nhu cầu']],
                ],
                'faqs' => [
                    ['q' => 'Có dùng thử trước khi triển khai không?', 'a' => 'Có bản demo để xem luồng đặt lịch và quản lý khách. Sau khi chốt nhu cầu, hệ thống được cấu hình theo dịch vụ và quy trình của doanh nghiệp.'],
                    ['q' => 'Nhập dữ liệu khách hiện có thế nào?', 'a' => 'Dữ liệu từ Excel hoặc phần mềm cũ có thể được chuẩn hóa và nhập vào hệ thống khi triển khai.'],
                    ['q' => 'Khách đặt lịch có cần tải ứng dụng không?', 'a' => 'Không. Khách đặt lịch ngay trên website hoặc đường dẫn được gửi qua Zalo, không cần cài thêm ứng dụng.'],
                ],
            ],
            [
                'slug' => 'seo-quang-cao',
                'section' => 'general',
                'title' => 'SEO, quảng cáo và đo lường để tăng trưởng bền vững',
                'menu_key' => 'marketing',
                'eyebrow' => 'SEO, QUẢNG CÁO & TRACKING',
                'highlight' => 'Kéo đúng khách về website và đo được từng đồng chi ra.',
                'description' => 'Tối ưu SEO, triển khai quảng cáo Google, Facebook, Zalo và thiết lập đo lường để doanh nghiệp biết kênh nào đem lại khách hàng thật.',
                'icon' => 'rocket',
                'price_from' => 'Báo giá theo phạm vi',
                'timeline' => '1 - 3 tháng thấy kết quả đầu',
                'cta' => 'Nhận tư vấn SEO & quảng cáo',
                'need_value' => 'SEO, quảng cáo và tracking',
                'meta_title' => 'SEO, quảng cáo Google Facebook Zalo và tracking tại Bắc Ninh',
                'meta_description' => 'Dịch vụ SEO tổng thể, quảng cáo Google, Facebook, Zalo và thiết lập đo lường tracking tại Bắc Ninh. Có báo cáo định kỳ và tối ưu liên tục.',
                'order' => 12,
                'image' => 'service-marketing.webp',
                'secondary_image' => 'marketing.webp',
                'audiences' => [
                    'Doanh nghiệp có website nhưng ít khách tìm thấy trên Google',
                    'Đơn vị đang chạy quảng cáo nhưng chưa biết hiệu quả thật',
                    'Doanh nghiệp cần nội dung chuẩn tìm kiếm cho dịch vụ, sản phẩm',
                    'Đội kinh doanh muốn có số liệu để ra quyết định',
                ],
                'problems' => [
                    ['icon' => 'search', 'title' => 'Website khó được tìm thấy', 'text' => 'Từ khóa quan trọng không có thứ hạng, nội dung chưa trả lời đúng điều khách tìm.'],
                    ['icon' => 'hand-coins', 'title' => 'Chi quảng cáo chưa rõ hiệu quả', 'text' => 'Có chi phí nhưng không biết khách đến từ đâu và chuyển đổi bao nhiêu.'],
                    ['icon' => 'chart-pie', 'title' => 'Thiếu số liệu', 'text' => 'Chưa gắn Analytics, Pixel hay sự kiện, quyết định dựa trên cảm tính.'],
                ],
                'pages' => [
                    'Kiểm tra kỹ thuật và nội dung hiện tại',
                    'Nghiên cứu từ khóa và đối thủ',
                    'Tối ưu trang và sản xuất nội dung',
                    'Quảng cáo Google, Facebook, Zalo',
                    'Thiết lập Analytics, Pixel, sự kiện',
                    'Báo cáo định kỳ và đề xuất',
                ],
                'features' => [
                    ['icon' => 'search', 'title' => 'SEO tổng thể', 'text' => 'Kỹ thuật, nội dung và liên kết được tối ưu theo lộ trình rõ ràng.'],
                    ['icon' => 'megaphone', 'title' => 'Quảng cáo đa kênh', 'text' => 'Google, Facebook, Zalo với ngân sách và mục tiêu thống nhất từ đầu.'],
                    ['icon' => 'target', 'title' => 'Đo lường chuyển đổi', 'text' => 'Gắn Analytics, Pixel, theo dõi form, cuộc gọi và đơn hàng.'],
                    ['icon' => 'file-text', 'title' => 'Nội dung chuẩn tìm kiếm', 'text' => 'Bài viết và trang dịch vụ viết theo ý định tìm kiếm của khách.'],
                    ['icon' => 'chart-column', 'title' => 'Báo cáo minh bạch', 'text' => 'Số liệu thực tế mỗi tháng kèm nhận định và việc sẽ làm tiếp.'],
                    ['icon' => 'settings', 'title' => 'Tối ưu liên tục', 'text' => 'Điều chỉnh từ khóa, mẫu quảng cáo và ngân sách theo kết quả.'],
                ],
                'packages' => [
                    ['name' => 'Nền tảng đo lường', 'price' => 'Báo giá theo phạm vi', 'desc' => 'Thiết lập một lần để mọi hoạt động sau đó đo được.', 'items' => ['Google Analytics, Search Console', 'Pixel và sự kiện chuyển đổi', 'Kiểm tra kỹ thuật SEO', 'Báo cáo hiện trạng']],
                    ['name' => 'SEO định kỳ', 'price' => 'Báo giá theo phạm vi', 'desc' => 'Tăng thứ hạng và lượng khách tự nhiên.', 'items' => ['Nghiên cứu từ khóa', 'Tối ưu trang và nội dung', 'Báo cáo hằng tháng', 'Đề xuất tháng sau'], 'featured' => true],
                    ['name' => 'Quảng cáo & tăng trưởng', 'price' => 'Báo giá theo ngân sách', 'desc' => 'Kết hợp SEO và quảng cáo để tăng khách ngay.', 'items' => ['Chiến dịch Google, Facebook, Zalo', 'Tối ưu liên tục', 'Tracking chuyển đổi', 'Chiến lược nội dung']],
                ],
                'faqs' => [
                    ['q' => 'SEO bao lâu thì có kết quả?', 'a' => 'Thông thường từ 3 đến 6 tháng tùy độ cạnh tranh của từ khóa và hiện trạng website. Những cải thiện về kỹ thuật và đo lường có thể thấy sớm hơn.'],
                    ['q' => 'Ngân sách quảng cáo có tính vào phí dịch vụ không?', 'a' => 'Không. Ngân sách quảng cáo trả trực tiếp cho nền tảng, phí dịch vụ là phần thiết lập, vận hành và tối ưu.'],
                    ['q' => 'Có cam kết thứ hạng không?', 'a' => 'Không ai kiểm soát hoàn toàn thuật toán tìm kiếm. WebApp Bắc Ninh cam kết quy trình, báo cáo minh bạch và mục tiêu đo lường được.'],
                ],
            ],
        ];
    }
}
