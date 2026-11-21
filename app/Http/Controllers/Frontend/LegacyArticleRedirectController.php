<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;

/**
 * The blog used to live under /kien-thuc/{bai-viet} and /kien-thuc/danh-muc/{danh-muc};
 * posts and categories now sit at the site root. Google still lists the old links, so each
 * one is sent to its new address with a 301. A slug that clashed with a fixed page got a
 * suffix on the move (thiet-ke-website → thiet-ke-website-blog), hence the prefix match.
 */
class LegacyArticleRedirectController extends Controller
{
    public function post(string $slug): RedirectResponse
    {
        return $this->to(Post::class, $slug);
    }

    public function category(string $slug): RedirectResponse
    {
        return $this->to(PostCategory::class, $slug);
    }

    /** @param  class-string<Model>  $model */
    private function to(string $model, string $slug): RedirectResponse
    {
        $target = $model::query()->where('slug', $slug)->first()
            ?? $model::query()->where('slug', 'like', $slug.'-%')->orderByRaw('LENGTH(slug)')->first();

        return redirect($target?->url ?? route('articles.index'), 301);
    }
}
