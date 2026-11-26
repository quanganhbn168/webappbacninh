<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * The content of a new install. The administrator is created separately:
     * php artisan app:setup-admin
     */
    public function run(): void
    {

        $this->call([
            SettingsSeeder::class,
            MenuSeeder::class,
            ServiceSeeder::class,
            OperationServiceSeeder::class,
            PageSeeder::class,
            TemplateSeeder::class,
            TechnologySeeder::class,
            ProjectSeeder::class,
            PostSeeder::class,
            ProductSeeder::class,
            PricingPlanSeeder::class,
            TestimonialSeeder::class,
            MiniAppSeeder::class,
        ]);
    }
}
