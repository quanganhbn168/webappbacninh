<?php

namespace App\Domain\Navigation;

use App\Models\Menu;

/**
 * The navigation shipped with the approved interface. Used to seed a fresh
 * install; afterwards the menus are edited under Nội dung → Menu.
 */
final class DefaultMenus
{
    /**
     * @return array<string, array{name: string, items: array<int, array<string, mixed>>}>
     */
    public static function definitions(): array
    {
        return [
            Menu::HEADER => ['name' => 'Menu chính', 'items' => [
                ['title' => 'Trang chủ', 'url' => '/'],
                ['title' => 'Dịch vụ', 'url' => '/dich-vu', 'children' => [
                    ['title' => 'Thiết kế Website', 'url' => '/dich-vu#thiet-ke-website', 'icon' => 'monitor'],
                    ['title' => 'Phần mềm doanh nghiệp', 'url' => '/dich-vu#phan-mem', 'icon' => 'brain'],
                    ['title' => 'CRM & Booking', 'url' => '/dich-vu#crm-booking', 'icon' => 'calendar-check'],
                    ['title' => 'Landing Page', 'url' => '/dich-vu#bang-gia', 'icon' => 'app-window'],
                    ['title' => 'SEO & Nội dung', 'url' => '/dich-vu#seo-quang-cao', 'icon' => 'rocket'],
                    ['title' => 'Quảng cáo & Tracking', 'url' => '/dich-vu#seo-quang-cao', 'icon' => 'megaphone'],
                    ['title' => 'Hosting / Domain / Email', 'url' => '/hosting-domain-email', 'icon' => 'server'],
                    ['title' => 'Chăm sóc & Vận hành Website', 'url' => '/dich-vu#quy-trinh', 'icon' => 'settings'],
                ]],
                ['title' => 'Giải pháp', 'url' => '/giai-phap'],
                ['title' => 'Sản phẩm', 'url' => '/san-pham'],
                ['title' => 'Dự án', 'url' => '/du-an'],
                ['title' => 'Bảng giá', 'url' => '/bang-gia'],
                ['title' => 'Blog', 'url' => '/kien-thuc'],
                ['title' => 'Liên hệ', 'url' => '/lien-he'],
            ]],
            Menu::FOOTER_SERVICES => ['name' => 'Dịch vụ', 'items' => [
                ['title' => 'Thiết kế Website', 'url' => '/dich-vu#thiet-ke-website'],
                ['title' => 'Phần mềm doanh nghiệp', 'url' => '/dich-vu#phan-mem'],
                ['title' => 'CRM & Booking', 'url' => '/dich-vu#crm-booking'],
                ['title' => 'SEO & Nội dung', 'url' => '/dich-vu#seo-quang-cao'],
                ['title' => 'Quảng cáo & Tracking', 'url' => '/dich-vu#seo-quang-cao'],
                ['title' => 'Chăm sóc & Vận hành', 'url' => '/dich-vu#quy-trinh'],
            ]],
            Menu::FOOTER_PRODUCTS => ['name' => 'Sản phẩm', 'items' => [
                ['title' => 'Website Spa', 'url' => '/san-pham'],
                ['title' => 'Website Du lịch', 'url' => '/san-pham'],
                ['title' => 'Website Nội thất', 'url' => '/san-pham'],
                ['title' => 'Website PCCC', 'url' => '/san-pham'],
                ['title' => 'CRM', 'url' => '/san-pham'],
                ['title' => 'Booking System', 'url' => '/san-pham'],
                ['title' => 'Mini ERP', 'url' => '/san-pham'],
            ]],
            Menu::FOOTER_ABOUT => ['name' => 'Về chúng tôi', 'items' => [
                ['title' => 'Giới thiệu', 'url' => '/gioi-thieu'],
                ['title' => 'Dự án tiêu biểu', 'url' => '/du-an'],
                ['title' => 'Bảng giá', 'url' => '/bang-gia'],
                ['title' => 'Blog - Kiến thức', 'url' => '/kien-thuc'],
                ['title' => 'Hợp tác Agency', 'url' => '/hop-tac-agency'],
                ['title' => 'Liên hệ', 'url' => '/lien-he'],
            ]],
        ];
    }

    /**
     * Creates each menu that does not exist yet; existing menus are left untouched.
     */
    public static function install(): void
    {
        foreach (self::definitions() as $location => $definition) {
            if (Menu::query()->where('location', $location)->exists()) {
                continue;
            }

            $menu = Menu::query()->create(['name' => $definition['name'], 'location' => $location, 'is_active' => true]);
            self::createItems($menu, $definition['items']);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private static function createItems(Menu $menu, array $items, ?int $parentId = null): void
    {
        foreach ($items as $position => $item) {
            $created = $menu->allItems()->create([
                'parent_id' => $parentId,
                'title' => $item['title'],
                'url' => $item['url'],
                'icon' => $item['icon'] ?? null,
                'position' => $position,
                'is_active' => true,
            ]);

            self::createItems($menu, $item['children'] ?? [], $created->id);
        }
    }
}
