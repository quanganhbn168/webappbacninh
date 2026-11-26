<?php

namespace App\Domain\Seo\Checks;

use App\Domain\Seo\GoogleDocs;
use App\Domain\Seo\SeoCheck;
use App\Domain\Seo\SeoChecks;
use App\Domain\Seo\SeoContext;
use App\Domain\Seo\SeoText;
use Awcodes\Curator\Models\Media;
use DOMElement;
use Illuminate\Support\Facades\Storage;

class ImageChecks implements SeoChecks
{
    /** Names a camera, a phone, a chat app or an uploader gives a file: they say nothing about the picture. */
    private const MEANINGLESS_NAME = '/^(img|dsc|dcim|pxl|image|photo|picture|screenshot|screen-shot|untitled|anh-chup|zalo|fb-img)([-_ ]|\d|$)|^(pic|anh|hinh)[-_ ]?\d+$|^z\d{6,}|^[\d\-_]+$|^[0-9a-f]{8}-[0-9a-f]{4}-|^[0-9a-hjkmnp-tv-z]{26}$/i';

    private const EXTENSIONS = ['image/jpeg' => ['jpg', 'jpeg'], 'image/png' => ['png'], 'image/webp' => ['webp'], 'image/gif' => ['gif'], 'image/avif' => ['avif'], 'image/svg+xml' => ['svg'], 'image/bmp' => ['bmp']];

    public function title(): string
    {
        return 'Hình ảnh';
    }

    public function run(SeoContext $context): array
    {
        $cover = $context->post->featuredMedia;
        $content = $context->contentImages();
        $library = $context->libraryImages();
        $noAlt = $content->where('alt', '')->count();
        $nameAlt = $content->filter(fn (array $image): bool => $image['alt'] !== '' && (preg_match('/\.(jpe?g|png|webp|gif)$/i', $image['alt']) || preg_match(self::MEANINGLESS_NAME, $image['alt'])))->count();
        $stuffedAlt = $content->filter(fn (array $image): bool => ($context->keyword !== '' && SeoText::occurrences($image['alt'], $context->keyword) > 1) || substr_count($image['alt'], ',') >= 4)->count();
        $missing = $content->filter(fn (array $image): bool => $image['local'] && $this->missing($image))->count()
            + ($cover && ! Storage::disk($cover->disk)->exists($cover->path) ? 1 : 0);
        $badNames = $library->filter(fn (Media $media): bool => preg_match(self::MEANINGLESS_NAME, pathinfo($media->path, PATHINFO_FILENAME)) === 1);
        $external = $content->where('local', false)->count();
        $heavy = $library->filter(fn (Media $media): bool => $media->size > 1024 * 1024)->count();
        $small = $content->filter(fn (array $image): bool => $image['media']?->width && $image['media']->width < 600)->count();
        $unsized = collect($context->page->elements('//article//img'))
            ->filter(fn (DOMElement $node): bool => ! $node->getAttribute('width') || ! $node->getAttribute('height'))->count();
        $large = $cover && $cover->width >= 1200 && $cover->width * $cover->height > 300000;
        $ratio = $cover?->height ? $cover->width / $cover->height : 1;
        $share = $context->post->ogMedia ?? $cover;
        $logos = array_filter([site_config('logo_wide_media_id'), site_config('logo_white_media_id'), site_config('logo_square_media_id')]);
        $shortAlt = $content->filter(fn (array $image): bool => $image['alt'] !== '' && SeoText::countWords($image['alt']) < 3)->count();
        $wrongExtension = $library->filter(fn (Media $media): bool => isset(self::EXTENSIONS[$media->type]) && ! in_array(strtolower((string) $media->ext), self::EXTENSIONS[$media->type], true))->count();
        $widescreen = $cover?->height && abs($cover->width / $cover->height - 16 / 9) <= 0.03;

        return [
            SeoCheck::rule('image_alt_detail', $shortAlt === 0, 'Mô tả ảnh đủ chi tiết.', $shortAlt.' mô tả ảnh quá sơ sài (dưới 3 chữ): tả rõ trong ảnh có gì, liên quan gì tới bài.', GoogleDocs::IMAGES, 2),
            SeoCheck::rule('preview_image', $share && ! in_array($share->id, $logos), 'Bài có ảnh đại diện riêng cho tìm kiếm và chia sẻ.', 'Bài chưa có ảnh riêng (đang dùng ảnh chung hoặc logo): Google khuyên tránh.', GoogleDocs::IMAGES, 3),
            SeoCheck::rule('cover_widescreen', (bool) $widescreen, 'Ảnh đại diện đúng tỷ lệ 16:9.', $cover
                ? 'Ảnh đại diện '.$cover->width.'×'.$cover->height.' chưa đúng 16:9 (ví dụ 1280×720).'
                : 'Chưa có ảnh đại diện tỷ lệ 16:9.', GoogleDocs::DISCOVER, 2),
            SeoCheck::rule('image_extension', $wrongExtension === 0, 'Đuôi file đúng với định dạng ảnh.', $wrongExtension.' ảnh có đuôi file không khớp định dạng.', GoogleDocs::IMAGES),
            SeoCheck::rule('image_width', $small === 0, 'Ảnh trong bài đủ nét.', $small.' ảnh trong bài hẹp hơn 600px, dễ bị mờ.', GoogleDocs::IMAGES),
            SeoCheck::rule('image_dimensions', $unsized === 0, 'Ảnh khai báo sẵn kích thước, trang không giật khi tải.', $unsized.' ảnh không khai báo kích thước, trang giật khi tải (chỉ số CLS).', GoogleDocs::VITALS, 2),
            SeoCheck::rule('image_alt', $noAlt === 0, 'Không ảnh nào trong bài thiếu mô tả (alt).', $noAlt.' ảnh trong bài thiếu mô tả (alt).', GoogleDocs::IMAGES, 3, SeoCheck::FAIL),
            SeoCheck::rule('image_alt_text', $nameAlt === 0, 'Không ảnh nào lấy tên file làm mô tả.', $nameAlt.' ảnh lấy tên file làm mô tả: viết lại theo nội dung ảnh.', GoogleDocs::IMAGES),
            SeoCheck::rule('image_alt_stuffing', $stuffedAlt === 0, 'Mô tả ảnh không nhồi từ khóa.', $stuffedAlt.' mô tả ảnh nhồi từ khóa.', GoogleDocs::IMAGES, 2),
            SeoCheck::rule('image_names', $badNames->isEmpty(), 'Tên file ảnh ngắn gọn, có nghĩa.', 'Tên file ảnh vô nghĩa ('.$badNames->take(2)->map(fn (Media $media): string => basename($media->path))->implode(', ').'): đặt tên theo nội dung rồi tải lên lại.', GoogleDocs::IMAGES),
            SeoCheck::rule('cover', $large, 'Ảnh đại diện đủ lớn cho Google Khám phá.', $cover
                ? 'Ảnh đại diện '.$cover->width.'×'.$cover->height.': Google Khám phá cần rộng từ 1200px và trên 300.000 điểm ảnh.'
                : 'Chưa có ảnh đại diện: Google Khám phá cần ảnh rộng từ 1200px.', GoogleDocs::DISCOVER, 2),
            SeoCheck::rule('cover_ratio', $ratio <= 3 && $ratio >= 1 / 3, 'Không dùng ảnh tỷ lệ quá dẹt hay quá dài.', 'Ảnh đại diện quá dẹt hoặc quá dài.', GoogleDocs::IMAGES),
            SeoCheck::rule('image_preview', str_contains($context->page->meta('robots'), 'max-image-preview:large'), 'Cho phép Google hiện ảnh lớn.', 'Trang chưa cho phép hiện ảnh lớn (max-image-preview:large).', GoogleDocs::DISCOVER),
            SeoCheck::rule('image_files', $missing === 0, 'Không ảnh nào bị mất file.', $missing.' ảnh không còn file trên máy chủ.', null, 3, SeoCheck::FAIL, true),
            SeoCheck::tip('share_image', $share && strtolower((string) $share->ext) !== 'svg' && $share->width >= 1200 && $share->height >= 630, 'Ảnh chia sẻ đạt 1200×630.', 'Ảnh chia sẻ Facebook, Zalo nên là ảnh riêng của bài, từ 1200×630.'),
            SeoCheck::tip('content_images', $context->words < 600 || $content->isNotEmpty(), 'Số ảnh trong bài phù hợp.', 'Bài dài nên có ảnh minh họa trong nội dung.'),
            SeoCheck::tip('image_external', $external === 0, 'Không lấy ảnh từ website khác.', $external.' ảnh lấy từ website khác: tải về thư viện để không mất ảnh.'),
            SeoCheck::tip('image_weight', $heavy === 0, 'Ảnh gốc không quá nặng.', $heavy.' ảnh gốc nặng hơn 1 MB: nên nén trước khi tải lên.'),
        ];
    }

    /** @param  array{src: string, media: Media|null}  $image */
    private function missing(array $image): bool
    {
        if ($image['media']) {
            return ! Storage::disk($image['media']->disk)->exists($image['media']->path);
        }

        $path = rawurldecode((string) parse_url($image['src'], PHP_URL_PATH));

        // Addresses under /curator are generated on request and have no file of their own.
        if (str_starts_with($path, '/curator/') || $path === '') {
            return false;
        }

        return str_starts_with($path, '/storage/')
            ? ! Storage::disk('public')->exists(substr($path, 9))
            : ! is_file(public_path(ltrim($path, '/')));
    }
}
