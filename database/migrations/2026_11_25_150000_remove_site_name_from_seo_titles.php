<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * The site name is added to page titles when they are rendered, so SEO titles
 * saved with "| Tên website" at the end lose it. The public titles stay the same.
 */
return new class extends Migration
{
    private const TABLES = [
        'pages', 'posts', 'post_categories', 'services', 'service_categories',
        'operation_services', 'projects', 'templates',
    ];

    public function up(): void
    {
        $name = trim((string) site_config('name'));
        if ($name === '') {
            return;
        }
        $suffix = '/\s*\|\s*'.preg_quote($name, '/').'\s*$/iu';

        foreach (self::TABLES as $table) {
            DB::table($table)->where('meta_title', 'like', '%|%')->orderBy('id')->get()
                ->each(function (object $row) use ($table, $suffix): void {
                    $title = trim((string) preg_replace($suffix, '', $row->meta_title));

                    if ($title !== '' && $title !== $row->meta_title) {
                        DB::table($table)->where('id', $row->id)->update(['meta_title' => $title]);
                    }
                });
        }

        DB::table('pages')->pluck('key')->each(fn (string $key) => Cache::forget(Page::cacheKey($key)));
    }

    public function down(): void {}
};
