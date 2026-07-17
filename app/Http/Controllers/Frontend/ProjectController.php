<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Contracts\View\View;

class ProjectController extends FrontendController
{
    public function index(): View
    {
        return $this->page('frontend.site.projects.index', [
            'projectItems' => projects(),
            'pageTitle' => 'Dự án website và phần mềm đã triển khai | WebApp Bắc Ninh',
            'pageDescription' => 'Tham khảo các dự án website doanh nghiệp, website bán hàng, landing page, website giáo dục, du lịch và hệ thống quản lý của WebApp Bắc Ninh.',
            'activeMenu' => 'projects',
            'headerCta' => '#projectContact',
            'floatingCta' => '#projectContact',
            'extraStyles' => ['projects.css'],
            'extraScripts' => ['projects.js'],
            'bodyClass' => 'page-projects',
            'ogImage' => frontend_asset('assets/images/project-corporate.webp'),
            'schemaItems' => collect(projects())->map(fn (array $project): array => [
                'name' => $project['title'],
                'url' => route('projects.show', $project['slug']),
            ])->all(),
        ]);
    }

    public function detail(string $slug): View
    {
        $project = project_by_slug($slug);
        abort_if($project === null, 404);

        return $this->page('frontend.site.projects.show', [
            'project' => $project,
            'relatedItems' => related_projects($project),
            'pageTitle' => $project['title'].' | Dự án WebApp Bắc Ninh',
            'pageDescription' => $project['excerpt'],
            'activeMenu' => 'projects',
            'headerCta' => '#projectConsult',
            'floatingCta' => '#projectConsult',
            'extraStyles' => ['project-detail.css'],
            'extraScripts' => ['project-detail.js'],
            'bodyClass' => 'page-project-detail',
            'ogImage' => frontend_asset($project['image']),
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
