<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Công nghệ',
            'Thiết kế wweb',
            'Tin tức',
            'Hướng dẫn',
            'Thủ thuật',
            'PHP',
            'Laravel',
            'Javascript',
            'HTML/CSS',
            'Bắc Ninh'
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate([
                'name' => $tag,
                'slug' => Str::slug($tag)
            ]);
        }
    }
}
