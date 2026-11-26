<?php

namespace App\Domain\Seo\Checks;

use App\Domain\Seo\GoogleDocs;
use App\Domain\Seo\SeoCheck;
use App\Domain\Seo\SeoChecks;
use App\Domain\Seo\SeoContext;
use App\Domain\Seo\SeoText;
use App\Http\Controllers\SitemapController;
use App\Models\Post;
use App\Models\Slug;
use DOMElement;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LinkChecks implements SeoChecks
{
    /** Link texts that say nothing about where they lead ("click here", "read more" are Google's examples). */
    private const VAGUE = ['', 'đây', 'tại đây', 'ở đây', 'xem tại đây', 'click vào đây', 'bấm vào đây', 'xem thêm', 'đọc thêm', 'chi tiết', 'xem chi tiết', 'link', 'click here', 'read more', 'here'];

    /** @var array<int, string>|null */
    private ?array $sitemap = null;

    public function title(): string
    {
        return 'Liên kết';
    }

    public function run(SeoContext $context): array
    {
        $links = collect($context->page->body('//a[@href]'))
            ->map(fn (DOMElement $node): array => ['href' => trim($node->getAttribute('href')), 'text' => SeoText::normalize($node->textContent), 'image' => $node->getElementsByTagName('img')->length > 0])
            ->reject(fn (array $link): bool => preg_match('/^(mailto:|tel:|javascript:)/i', $link['href']) === 1);
        $web = $links->reject(fn (array $link): bool => str_starts_with($link['href'], '#'));
        [$internal, $external] = $web->partition(fn (array $link): bool => SeoContext::isOwnAddress($link['href']));
        $broken = $this->broken($context, $links, $internal);
        $vague = $web->filter(fn (array $link): bool => ! $link['image'] && in_array($link['text'], self::VAGUE, true))->count();
        $insecure = $external->filter(fn (array $link): bool => str_starts_with(strtolower($link['href']), 'http://'))->count();
        $slug = (string) $context->post->slug;
        $inbound = Post::query()->published()->whereKeyNot($context->post->getKey() ?? 0)
            ->where(fn ($query) => $query->where('content', 'like', '%/'.$slug.'"%')->orWhere('content', 'like', '%/'.$slug.'#%'))->count();

        return [
            SeoCheck::rule('anchor_text', $vague === 0, 'Chữ trên liên kết nói được trang đích.', $vague.' liên kết chỉ ghi kiểu "tại đây", "xem thêm": viết rõ nội dung trang đích.', GoogleDocs::LINKS, 2),
            SeoCheck::rule('broken_links', $broken->isEmpty(), 'Không có liên kết nội bộ hỏng.', 'Liên kết hỏng hoặc trỏ tới trang chưa công khai: '.$broken->take(3)->implode(', '), null, 3, SeoCheck::FAIL),
            SeoCheck::tip('internal_links', $internal->isNotEmpty(), 'Có '.$internal->count().' liên kết nội bộ.', 'Nên liên kết tới bài hoặc dịch vụ liên quan.'),
            SeoCheck::tip('inbound_links', $inbound > 0, $inbound.' bài khác đang liên kết tới bài này.', 'Nên thêm liên kết tới bài này từ một bài liên quan.'),
            SeoCheck::tip('external_links', $external->isNotEmpty(), 'Có dẫn nguồn ra ngoài.', 'Có thể dẫn 1–2 nguồn uy tín bên ngoài.'),
            SeoCheck::tip('secure_links', $insecure === 0, 'Không liên kết ngoài nào dùng http.', $insecure.' liên kết ngoài dùng http không bảo mật.'),
        ];
    }

    /**
     * @param  Collection<int, array{href: string}>  $links
     * @param  Collection<int, array{href: string}>  $internal
     * @return Collection<int, string>
     */
    private function broken(SeoContext $context, Collection $links, Collection $internal): Collection
    {
        // A jump inside the page needs something to jump to.
        $anchors = $links->pluck('href')->filter(fn (string $href): bool => str_starts_with($href, '#') && strlen($href) > 1)
            ->reject(fn (string $href): bool => $context->page->elements('//*[@id="'.str_replace('"', '', substr($href, 1)).'"]') !== []);

        return $internal->pluck('href')->reject(fn (string $href): bool => $this->exists($context, $href))->concat($anchors)->unique()->values();
    }

    private function exists(SeoContext $context, string $href): bool
    {
        $path = trim(rawurldecode((string) parse_url($href, PHP_URL_PATH)), '/');

        if ($path === '' || $path === $context->post->slug || is_file(public_path($path))) {
            return true;
        }

        if (preg_match('#^storage/(.+)$#', $path, $file)) {
            return is_file(storage_path('app/public/'.$file[1]));
        }

        if ($entry = Slug::query()->where('key', $path)->first()) {
            return $entry->reference_type !== Post::class || Post::query()->published()->whereKey($entry->reference_id)->exists();
        }

        // Projects, themes and the other detail pages are all listed in the sitemap.
        $this->sitemap ??= $this->sitemapPaths();

        if (in_array($path, $this->sitemap, true)) {
            return true;
        }

        try {
            $route = Route::getRoutes()->match(Request::create('/'.$path));
        } catch (HttpException) {
            return false;
        }

        // A fixed route exists as it is; one with a parameter would have been found above.
        return ! str_contains($route->uri(), '{');
    }

    /** @return array<int, string> */
    private function sitemapPaths(): array
    {
        preg_match_all('#<loc>(.*?)</loc>#', (string) app(SitemapController::class)->index()->getContent(), $matches);

        return array_map(fn (string $url): string => trim(rawurldecode((string) parse_url(html_entity_decode($url), PHP_URL_PATH)), '/'), $matches[1]);
    }
}
