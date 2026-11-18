<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Slug;
use App\Models\Template;
use App\Models\TemplateCategory;

class SlugController extends Controller
{
    public function show($slug)
    {
        // Find the slug entry
        $slugEntry = Slug::where('key', $slug)->firstOrFail();

        // Dispatch based on reference type
        return match ($slugEntry->reference_type) {
            Template::class => redirect()->route('themes.show', $slugEntry->key, 301),
            TemplateCategory::class => redirect()->route('themes.index', status: 301),
            // Blog posts and categories live at the site root: /{bai-viet}, /{danh-muc}.
            Post::class => app(ArticleController::class)->detail($slugEntry->key),
            PostCategory::class => app(ArticleController::class)->category($slugEntry->key),
            Service::class => app(ServiceController::class)->detail($slugEntry->reference),
            ServiceCategory::class => app(ServiceController::class)->servicesByCate($slugEntry->reference),
            default => abort(404),
        };
    }
}
