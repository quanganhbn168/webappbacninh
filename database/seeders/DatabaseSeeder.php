<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            PermissionSeeder::class,
            AdminSeeder::class,
            SettingsSeeder::class,
            MenuSeeder::class,
            FrontendDataSeeder::class,
            PageSeeder::class,
            TemplateSeeder::class,
            ProjectSeeder::class,
            PostSeeder::class,
            MediaSeeder::class,
            MiniAppSeeder::class,
            AdBannerSeeder::class,
            TagSeeder::class,
        ]);
    }
}
