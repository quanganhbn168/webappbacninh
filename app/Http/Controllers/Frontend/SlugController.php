<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Slug;
use App\Models\Template;
use App\Models\TemplateCategory;
use App\Models\Post;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class SlugController extends Controller
{
    public function show($slug)
    {
        // Find the slug entry
        $slugEntry = Slug::where('key', $slug)->firstOrFail();
        
        // Dispatch based on reference type
        return match($slugEntry->reference_type) {
            Template::class => app(TemplateController::class)->show($slugEntry->reference),
            TemplateCategory::class => app(TemplateController::class)->index(new Request(['category' => $slugEntry->reference->slug])),
            Post::class => app(\App\Http\Controllers\BlogPostController::class)->show($slugEntry->key), // Legacy support if needed, or update blog controller
            Service::class => app(ServiceController::class)->detail($slugEntry->reference),
            ServiceCategory::class => app(ServiceController::class)->servicesByCate($slugEntry->reference),
            default => abort(404),
        };
    }
}
