<?php

namespace Database\Seeders;

use App\Domain\Pages\SitePages;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * One SEO record per fixed page; records edited in the admin are kept.
     */
    public function run(): void
    {
        foreach (array_keys(SitePages::PAGES) as $key) {
            Page::query()->firstOrCreate(['key' => $key], ['title' => SitePages::title($key)]);
        }
    }
}
