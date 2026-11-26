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

/** Google's "title link" best practices. It sets no length limit, so length is only reported. */
class TitleChecks implements SeoChecks
{
    /** Titles that say nothing about the page ("Home" is Google's own example). */
    private const VAGUE = ['trang chủ', 'bài viết', 'bài viết mới', 'tin tức', 'kiến thức', 'không có tiêu đề', 'chưa đặt tên', 'sản phẩm', 'dịch vụ', 'home', 'untitled', 'new post'];

    private const VIETNAMESE = '/[àáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵđ]/u';

    public function title(): string
    {
        return 'Tiêu đề SEO';
    }

    public function run(SeoContext $context): array
    {
        $title = $context->seoTitle();
        $normalized = SeoText::normalize($title);
        $shown = $context->page->title();
        $others = Post::query()->whereKeyNot($context->post->getKey() ?? 0)->get(['title', 'meta_title'])
            ->concat(Page::query()->get(['title', 'meta_title']));
        $duplicate = $normalized !== '' && $others->contains(fn ($row): bool => SeoText::normalize((string) ($row->meta_title ?: $row->title)) === $normalized);
        $headings = count($context->page->elements('//h1'));
        $foreign = preg_match(self::VIETNAMESE, mb_strtolower($context->text)) === 1 && preg_match(self::VIETNAMESE, mb_strtolower($title)) !== 1;

        return [
            SeoCheck::rule('title_present', $normalized !== '', 'Trang có tiêu đề.', 'Trang chưa có tiêu đề.', GoogleDocs::TITLE, 3, SeoCheck::FAIL),
            SeoCheck::rule('title_descriptive', SeoText::countWords($title) >= 3 && ! in_array($normalized, self::VAGUE, true), 'Tiêu đề mô tả được nội dung trang.', 'Tiêu đề quá chung chung: viết rõ trang này nói về điều gì.', GoogleDocs::TITLE, 3),
            SeoCheck::rule('title_stuffing', ! $this->stuffed($title, $context->keyword), 'Tiêu đề không lặp từ khóa.', 'Tiêu đề lặp đi lặp lại cùng một cụm từ (nhồi từ khóa).', GoogleDocs::TITLE, 3, SeoCheck::FAIL),
            SeoCheck::rule('title_clickbait', ! Sensational::in($title.' ') && ! Sensational::in((string) $context->post->title), 'Tiêu đề không giật gân.', 'Tiêu đề giật gân hoặc viết hoa, dấu câu quá đà: viết đúng nội dung bài.', GoogleDocs::DISCOVER, 3, SeoCheck::FAIL),
            SeoCheck::rule('title_unique', ! $duplicate, 'Tiêu đề không trùng trang nào.', 'Tiêu đề trùng với một trang khác: mỗi trang cần tiêu đề riêng.', GoogleDocs::TITLE, 3, SeoCheck::FAIL),
            SeoCheck::rule('title_brand', $this->brandedOnce($shown), 'Tên website đặt gọn ở đầu hoặc cuối tiêu đề.', 'Tên website nên xuất hiện một lần, ở đầu hoặc cuối, ngăn bằng dấu | - hoặc :.', GoogleDocs::TITLE),
            SeoCheck::rule('title_main', $headings === 1, 'Trang có một tiêu đề chính (H1) rõ ràng.', 'Trang có '.$headings.' thẻ H1, Google khó biết đâu là tiêu đề chính.', GoogleDocs::TITLE, 2),
            SeoCheck::rule('title_language', ! $foreign, 'Tiêu đề cùng ngôn ngữ, kiểu chữ với bài.', 'Bài viết tiếng Việt có dấu nhưng tiêu đề không dấu: Google có thể tự thay tiêu đề khác.', GoogleDocs::TITLE),
            SeoCheck::info('title_length', 'Tiêu đề hiển thị dài '.mb_strlen($shown).' ký tự. Google không giới hạn độ dài, chỉ cắt bớt theo bề rộng màn hình; tránh dài dòng.', GoogleDocs::TITLE),
        ];
    }

    /** The keyword twice, or any pair of words three times. */
    private function stuffed(string $title, string $keyword): bool
    {
        if ($keyword !== '' && SeoText::occurrences($title, $keyword) > 1) {
            return true;
        }

        $words = SeoText::words($title);
        $pairs = array_map(fn (int $i): string => $words[$i].' '.$words[$i + 1], array_keys(array_slice($words, 0, -1)));

        return $pairs !== [] && max(array_count_values($pairs)) >= 3;
    }

    private function brandedOnce(string $shown): bool
    {
        $name = trim((string) site_config('name'));
        $count = SeoText::occurrences($shown, $name);

        if ($count === 0) {
            return true;
        }

        $quoted = preg_quote($name, '/');

        return $count === 1 && preg_match('/^'.$quoted.'\s*[|\-–—:]|[|\-–—:]\s*'.$quoted.'$|^'.$quoted.'$/iu', trim($shown)) === 1;
    }
}
