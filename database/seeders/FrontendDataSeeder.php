<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FrontendDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        foreach (config('themes') as $order => $theme) {
            $categoryId = $this->category('template_categories', $theme['industry'], $theme['industryLabel'], $order, $now);
            DB::table('templates')->updateOrInsert(['slug' => $theme['slug']], [
                'template_category_id' => $categoryId, 'code' => $theme['code'], 'name' => $theme['name'],
                'image' => 'frontend/assets/images/'.$theme['image'], 'category' => $theme['industryLabel'],
                'type' => $theme['type'], 'industry' => $theme['industry'], 'price' => $theme['price'],
                'year' => $theme['year'], 'description' => $theme['description'], 'content' => json_encode($theme['includedFeatures'], JSON_UNESCAPED_UNICODE),
                'badge' => $theme['badge'], 'duration' => $theme['duration'], 'data' => $this->json($theme),
                'is_featured' => (bool) $theme['featured'], 'is_premium' => $theme['price'] > 0, 'is_free' => $theme['price'] === 0,
                'order' => $order, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);
            $templateId = DB::table('templates')->where('slug', $theme['slug'])->value('id');
            foreach ($theme['featureKeys'] as $feature) {
                DB::table('theme_features')->updateOrInsert(['slug' => $feature], ['name' => Str::headline($feature), 'created_at' => $now, 'updated_at' => $now]);
                $featureId = DB::table('theme_features')->where('slug', $feature)->value('id');
                DB::table('template_theme_feature')->updateOrInsert(['template_id' => $templateId, 'theme_feature_id' => $featureId]);
            }
        }

        foreach (config('projects') as $order => $project) {
            $categoryId = $this->category('project_categories', $project['industry'], $project['industry_label'], $order, $now);
            DB::table('projects')->updateOrInsert(['slug' => $project['slug']], [
                'project_category_id' => $categoryId, 'code' => $project['code'], 'title' => $project['title'],
                'description' => $project['excerpt'], 'excerpt' => $project['excerpt'], 'image' => 'frontend/'.$project['image'],
                'category' => $project['category'], 'industry' => $project['industry'], 'year' => $project['year'],
                'client' => $project['client'], 'duration' => $project['duration'], 'website_type' => $project['website_type'],
                'challenge' => $project['challenge'], 'solution' => $project['solution'], 'gallery' => $this->json($project['gallery']),
                'results' => $this->json($project['results']), 'deliverables' => $this->json($project['deliverables']),
                'technologies' => $this->json($project['technologies']), 'data' => $this->json($project),
                'is_featured' => (bool) $project['featured'], 'is_active' => true, 'order' => $order,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        foreach (config('articles') as $article) {
            $categoryId = $this->category('post_categories', $article['category'], $article['category_label'], 0, $now);
            DB::table('posts')->updateOrInsert(['slug' => $article['slug']], [
                'category_id' => $categoryId, 'title' => $article['title'], 'summary' => $article['excerpt'],
                'content' => $this->json($article['sections']), 'featured_image' => 'frontend/'.$article['image'],
                'meta_title' => $article['title'].' | WebApp Bắc Ninh', 'meta_description' => $article['excerpt'],
                'read_time' => (int) $article['read_time'], 'is_featured' => (bool) $article['featured'],
                'is_published' => true, 'published_at' => Carbon::createFromFormat('d/m/Y', $article['published_at'])->startOfDay(),
                'data' => $this->json($article), 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        foreach (array_values(config('website_services')) as $order => $service) {
            DB::table('services')->updateOrInsert(['slug' => $service['slug']], [
                'title' => $service['title'], 'menu_key' => $service['menu_key'], 'eyebrow' => $service['eyebrow'],
                'highlight' => $service['highlight'], 'icon' => $service['icon'], 'description' => $service['description'],
                'content' => $this->json($service['features']), 'image' => 'frontend/'.$service['image'],
                'secondary_image' => 'frontend/'.$service['secondary_image'], 'price_from' => $service['price_from'],
                'timeline' => $service['timeline'], 'meta_title' => $service['meta_title'], 'meta_description' => $service['meta_description'],
                'data' => $this->json($service), 'order' => $order, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        foreach (array_values(config('operation_services')) as $order => $service) {
            $slug = pathinfo($service['route'], PATHINFO_FILENAME);
            DB::table('operation_services')->updateOrInsert(['slug' => $slug], [
                'title' => $service['title'], 'menu_key' => $service['menu_key'], 'eyebrow' => $service['eyebrow'],
                'highlight' => $service['highlight'], 'description' => $service['description'], 'icon' => $service['icon'],
                'image' => 'frontend/'.$service['image'], 'secondary_image' => 'frontend/'.$service['secondary_image'],
                'price_from' => $service['price_from'], 'cadence' => $service['cadence'], 'meta_title' => $service['meta_title'],
                'meta_description' => $service['meta_description'], 'data' => $this->json($service), 'order' => $order,
                'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        foreach (config('legal_pages') as $page) {
            DB::table('legal_pages')->updateOrInsert(['slug' => $page['slug']], [
                'title' => $page['title'], 'short_title' => $page['short_title'], 'description' => $page['description'],
                'icon' => $page['icon'], 'content_updated_at' => Carbon::createFromFormat('d/m/Y', $page['updated_at']),
                'data' => $this->json($page), 'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }

    private function category(string $table, string $slug, string $name, int $order, mixed $now): int
    {
        $values = ['name' => $name, 'created_at' => $now, 'updated_at' => $now];
        if ($table !== 'template_categories') {
            $values += ['order' => $order, 'is_active' => true];
        }
        DB::table($table)->updateOrInsert(['slug' => $slug], $values);

        return (int) DB::table($table)->where('slug', $slug)->value('id');
    }

    private function json(mixed $value): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
