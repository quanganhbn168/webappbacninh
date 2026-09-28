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
            ServiceSeeder::class,
            OperationServiceSeeder::class,
            PageSeeder::class,
            TemplateSeeder::class,
            ProjectSeeder::class,
            PostSeeder::class,
            MiniAppSeeder::class,
            AdBannerSeeder::class,
            TagSeeder::class,
        ]);
    }
}
