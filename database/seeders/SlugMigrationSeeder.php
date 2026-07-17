<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;
use App\Models\TemplateCategory;
use App\Models\Slug;
use App\Models\Service;
use App\Models\ServiceCategory;

class SlugMigrationSeeder extends Seeder
{
    public function run()
    {
        // Migrate Service Categories before services so the category URLs are available first.
        $serviceCategories = ServiceCategory::all();
        foreach ($serviceCategories as $category) {
            if ($category->slug) {
                Slug::updateOrCreate(
                    [
                        'reference_id' => $category->id,
                        'reference_type' => ServiceCategory::class,
                    ],
                    ['key' => $category->slug]
                );
            }
        }

        // Migrate Services
        $dedicatedLandingSlugs = collect(config('website_services'))->pluck('slug')->all();
        $services = Service::all();
        foreach ($services as $service) {
            if (in_array($service->slug, $dedicatedLandingSlugs, true)) {
                Slug::where('reference_id', $service->id)
                    ->where('reference_type', Service::class)
                    ->delete();

                continue;
            }

            if ($service->slug) {
                Slug::updateOrCreate(
                    [
                        'reference_id' => $service->id,
                        'reference_type' => Service::class,
                    ],
                    ['key' => $service->slug]
                );
            }
        }

        // Migrate Template Categories
        $categories = TemplateCategory::all();
        foreach ($categories as $category) {
            if ($category->slug) {
                Slug::firstOrCreate(
                    [
                        'reference_id' => $category->id,
                        'reference_type' => TemplateCategory::class,
                    ],
                    ['key' => $category->slug]
                );
            }
        }

        // Migrate Templates
        $templates = Template::all();
        foreach ($templates as $template) {
            if ($template->slug) {
                Slug::firstOrCreate(
                    [
                        'reference_id' => $template->id,
                        'reference_type' => Template::class,
                    ],
                    ['key' => $template->slug]
                );
            }
        }

        // Migrate Post Categories
        $postCategories = \App\Models\PostCategory::all();
        foreach ($postCategories as $category) {
            if ($category->slug) {
                Slug::firstOrCreate(
                    [
                        'reference_id' => $category->id,
                        'reference_type' => \App\Models\PostCategory::class,
                    ],
                    ['key' => $category->slug]
                );
            }
        }

        // Migrate Posts
        $posts = \App\Models\Post::all();
        foreach ($posts as $post) {
            if ($post->slug) {
                Slug::firstOrCreate(
                    [
                        'reference_id' => $post->id,
                        'reference_type' => \App\Models\Post::class,
                    ],
                    ['key' => $post->slug]
                );
            }
        }
        
        $this->command->info('Slugs migrated successfully!');
    }
}
