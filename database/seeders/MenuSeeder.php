<?php

namespace Database\Seeders;

use App\Domain\Navigation\DefaultMenus;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DefaultMenus::install();
    }
}
