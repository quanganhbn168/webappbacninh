<?php

namespace App\Domain\Seo\Checks;

use App\Domain\Seo\GoogleDocs;
use App\Domain\Seo\ParsedPage;
use App\Domain\Seo\SeoCheck;
use App\Domain\Seo\SeoChecks;
use App\Domain\Seo\SeoContext;
use App\Domain\Seo\SeoText;
use App\Models\Post;
use DOMElement;

class ContentChecks implements SeoChecks
{
    /** Google names no figure; above this share the same phrase reads as repeated on purpose. */
    private const STUFFING_PERCENT = 5;

    /** Share of this article's ten-word runs found in another article before it counts as a copy. */
    private const COPIED_PERCENT = 30;

    private const SHINGLE = 10;

    /** A block naming this many places reads as a list made for search engines. */
    private const PLACES_LIMIT = 8;

    private const PLACES = ['Hà Nội', 'Hồ Chí Minh', 'Hải Phòng', 'Đà Nẵng', 'Cần Thơ', 'Huế', 'Bắc Ninh', 'Bắc Giang', 'Hải Dương', 'Hưng Yên', 'Vĩnh Phúc', 'Hà Nam', 'Nam Định', 'Thái Bình', 'Ninh Bình', 'Quảng Ninh', 'Thái Nguyên', 'Phú Thọ', 'Lạng Sơn', 'Cao Bằng', 'Lào Cai', 'Yên Bái', 'Sơn La', 'Hòa Bình', 'Thanh Hóa', 'Nghệ An', 'Hà Tĩnh', 'Quảng Bình', 'Quảng Trị', 'Quảng Nam', 'Quảng Ngãi', 'Bình Định', 'Khánh Hòa', 'Lâm Đồng', 'Gia Lai', 'Đắk Lắk', 'Bình Dương', 'Đồng Nai', 'Bà Rịa', 'Vũng Tàu', 'Tây Ninh', 'Long An', 'Tiền Giang', 'An Giang', 'Kiên Giang', 'Cà Mau', 'Vĩnh Long', 'Bến Tre'];

    /** Styles that keep text from readers but not from crawlers. */
    private const HIDING_STYLE = '/display\s*:\s*none|visibility\s*:\s*hidden|font-size\s*:\s*0(?![.\d])|opacity\s*:\s*0(?![.\d])|(left|top|text-indent)\s*:\s*-\d{3,}|(?<![-\w])color\s*:\s*(white|#fff(fff)?)\b/i';

    public function title(): string
    {
        return 'Nội dung và đường dẫn';
    }

    public function run(SeoContext $context): array
    {
        $page = $context->page;
        $words = $context->words;
        $slug = (string) $context->post->slug;
        $density = $context->keyword !== '' && $words
            ? SeoText::occurrences($context->text, $context->keyword) * SeoText::countWords($context->keyword) / $words * 100
            : 0;
        $headings = collect($page->body('//*[self::h2 or self::h3 or self::h4]'));
        $titles = $headings->map(fn (DOMElement $node): string => SeoText::normalize($node->textContent));
        $long = $words >= 300;
        $longParagraphs = collect($page->body('//*[self::p or self::li]'))
            ->filter(fn (DOMElement $node): bool => SeoText::countWords(ParsedPage::textOf($node)) > 120)->count();
        $longSentences = $this->longSentences($page);
        // Read from the text as typed: what the page cleans away today could come back.
        $raw = new ParsedPage('<div>'.$context->post->content.'</div>');
        $hidden = collect($raw->elements('//*[@style]'))->filter(fn (DOMElement $node): bool => preg_match(self::HIDING_STYLE, $node->getAttribute('style')) === 1 && trim($node->textContent) !== '')->count();
        $tinyLinks = collect($raw->elements('//a[@href]'))->filter(fn (DOMElement $node): bool => mb_strlen(trim($node->textContent)) === 1 && $node->getElementsByTagName('img')->length === 0)->count();
        $places = collect($page->body('//*[self::p or self::li or self::td]'))
            ->max(fn (DOMElement $node): int => collect(self::PLACES)->filter(fn (string $place): bool => SeoText::contains($node->textContent, $place))->count()) ?? 0;
        [$copied, $original] = $this->mostCopied($context);

        return [
            SeoCheck::rule('hidden_text', $hidden === 0 && $tinyLinks === 0, 'Không có chữ hay liên kết bị giấu.', 'Có chữ bị giấu (ẩn, cỡ 0, màu trắng…) hoặc liên kết gắn vào một ký tự: Google coi là spam.', GoogleDocs::SPAM, 3, SeoCheck::FAIL),
            SeoCheck::rule('duplicate_content', $copied <= self::COPIED_PERCENT, 'Nội dung không chép từ bài khác trên website.', $copied.'% nội dung trùng với bài "'.$original.'": viết lại phần riêng của bài này.', GoogleDocs::SPAM, 3, SeoCheck::FAIL),
            SeoCheck::rule('place_list', $places < self::PLACES_LIMIT, 'Không liệt kê địa danh để bắt từ khóa.', 'Có đoạn liệt kê '.$places.' tỉnh, thành: Google nêu đây là ví dụ nhồi từ khóa.', GoogleDocs::SPAM, 2),
            SeoCheck::rule('content_present', $page->body('//*[normalize-space()]') !== [], 'Bài có nội dung.', 'Bài chưa có nội dung.', GoogleDocs::STARTER, 3, SeoCheck::FAIL),
            SeoCheck::rule('content_stuffing', $density <= self::STUFFING_PERCENT, 'Không thấy dấu hiệu nhồi từ khóa.', 'Từ khóa chiếm '.number_format($density, 1, ',', '.').'% bài, đọc không tự nhiên: Google coi nhồi từ khóa là spam.', GoogleDocs::SPAM, 3, SeoCheck::FAIL),
            SeoCheck::rule('url_words', preg_match('/\p{L}{2,}/u', $slug) === 1 && preg_match('/\d{6,}/', $slug) !== 1, 'Đường dẫn dùng từ đọc được.', 'Đường dẫn nên là từ đọc được, không phải dãy số.', GoogleDocs::URLS),
            SeoCheck::rule('url_hyphens', ! str_contains($slug, '_') && preg_match('/^[\x21-\x7E]+$/', $slug) === 1, 'Đường dẫn ngăn từ bằng gạch ngang, không dấu.', 'Ngăn từ bằng gạch ngang (-) và không dùng ký tự có dấu trong đường dẫn.', GoogleDocs::URLS),
            SeoCheck::info('content_length', 'Bài dài '.$words.' chữ. Google không yêu cầu số chữ tối thiểu hay lý tưởng.', GoogleDocs::STARTER),
            SeoCheck::tip('headings_used', ! $long || $headings->isNotEmpty(), 'Bài được chia mục.', 'Bài dài nên chia mục bằng H2 cho dễ đọc.'),
            SeoCheck::tip('heading_text', ! $titles->contains('') && $titles->unique()->count() === $titles->count(), 'Tiêu đề mục không trống, không lặp.', 'Có tiêu đề mục để trống hoặc trùng nhau.'),
            SeoCheck::tip('paragraph_length', $longParagraphs === 0, 'Các đoạn văn vừa mắt.', $longParagraphs.' đoạn dài hơn 120 chữ: tách nhỏ cho dễ đọc trên điện thoại.'),
            SeoCheck::tip('sentence_length', $longSentences <= 25, 'Câu văn gọn.', $longSentences.'% số câu dài hơn 35 chữ: ngắt câu cho ngắn lại.'),
            SeoCheck::tip('lists', ! $long || $page->body('//*[self::ul or self::ol or self::table]') !== [], 'Người đọc lướt được ý chính.', 'Thêm danh sách hoặc bảng để người đọc lướt được ý chính.'),
            SeoCheck::tip('intro', filled($context->post->intro), 'Có đoạn mở bài.', 'Chưa có đoạn mở bài.'),
        ];
    }

    /** @return array{int, string} percent of this text found in the closest other article, and its title */
    private function mostCopied(SeoContext $context): array
    {
        $own = $this->shingles($context->post->content);
        $worst = [0, ''];

        if (count($own) < 20) {
            return $worst;
        }

        foreach (Post::query()->whereKeyNot($context->post->getKey() ?? 0)->select(['id', 'title', 'content'])->lazyById(100) as $other) {
            $share = (int) round(count(array_intersect_key($own, $this->shingles($other->content))) / count($own) * 100);
            $worst = $share > $worst[0] ? [$share, $other->title] : $worst;
        }

        return $worst;
    }

    /** @return array<string, int> */
    private function shingles(?string $html): array
    {
        $words = SeoText::words(html_entity_decode(strip_tags(str_replace('<', ' <', (string) $html)), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $runs = [];

        for ($i = 0; $i + self::SHINGLE <= count($words); $i++) {
            $runs[implode(' ', array_slice($words, $i, self::SHINGLE))] = 1;
        }

        return $runs;
    }

    /** @return int percent of sentences longer than 35 words */
    private function longSentences(ParsedPage $page): int
    {
        $sentences = collect($page->body('//*[self::p or self::li]'))
            ->flatMap(fn (DOMElement $node): array => preg_split('/(?<=[.!?…])\s+/u', ParsedPage::textOf($node), -1, PREG_SPLIT_NO_EMPTY) ?: []);

        return $sentences->isEmpty() ? 0 : (int) round($sentences->filter(fn (string $sentence): bool => SeoText::countWords($sentence) > 35)->count() / $sentences->count() * 100);
    }
}
