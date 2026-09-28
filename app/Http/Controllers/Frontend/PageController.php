<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use Illuminate\Contracts\View\View;

class PageController extends FrontendController
{
    public function show(string $slug): View
    {
        return $this->render(Page::query()->active()->where('slug', $slug)->firstOrFail());
    }

    public function render(Page $page): View
    {
        abort_unless($page->is_active, 404);

        $template = $page->template;

        return $this->page($template->view(), [
            'page' => $page,
            'pageTitle' => $page->meta_title ?: $page->title.' | '.site_config('name'),
            'pageDescription' => $page->meta_description ?: (string) $page->summary,
            'bodyClass' => 'page-'.$template->value.' page-'.$page->slug,
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => $page->breadcrumbTitle(), 'url' => request()->url()],
            ],
        ]);
    }
}
