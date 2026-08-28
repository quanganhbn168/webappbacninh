<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

abstract class FrontendController extends Controller
{
    protected function simplePage(string $view, string $title, string $description, string $menu, array $styles = [], string $bodyClass = '', array $scripts = [], ?string $cta = null): View
    {
        $seoKey = match ($menu) {
            'website-service' => 'services',
            'knowledge' => 'articles',
            default => $menu,
        };
        $seo = site_page_seo($seoKey, ['title' => $title, 'description' => $description]);
        $data = [
            'pageTitle' => $seo['title'],
            'pageDescription' => $seo['description'],
            'activeMenu' => $menu,
            'headerCta' => $cta ?? route('contact'),
            'floatingCta' => $cta ?? route('contact'),
            'extraStyles' => $styles,
            'extraScripts' => $scripts,
            'bodyClass' => $bodyClass,
        ];

        foreach (['keywords' => 'pageKeywords', 'canonical_url' => 'canonicalUrl', 'og_image' => 'ogImage', 'robots' => 'robots'] as $seoKey => $dataKey) {
            if (filled($seo[$seoKey] ?? null)) {
                $data[$dataKey] = $seo[$seoKey];
            }
        }

        return $this->page($view, $data);
    }

    protected function page(string $contentView, array $data): View
    {
        $data += [
            'contentView' => $contentView,
            'pageTitle' => site_config('default_meta_title', site_config('name')),
            'pageDescription' => site_config('default_meta_description', ''),
            'pageKeywords' => site_config('default_meta_keywords', ''),
            'canonicalUrl' => request()->url(),
            'ogImage' => absolute_url(site_config('default_og_image') ?: frontend_asset('assets/images/hero-industrial.webp')),
            'ogType' => 'website',
            'ogImageAlt' => site_config('name'),
            'robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            'language' => site_config('default_language', 'vi'),
            'alternateLinks' => [],
            'extraStyles' => [],
            'extraScripts' => [],
            'bodyClass' => '',
            'activeMenu' => '',
            'activeSubmenu' => '',
            'headerCta' => route('contact'),
            'floatingCta' => route('contact'),
            'jsonLd' => null,
            'schemaType' => null,
            'schemaData' => [],
            'schemaFaqs' => [],
            'schemaItems' => [],
            'breadcrumbs' => [],
        ];

        $data['schemaType'] ??= $this->schemaTypeFor($contentView);
        $data['canonicalUrl'] = filled($data['canonicalUrl']) ? $data['canonicalUrl'] : request()->url();
        $data['ogImage'] = absolute_url((string) $data['ogImage']);
        $data['jsonLd'] ??= $this->buildJsonLd($data);

        return view('layouts.master', $data);
    }

    private function schemaTypeFor(string $contentView): string
    {
        return match ($contentView) {
            'frontend.site.pages.about' => 'AboutPage',
            'frontend.site.pages.contact' => 'ContactPage',
            'frontend.site.pages.website-service',
            'frontend.site.pages.operations',
            'frontend.site.pages.pricing',
            'frontend.site.pages.agency' => 'Service',
            'frontend.site.themes.index',
            'frontend.site.projects.index',
            'frontend.site.articles.index',
            'frontend.site.services.category' => 'CollectionPage',
            default => 'WebPage',
        };
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
