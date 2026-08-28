<?php

namespace Database\Seeders;

use App\Support\FrontendMenuCache;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $menus = [
            'header' => 'Menu chính',
            'footer_website' => 'Footer - Thiết kế website',
            'footer_operations' => 'Footer - Dịch vụ duy trì',
            'footer_information' => 'Footer - Thông tin',
        ];

        foreach ($menus as $location => $name) {
            DB::table('menus')->updateOrInsert(['location' => $location], compact('name') + ['is_active' => true, 'created_at' => $now, 'updated_at' => $now]);
        }

        $header = DB::table('menus')->where('location', 'header')->value('id');
        $items = [
            ['title' => 'Trang chủ', 'route_name' => 'home'],
            ['title' => 'Thiết kế website', 'route_name' => 'services.index', 'children' => [
                ['title' => 'Tổng quan dịch vụ', 'route_name' => 'services.index'],
                ['title' => 'Website doanh nghiệp', 'route_name' => 'services.show', 'route_parameter' => 'website-doanh-nghiep'],
                ['title' => 'Website bán hàng', 'route_name' => 'services.show', 'route_parameter' => 'website-ban-hang'],
                ['title' => 'Landing page', 'route_name' => 'services.show', 'route_parameter' => 'landing-page'],
                ['title' => 'Thiết kế lại website', 'route_name' => 'services.show', 'route_parameter' => 'thiet-ke-lai-website'],
            ]],
            ['title' => 'Kho giao diện', 'route_name' => 'themes.index'],
            ['title' => 'Dịch vụ vận hành', 'route_name' => 'operations.index', 'children' => [
                ['title' => 'Hosting và bảo trì', 'route_name' => 'operations.show', 'route_parameter' => 'hosting-bao-tri-website'],
                ['title' => 'Quản trị và đăng bài', 'route_name' => 'operations.show', 'route_parameter' => 'quan-tri-dang-bai-website'],
                ['title' => 'SEO website', 'route_name' => 'operations.show', 'route_parameter' => 'seo-website'],
                ['title' => 'Nội dung Facebook', 'route_name' => 'operations.show', 'route_parameter' => 'noi-dung-facebook'],
                ['title' => 'Nâng cấp và tích hợp', 'route_name' => 'operations.show', 'route_parameter' => 'nang-cap-tich-hop-website'],
                ['title' => 'Đo lường và báo cáo', 'route_name' => 'operations.show', 'route_parameter' => 'do-luong-bao-cao-website'],
            ]],
            ['title' => 'Dự án', 'route_name' => 'projects.index'],
            ['title' => 'Bảng giá', 'route_name' => 'pricing'],
            ['title' => 'Hợp tác Agency', 'route_name' => 'agency'],
            ['title' => 'Kiến thức', 'route_name' => 'articles.index'],
            ['title' => 'Giới thiệu', 'route_name' => 'about'],
            ['title' => 'Liên hệ', 'route_name' => 'contact'],
        ];
        $this->seedItems($header, $items, null, $now);

        $footerGroups = [
            'footer_website' => array_slice($items[1]['children'], 1),
            'footer_operations' => $items[3]['children'],
            'footer_information' => [$items[8], $items[4], $items[5], $items[6], $items[7]],
        ];
        foreach ($footerGroups as $location => $groupItems) {
            $menuId = DB::table('menus')->where('location', $location)->value('id');
            $this->seedItems($menuId, $groupItems, null, $now);
        }

        foreach (array_keys($menus) as $location) {
            app(FrontendMenuCache::class)->forget($location);
        }
    }

    private function seedItems(int $menuId, array $items, ?int $parentId, mixed $now): void
    {
        foreach ($items as $position => $item) {
            $children = $item['children'] ?? [];
            unset($item['children']);
            $identity = ['menu_id' => $menuId, 'parent_id' => $parentId, 'title' => $item['title']];
            DB::table('menu_items')->updateOrInsert($identity, $item + ['position' => $position, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]);
            $id = DB::table('menu_items')->where($identity)->value('id');
            if ($children) {
                $this->seedItems($menuId, $children, $id, $now);
            }
        }
    }
}
