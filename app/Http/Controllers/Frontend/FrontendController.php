<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

abstract class FrontendController extends Controller
{
    protected function simplePage(string $view, string $title, string $description, string $menu, array $styles = [], string $bodyClass = '', array $scripts = [], ?string $cta = null): View
    {
        return $this->page($view, [
            'pageTitle' => $title,
            'pageDescription' => $description,
            'activeMenu' => $menu,
            'headerCta' => $cta ?? route('contact'),
            'floatingCta' => $cta ?? route('contact'),
            'extraStyles' => $styles,
            'extraScripts' => $scripts,
            'bodyClass' => $bodyClass,
        ]);
    }

    protected function page(string $contentView, array $data): View
    {
        $data += [
            'contentView' => $contentView,
            'pageTitle' => site_config('name'),
            'pageDescription' => '',
            'canonicalUrl' => request()->url(),
            'ogImage' => frontend_asset('assets/images/hero-industrial.webp'),
            'ogType' => 'website',
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
        $data['jsonLd'] ??= $this->buildJsonLd($data);

        return view('frontend.site.layout', $data);
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

        $organization = [
            '@type' => 'ProfessionalService',
            '@id' => $organizationId,
            'name' => site_config('name'),
            'url' => $siteUrl,
            'telephone' => site_config('phone_href'),
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
