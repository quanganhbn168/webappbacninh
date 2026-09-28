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
