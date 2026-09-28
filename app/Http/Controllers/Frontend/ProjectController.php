<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Project;
use Illuminate\Contracts\View\View;

class ProjectController extends FrontendController
{
    public function detail(string $slug): View
    {
        $project = Project::query()->active()->with(['category', 'image'])->where('slug', $slug)->firstOrFail();

        return $this->page('frontend.site.projects.show', [
            'project' => $project,
            'relatedItems' => $project->related(),
            'pageTitle' => $project->meta_title ?: $project->title.' | Dự án '.site_config('name'),
            'pageDescription' => $project->meta_description ?: (string) $project->excerpt,
            'extraScripts' => ['project-detail.js'],
            'bodyClass' => 'page-project-detail',
            'ogImage' => $project->image_url,
            'schemaType' => 'CreativeWork',
            'schemaData' => ['author' => ['@id' => rtrim((string) site_config('site_url'), '/').'/#organization']],
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => 'Dự án', 'url' => route('projects.index')],
                ['name' => $project->title, 'url' => $project->url],
            ],
        ]);
    }
}
