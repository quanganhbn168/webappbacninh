<?php

namespace App\Domain\Seo\Checks;

use App\Domain\Seo\GoogleDocs;
use App\Domain\Seo\Sensational;
use App\Domain\Seo\SeoCheck;
use App\Domain\Seo\SeoChecks;
use App\Domain\Seo\SeoContext;
use App\Domain\Seo\SeoText;
use App\Models\Page;
use App\Models\Post;

/** Google's meta description best practices. It sets no length limit, so length is only reported. */
class DescriptionChecks implements SeoChecks
{
    public function title(): string
    {
        return 'Mô tả SEO (meta description)';
    }

    public function run(SeoContext $context): array
    {
        $post = $context->post;
        $description = $context->page->meta('description');
        $normalized = SeoText::normalize($description);
        $others = Post::query()->whereKeyNot($post->getKey() ?? 0)->get(['summary', 'meta_description'])
            ->map(fn (Post $row): string => (string) ($row->meta_description ?: $row->summary))
            ->concat(Page::query()->pluck('meta_description'));
        $duplicate = $normalized !== '' && $others->contains(fn (?string $other): bool => SeoText::normalize((string) $other) === $normalized);
        $written = filled($post->meta_description) || filled($post->summary);

        return [
            SeoCheck::rule('description_present', $normalized !== '', 'Trang có meta description.', 'Trang chưa có meta description.', GoogleDocs::SNIPPET, 2, SeoCheck::FAIL),
            SeoCheck::rule('description_unique', ! $duplicate, 'Mô tả riêng cho bài này.', 'Mô tả trùng với một trang khác: mỗi trang cần mô tả riêng.', GoogleDocs::SNIPPET, 3, SeoCheck::FAIL),
            SeoCheck::rule('description_sentences', ! $this->keywordList($description, $context->keyword), 'Mô tả viết thành câu, người đọc hiểu được.', 'Mô tả giống một chuỗi từ khóa: viết thành câu giới thiệu nội dung bài.', GoogleDocs::SNIPPET, 3, SeoCheck::FAIL),
            SeoCheck::rule('description_clickbait', ! Sensational::in($description), 'Mô tả không giật gân.', 'Mô tả giật gân, phóng đại: Google khuyên tránh trong tiêu đề và đoạn xem trước.', GoogleDocs::DISCOVER, 2),
            SeoCheck::rule('description_informative', SeoText::countWords($description) >= 8, 'Mô tả đủ thông tin về bài.', 'Mô tả quá ngắn, chưa nói được bài có gì.', GoogleDocs::SNIPPET, 2),
            SeoCheck::info('description_source', $written
                ? 'Mô tả do người viết soạn.'
                : 'Website đang tự lấy đoạn đầu bài. Google chấp nhận mô tả tạo tự động nếu đọc được; tự viết thì chủ động hơn.', GoogleDocs::SNIPPET),
            SeoCheck::info('description_length', 'Mô tả dài '.mb_strlen($description).' ký tự. Google không giới hạn độ dài, chỉ cắt theo bề rộng màn hình, và có thể tự lấy đoạn khác trong bài.', GoogleDocs::SNIPPET),
        ];
    }

    /** Many short comma-separated pieces, or the keyword over and over. */
    private function keywordList(string $description, string $keyword): bool
    {
        if ($keyword !== '' && SeoText::occurrences($description, $keyword) > 2) {
            return true;
        }

        $pieces = array_filter(array_map('trim', preg_split('/[,;|]/u', $description) ?: []));

        return count($pieces) >= 4 && SeoText::countWords($description) / count($pieces) <= 4;
    }
}
