<?php

use App\Models\Page;
use App\Support\FrontendMenuCache;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * The article list moves from /blog to /kien-thuc and is called "Kiến thức" everywhere.
 * Titles the admin has reworded are left alone.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('menu_items')->whereIn('url', ['/blog', '/tin-tuc'])->update(['url' => '/kien-thuc']);
        DB::table('menu_items')->where('url', '/kien-thuc')->whereIn('title', ['Blog', 'Blog - Kiến thức', 'Tin tức'])
            ->update(['title' => 'Kiến thức']);
        app(FrontendMenuCache::class)->forgetAll();

        DB::table('pages')->where('key', 'articles')->where('title', 'Blog')->update(['title' => 'Kiến thức']);
        Cache::forget(Page::cacheKey('articles'));

        // The removed sitemap:generate command wrote this file with dead links; it shadows the /sitemap.xml route.
        File::delete(public_path('sitemap.xml'));
    }

    public function down(): void {}
};
