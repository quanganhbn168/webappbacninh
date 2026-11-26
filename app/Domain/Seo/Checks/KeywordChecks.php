<?php

namespace App\Domain\Seo\Checks;

use App\Domain\Seo\SeoCheck;
use App\Domain\Seo\SeoChecks;
use App\Domain\Seo\SeoContext;
use App\Domain\Seo\SeoText;
use App\Models\Post;
use DOMElement;
use Illuminate\Support\Str;

/**
 * Where the chosen phrase appears, as SEO plugins suggest it. Google asks for none of these
 * placements, so they are advice only.
 */
class KeywordChecks implements SeoChecks
{
    public function title(): string
    {
        return 'Từ khóa chính';
    }

    public function run(SeoContext $context): array
    {
        $keyword = $context->keyword;

        if (SeoText::normalize($keyword) === '') {
            return [SeoCheck::tip('keyword_set', false, '', 'Nhập "Từ khóa chính" để được gợi ý vị trí đặt từ khóa.')];
        }

        $page = $context->page;
        $opening = implode(' ', array_slice(SeoText::words($context->text), 0, 120));
        $headings = collect($page->body('//*[self::h2 or self::h3]'))->map(fn (DOMElement $node): string => $node->textContent);
        $other = Post::query()->whereKeyNot($context->post->getKey() ?? 0)->get(['id', 'title', 'data'])
            ->first(fn (Post $post): bool => SeoText::normalize((string) data_get($post->data, 'focus_keyword')) === SeoText::normalize($keyword));

        return [
            SeoCheck::tip('keyword_title', SeoText::contains($context->seoTitle(), $keyword), 'Tiêu đề có từ khóa.', 'Tiêu đề chưa có từ khóa "'.$keyword.'".'),
            SeoCheck::tip('keyword_description', SeoText::contains($page->meta('description'), $keyword), 'Mô tả có từ khóa.', 'Mô tả chưa có từ khóa.'),
            SeoCheck::tip('keyword_slug', str_contains('-'.$context->post->slug.'-', '-'.Str::slug($keyword).'-'), 'Đường dẫn có từ khóa.', 'Đường dẫn chưa có "'.Str::slug($keyword).'".'),
            SeoCheck::tip('keyword_opening', SeoText::contains($opening, $keyword), 'Từ khóa xuất hiện ở phần mở đầu.', 'Nên nhắc từ khóa trong đoạn mở đầu.'),
            SeoCheck::tip('keyword_heading', $headings->contains(fn (string $text): bool => SeoText::contains($text, $keyword)), 'Có tiêu đề mục chứa từ khóa.', 'Chưa tiêu đề mục nào chứa từ khóa.'),
            SeoCheck::tip('keyword_unique', $other === null, 'Không bài nào khác dùng từ khóa này.', 'Bài "'.$other?->title.'" cũng dùng từ khóa này.'),
        ];
    }
}
