<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PostCategory;

class PostCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Tin tức', 'slug' => 'tin-tuc', 'description' => 'Tin tức mới nhất', 'color' => '#17a2b8', 'order' => 1],
            ['name' => 'Hướng dẫn', 'slug' => 'huong-dan', 'description' => 'Bài viết hướng dẫn chi tiết', 'color' => '#28a745', 'order' => 2],
            ['name' => 'Công nghệ', 'slug' => 'cong-nghe', 'description' => 'Xu hướng công nghệ mới', 'color' => '#007bff', 'order' => 3],
            ['name' => 'Kinh doanh', 'slug' => 'kinh-doanh', 'description' => 'Chia sẻ về kinh doanh', 'color' => '#ffc107', 'order' => 4],
            ['name' => 'Sự kiện', 'slug' => 'su-kien', 'description' => 'Thông báo sự kiện', 'color' => '#dc3545', 'order' => 5],
        ];

        foreach ($categories as $category) {
            PostCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
