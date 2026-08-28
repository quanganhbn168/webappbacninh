<?php

namespace App\Http\Controllers\Frontend;

use App\Support\FrontendContent;
use Illuminate\Contracts\View\View;

class ProjectController extends FrontendController
{
    public function __construct(private readonly FrontendContent $content)
    {
    }

    public function index(): View
    {
        $projects = $this->content->projects();
        $seo = site_page_seo('projects', [
            'title' => 'Dự án website và phần mềm đã triển khai | WebApp Bắc Ninh',
            'description' => 'Tham khảo các dự án website doanh nghiệp, website bán hàng, landing page, website giáo dục, du lịch và hệ thống quản lý của WebApp Bắc Ninh.',
        ]);

        return $this->page('frontend.site.projects.index', [
            'projectItems' => $projects,
            'pageTitle' => $seo['title'],
            'pageDescription' => $seo['description'],
            'pageKeywords' => $seo['keywords'] ?? '',
            'canonicalUrl' => $seo['canonical_url'] ?? request()->url(),
            'robots' => $seo['robots'] ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            'activeMenu' => 'projects',
            'headerCta' => '#projectContact',
            'floatingCta' => '#projectContact',
            'extraStyles' => ['projects.css'],
            'extraScripts' => ['projects.js'],
            'bodyClass' => 'page-projects',
            'ogImage' => $seo['og_image'] ?? frontend_asset('assets/images/project-corporate.webp'),
            'schemaItems' => collect($projects)->map(fn (array $project): array => [
                'name' => $project['title'],
                'url' => route('projects.show', $project['slug']),
            ])->all(),
        ]);
    }

    public function detail(string $slug): View
    {
        $project = $this->content->projectBySlug($slug);
        abort_if($project === null, 404);

        return $this->page('frontend.site.projects.show', [
            'project' => $project,
            'relatedItems' => $this->content->relatedProjects($project),
            'pageTitle' => $project['meta_title'],
            'pageDescription' => $project['meta_description'],
            'pageKeywords' => $project['meta_keywords'],
            'canonicalUrl' => $project['canonical_url'] ?: request()->url(),
            'robots' => $project['robots'],
            'activeMenu' => 'projects',
            'headerCta' => '#projectConsult',
            'floatingCta' => '#projectConsult',
            'extraStyles' => ['project-detail.css'],
            'extraScripts' => ['project-detail.js'],
            'bodyClass' => 'page-project-detail',
            'ogImage' => $project['og_image_url'],
            'schemaType' => 'CreativeWork',
            'schemaData' => ['author' => ['@id' => rtrim((string) site_config('site_url'), '/').'/#organization']],
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => 'Dự án', 'url' => route('projects.index')],
                ['name' => $project['title'], 'url' => request()->url()],
            ],
        ]);
    }
}
