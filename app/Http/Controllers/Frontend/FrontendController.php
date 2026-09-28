<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\Pages\SitePages;
use App\Domain\Site\Actions\ResolveSocialChannels;
use App\Domain\Tools\ToolPages;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

abstract class FrontendController extends Controller
{
    /**
     * A fixed site page: SEO fields and hero banner come from its admin record (App\Models\Page).
     */
    protected function sitePage(string $key, string $view, array $data = []): View
    {
        $page = Page::for($key);
        $defaults = [
            'pageTitle' => $page->meta_title ?: ($key === 'home'
                ? site_config('default_meta_title', site_config('name'))
                : $page->title.' | '.site_config('name')),
            'pageDescription' => $page->meta_description ?: SitePages::description($key),
            'seoPage' => $page,
        ];

        if (filled($page->meta_keywords)) {
            $defaults['pageKeywords'] = $page->meta_keywords;
        }
        if ($image = $page->ogImage?->url ?? $page->banner_image_url) {
            $defaults['ogImage'] = $image;
        }
        if ($page->noindex) {
            $defaults['robots'] = 'noindex, follow';
        }
        if ($key !== 'home') {
            $defaults['breadcrumbs'] = [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => $page->title, 'url' => request()->url()],
            ];
        }

        return $this->page($view, $data + $defaults);
    }

    /**
     * A free tool page (resources/views/tools), inside the site chrome under /cong-cu.
     */
    protected function toolPage(string $key, array $data = []): View
    {
        $tool = ToolPages::PAGES[$key];

        return $this->page('tools.'.$key, $data + [
            'pageTitle' => $tool['title'].' | '.site_config('name'),
            'pageDescription' => $tool['description'],
            'pageKeywords' => $tool['keywords'],
            'schemaType' => 'WebApplication',
            'schemaData' => ['applicationCategory' => 'UtilitiesApplication', 'operatingSystem' => 'Web', 'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'VND']],
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => 'Công cụ', 'url' => route('tools.index')],
                ['name' => $tool['name'], 'url' => request()->url()],
            ],
        ]);
    }

    protected function page(string $view, array $data): View
    {
        $data += [
            'pageTitle' => site_config('default_meta_title', site_config('name')),
            'pageDescription' => site_config('default_meta_description', ''),
            'pageKeywords' => site_config('default_meta_keywords', ''),
            'canonicalUrl' => request()->url(),
            'ogImage' => site_config('default_og_image') ?: asset('frontend/images/hero-home.webp'),
            'ogType' => 'website',
            'ogImageAlt' => site_config('name'),
            'robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            'language' => site_config('default_language', 'vi'),
            'bodyClass' => null,
            'jsonLd' => null,
            'schemaType' => 'WebPage',
            'schemaData' => [],
            'schemaFaqs' => [],
            'schemaItems' => [],
            'breadcrumbs' => [],
            'seoPage' => null,
        ];

        $data['canonicalUrl'] = filled($data['canonicalUrl']) ? $data['canonicalUrl'] : request()->url();
        $data['ogImage'] = absolute_url((string) $data['ogImage']);
        $data['jsonLd'] ??= $this->buildJsonLd($data);
        $data['socialChannels'] = app(ResolveSocialChannels::class)->execute();

        // Components (hero, breadcrumbs) read these without every view passing them along.
        ViewFacade::share('seoPage', $data['seoPage']);
        ViewFacade::share('pageBreadcrumbs', $data['breadcrumbs']);

        return view($view, $data);
    }

    private function buildJsonLd(array $data): array
    {
        $siteUrl = rtrim((string) site_config('site_url', config('app.url')), '/');
        $canonicalUrl = $data['canonicalUrl'];
        $organizationId = $siteUrl.'/#organization';
        $contactPoints = [[
            '@type' => 'ContactPoint',
            'telephone' => site_config('phone_href'),
            'contactType' => 'customer service',
            'availableLanguage' => ['vi'],
        ]];
        $secondaryPhone = trim((string) site_config('phone_secondary'));
        $secondaryPhoneHref = trim((string) site_config('phone_secondary_href'));

        if ($secondaryPhone !== '' && $secondaryPhoneHref !== '') {
            $contactPoints[] = [
                '@type' => 'ContactPoint',
                'telephone' => $secondaryPhoneHref,
                'contactType' => 'customer service',
                'availableLanguage' => ['vi'],
            ];
        }

        $organization = [
            '@type' => 'ProfessionalService',
            '@id' => $organizationId,
            'name' => site_config('name'),
            'url' => $siteUrl,
            'telephone' => site_config('phone_href'),
            'contactPoint' => $contactPoints,
            'email' => site_config('email'),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => site_config('address'),
                'addressCountry' => 'VN',
            ],
        ];
        $website = [
            '@type' => 'WebSite',
            '@id' => $siteUrl.'/#website',
            'url' => $siteUrl,
            'name' => site_config('name'),
            'publisher' => ['@id' => $organizationId],
        ];
        $page = array_merge([
            '@type' => $data['schemaType'],
            '@id' => $canonicalUrl.'#webpage',
            'url' => $canonicalUrl,
            'name' => $data['pageTitle'],
            'description' => $data['pageDescription'],
            'image' => $data['ogImage'],
            'inLanguage' => $data['language'] ?? 'vi',
            'publisher' => ['@id' => $organizationId],
            'isPartOf' => ['@id' => $siteUrl.'/#website'],
        ], $data['schemaData']);

        $graph = [$organization, $website];
        $items = collect($data['schemaItems'])->values()->map(function (array $item, int $index): array {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'url' => $item['url'],
            ];
        })->all();
        if ($items !== []) {
            $itemListId = $canonicalUrl.'#itemlist';
            $page['mainEntity'] = ['@id' => $itemListId];
            $graph[] = ['@type' => 'ItemList', '@id' => $itemListId, 'itemListElement' => $items];
        }

        $graph[] = $page;

        $faqItems = collect($data['schemaFaqs'])->map(function (array $faq): array {
            return [
                '@type' => 'Question',
                'name' => $faq['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['a']],
            ];
        })->values()->all();
        if ($faqItems !== []) {
            $graph[] = ['@type' => 'FAQPage', '@id' => $canonicalUrl.'#faq', 'mainEntity' => $faqItems];
        }

        $breadcrumbs = $data['breadcrumbs'] ?: [
            ['name' => 'Trang chủ', 'url' => route('home')],
            ['name' => $data['pageTitle'], 'url' => $canonicalUrl],
        ];
        if (count($breadcrumbs) > 1) {
            $graph[] = [
                '@type' => 'BreadcrumbList',
                '@id' => $canonicalUrl.'#breadcrumb',
                'itemListElement' => collect($breadcrumbs)->values()->map(fn (array $item, int $index): array => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['name'],
                    'item' => $item['url'],
                ])->all(),
            ];
        }

        return ['@context' => 'https://schema.org', '@graph' => $graph];
    }
}
