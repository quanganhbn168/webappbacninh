<?php

use App\Domain\Content\ResolveUniqueSlug;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Slug;
use App\Support\FrontendMenuCache;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The blog list moves to /blog and every post and category is served from the
 * site root (/{bai-viet}, /{danh-muc}). No redirects: nothing was indexed yet.
 * A post or category whose slug is a fixed page path gets "-blog" appended,
 * and the slug registry that the root handler reads is filled for all of them.
 */
return new class extends Migration
{
    public function up(): void
    {
        $reserved = ResolveUniqueSlug::reserved();

        foreach ([PostCategory::class, Post::class] as $model) {
            $model::query()->get()->each(function ($record) use ($reserved): void {
                $slug = $record->slug;
                if (in_array($slug, $reserved, true)) {
                    $slug .= '-blog';
                    DB::table($record->getTable())->where('id', $record->id)->update(['slug' => $slug]);
                }

                Slug::query()->updateOrCreate(
                    ['reference_type' => $record->getMorphClass(), 'reference_id' => $record->id],
                    ['key' => $slug],
                );
            });
        }

        DB::table('menu_items')->where('url', '/kien-thuc')->update(['url' => '/blog']);
        DB::table('menu_items')->where('url', 'like', '/kien-thuc/danh-muc/%')
            ->update(['url' => DB::raw("REPLACE(url, '/kien-thuc/danh-muc/', '/')")]);
        DB::table('menu_items')->where('url', 'like', '/kien-thuc/%')
            ->update(['url' => DB::raw("REPLACE(url, '/kien-thuc/', '/')")]);

        app(FrontendMenuCache::class)->forgetAll();
    }

    public function down(): void {}
};
