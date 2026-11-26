<?php

namespace App\Domain\Seo;

use App\Domain\Seo\Checks\ContentChecks;
use App\Domain\Seo\Checks\DescriptionChecks;
use App\Domain\Seo\Checks\ImageChecks;
use App\Domain\Seo\Checks\KeywordChecks;
use App\Domain\Seo\Checks\LinkChecks;
use App\Domain\Seo\Checks\SiteChecks;
use App\Domain\Seo\Checks\TechnicalChecks;
use App\Domain\Seo\Checks\TitleChecks;
use App\Http\Controllers\Frontend\ArticleController;
use App\Models\Post;
use Illuminate\Support\Collection;

/**
 * Scores an article by fixed rules, saved or not: its page is produced exactly as a visitor
 * would get it, then read back. Nothing is written and nothing leaves the server.
 *
 * What is scored comes from Google's documents (see GoogleDocs) or is plainly broken. The
 * points, weights and the pass mark are this website's: Google publishes no score.
 */
final class AnalyzePost
{
    private const GROUPS = [TitleChecks::class, DescriptionChecks::class, ContentChecks::class, ImageChecks::class, LinkChecks::class, TechnicalChecks::class, KeywordChecks::class, SiteChecks::class];

    /** The standard is every scored rule passing: 100. A warning or a failure keeps it below. */
    private const WARNED_CAP = 99;

    private const FAILED_CAP = 79;

    /** A page Google cannot index, or with missing pictures, never scores above this. */
    private const CRITICAL_CAP = 49;

    private const ORDER = [SeoCheck::FAIL => 0, SeoCheck::WARN => 1, SeoCheck::TIP => 2, SeoCheck::INFO => 3, SeoCheck::PASS => 4];

    /**
     * @return array{score: int, grade: string, verdict: string, preview: array{title: string, url: string, description: string}, groups: array<int, array{title: string, advice: bool, problems: int, checks: array<int, array{key: string, status: string, message: string, source: string|null}>}>}
     */
    public function execute(Post $post): array
    {
        // A copy: a draft without a date is read as if published now, which must not be saved.
        $post = clone $post;
        $post->published_at ??= now();
        $post->load(['author', 'category', 'featuredMedia', 'ogMedia']);
        $page = new ParsedPage(app(ArticleController::class)->articlePage($post)->render());
        $context = new SeoContext($post, $page);
        $all = collect();
        $groups = [];

        foreach (self::GROUPS as $class) {
            $group = app($class);
            $checks = collect($group->run($context));
            $all = $all->concat($checks);
            $rules = $checks->where('advice', false);

            if ($rules->isNotEmpty()) {
                $groups[] = $this->group($group->title(), $rules, false);
            }
        }

        // Everything Google does not ask for sits together at the end.
        $groups[] = $this->group('Gợi ý thêm (không tính điểm)', $all->where('advice', true), true);

        $total = $all->sum('weight');
        $earned = $all->sum(fn (SeoCheck $check): float => match ($check->status) {
            SeoCheck::PASS => $check->weight,
            SeoCheck::WARN => $check->weight / 2,
            default => 0,
        });
        $failed = $all->contains('status', SeoCheck::FAIL);
        $critical = $all->contains(fn (SeoCheck $check): bool => $check->critical && $check->status === SeoCheck::FAIL);
        $warned = $all->contains('status', SeoCheck::WARN);
        $score = min($total ? (int) round($earned / $total * 100) : 0, match (true) {
            $critical => self::CRITICAL_CAP,
            $failed => self::FAILED_CAP,
            $warned => self::WARNED_CAP,
            default => 100,
        });
        $remaining = $all->whereIn('status', [SeoCheck::FAIL, SeoCheck::WARN])->count();

        return [
            'score' => $score,
            'grade' => self::grade($score),
            'verdict' => match (true) {
                $critical => 'Có lỗi nghiêm trọng',
                $remaining === 0 => 'Đạt chuẩn',
                default => 'Chưa đạt: còn '.$remaining.' mục',
            },
            'preview' => ['title' => $page->title(), 'url' => $post->url, 'description' => $page->meta('description')],
            'groups' => $groups,
        ];
    }

    public static function grade(int $score): string
    {
        return match (true) {
            $score === 100 => 'good',
            $score >= 50 => 'fair',
            default => 'poor',
        };
    }

    /**
     * @param  Collection<int, SeoCheck>  $checks
     * @return array{title: string, advice: bool, problems: int, checks: array<int, array{key: string, status: string, message: string, source: string|null}>}
     */
    private function group(string $title, Collection $checks, bool $advice): array
    {
        return [
            'title' => $title,
            'advice' => $advice,
            'problems' => $checks->whereIn('status', [SeoCheck::FAIL, SeoCheck::WARN, SeoCheck::TIP])->count(),
            'checks' => $checks->sortBy(fn (SeoCheck $check): int => self::ORDER[$check->status] * 100 - $check->weight)
                ->map(fn (SeoCheck $check): array => ['key' => $check->key, 'status' => $check->status, 'message' => $check->message, 'source' => $check->source])
                ->values()->all(),
        ];
    }
}
