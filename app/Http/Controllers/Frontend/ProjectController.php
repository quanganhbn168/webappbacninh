<?php

namespace App\Http\Controllers\Frontend;

use App\Support\FrontendContent;
use Illuminate\Contracts\View\View;

class ProjectController extends FrontendController
{
    public function __construct(private readonly FrontendContent $content) {}

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
