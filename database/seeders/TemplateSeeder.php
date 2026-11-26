<?php

namespace Database\Seeders;

use App\Domain\Media\Actions\ImportLocalImage;
use App\Enums\LandingTemplate;
use App\Models\Template;
use App\Models\TemplateCategory;
use App\Models\ThemeFeature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class TemplateSeeder extends Seeder
{
    private const FEATURES = [
        'multilang' => 'Đa ngôn ngữ',
        'ecommerce' => 'Giỏ hàng - đặt hàng',
        'booking' => 'Đặt lịch - booking',
        'lead' => 'Form thu lead',
    ];

    /**
     * Creates the sample templates once; templates edited in the admin are never overwritten.
     */
    public function run(ImportLocalImage $images): void
    {
        foreach (self::FEATURES as $slug => $name) {
            ThemeFeature::query()->firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        $drafts = require database_path('data/industry-pages.php');

        foreach ([...$this->templates(), ...$this->landingSamples()] as $row) {
            if (Template::query()->where('slug', $row['slug'])->exists()) {
                continue;
            }

            [$industrySlug, $industryName] = $row['industry'];
            $draft = $drafts[$industrySlug] ?? [];
            $category = TemplateCategory::query()->firstOrCreate(['slug' => $industrySlug], ['name' => $industryName] + ($draft === [] ? [] : array_merge($draft, ['faqs' => json_decode($draft['faqs'], true)])));
            // A path with a folder is already complete; a bare file name lives in public/images.
            $image = fn (string $file): ?int => $images->execute(str_contains($file, '/') ? $file : 'images/'.$file, 'showcase', $row['name'])?->id;

            $template = Template::query()->create(Arr::except($row, ['industry', 'image', 'gallery', 'features']) + [
                'template_category_id' => $category->id,
                'image_id' => $image($row['image']),
                'gallery' => array_values(array_filter(array_map($image, $row['gallery']))),
                'is_active' => true,
            ]);
            $template->features()->sync(ThemeFeature::query()->whereIn('slug', $row['features'])->pluck('id'));
        }
    }

    /**
     * Sample pages that run on our own landing layouts: the page itself is the demo.
     *
     * @return array<int, array<string, mixed>>
     */
    private function landingSamples(): array
    {
        $sample = function (LandingTemplate $layout, string $code, string $slug, int $order, string $name, string $description, array $pages): array {
            $defaults = $layout->sample();

            return [
                'code' => $code,
                'slug' => $slug,
                'name' => $name,
                'type' => 'landing-page',
                'industry' => ['dien-gia-dung', 'Điện gia dụng'],
                'description' => $description,
                'year' => 2026,
                'order' => $order,
                'image' => 'landing/'.$layout->value.'/preview.webp',
                'gallery' => [],
                'features' => ['lead'],
                'tags' => ['Landing page', 'Chạy quảng cáo'],
                'pages' => $pages,
                'included_features' => ['Hiển thị tốt trên điện thoại', 'Form thu thông tin khách hàng', 'Nút gọi và liên hệ', 'Gắn mã đo lường quảng cáo'],
                'demo_layout' => $layout->value,
                'demo' => ['price' => $defaults['price'], 'compare_price' => $defaults['compare_price'], 'content' => $layout->defaults()],
            ];
        };

        return [
            $sample(LandingTemplate::Purehome, 'WABN-LP01', 'landing-purehome-may-loc-khong-khi', 12, 'PureHome - Landing page bán sản phẩm gia dụng',
                'Trang đích một sản phẩm, tông xanh nhẹ: giới thiệu ưu điểm, đánh giá, ưu đãi và form để lại số điện thoại.',
                ['Đầu trang kèm giá', 'Ưu điểm sản phẩm', 'Công nghệ', 'Đánh giá khách hàng', 'Ưu đãi', 'Câu hỏi thường gặp']),
            $sample(LandingTemplate::Freshair, 'WABN-LP02', 'landing-freshair-may-loc-khong-khi', 13, 'FreshAir - Landing page bán hàng có thư viện ảnh',
                'Trang đích bán hàng với thư viện ảnh, thông số, quà tặng, voucher, giỏ hàng và form tư vấn.',
                ['Khối mua hàng', 'Thư viện ảnh', 'Thông số kỹ thuật', 'Quà tặng và voucher', 'Đánh giá khách hàng', 'Câu hỏi thường gặp']),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function templates(): array
    {
        return
        [
            [
                'code' => 'WABN-013',
                'slug' => 's-star-may-nen-khi',
                'name' => 'S-STAR - Máy nén khí công nghiệp',
                'type' => 'doanh-nghiep',
                'industry' => ['may-moc-thiet-bi', 'Máy móc - thiết bị'],
                'description' => 'Website doanh nghiệp cung cấp thiết bị công nghiệp: sản phẩm, dịch vụ, giải pháp ngành, tin kỹ thuật và form báo giá.',
                'badge' => 'Mới',
                'demo_url' => 'https://s-star.webappbacninh.vn/',
                'year' => 2026,
                'is_featured' => true,
                'order' => 0,
                'image' => 'images/showcase/s-star.webp',
                'gallery' => [],
                'features' => [],
            ],
            [
                'code' => 'WABN-001',
                'slug' => 'corporate-pro-doanh-nghiep-san-xuat',
                'name' => 'Corporate Pro - Doanh nghiệp sản xuất',
                'type' => 'doanh-nghiep',
                'industry' => [
                    'san-xuat',
                    'Sản xuất',
                ],
                'price' => 12000000,
                'year' => 2026,
                'description' => 'Giao diện chắc chắn, phù hợp công ty sản xuất, cơ khí, linh kiện và doanh nghiệp trong khu công nghiệp.',
                'badge' => 'Nổi bật',
                'duration' => '12 - 18 ngày',
                'is_featured' => true,
                'order' => 0,
                'image' => 'project-corporate.webp',
                'gallery' => [
                    'project-corporate.webp',
                    'project-ecommerce.webp',
                    'project-landing.webp',
                ],
                'features' => [
                    'multilang',
                    'lead',
                ],
                'tags' => [
                    'Hồ sơ năng lực',
                    'Sản phẩm',
                    'Đa ngôn ngữ',
                ],
                'audiences' => [
                    'Doanh nghiệp hoạt động trong lĩnh vực sản xuất',
                    'Đơn vị cần website rõ dịch vụ, dễ nhận khách và dễ quản trị',
                    'Doanh nghiệp muốn có nền tảng để tiếp tục làm SEO và nội dung',
                ],
                'pages' => [
                    'Trang chủ',
                    'Giới thiệu',
                    'Dịch vụ / Sản phẩm',
                    'Dự án / Thư viện',
                    'Tin tức / Kiến thức',
                    'Liên hệ',
                ],
                'included_features' => [
                    'Giao diện responsive cho máy tính, tablet và điện thoại',
                    'Trang quản trị nội dung dễ cập nhật',
                    'Form liên hệ và nút gọi / Zalo nổi',
                    'SEO kỹ thuật nền tảng, sitemap và meta cơ bản',
                    'Tối ưu ảnh, cấu trúc heading và tốc độ tải trang',
                    'Hướng dẫn quản trị và bảo hành sau bàn giao',
                    'Cấu trúc đa ngôn ngữ theo phạm vi thống nhất',
                ],
                'customizations' => [
                    'Thay logo và bộ màu thương hiệu',
                    'Thay toàn bộ hình ảnh, nội dung và thông tin liên hệ',
                    'Điều chỉnh bố cục trang chủ theo ngành',
                    'Thêm hoặc bớt trang nội dung',
                    'Bổ sung form, Zalo OA, tracking hoặc tích hợp theo yêu cầu',
                ],
            ],
            [
                'code' => 'WABN-002',
                'slug' => 'commerce-plus-website-ban-hang',
                'name' => 'Commerce Plus - Website bán hàng',
                'type' => 'ban-hang',
                'industry' => [
                    'thuong-mai',
                    'Thương mại',
                ],
                'price' => 18000000,
                'year' => 2026,
                'description' => 'Trình bày sản phẩm rõ, có danh mục, giỏ hàng, đặt hàng và kết nối các kênh tư vấn.',
                'badge' => 'Bán chạy',
                'duration' => '18 - 28 ngày',
                'is_featured' => true,
                'order' => 1,
                'image' => 'project-ecommerce.webp',
                'gallery' => [
                    'project-ecommerce.webp',
                    'project-corporate.webp',
                    'project-landing.webp',
                ],
                'features' => [
                    'ecommerce',
                    'lead',
                ],
                'tags' => [
                    'Giỏ hàng',
                    'Đặt hàng',
                    'Danh mục',
                ],
                'audiences' => [
                    'Doanh nghiệp hoạt động trong lĩnh vực thương mại',
                    'Đơn vị cần website rõ dịch vụ, dễ nhận khách và dễ quản trị',
                    'Doanh nghiệp muốn có nền tảng để tiếp tục làm SEO và nội dung',
                ],
                'pages' => [
                    'Trang chủ',
                    'Danh mục sản phẩm',
                    'Chi tiết sản phẩm',
                    'Giỏ hàng',
                    'Đặt hàng',
                    'Tin tức',
                    'Liên hệ',
                ],
                'included_features' => [
                    'Giao diện responsive cho máy tính, tablet và điện thoại',
                    'Trang quản trị nội dung dễ cập nhật',
                    'Form liên hệ và nút gọi / Zalo nổi',
                    'SEO kỹ thuật nền tảng, sitemap và meta cơ bản',
                    'Tối ưu ảnh, cấu trúc heading và tốc độ tải trang',
                    'Hướng dẫn quản trị và bảo hành sau bàn giao',
                    'Giỏ hàng, đặt hàng và quản lý đơn cơ bản',
                ],
                'customizations' => [
                    'Thay logo và bộ màu thương hiệu',
                    'Thay toàn bộ hình ảnh, nội dung và thông tin liên hệ',
                    'Điều chỉnh bố cục trang chủ theo ngành',
                    'Thêm hoặc bớt trang nội dung',
                    'Bổ sung form, Zalo OA, tracking hoặc tích hợp theo yêu cầu',
                ],
            ],
            [
                'code' => 'WABN-003',
                'slug' => 'tour-explorer-du-lich-va-tour',
                'name' => 'Tour Explorer - Du lịch và tour',
                'type' => 'doanh-nghiep',
                'industry' => [
                    'du-lich',
                    'Du lịch',
                ],
                'price' => 15000000,
                'year' => 2026,
                'description' => 'Phù hợp công ty lữ hành, tour nội địa, điểm đến và dịch vụ đặt lịch tư vấn.',
                'badge' => 'Mới',
                'duration' => '15 - 22 ngày',
                'is_featured' => true,
                'order' => 2,
                'image' => 'project-tour.webp',
                'gallery' => [
                    'project-tour.webp',
                    'project-corporate.webp',
                    'project-landing.webp',
                    'about-bacninh.webp',
                ],
                'features' => [
                    'booking',
                    'lead',
                    'multilang',
                ],
                'tags' => [
                    'Tour',
                    'Booking',
                    'Bản đồ',
                ],
                'audiences' => [
                    'Doanh nghiệp hoạt động trong lĩnh vực du lịch',
                    'Đơn vị cần website rõ dịch vụ, dễ nhận khách và dễ quản trị',
                    'Doanh nghiệp muốn có nền tảng để tiếp tục làm SEO và nội dung',
                ],
                'pages' => [
                    'Trang chủ',
                    'Giới thiệu',
                    'Dịch vụ / Sản phẩm',
                    'Dự án / Thư viện',
                    'Tin tức / Kiến thức',
                    'Liên hệ',
                ],
                'included_features' => [
                    'Giao diện responsive cho máy tính, tablet và điện thoại',
                    'Trang quản trị nội dung dễ cập nhật',
                    'Form liên hệ và nút gọi / Zalo nổi',
                    'SEO kỹ thuật nền tảng, sitemap và meta cơ bản',
                    'Tối ưu ảnh, cấu trúc heading và tốc độ tải trang',
                    'Hướng dẫn quản trị và bảo hành sau bàn giao',
                    'Cấu trúc đa ngôn ngữ theo phạm vi thống nhất',
                    'Form đặt lịch / booking theo nhu cầu',
                ],
                'customizations' => [
                    'Thay logo và bộ màu thương hiệu',
                    'Thay toàn bộ hình ảnh, nội dung và thông tin liên hệ',
                    'Điều chỉnh bố cục trang chủ theo ngành',
                    'Thêm hoặc bớt trang nội dung',
                    'Bổ sung form, Zalo OA, tracking hoặc tích hợp theo yêu cầu',
                ],
            ],
            [
                'code' => 'WABN-004',
                'slug' => 'edu-center-trung-tam-giao-duc',
                'name' => 'Edu Center - Trung tâm giáo dục',
                'type' => 'dich-vu',
                'industry' => [
                    'giao-duc',
                    'Giáo dục',
                ],
                'price' => 13000000,
                'year' => 2026,
                'description' => 'Giới thiệu khóa học, giáo viên, lịch khai giảng và form đăng ký tư vấn.',
                'badge' => '',
                'duration' => '12 - 18 ngày',
                'is_featured' => true,
                'order' => 3,
                'image' => 'project-school.webp',
                'gallery' => [
                    'project-school.webp',
                    'project-corporate.webp',
                    'project-landing.webp',
                    'about-bacninh.webp',
                ],
                'features' => [
                    'lead',
                ],
                'tags' => [
                    'Khóa học',
                    'Tuyển sinh',
                    'Form lead',
                ],
                'audiences' => [
                    'Doanh nghiệp hoạt động trong lĩnh vực giáo dục',
                    'Đơn vị cần website rõ dịch vụ, dễ nhận khách và dễ quản trị',
                    'Doanh nghiệp muốn có nền tảng để tiếp tục làm SEO và nội dung',
                ],
                'pages' => [
                    'Trang chủ',
                    'Giới thiệu',
                    'Dịch vụ / Sản phẩm',
                    'Dự án / Thư viện',
                    'Tin tức / Kiến thức',
                    'Liên hệ',
                ],
                'included_features' => [
                    'Giao diện responsive cho máy tính, tablet và điện thoại',
                    'Trang quản trị nội dung dễ cập nhật',
                    'Form liên hệ và nút gọi / Zalo nổi',
                    'SEO kỹ thuật nền tảng, sitemap và meta cơ bản',
                    'Tối ưu ảnh, cấu trúc heading và tốc độ tải trang',
                    'Hướng dẫn quản trị và bảo hành sau bàn giao',
                ],
                'customizations' => [
                    'Thay logo và bộ màu thương hiệu',
                    'Thay toàn bộ hình ảnh, nội dung và thông tin liên hệ',
                    'Điều chỉnh bố cục trang chủ theo ngành',
                    'Thêm hoặc bớt trang nội dung',
                    'Bổ sung form, Zalo OA, tracking hoặc tích hợp theo yêu cầu',
                ],
            ],
            [
                'code' => 'WABN-005',
                'slug' => 'lead-conversion-landing-page-dich-vu',
                'name' => 'Lead Conversion - Landing page dịch vụ',
                'type' => 'landing-page',
                'industry' => [
                    'thuong-mai',
                    'Dịch vụ',
                ],
                'price' => 6500000,
                'year' => 2026,
                'description' => 'Landing page tập trung chuyển đổi cho quảng cáo, chiến dịch và dịch vụ cần thu lead.',
                'badge' => 'Bán chạy',
                'duration' => '5 - 8 ngày',
                'is_featured' => true,
                'order' => 4,
                'image' => 'project-landing.webp',
                'gallery' => [
                    'project-landing.webp',
                    'project-ecommerce.webp',
                    'project-corporate.webp',
                    'seo-operation.webp',
                ],
                'features' => [
                    'lead',
                ],
                'tags' => [
                    'CTA mạnh',
                    'Form lead',
                    'Tracking',
                ],
                'audiences' => [
                    'Doanh nghiệp hoạt động trong lĩnh vực dịch vụ',
                    'Đơn vị cần website rõ dịch vụ, dễ nhận khách và dễ quản trị',
                    'Doanh nghiệp muốn có nền tảng để tiếp tục làm SEO và nội dung',
                ],
                'pages' => [
                    'Hero bán hàng',
                    'Vấn đề & giải pháp',
                    'Lợi ích',
                    'Bảng giá / ưu đãi',
                    'FAQ',
                    'Form nhận tư vấn',
                ],
                'included_features' => [
                    'Giao diện responsive cho máy tính, tablet và điện thoại',
                    'Trang quản trị nội dung dễ cập nhật',
                    'Form liên hệ và nút gọi / Zalo nổi',
                    'SEO kỹ thuật nền tảng, sitemap và meta cơ bản',
                    'Tối ưu ảnh, cấu trúc heading và tốc độ tải trang',
                    'Hướng dẫn quản trị và bảo hành sau bàn giao',
                ],
                'customizations' => [
                    'Thay logo và bộ màu thương hiệu',
                    'Thay toàn bộ hình ảnh, nội dung và thông tin liên hệ',
                    'Điều chỉnh bố cục trang chủ theo ngành',
                    'Thêm hoặc bớt trang nội dung',
                    'Bổ sung form, Zalo OA, tracking hoặc tích hợp theo yêu cầu',
                ],
            ],
            [
                'code' => 'WABN-006',
                'slug' => 'interior-studio-noi-that-xay-dung',
                'name' => 'Interior Studio - Nội thất và xây dựng',
                'type' => 'doanh-nghiep',
                'industry' => [
                    'noi-that',
                    'Nội thất - xây dựng',
                ],
                'price' => 14500000,
                'year' => 2025,
                'description' => 'Bố cục nhiều hình ảnh, phù hợp showroom, nội thất, kiến trúc và công ty xây dựng.',
                'badge' => '',
                'duration' => '14 - 20 ngày',
                'is_featured' => true,
                'order' => 5,
                'image' => 'about-bacninh.webp',
                'gallery' => [
                    'about-bacninh.webp',
                    'project-corporate.webp',
                    'project-ecommerce.webp',
                    'project-landing.webp',
                ],
                'features' => [
                    'lead',
                ],
                'tags' => [
                    'Dự án',
                    'Thư viện ảnh',
                    'Báo giá',
                ],
                'audiences' => [
                    'Doanh nghiệp hoạt động trong lĩnh vực nội thất - xây dựng',
                    'Đơn vị cần website rõ dịch vụ, dễ nhận khách và dễ quản trị',
                    'Doanh nghiệp muốn có nền tảng để tiếp tục làm SEO và nội dung',
                ],
                'pages' => [
                    'Trang chủ',
                    'Giới thiệu',
                    'Dịch vụ / Sản phẩm',
                    'Dự án / Thư viện',
                    'Tin tức / Kiến thức',
                    'Liên hệ',
                ],
                'included_features' => [
                    'Giao diện responsive cho máy tính, tablet và điện thoại',
                    'Trang quản trị nội dung dễ cập nhật',
                    'Form liên hệ và nút gọi / Zalo nổi',
                    'SEO kỹ thuật nền tảng, sitemap và meta cơ bản',
                    'Tối ưu ảnh, cấu trúc heading và tốc độ tải trang',
                    'Hướng dẫn quản trị và bảo hành sau bàn giao',
                ],
                'customizations' => [
                    'Thay logo và bộ màu thương hiệu',
                    'Thay toàn bộ hình ảnh, nội dung và thông tin liên hệ',
                    'Điều chỉnh bố cục trang chủ theo ngành',
                    'Thêm hoặc bớt trang nội dung',
                    'Bổ sung form, Zalo OA, tracking hoặc tích hợp theo yêu cầu',
                ],
            ],
            [
                'code' => 'WABN-007',
                'slug' => 'food-house-nha-hang-am-thuc',
                'name' => 'Food House - Nhà hàng và ẩm thực',
                'type' => 'dich-vu',
                'industry' => [
                    'nha-hang',
                    'Nhà hàng - ẩm thực',
                ],
                'price' => 9800000,
                'year' => 2025,
                'description' => 'Giới thiệu thực đơn, không gian, ưu đãi và nút gọi hoặc đặt bàn nhanh.',
                'badge' => '',
                'duration' => '10 - 15 ngày',
                'is_featured' => true,
                'order' => 6,
                'image' => 'hero-industrial.webp',
                'gallery' => [
                    'hero-industrial.webp',
                    'project-corporate.webp',
                    'project-ecommerce.webp',
                    'project-landing.webp',
                ],
                'features' => [
                    'booking',
                    'lead',
                ],
                'tags' => [
                    'Menu',
                    'Đặt bàn',
                    'Ưu đãi',
                ],
                'audiences' => [
                    'Doanh nghiệp hoạt động trong lĩnh vực nhà hàng - ẩm thực',
                    'Đơn vị cần website rõ dịch vụ, dễ nhận khách và dễ quản trị',
                    'Doanh nghiệp muốn có nền tảng để tiếp tục làm SEO và nội dung',
                ],
                'pages' => [
                    'Trang chủ',
                    'Giới thiệu',
                    'Dịch vụ / Sản phẩm',
                    'Dự án / Thư viện',
                    'Tin tức / Kiến thức',
                    'Liên hệ',
                ],
                'included_features' => [
                    'Giao diện responsive cho máy tính, tablet và điện thoại',
                    'Trang quản trị nội dung dễ cập nhật',
                    'Form liên hệ và nút gọi / Zalo nổi',
                    'SEO kỹ thuật nền tảng, sitemap và meta cơ bản',
                    'Tối ưu ảnh, cấu trúc heading và tốc độ tải trang',
                    'Hướng dẫn quản trị và bảo hành sau bàn giao',
                    'Form đặt lịch / booking theo nhu cầu',
                ],
                'customizations' => [
                    'Thay logo và bộ màu thương hiệu',
                    'Thay toàn bộ hình ảnh, nội dung và thông tin liên hệ',
                    'Điều chỉnh bố cục trang chủ theo ngành',
                    'Thêm hoặc bớt trang nội dung',
                    'Bổ sung form, Zalo OA, tracking hoặc tích hợp theo yêu cầu',
                ],
            ],
            [
                'code' => 'WABN-008',
                'slug' => 'beauty-booking-spa-lam-dep',
                'name' => 'Beauty Booking - Spa và làm đẹp',
                'type' => 'dich-vu',
                'industry' => [
                    'spa',
                    'Spa - làm đẹp',
                ],
                'price' => 11800000,
                'year' => 2026,
                'description' => 'Giao diện nhẹ nhàng, có danh mục dịch vụ, bảng giá và đặt lịch chăm sóc.',
                'badge' => 'Mới',
                'duration' => '12 - 18 ngày',
                'is_featured' => true,
                'order' => 7,
                'image' => 'agency-partnership.webp',
                'gallery' => [
                    'agency-partnership.webp',
                    'project-corporate.webp',
                    'project-ecommerce.webp',
                    'project-landing.webp',
                ],
                'features' => [
                    'booking',
                    'lead',
                ],
                'tags' => [
                    'Đặt lịch',
                    'Dịch vụ',
                    'Khuyến mại',
                ],
                'audiences' => [
                    'Doanh nghiệp hoạt động trong lĩnh vực spa - làm đẹp',
                    'Đơn vị cần website rõ dịch vụ, dễ nhận khách và dễ quản trị',
                    'Doanh nghiệp muốn có nền tảng để tiếp tục làm SEO và nội dung',
                ],
                'pages' => [
                    'Trang chủ',
                    'Giới thiệu',
                    'Dịch vụ / Sản phẩm',
                    'Dự án / Thư viện',
                    'Tin tức / Kiến thức',
                    'Liên hệ',
                ],
                'included_features' => [
                    'Giao diện responsive cho máy tính, tablet và điện thoại',
                    'Trang quản trị nội dung dễ cập nhật',
                    'Form liên hệ và nút gọi / Zalo nổi',
                    'SEO kỹ thuật nền tảng, sitemap và meta cơ bản',
                    'Tối ưu ảnh, cấu trúc heading và tốc độ tải trang',
                    'Hướng dẫn quản trị và bảo hành sau bàn giao',
                    'Form đặt lịch / booking theo nhu cầu',
                ],
                'customizations' => [
                    'Thay logo và bộ màu thương hiệu',
                    'Thay toàn bộ hình ảnh, nội dung và thông tin liên hệ',
                    'Điều chỉnh bố cục trang chủ theo ngành',
                    'Thêm hoặc bớt trang nội dung',
                    'Bổ sung form, Zalo OA, tracking hoặc tích hợp theo yêu cầu',
                ],
            ],
            [
                'code' => 'WABN-009',
                'slug' => 'tech-service-thiet-bi-ky-thuat',
                'name' => 'Tech Service - Thiết bị và kỹ thuật',
                'type' => 'doanh-nghiep',
                'industry' => [
                    'may-moc-thiet-bi',
                    'Máy móc - thiết bị',
                ],
                'price' => 12500000,
                'year' => 2025,
                'description' => 'Phù hợp doanh nghiệp cung cấp thiết bị, bảo trì, lắp đặt và dịch vụ kỹ thuật.',
                'badge' => '',
                'duration' => '12 - 18 ngày',
                'is_featured' => true,
                'order' => 8,
                'image' => 'project-software.webp',
                'gallery' => [
                    'project-software.webp',
                    'project-corporate.webp',
                    'project-ecommerce.webp',
                    'project-landing.webp',
                ],
                'features' => [
                    'lead',
                ],
                'tags' => [
                    'Thiết bị',
                    'Dịch vụ',
                    'Bảo hành',
                ],
                'audiences' => [
                    'Doanh nghiệp hoạt động trong lĩnh vực thiết bị - kỹ thuật',
                    'Đơn vị cần website rõ dịch vụ, dễ nhận khách và dễ quản trị',
                    'Doanh nghiệp muốn có nền tảng để tiếp tục làm SEO và nội dung',
                ],
                'pages' => [
                    'Trang chủ',
                    'Giới thiệu',
                    'Dịch vụ / Sản phẩm',
                    'Dự án / Thư viện',
                    'Tin tức / Kiến thức',
                    'Liên hệ',
                ],
                'included_features' => [
                    'Giao diện responsive cho máy tính, tablet và điện thoại',
                    'Trang quản trị nội dung dễ cập nhật',
                    'Form liên hệ và nút gọi / Zalo nổi',
                    'SEO kỹ thuật nền tảng, sitemap và meta cơ bản',
                    'Tối ưu ảnh, cấu trúc heading và tốc độ tải trang',
                    'Hướng dẫn quản trị và bảo hành sau bàn giao',
                ],
                'customizations' => [
                    'Thay logo và bộ màu thương hiệu',
                    'Thay toàn bộ hình ảnh, nội dung và thông tin liên hệ',
                    'Điều chỉnh bố cục trang chủ theo ngành',
                    'Thêm hoặc bớt trang nội dung',
                    'Bổ sung form, Zalo OA, tracking hoặc tích hợp theo yêu cầu',
                ],
            ],
            [
                'code' => 'WABN-010',
                'slug' => 'export-business-doanh-nghiep-da-ngon-ngu',
                'name' => 'Export Business - Doanh nghiệp đa ngôn ngữ',
                'type' => 'doanh-nghiep',
                'industry' => [
                    'san-xuat',
                    'Sản xuất',
                ],
                'price' => 22000000,
                'year' => 2026,
                'description' => 'Dành cho doanh nghiệp cần tiếng Việt, Anh, Trung và cấu trúc sản phẩm xuất khẩu.',
                'badge' => 'Cao cấp',
                'duration' => '22 - 32 ngày',
                'is_featured' => true,
                'order' => 9,
                'image' => 'seo-operation.webp',
                'gallery' => [
                    'seo-operation.webp',
                    'project-corporate.webp',
                    'project-ecommerce.webp',
                    'project-landing.webp',
                ],
                'features' => [
                    'multilang',
                    'lead',
                ],
                'tags' => [
                    '3 ngôn ngữ',
                    'Catalog',
                    'SEO',
                ],
                'audiences' => [
                    'Doanh nghiệp hoạt động trong lĩnh vực sản xuất',
                    'Đơn vị cần website rõ dịch vụ, dễ nhận khách và dễ quản trị',
                    'Doanh nghiệp muốn có nền tảng để tiếp tục làm SEO và nội dung',
                ],
                'pages' => [
                    'Trang chủ',
                    'Giới thiệu',
                    'Dịch vụ / Sản phẩm',
                    'Dự án / Thư viện',
                    'Tin tức / Kiến thức',
                    'Liên hệ',
                ],
                'included_features' => [
                    'Giao diện responsive cho máy tính, tablet và điện thoại',
                    'Trang quản trị nội dung dễ cập nhật',
                    'Form liên hệ và nút gọi / Zalo nổi',
                    'SEO kỹ thuật nền tảng, sitemap và meta cơ bản',
                    'Tối ưu ảnh, cấu trúc heading và tốc độ tải trang',
                    'Hướng dẫn quản trị và bảo hành sau bàn giao',
                    'Cấu trúc đa ngôn ngữ theo phạm vi thống nhất',
                ],
                'customizations' => [
                    'Thay logo và bộ màu thương hiệu',
                    'Thay toàn bộ hình ảnh, nội dung và thông tin liên hệ',
                    'Điều chỉnh bố cục trang chủ theo ngành',
                    'Thêm hoặc bớt trang nội dung',
                    'Bổ sung form, Zalo OA, tracking hoặc tích hợp theo yêu cầu',
                ],
            ],
            [
                'code' => 'WABN-011',
                'slug' => 'product-launch-landing-page-san-pham',
                'name' => 'Product Launch - Landing page sản phẩm',
                'type' => 'landing-page',
                'industry' => [
                    'thuong-mai',
                    'Thương mại',
                ],
                'price' => 7800000,
                'year' => 2025,
                'description' => 'Ra mắt sản phẩm mới, chạy quảng cáo, thu thông tin và đo lường chuyển đổi.',
                'badge' => '',
                'duration' => '6 - 10 ngày',
                'is_featured' => true,
                'order' => 10,
                'image' => 'project-ecommerce.webp',
                'gallery' => [
                    'project-landing.webp',
                    'project-ecommerce.webp',
                    'project-corporate.webp',
                    'seo-operation.webp',
                ],
                'features' => [
                    'lead',
                ],
                'tags' => [
                    'Sản phẩm mới',
                    'Quảng cáo',
                    'Tracking',
                ],
                'audiences' => [
                    'Doanh nghiệp hoạt động trong lĩnh vực thương mại',
                    'Đơn vị cần website rõ dịch vụ, dễ nhận khách và dễ quản trị',
                    'Doanh nghiệp muốn có nền tảng để tiếp tục làm SEO và nội dung',
                ],
                'pages' => [
                    'Hero bán hàng',
                    'Vấn đề & giải pháp',
                    'Lợi ích',
                    'Bảng giá / ưu đãi',
                    'FAQ',
                    'Form nhận tư vấn',
                ],
                'included_features' => [
                    'Giao diện responsive cho máy tính, tablet và điện thoại',
                    'Trang quản trị nội dung dễ cập nhật',
                    'Form liên hệ và nút gọi / Zalo nổi',
                    'SEO kỹ thuật nền tảng, sitemap và meta cơ bản',
                    'Tối ưu ảnh, cấu trúc heading và tốc độ tải trang',
                    'Hướng dẫn quản trị và bảo hành sau bàn giao',
                ],
                'customizations' => [
                    'Thay logo và bộ màu thương hiệu',
                    'Thay toàn bộ hình ảnh, nội dung và thông tin liên hệ',
                    'Điều chỉnh bố cục trang chủ theo ngành',
                    'Thêm hoặc bớt trang nội dung',
                    'Bổ sung form, Zalo OA, tracking hoặc tích hợp theo yêu cầu',
                ],
            ],
            [
                'code' => 'WABN-012',
                'slug' => 'company-basic-website-doanh-nghiep',
                'name' => 'Company Basic - Website doanh nghiệp gọn nhẹ',
                'type' => 'doanh-nghiep',
                'industry' => [
                    'thuong-mai',
                    'Doanh nghiệp dịch vụ',
                ],
                'price' => 8500000,
                'year' => 2025,
                'description' => 'Gói giao diện gọn, phù hợp doanh nghiệp mới cần hiện diện chuyên nghiệp và dễ quản trị.',
                'badge' => '',
                'duration' => '8 - 12 ngày',
                'is_featured' => true,
                'order' => 11,
                'image' => 'project-corporate.webp',
                'gallery' => [
                    'project-corporate.webp',
                    'project-ecommerce.webp',
                    'project-landing.webp',
                ],
                'features' => [
                    'lead',
                ],
                'tags' => [
                    'Giới thiệu',
                    'Dịch vụ',
                    'Liên hệ',
                ],
                'audiences' => [
                    'Doanh nghiệp hoạt động trong lĩnh vực doanh nghiệp dịch vụ',
                    'Đơn vị cần website rõ dịch vụ, dễ nhận khách và dễ quản trị',
                    'Doanh nghiệp muốn có nền tảng để tiếp tục làm SEO và nội dung',
                ],
                'pages' => [
                    'Trang chủ',
                    'Giới thiệu',
                    'Dịch vụ / Sản phẩm',
                    'Dự án / Thư viện',
                    'Tin tức / Kiến thức',
                    'Liên hệ',
                ],
                'included_features' => [
                    'Giao diện responsive cho máy tính, tablet và điện thoại',
                    'Trang quản trị nội dung dễ cập nhật',
                    'Form liên hệ và nút gọi / Zalo nổi',
                    'SEO kỹ thuật nền tảng, sitemap và meta cơ bản',
                    'Tối ưu ảnh, cấu trúc heading và tốc độ tải trang',
                    'Hướng dẫn quản trị và bảo hành sau bàn giao',
                ],
                'customizations' => [
                    'Thay logo và bộ màu thương hiệu',
                    'Thay toàn bộ hình ảnh, nội dung và thông tin liên hệ',
                    'Điều chỉnh bố cục trang chủ theo ngành',
                    'Thêm hoặc bớt trang nội dung',
                    'Bổ sung form, Zalo OA, tracking hoặc tích hợp theo yêu cầu',
                ],
            ],
        ];
    }
}
