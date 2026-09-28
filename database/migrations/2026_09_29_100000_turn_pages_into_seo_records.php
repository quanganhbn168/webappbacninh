<?php

use App\Domain\Pages\SitePages;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * Pages become one SEO + banner record per fixed site page; their content moves into Blade views.
 * The previous rows (including any page content edited in the admin) are saved to
 * storage/app/private/backups/ before the table is rebuilt.
 */
return new class extends Migration
{
    /** Old page slugs and the site page they became. */
    private const SLUG_KEYS = [
        'gioi-thieu' => 'about',
        'hop-tac-agency' => 'agency',
        'chinh-sach-bao-mat' => 'privacy',
        'dieu-khoan-su-dung' => 'terms',
        'chinh-sach-bao-hanh' => 'warranty',
        'quy-trinh-thanh-toan' => 'payment-process',
    ];

    /** Keys of the old seo.page_meta setting and the site page they describe. */
    private const META_KEYS = [
        'home' => 'home', 'about' => 'about', 'contact' => 'contact', 'pricing' => 'pricing', 'agency' => 'agency',
        'services' => 'website-design', 'themes' => 'themes', 'projects' => 'projects', 'articles' => 'articles',
        'operations' => 'operations', 'hosting' => 'hosting', 'solutions' => 'solutions', 'products' => 'products',
    ];

    public function up(): void
    {
        $seo = [];

        if (Schema::hasTable('pages') && Schema::hasColumn('pages', 'slug')) {
            $old = DB::table('pages')->get();
            if ($old->isNotEmpty()) {
                Storage::disk('local')->put('backups/pages-before-seo-records.json', $old->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
            foreach ($old as $row) {
                if (isset(self::SLUG_KEYS[$row->slug])) {
                    $seo[self::SLUG_KEYS[$row->slug]] = ['meta_title' => $row->meta_title, 'meta_description' => $row->meta_description];
                }
            }
            DB::table('slugs')->where('reference_type', 'App\\Models\\Page')->delete();
        }

        $meta = json_decode((string) DB::table('settings')->where('group', 'seo')->where('name', 'page_meta')->value('payload'), true);
        foreach (is_array($meta) ? $meta : [] as $metaKey => $values) {
            $key = self::META_KEYS[$metaKey] ?? null;
            if ($key === null || ! is_array($values)) {
                continue;
            }
            $seo[$key] = array_filter([
                'meta_title' => $values['title'] ?? null,
                'meta_description' => $values['description'] ?? null,
                'meta_keywords' => $values['keywords'] ?? null,
            ]) + ($seo[$key] ?? []);
        }

        Schema::dropIfExists('pages');
        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('title');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->foreignId('og_image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->boolean('noindex')->default(false);
            $table->string('banner_eyebrow')->nullable();
            $table->string('banner_title')->nullable();
            $table->string('banner_highlight')->nullable();
            $table->text('banner_subtitle')->nullable();
            $table->foreignId('banner_image_id')->nullable()->constrained('curator')->nullOnDelete();
            $table->timestamps();
        });

        foreach (array_keys(SitePages::PAGES) as $key) {
            DB::table('pages')->insert([
                'key' => $key,
                'title' => SitePages::title($key),
                'meta_title' => $seo[$key]['meta_title'] ?? null,
                'meta_description' => $seo[$key]['meta_description'] ?? null,
                'meta_keywords' => $seo[$key]['meta_keywords'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // The old page builder is gone; restore from the JSON backup if needed.
    }
};
