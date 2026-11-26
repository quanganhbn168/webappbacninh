<?php

namespace App\Domain\Seo\Checks;

use App\Domain\Seo\GoogleDocs;
use App\Domain\Seo\SeoCheck;
use App\Domain\Seo\SeoChecks;
use App\Domain\Seo\SeoContext;

/** Read from the finished page: what a search engine actually receives. */
class TechnicalChecks implements SeoChecks
{
    /** The Article properties Google recommends (none is required). */
    private const ARTICLE_PROPERTIES = ['headline', 'image', 'datePublished', 'dateModified', 'author'];

    /** Googlebot reads this much of an HTML file. */
    private const GOOGLEBOT_LIMIT_KB = 2048;

    public function title(): string
    {
        return 'Kỹ thuật: lập chỉ mục, tác giả, trải nghiệm trang';
    }

    public function run(SeoContext $context): array
    {
        $page = $context->page;
        $post = $context->post;
        $canonical = $page->attribute('//link[@rel="canonical"]', 'href');
        $indexable = ! str_contains($page->meta('robots'), 'noindex');
        $self = $canonical === canonical_url($post->url) && str_starts_with($canonical, 'http');
        $schema = collect($page->schema());
        $article = $schema->firstWhere('@type', 'Article') ?? [];
        $absent = collect(self::ARTICLE_PROPERTIES)->reject(fn (string $property): bool => filled($article[$property] ?? null));
        $crumbs = $schema->firstWhere('@type', 'BreadcrumbList')['itemListElement'] ?? [];
        $picture = $post->ogMedia ?? $post->featuredMedia;
        $social = collect(['og:title', 'og:description', 'og:url', 'og:image'])->filter(fn (string $name): bool => $page->meta($name) === '');
        $secure = str_starts_with($canonical, 'https://');
        $insecure = $secure ? count($page->elements('//*[self::img or self::script or self::iframe or self::source][starts-with(@src, "http://")]')) : 0;
        $size = (int) ceil(strlen($page->html) / 1024);
        $byline = $page->text('//p[contains(@class, "article-meta")]');
        $shownDates = array_map(fn ($node): string => $node->getAttribute('datetime'), $page->elements('//p[contains(@class, "article-meta")]//time'));
        $sameDates = $shownDates !== [] && $shownDates[0] === substr((string) ($article['datePublished'] ?? ''), 0, 10)
            && end($shownDates) === substr((string) ($article['dateModified'] ?? ''), 0, 10);

        return [
            SeoCheck::rule('byline', str_contains($byline, $post->author_name), 'Trang ghi rõ tác giả.', 'Trang chưa ghi tác giả.', GoogleDocs::HELPFUL, 2),
            SeoCheck::rule('visible_date', $sameDates, 'Ngày hiển thị trên trang khớp với dữ liệu cấu trúc.', 'Trang chưa hiện ngày đăng, hoặc ngày hiển thị không khớp dữ liệu cấu trúc.', GoogleDocs::DATES, 2),
            SeoCheck::rule('https', $secure, 'Trang chạy https.', 'Trang chưa chạy https.', GoogleDocs::PAGE_EXPERIENCE, 2),
            SeoCheck::rule('mixed_content', $insecure === 0, 'Không tải tài nguyên qua http.', $insecure.' tài nguyên tải qua http.', GoogleDocs::PAGE_EXPERIENCE, 2),
            SeoCheck::rule('mobile', $page->meta('viewport') !== '' && $page->attribute('//html', 'lang') !== '', 'Khai báo hiển thị di động và ngôn ngữ.', 'Thiếu thẻ viewport hoặc ngôn ngữ trang.', GoogleDocs::PAGE_EXPERIENCE),
            SeoCheck::rule('html_size', $size <= self::GOOGLEBOT_LIMIT_KB, 'Mã HTML '.$size.' KB, Googlebot đọc được hết.', 'Mã HTML '.$size.' KB: Googlebot chỉ đọc 2 MB đầu.', GoogleDocs::GOOGLEBOT, 2, SeoCheck::FAIL, true),
            SeoCheck::info('published', $post->isLive() ? 'Bài đang công khai.' : 'Bài chưa công khai (nháp hoặc hẹn giờ): Google chưa thấy.'),
            SeoCheck::rule('indexable', $indexable, 'Cho phép Google lập chỉ mục.', 'Trang đang chặn Google (noindex): sẽ không hiện trên tìm kiếm.', GoogleDocs::NOINDEX, 5, SeoCheck::FAIL, true),
            SeoCheck::rule('canonical', $self, 'Canonical là địa chỉ đầy đủ, trỏ về chính bài.', 'Canonical trỏ sang địa chỉ khác: Google được bảo hiển thị trang kia thay bài này.', GoogleDocs::CANONICAL, 4, SeoCheck::FAIL, true),
            SeoCheck::rule('sitemap', $indexable && $self, 'Bài có trong sitemap khi được công khai.', 'Bài bị loại khỏi sitemap.', GoogleDocs::SITEMAP, 2),
            SeoCheck::rule('article_schema', $absent->isEmpty(), 'Dữ liệu cấu trúc Article đủ các thuộc tính Google khuyến nghị.', 'Dữ liệu cấu trúc Article còn thiếu: '.$absent->implode(', ').'.', GoogleDocs::ARTICLE, 2),
            SeoCheck::rule('article_image', ! $picture || $picture->width * $picture->height >= 50000, 'Ảnh trong dữ liệu cấu trúc đủ lớn.', 'Ảnh của bài nhỏ hơn 50.000 điểm ảnh.', GoogleDocs::ARTICLE),
            SeoCheck::rule('breadcrumb_schema', count($crumbs) >= 2, 'Có đường dẫn phân cấp (BreadcrumbList).', 'Thiếu BreadcrumbList với ít nhất 2 cấp.', GoogleDocs::BREADCRUMB),
            SeoCheck::tip('category', (bool) $post->category?->is_active, 'Bài thuộc một danh mục đang hiện.', 'Chọn danh mục cho bài.'),
            SeoCheck::tip('social_tags', $social->isEmpty(), 'Đủ thẻ chia sẻ mạng xã hội.', 'Thiếu thẻ chia sẻ: '.$social->implode(', ').'.'),
        ];
    }
}
