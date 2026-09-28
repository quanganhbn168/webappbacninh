<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Project;
use Illuminate\Contracts\View\View;

class ProjectController extends FrontendController
{
    public function index(): View
    {
        $projects = Project::query()->forCatalog()->get();

        return $this->sitePage('projects', 'site.projects.index', [
            'projects' => $projects,
            'categories' => $projects->pluck('category')->filter()->unique('id')->values(),
            'schemaType' => 'CollectionPage',
            'schemaItems' => $projects->map(fn (Project $project): array => ['name' => $project->title, 'url' => $project->url])->all(),
        ]);
    }

    public function detail(string $slug): View
    {
        $project = Project::query()->active()->with(['category', 'image'])->where('slug', $slug)->firstOrFail();

        return $this->page('site.projects.show', [
            'project' => $project,
            'relatedItems' => $project->related(),
            'pageTitle' => $project->meta_title ?: $project->title.' | Dự án '.site_config('name'),
            'pageDescription' => $project->meta_description ?: (string) $project->excerpt,
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
