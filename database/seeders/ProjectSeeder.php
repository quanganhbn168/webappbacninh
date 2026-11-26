<?php

namespace Database\Seeders;

use App\Domain\Media\Actions\ImportLocalImage;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Technology;
use App\Models\TemplateCategory;
use App\Settings\ProjectSettings;
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
            $image = fn (string $path): ?int => $images->execute('images/'.$path, 'projects', $row['title'])?->id;

            $project = Project::query()->create(Arr::except($row, ['category', 'image', 'gallery', 'industry', 'technologies', 'challenge', 'solution']) + [
                'project_category_id' => $category->id,
                'challenge' => $this->html($row['challenge']),
                'solution' => $this->html($row['solution']),
                'image_id' => $image($row['image']),
                'gallery' => array_values(array_filter(array_map($image, $row['gallery']))),
                'is_active' => true,
            ]);
            $project->industries()->sync(array_map(fn (string $name): int => TemplateCategory::findOrCreateByName($name)->id, (array) $row['industry']));
            $project->technologies()->sync(array_map(fn (string $name): int => Technology::findOrCreateByName($name)->id, $row['technologies']));
        }

        $settings = app(ProjectSettings::class)->refresh();
        $settings->code_next = max($settings->code_next, Project::query()->count() + 1);
        $settings->save();
    }

    /** A story written as plain text becomes one paragraph. */
    private function html(string $text): string
    {
        return str_starts_with($text, '<') ? $text : '<p>'.$text.'</p>';
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
                'image' => 'project-corporate.webp',
                'gallery' => [
                    'project-corporate.webp',
                    'hero-industrial.webp',
                    'about-bacninh.webp',
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
                'image' => 'project-ecommerce.webp',
                'gallery' => [
                    'project-ecommerce.webp',
                    'project-corporate.webp',
                    'project-software.webp',
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
                'image' => 'project-tour.webp',
                'gallery' => [
                    'project-tour.webp',
                    'project-landing.webp',
                    'hero-industrial.webp',
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
                'image' => 'project-school.webp',
                'gallery' => [
                    'project-school.webp',
                    'project-corporate.webp',
                    'about-bacninh.webp',
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
                'image' => 'project-landing.webp',
                'gallery' => [
                    'project-landing.webp',
                    'project-ecommerce.webp',
                    'seo-operation.webp',
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
                'industry' => 'Dịch vụ',
                'year' => 2026,
                'excerpt' => 'Quản lý khách hàng, lịch sử chăm sóc, công việc, báo giá và tiến độ trên một hệ thống thống nhất.',
                'client' => 'Doanh nghiệp dịch vụ kỹ thuật',
                'duration' => 'Theo giai đoạn',
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
                'image' => 'project-software.webp',
                'gallery' => [
                    'project-software.webp',
                    'seo-operation.webp',
                    'agency-partnership.webp',
                ],
            ],
            [
                'code' => 'DA-007',
                'slug' => 'thiet-ke-website-du-lich-hgtrip-nen-tang-gioi-thieu-tour-va-dat-dich-vu-du-lich-truc-tuyen',
                'title' => 'Thiết kế website du lịch HGTrip – Nền tảng giới thiệu tour và đặt dịch vụ du lịch trực tuyến',
                'category' => [
                    'dich-vu',
                    'Website dịch vụ',
                ],
                'industry' => [
                    'Dịch vụ',
                    'Du lịch',
                ],
                'year' => 2026,
                'excerpt' => 'HGTrip là dự án thiết kế website dành cho lĩnh vực du lịch và lữ hành, hướng đến xây dựng một nền tảng trực tuyến giúp doanh nghiệp giới thiệu tour, quảng bá điểm đến và hỗ trợ khách hàng tìm kiếm hành trình phù hợp.
Website được phát triển với giao diện hiện đại, hình ảnh du lịch trực quan, bố cục nội dung rõ ràng và trải nghiệm sử dụng tối ưu trên nhiều thiết bị.',
                'client' => 'HG Trip',
                'duration' => '2 tuần',
                'cost' => 'Từ 5 triệu',
                'link' => 'https://hgtrip.vn',
                'meta_title' => 'Thiết kế website du lịch HGTrip – Giao diện Travel hiện đại',
                'meta_description' => 'WebApp Bắc Ninh triển khai thiết kế website du lịch HGTrip với giao diện hiện đại, danh mục tour, điểm đến, ưu đãi và nội dung du lịch trực quan, tối ưu trải nghiệm khách hàng.',
                'challenge' => '<p>Trong lĩnh vực du lịch, khách hàng thường cần nhiều thông tin trước khi quyết định lựa chọn một hành trình: điểm đến, lịch trình, thời gian, chi phí, dịch vụ đi kèm và các chương trình ưu đãi.<br>Một website giới thiệu đơn thuần chưa đủ để đáp ứng nhu cầu đó. HGTrip cần một nền tảng có khả năng:<br>Giới thiệu đa dạng tour du lịch và hành trình nổi bật.<br>Phân loại tour theo nhu cầu và hình thức du lịch.<br>Quảng bá các điểm đến trong nước và quốc tế.<br>Làm nổi bật chương trình khuyến mãi và ưu đãi.<br>Tăng độ tin cậy thông qua hình ảnh, đánh giá và thông tin doanh nghiệp.<br>Hỗ trợ khách hàng tìm kiếm, khám phá và gửi yêu cầu tư vấn.</p>',
                'solution' => '<p>WebApp Bắc Ninh xây dựng giao diện website theo hướng Travel Platform, tập trung vào trải nghiệm khám phá và lựa chọn hành trình.</p><p>Các hạng mục chính:</p><p>1. Trang chủ và nhận diện thương hiệu</p><p>Banner hình ảnh du lịch nổi bật.</p><p>Thông điệp thương hiệu và lời kêu gọi hành động.</p><p>Thanh tìm kiếm hành trình trực quan.</p><p>2. Danh mục tour du lịch</p><p>Phân loại tour theo nhu cầu.</p><p>Danh sách hành trình tiêu biểu.</p><p>Hiển thị hình ảnh, giá tour và thông tin cơ bản.</p><p>Khu vực chương trình ưu đãi.</p><p>3. Hệ thống điểm đến</p><p>Giới thiệu điểm đến theo khu vực và châu lục.</p><p>Thiết kế dạng lưới hình ảnh trực quan.</p><p>Tạo cảm hứng khám phá các hành trình mới.</p><p>4. Nội dung và dịch vụ</p><p>Giới thiệu dịch vụ hỗ trợ chuyến đi.</p><p>Thiết kế tour theo yêu cầu.</p><p>Khu vực đánh giá khách hàng.</p><p>Thư viện hình ảnh và khoảnh khắc du lịch.</p><p>5. Nội dung truyền thông</p><p>Tin tức và cẩm nang du lịch.</p><p>Nội dung giới thiệu điểm đến.</p><p>Tối ưu cấu trúc nội dung phục vụ SEO.</p>',
                'results' => [],
                'deliverables' => [
                    'Toàn bộ source code',
                    'Thông tin quản trị website',
                    'thông tin quản trị tên miền',
                    'thông tin quản trị hosting',
                ],
                'technologies' => [
                    'Laravel',
                    'CSS',
                    'JavaScript',
                ],
                'is_featured' => true,
                'order' => 0,
                'image' => 'projects/hgtrip-1.webp',
                'gallery' => [
                    'projects/hgtrip-1.webp',
                    'projects/hgtrip-2.webp',
                    'projects/hgtrip-3.webp',
                    'projects/hgtrip-4.webp',
                    'projects/hgtrip-5.webp',
                    'projects/hgtrip-6.webp',
                    'projects/hgtrip-7.webp',
                    'projects/hgtrip-8.webp',
                ],
            ],
        ];
    }
}
