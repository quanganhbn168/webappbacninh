<?php

namespace Database\Seeders;

use App\Domain\Media\Actions\ImportLocalImage;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProjectSeeder extends Seeder
{
    /**
     * Creates the sample projects once; projects edited in the admin are never overwritten.
     */
    public function run(ImportLocalImage $images): void
    {
        foreach ($this->projects() as $row) {
            if (Project::query()->where('slug', $row['slug'])->exists()) {
                continue;
            }

            [$categorySlug, $categoryName] = $row['category'];
            $category = ProjectCategory::query()->firstOrCreate(['slug' => $categorySlug], ['name' => $categoryName]);
            $image = fn (string $path): ?int => $images->execute('frontend/'.$path, 'projects', $row['title'])?->id;

            Project::query()->create(Arr::except($row, ['category', 'image', 'gallery']) + [
                'project_category_id' => $category->id,
                'image_id' => $image($row['image']),
                'gallery' => array_values(array_filter(array_map($image, $row['gallery']))),
                'is_active' => true,
            ]);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function projects(): array
    {
        return
        [
            [
                'code' => 'DA-001',
                'slug' => 'website-doanh-nghiep-san-xuat',
                'title' => 'Website giới thiệu năng lực cho doanh nghiệp sản xuất',
                'category' => [
                    'doanh-nghiep',
                    'Website doanh nghiệp',
                ],
                'industry' => 'Sản xuất',
                'year' => 2026,
                'excerpt' => 'Tổ chức lại hồ sơ năng lực, nhóm sản phẩm, nhà máy, chứng nhận và dự án để hỗ trợ đội kinh doanh gửi khách hàng.',
                'client' => 'Doanh nghiệp sản xuất tại Bắc Ninh',
                'duration' => '4 tuần',
                'website_type' => 'Website giới thiệu doanh nghiệp',
                'challenge' => 'Thông tin doanh nghiệp nằm rải rác trong nhiều tài liệu, website cũ thiếu cấu trúc sản phẩm và không thể hiện rõ năng lực nhà máy.',
                'solution' => 'Xây dựng lại hệ thống nội dung theo hành trình khách hàng: năng lực, sản phẩm, quy trình, chứng nhận, dự án và form tiếp nhận yêu cầu báo giá.',
                'results' => [
                    'Hồ sơ năng lực trực tuyến rõ ràng hơn cho đội kinh doanh',
                    'Khách hàng dễ tìm nhóm sản phẩm và thông tin liên hệ',
                    'Cấu trúc sẵn sàng để phát triển SEO theo nhóm sản phẩm',
                ],
                'deliverables' => [
                    'Trang chủ',
                    'Giới thiệu',
                    'Năng lực nhà máy',
                    'Sản phẩm',
                    'Dự án',
                    'Tin tức',
                    'Liên hệ',
                    'Quản trị nội dung',
                ],
                'technologies' => [
                    'PHP',
                    'Tailwind CSS',
                    'Responsive',
                    'SEO nền tảng',
                ],
                'is_featured' => true,
                'order' => 0,
                'image' => 'assets/images/project-corporate.webp',
                'gallery' => [
                    'assets/images/project-corporate.webp',
                    'assets/images/hero-industrial.webp',
                    'assets/images/about-bacninh.webp',
                ],
            ],
            [
                'code' => 'DA-002',
                'slug' => 'website-ban-hang-thuong-mai',
                'title' => 'Website bán hàng với danh mục sản phẩm và đơn hàng tập trung',
                'category' => [
                    'ban-hang',
                    'Website bán hàng',
                ],
                'industry' => 'Thương mại',
                'year' => 2026,
                'excerpt' => 'Chuẩn hóa danh mục, trang chi tiết sản phẩm, giỏ hàng và luồng tiếp nhận đơn để doanh nghiệp quản lý tập trung.',
                'client' => 'Doanh nghiệp thương mại và phân phối',
                'duration' => '5 tuần',
                'website_type' => 'Website bán hàng',
                'challenge' => 'Sản phẩm được giới thiệu qua nhiều kênh khác nhau, khách khó so sánh và nhân viên phải tiếp nhận đơn thủ công.',
                'solution' => 'Thiết kế website theo danh mục sản phẩm, bộ lọc, trang chi tiết, giỏ hàng, đặt hàng và khu vực quản trị đơn hàng cơ bản.',
                'results' => [
                    'Tập trung sản phẩm và thông tin bán hàng trên một hệ thống',
                    'Rút ngắn bước khách hàng gửi yêu cầu mua hàng',
                    'Dễ mở rộng thanh toán và vận chuyển khi cần',
                ],
                'deliverables' => [
                    'Danh mục sản phẩm',
                    'Chi tiết sản phẩm',
                    'Giỏ hàng',
                    'Đặt hàng',
                    'Tin tức',
                    'Liên hệ',
                    'Quản lý đơn',
                ],
                'technologies' => [
                    'PHP',
                    'Tailwind CSS',
                    'Giỏ hàng',
                    'Quản trị đơn hàng',
                ],
                'is_featured' => true,
                'order' => 1,
                'image' => 'assets/images/project-ecommerce.webp',
                'gallery' => [
                    'assets/images/project-ecommerce.webp',
                    'assets/images/project-corporate.webp',
                    'assets/images/project-software.webp',
                ],
            ],
            [
                'code' => 'DA-003',
                'slug' => 'website-du-lich-va-tour',
                'title' => 'Website du lịch trình bày tour rõ và nhận yêu cầu nhanh',
                'category' => [
                    'doanh-nghiep',
                    'Website doanh nghiệp',
                ],
                'industry' => 'Du lịch',
                'year' => 2026,
                'excerpt' => 'Danh mục tour, lịch trình, điểm đến, chính sách và form tư vấn được sắp xếp để khách hàng dễ tìm và liên hệ.',
                'client' => 'Đơn vị du lịch và lữ hành',
                'duration' => '4 tuần',
                'website_type' => 'Website dịch vụ du lịch',
                'challenge' => 'Thông tin tour dài, nhiều lịch trình và mức giá khiến khách khó theo dõi trên mạng xã hội.',
                'solution' => 'Xây dựng kho tour có phân loại, trang lịch trình chi tiết, điểm đến, bài viết kinh nghiệm và form tư vấn theo từng tour.',
                'results' => [
                    'Khách dễ xem và so sánh các chương trình tour',
                    'Form tư vấn gắn trực tiếp với từng sản phẩm',
                    'Có nền tảng phát triển nội dung điểm đến và SEO',
                ],
                'deliverables' => [
                    'Danh mục tour',
                    'Chi tiết tour',
                    'Điểm đến',
                    'Tin du lịch',
                    'Form tư vấn',
                    'Bản đồ',
                    'Quản trị nội dung',
                ],
                'technologies' => [
                    'PHP',
                    'Tailwind CSS',
                    'Form lead',
                    'SEO nội dung',
                ],
                'is_featured' => true,
                'order' => 2,
                'image' => 'assets/images/project-tour.webp',
                'gallery' => [
                    'assets/images/project-tour.webp',
                    'assets/images/project-landing.webp',
                    'assets/images/hero-industrial.webp',
                ],
            ],
            [
                'code' => 'DA-004',
                'slug' => 'website-trung-tam-giao-duc',
                'title' => 'Website tuyển sinh và cập nhật hoạt động giáo dục',
                'category' => [
                    'dich-vu',
                    'Website dịch vụ',
                ],
                'industry' => 'Giáo dục',
                'year' => 2026,
                'excerpt' => 'Giới thiệu khóa học, giáo viên, lịch khai giảng, tin hoạt động và tiếp nhận đăng ký tư vấn.',
                'client' => 'Trung tâm đào tạo và giáo dục',
                'duration' => '3 tuần',
                'website_type' => 'Website giáo dục',
                'challenge' => 'Thông tin khóa học và lịch khai giảng thường xuyên thay đổi, phụ huynh khó tìm lại nội dung trên fanpage.',
                'solution' => 'Thiết kế hệ thống khóa học, giáo viên, lịch khai giảng, thư viện hoạt động và form đăng ký tư vấn tập trung.',
                'results' => [
                    'Nội dung tuyển sinh được cập nhật chủ động',
                    'Phụ huynh dễ tìm chương trình phù hợp',
                    'Tạo nguồn nội dung dài hạn cho tìm kiếm',
                ],
                'deliverables' => [
                    'Khóa học',
                    'Giáo viên',
                    'Lịch khai giảng',
                    'Hoạt động',
                    'Tin tức',
                    'Form đăng ký',
                ],
                'technologies' => [
                    'PHP',
                    'Tailwind CSS',
                    'Responsive',
                    'Form đăng ký',
                ],
                'is_featured' => true,
                'order' => 3,
                'image' => 'assets/images/project-school.webp',
                'gallery' => [
                    'assets/images/project-school.webp',
                    'assets/images/project-corporate.webp',
                    'assets/images/about-bacninh.webp',
                ],
            ],
            [
                'code' => 'DA-005',
                'slug' => 'landing-page-chien-dich',
                'title' => 'Landing page chiến dịch tập trung thu lead',
                'category' => [
                    'landing-page',
                    'Landing page',
                ],
                'industry' => 'Dịch vụ',
                'year' => 2026,
                'excerpt' => 'Tập trung một dịch vụ, thông điệp bán hàng, bằng chứng, gói giá và form đăng ký cho chiến dịch quảng cáo.',
                'client' => 'Doanh nghiệp dịch vụ',
                'duration' => '7 - 10 ngày',
                'website_type' => 'Landing page chuyển đổi',
                'challenge' => 'Quảng cáo dẫn về trang chủ chung khiến thông điệp bị phân tán và khách không biết bước tiếp theo.',
                'solution' => 'Xây dựng một trang đích riêng với thông điệp rõ, lợi ích, quy trình, bảng giá, câu hỏi thường gặp và CTA lặp lại hợp lý.',
                'results' => [
                    'Thông điệp quảng cáo và nội dung trang thống nhất',
                    'Form lead ngắn, dễ đo lường',
                    'Có thể nhân bản cho chiến dịch tiếp theo',
                ],
                'deliverables' => [
                    'Hero',
                    'Vấn đề - giải pháp',
                    'Lợi ích',
                    'Quy trình',
                    'Bảng giá',
                    'FAQ',
                    'Form lead',
                    'Tracking cơ bản',
                ],
                'technologies' => [
                    'HTML/PHP',
                    'Tailwind CSS',
                    'AOS',
                    'Tracking',
                ],
                'is_featured' => true,
                'order' => 4,
                'image' => 'assets/images/project-landing.webp',
                'gallery' => [
                    'assets/images/project-landing.webp',
                    'assets/images/project-ecommerce.webp',
                    'assets/images/seo-operation.webp',
                ],
            ],
            [
                'code' => 'DA-006',
                'slug' => 'he-thong-quan-ly-khach-hang',
                'title' => 'Hệ thống quản lý khách hàng và công việc nội bộ',
                'category' => [
                    'phan-mem',
                    'Phần mềm quản lý',
                ],
                'industry' => 'Dịch vụ kỹ thuật',
                'year' => 2026,
                'excerpt' => 'Quản lý khách hàng, lịch sử chăm sóc, công việc, báo giá và tiến độ trên một hệ thống thống nhất.',
                'client' => 'Doanh nghiệp dịch vụ kỹ thuật',
                'duration' => 'Theo giai đoạn',
                'website_type' => 'WebApp quản lý nội bộ',
                'challenge' => 'Dữ liệu khách hàng, báo giá và công việc nằm ở nhiều file, khó theo dõi trách nhiệm và lịch sử xử lý.',
                'solution' => 'Phân tích quy trình rồi triển khai theo module: khách hàng, liên hệ, ghi chú, công việc, báo giá, thanh toán và báo cáo.',
                'results' => [
                    'Dữ liệu khách hàng tập trung',
                    'Rõ người phụ trách và trạng thái công việc',
                    'Có thể mở rộng theo nghiệp vụ thực tế',
                ],
                'deliverables' => [
                    'Khách hàng',
                    'Người liên hệ',
                    'Công việc',
                    'Báo giá',
                    'Thanh toán',
                    'Báo cáo',
                    'Phân quyền',
                ],
                'technologies' => [
                    'Laravel',
                    'Tailwind/React',
                    'MySQL',
                    'Phân quyền',
                ],
                'is_featured' => true,
                'order' => 5,
                'image' => 'assets/images/project-software.webp',
                'gallery' => [
                    'assets/images/project-software.webp',
                    'assets/images/seo-operation.webp',
                    'assets/images/agency-partnership.webp',
                ],
            ],
        ];
    }
}
