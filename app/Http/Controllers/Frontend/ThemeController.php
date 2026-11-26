<?php

namespace App\Http\Controllers\Frontend;

use App\Models\OperationService;
use App\Models\Template;
use App\Models\TemplateCategory;
use App\Models\ThemeFeature;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class ThemeController extends FrontendController
{
    public function index(): View
    {
        $themes = Template::query()->forCatalog()->get();

        return $this->sitePage('themes', 'site.themes.index', [
            'themes' => $themes,
            'industries' => $themes->pluck('category')->filter()->unique('id')->sortBy(fn (TemplateCategory $industry): string => Str::ascii($industry->name))->values(),
            'features' => ThemeFeature::query()->whereHas('templates', fn ($query) => $query->active())->orderBy('name')->get(),
            'schemaType' => 'CollectionPage',
            'schemaItems' => $themes->map(fn (Template $theme): array => [
                'name' => $theme->name,
                'url' => $theme->url,
            ])->all(),
        ]);
    }

    /** The designs of one industry, with what a website in that field needs. */
    public function industry(string $slug): View
    {
        $industry = TemplateCategory::query()->with('bannerImage')->where('slug', $slug)->firstOrFail();
        $themes = Template::query()->forCatalog()->where('template_category_id', $industry->id)->get();
        abort_if($themes->isEmpty(), 404);

        $name = 'Mẫu website '.mb_strtolower($industry->name);

        return $this->page('site.themes.industry', [
            'industry' => $industry,
            'heading' => $name,
            'themes' => $themes,
            'projects' => $industry->projects()->forCatalog()->take(3)->get(),
            'otherIndustries' => TemplateCategory::query()->whereKeyNot($industry->id)->whereHas('templates', fn ($query) => $query->active())->orderBy('name')->get(),
            'pageTitle' => $industry->meta_title ?: $name.' | '.site_config('name'),
            'pageDescription' => $industry->meta_description ?: (Str::limit((string) $industry->description, 160) ?: $name.': xem các giao diện và nhận tư vấn tùy chỉnh theo doanh nghiệp.'),
            'ogImage' => $industry->bannerImage?->url ?: $themes->first()->cover['src'],
            ...($industry->isIndexable() ? [] : ['robots' => 'noindex, follow']),
            'schemaType' => 'CollectionPage',
            'schemaItems' => $themes->map(fn (Template $theme): array => ['name' => $theme->name, 'url' => $theme->url])->all(),
            'schemaFaqs' => $industry->faqItems(),
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => 'Kho giao diện', 'url' => route('themes.index')],
                ['name' => $industry->name, 'url' => $industry->url],
            ],
        ]);
    }

    public function detail(string $slug): View
    {
        $theme = Template::query()->active()->with(['category', 'features', 'image'])->where('slug', $slug)->firstOrFail();
        $description = $theme->meta_description ?: $theme->description;
        // Neighbours in catalog order, for the "previous / next design" links.
        $catalog = Template::query()->active()->ordered()->get(['id', 'name', 'slug']);
        $position = $catalog->search(fn (Template $item): bool => $item->is($theme));

        return $this->page('site.themes.show', [
            'theme' => $theme,
            'previousTheme' => $catalog->get($position - 1),
            'nextTheme' => $catalog->get($position + 1),
            'relatedThemes' => $theme->related(),
            'operationServices' => OperationService::query()->active()->ordered()->take(4)->get(),
            'pageTitle' => $theme->meta_title ?: $theme->name.' | '.site_config('name'),
            'pageDescription' => trim($description.' Xem chi tiết phạm vi bàn giao, chức năng, thời gian và chi phí triển khai.'),
            'ogType' => 'product',
            'ogImage' => $theme->cover['src'],
            // Google asks a Product for a price, so a design priced on request stays a plain page.
            ...($theme->effective_price ? [
                'schemaType' => 'Product',
                'schemaData' => [
                    'name' => $theme->name,
                    'brand' => ['@type' => 'Brand', 'name' => site_config('name')],
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => (string) $theme->effective_price,
                        'priceCurrency' => 'VND',
                        'availability' => 'https://schema.org/InStock',
                    ],
                ],
            ] : []),
            'breadcrumbs' => array_values(array_filter([
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => 'Kho giao diện', 'url' => route('themes.index')],
                $theme->category ? ['name' => $theme->category->name, 'url' => $theme->category->url] : null,
                ['name' => $theme->name, 'url' => $theme->url],
            ])),
        ]);
    }

    /** The demo in a frame the visitor can switch between computer, tablet and phone. */
    public function demo(string $slug): View
    {
        $theme = Template::query()->active()->where('slug', $slug)->firstOrFail();
        abort_unless($theme->demo_source, 404);

        return view('site.themes.demo', ['theme' => $theme]);
    }

    /** The demo page itself, for a design that uses one of our landing layouts. */
    public function demoFrame(string $slug): View
    {
        $theme = Template::query()->active()->where('slug', $slug)->firstOrFail();
        $landing = $theme->demoPage();
        abort_unless($landing, 404);

        return view($landing->template->view(), ['landing' => $landing, 'c' => $landing->data()]);
    }
}
