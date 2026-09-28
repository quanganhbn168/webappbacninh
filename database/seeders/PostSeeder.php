<?php

namespace Database\Seeders;

use App\Domain\Media\Actions\ImportLocalImage;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PostSeeder extends Seeder
{
    /**
     * Creates the sample articles once; articles edited in the admin are never overwritten.
     */
    public function run(ImportLocalImage $images): void
    {
        foreach ($this->posts() as $row) {
            if (Post::query()->where('slug', $row['slug'])->exists()) {
                continue;
            }

            [$categorySlug, $categoryName] = $row['category'];
            $category = PostCategory::query()->firstOrCreate(['slug' => $categorySlug], ['name' => $categoryName, 'is_active' => true]);

            Post::query()->create(Arr::except($row, ['category', 'image', 'intro']) + [
                'category_id' => $category->id,
                'featured_media_id' => $images->execute('frontend/'.$row['image'], 'blog/imports', $row['title'])?->id,
                'curator_managed' => true,
                'data' => ['intro' => $row['intro']],
                'is_published' => true,
            ]);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function posts(): array
    {
        return
        [
            [
                'slug' => 'website-doanh-nghiep-can-nhung-trang-nao',
                'title' => 'Website doanh nghiệp cần những trang nào để không bị thừa hoặc thiếu?',
                'summary' => 'Cấu trúc thực dụng cho doanh nghiệp sản xuất, thương mại và dịch vụ khi bắt đầu xây dựng website.',
                'category' => [
                    'thiet-ke-website',
                    'Thiết kế website',
                ],
                'published_at' => '2026-07-10',
                'is_featured' => true,
                'image' => 'assets/images/project-corporate.webp',
                'intro' => 'Website doanh nghiệp không cần thật nhiều trang. Điều quan trọng là mỗi trang có một nhiệm vụ rõ ràng trong hành trình tìm hiểu và liên hệ của khách hàng.',
                'content' => '<h2>Nhóm trang nền tảng nên có</h2><p>Một website giới thiệu cơ bản nên bắt đầu từ Trang chủ, Giới thiệu, Dịch vụ hoặc Sản phẩm, Dự án, Tin tức và Liên hệ. Đây là bộ khung đủ để khách hàng hiểu doanh nghiệp là ai, đang cung cấp gì và liên hệ bằng cách nào.</p><p>Với doanh nghiệp sản xuất, nên bổ sung Năng lực nhà máy, Chứng nhận, Quy trình và Catalog sản phẩm. Với doanh nghiệp dịch vụ, nên ưu tiên trang dịch vụ chi tiết, quy trình triển khai và câu hỏi thường gặp.</p><ul><li>Trang chủ định hướng nhanh</li><li>Giới thiệu và năng lực</li><li>Dịch vụ hoặc sản phẩm</li><li>Dự án hoặc khách hàng tiêu biểu</li><li>Kiến thức và tin tức</li><li>Liên hệ và biểu mẫu</li></ul><h2>Không nên gom mọi dịch vụ vào một trang</h2><p>Mỗi dịch vụ quan trọng nên có một trang riêng để trình bày đối tượng phù hợp, vấn đề giải quyết, phạm vi công việc, quy trình, bảng giá tham khảo và lời kêu gọi hành động.</p><p>Cách này vừa giúp khách đọc dễ hơn, vừa tạo nền tảng SEO tốt hơn so với một trang dài chứa tất cả dịch vụ.</p><h2>Cấu trúc phải đi theo mục tiêu kinh doanh</h2><p>Doanh nghiệp cần xác định website dùng để xây dựng uy tín, nhận yêu cầu báo giá, tuyển đại lý, bán sản phẩm hay hỗ trợ đội kinh doanh. Mục tiêu khác nhau sẽ dẫn đến cấu trúc trang và CTA khác nhau.</p><p>Trước khi thiết kế, nên chốt sơ đồ trang và nội dung cần chuẩn bị. Đây là bước giúp giảm sửa đi sửa lại trong quá trình triển khai.</p>',
            ],
            [
                'slug' => 'seo-nen-tang-gom-nhung-gi',
                'title' => 'SEO nền tảng gồm những gì và tại sao nên làm ngay từ khi xây website?',
                'summary' => 'Technical SEO, cấu trúc nội dung, tốc độ, schema, sitemap và các phần cần chuẩn bị trước khi đăng bài dài hạn.',
                'category' => [
                    'seo',
                    'SEO website',
                ],
                'published_at' => '2026-07-08',
                'is_featured' => true,
                'image' => 'assets/images/seo-operation.webp',
                'intro' => 'SEO nền tảng không phải là cam kết lên top ngay. Đây là việc xây một website đủ sạch, rõ và dễ thu thập dữ liệu để nội dung sau này có cơ hội phát huy hiệu quả.',
                'content' => '<h2>Technical SEO cơ bản</h2><p>Website cần có URL dễ hiểu, title và description riêng, heading hợp lý, canonical, sitemap, robots, schema và tốc độ tải ổn định trên mobile.</p><ul><li>Cấu trúc URL và canonical</li><li>Title, description và heading</li><li>Sitemap, robots và Search Console</li><li>Schema cho doanh nghiệp, dịch vụ và bài viết</li><li>Tối ưu ảnh và tốc độ tải</li></ul><h2>Nội dung dịch vụ quan trọng hơn số lượng bài viết</h2><p>Trước khi chạy số lượng bài lớn, doanh nghiệp nên hoàn thiện các trang dịch vụ chính. Đây là nhóm trang gần nhu cầu mua hàng nhất và thường là nơi nhận chuyển đổi.</p><h2>Theo dõi dữ liệu sau khi xuất bản</h2><p>SEO cần theo dõi truy vấn, lượt hiển thị, trang được truy cập, tỷ lệ nhấp và chuyển đổi. Dữ liệu giúp quyết định bài nào cần nâng cấp và dịch vụ nào nên đầu tư tiếp.</p>',
            ],
            [
                'slug' => 'bao-tri-website-dinh-ky-gom-nhung-gi',
                'title' => 'Bảo trì website định kỳ gồm những gì?',
                'summary' => 'Danh sách công việc kỹ thuật, nội dung và dữ liệu doanh nghiệp nên duy trì sau khi website được bàn giao.',
                'category' => [
                    'van-hanh',
                    'Vận hành website',
                ],
                'published_at' => '2026-07-05',
                'is_featured' => true,
                'image' => 'assets/images/project-software.webp',
                'intro' => 'Website đã hoạt động vẫn cần được kiểm tra, sao lưu và cập nhật. Bảo trì đúng cách giúp giảm rủi ro gián đoạn và tránh để website trở nên lỗi thời.',
                'content' => '<h2>Nhóm công việc kỹ thuật</h2><p>Các công việc phổ biến gồm backup dữ liệu, kiểm tra SSL, theo dõi hosting, cập nhật hệ thống, rà soát form liên hệ và xử lý liên kết lỗi.</p><ul><li>Backup định kỳ</li><li>Kiểm tra SSL và tên miền</li><li>Theo dõi dung lượng và tài nguyên</li><li>Cập nhật thư viện và vá lỗi</li><li>Kiểm tra form, email và liên kết</li></ul><h2>Nhóm công việc nội dung</h2><p>Doanh nghiệp nên cập nhật dịch vụ, sản phẩm, dự án, chính sách và thông tin liên hệ. Website có nội dung cũ dễ tạo cảm giác doanh nghiệp không còn hoạt động.</p><h2>Nên chọn gói theo nhu cầu thực tế</h2><p>Website ít thay đổi có thể dùng gói bảo trì theo quý hoặc theo năm. Website cần SEO, đăng bài và cập nhật sản phẩm thường xuyên nên dùng gói vận hành theo tháng.</p>',
            ],
            [
                'slug' => 'chon-giao-dien-mau-hay-thiet-ke-rieng',
                'title' => 'Nên chọn giao diện mẫu hay thiết kế riêng?',
                'summary' => 'So sánh chi phí, thời gian, khả năng tùy chỉnh và trường hợp phù hợp với từng cách triển khai website.',
                'category' => [
                    'thiet-ke-website',
                    'Thiết kế website',
                ],
                'published_at' => '2026-07-02',
                'is_featured' => true,
                'image' => 'assets/images/project-ecommerce.webp',
                'intro' => 'Không phải dự án nào cũng cần thiết kế hoàn toàn từ đầu. Chọn đúng cách triển khai giúp doanh nghiệp cân bằng giữa ngân sách, thời gian và mức độ khác biệt.',
                'content' => '<h2>Khi nào nên dùng mẫu</h2><p>Mẫu phù hợp khi doanh nghiệp cần ra mắt nhanh, nội dung tương đối tiêu chuẩn và ngân sách có giới hạn. Mẫu vẫn có thể thay màu sắc, hình ảnh, logo và bố cục nội dung.</p><h2>Khi nào nên thiết kế riêng</h2><p>Thiết kế riêng phù hợp khi thương hiệu có nhận diện rõ, cần trải nghiệm khác biệt hoặc có nhiều loại nội dung và hành trình khách hàng đặc thù.</p><h2>Giải pháp kết hợp</h2><p>Nhiều dự án có thể bắt đầu từ một cấu trúc tham khảo, sau đó thiết kế lại phần hero, dịch vụ, dự án và CTA để vừa tiết kiệm thời gian vừa giữ được dấu ấn riêng.</p>',
            ],
            [
                'slug' => 'hosting-ten-mien-ssl-khac-nhau-the-nao',
                'title' => 'Hosting, tên miền và SSL khác nhau như thế nào?',
                'summary' => 'Giải thích ngắn gọn ba thành phần cơ bản để một website có thể hoạt động ổn định trên Internet.',
                'category' => [
                    'van-hanh',
                    'Vận hành website',
                ],
                'published_at' => '2026-06-29',
                'is_featured' => true,
                'image' => 'assets/images/hero-industrial.webp',
                'intro' => 'Tên miền là địa chỉ, hosting là nơi lưu trữ website và SSL là lớp mã hóa kết nối. Ba thành phần này có vai trò khác nhau nhưng đều cần thiết.',
                'content' => '<h2>Tên miền</h2><p>Tên miền là địa chỉ khách hàng nhập trên trình duyệt. Doanh nghiệp nên ưu tiên tên ngắn, dễ đọc, dễ nhớ và phù hợp thương hiệu.</p><h2>Hosting</h2><p>Hosting lưu mã nguồn, hình ảnh và dữ liệu. Cấu hình cần phù hợp lượng truy cập, dung lượng ảnh, chức năng và công nghệ website.</p><h2>SSL</h2><p>SSL tạo kết nối HTTPS, giúp mã hóa dữ liệu và tăng độ tin cậy. Website có form liên hệ, đăng nhập hoặc thanh toán cần đặc biệt chú ý phần này.</p>',
            ],
            [
                'slug' => 'dong-bo-noi-dung-website-va-facebook',
                'title' => 'Đồng bộ nội dung website và Facebook để tránh làm hai lần',
                'summary' => 'Cách lên một chủ đề nội dung nhưng khai thác phù hợp cho website, fanpage và hoạt động bán hàng.',
                'category' => [
                    'noi-dung',
                    'Nội dung số',
                ],
                'published_at' => '2026-06-25',
                'is_featured' => true,
                'image' => 'assets/images/agency-partnership.webp',
                'intro' => 'Website và Facebook có vai trò khác nhau. Website lưu nội dung dài hạn và hỗ trợ tìm kiếm; Facebook phù hợp tương tác nhanh và phân phối tới cộng đồng.',
                'content' => '<h2>Dùng website làm kho nội dung gốc</h2><p>Bài viết chuyên sâu, hướng dẫn, dự án và thông tin dịch vụ nên được xuất bản đầy đủ trên website để tạo tài sản nội dung lâu dài.</p><h2>Rút gọn cho Facebook</h2><p>Từ bài gốc, có thể tách thành bài ngắn, hình ảnh, checklist, câu hỏi hoặc video ngắn rồi dẫn người đọc về trang chi tiết khi phù hợp.</p><h2>Lập lịch theo cụm chủ đề</h2><p>Mỗi tháng nên chọn một số chủ đề gắn với dịch vụ chính, sau đó phân bổ thành bài website, bài Facebook và nội dung bán hàng. Cách này giảm tình trạng đăng rời rạc.</p>',
            ],
        ];
    }
}
