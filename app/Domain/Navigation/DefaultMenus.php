<?php

namespace App\Domain\Navigation;

use App\Models\Menu;
use Illuminate\Support\Arr;

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
                ['title' => 'Dịch vụ', 'url' => '/dich-vu', 'is_mega' => true, 'children' => [
                    ['title' => 'Thiết kế Website', 'url' => '/thiet-ke-website', 'icon' => 'monitor'],
                    ['title' => 'Phần mềm doanh nghiệp', 'url' => '/dich-vu/phan-mem', 'icon' => 'brain'],
                    ['title' => 'CRM & Booking', 'url' => '/dich-vu/crm-booking', 'icon' => 'calendar-check'],
                    ['title' => 'Landing Page', 'url' => '/thiet-ke-website/landing-page', 'icon' => 'app-window'],
                    ['title' => 'SEO & Nội dung', 'url' => '/dich-vu/seo-quang-cao', 'icon' => 'rocket'],
                    ['title' => 'Quảng cáo & Tracking', 'url' => '/dich-vu/seo-quang-cao', 'icon' => 'megaphone'],
                    ['title' => 'Hosting / Domain / Email', 'url' => '/hosting-domain-email', 'icon' => 'server'],
                    ['title' => 'Chăm sóc & Vận hành Website', 'url' => '/dich-vu-van-hanh', 'icon' => 'settings'],
                ]],
                ['title' => 'Giải pháp', 'url' => '/giai-phap'],
                ['title' => 'Sản phẩm', 'url' => '/san-pham'],
                ['title' => 'Kho giao diện', 'url' => '/kho-giao-dien', 'is_mega' => true, 'children' => [...self::designWays(), ...self::industries()]],
                ['title' => 'Dự án', 'url' => '/du-an'],
                ['title' => 'Bảng giá', 'url' => '/bang-gia'],
                ['title' => 'Công cụ', 'url' => '/cong-cu'],
                ['title' => 'Kiến thức', 'url' => '/kien-thuc'],
                ['title' => 'Liên hệ', 'url' => '/lien-he'],
            ]],
            Menu::FOOTER_SERVICES => ['name' => 'Dịch vụ', 'items' => [
                ['title' => 'Thiết kế Website', 'url' => '/thiet-ke-website'],
                ['title' => 'Phần mềm doanh nghiệp', 'url' => '/dich-vu/phan-mem'],
                ['title' => 'CRM & Booking', 'url' => '/dich-vu/crm-booking'],
                ['title' => 'SEO & Nội dung', 'url' => '/dich-vu/seo-quang-cao'],
                ['title' => 'Quảng cáo & Tracking', 'url' => '/dich-vu/seo-quang-cao'],
                ['title' => 'Chăm sóc & Vận hành', 'url' => '/dich-vu-van-hanh'],
            ]],
            Menu::FOOTER_PRODUCTS => ['name' => 'Sản phẩm', 'items' => [
                ['title' => 'Website Spa', 'url' => '/mau-website/spa'],
                ['title' => 'Website Du lịch', 'url' => '/mau-website/du-lich'],
                ['title' => 'Website Nội thất', 'url' => '/mau-website/noi-that'],
                ['title' => 'Website Nhà hàng', 'url' => '/mau-website/nha-hang'],
                ['title' => 'CRM & Booking', 'url' => '/dich-vu/crm-booking'],
                ['title' => 'Mini ERP', 'url' => '/dich-vu/phan-mem'],
                ['title' => 'Tất cả sản phẩm', 'url' => '/san-pham'],
            ]],
            Menu::FOOTER_ABOUT => ['name' => 'Về chúng tôi', 'items' => [
                ['title' => 'Giới thiệu', 'url' => '/gioi-thieu'],
                ['title' => 'Dự án tiêu biểu', 'url' => '/du-an'],
                ['title' => 'Bảng giá', 'url' => '/bang-gia'],
                ['title' => 'Kiến thức', 'url' => '/kien-thuc'],
                ['title' => 'Hợp tác Agency', 'url' => '/hop-tac-agency'],
                ['title' => 'Liên hệ', 'url' => '/lien-he'],
            ]],
        ];
    }

    /** @return array<int, array<string, string>> */
    private static function designWays(): array
    {
        return array_map(fn (array $way): array => $way + ['column_title' => 'Hình thức thiết kế'], [
            ['title' => 'Thiết kế theo mẫu có sẵn', 'url' => '/kho-giao-dien', 'icon' => 'layout-grid', 'description' => 'Chọn mẫu theo ngành, gửi logo và nội dung.', 'badge' => 'Nhanh nhất'],
            ['title' => 'Thiết kế theo mẫu bạn thích', 'url' => '/lien-he', 'icon' => 'link', 'description' => 'Gửi link website tham khảo, chúng tôi làm theo thương hiệu của bạn.'],
            ['title' => 'Thiết kế theo yêu cầu', 'url' => '/thiet-ke-website', 'icon' => 'pen-tool', 'description' => 'Giao diện riêng, chức năng theo quy trình vận hành.'],
        ]);
    }

    /** The industry pages of the theme catalog. @return array<int, array<string, string>> */
    private static function industries(): array
    {
        $industries = [
            'du-lich' => ['Du lịch', 'plane'],
            'dien-gia-dung' => ['Điện gia dụng', 'washing-machine'],
            'giao-duc' => ['Giáo dục', 'graduation-cap'],
            'may-moc-thiet-bi' => ['Máy móc - thiết bị', 'cog'],
            'nha-hang' => ['Nhà hàng - ẩm thực', 'utensils'],
            'noi-that' => ['Nội thất - xây dựng', 'sofa'],
            'san-xuat' => ['Sản xuất', 'factory'],
            'spa' => ['Spa - làm đẹp', 'flower-2'],
            'thuong-mai' => ['Thương mại', 'shopping-bag'],
        ];

        return array_map(fn (string $slug, array $industry): array => [
            'title' => $industry[0], 'url' => '/mau-website/'.$slug, 'icon' => $industry[1], 'column_title' => 'Theo ngành',
        ], array_keys($industries), $industries);
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
            $created = $menu->allItems()->create(Arr::except($item, 'children') + [
                'parent_id' => $parentId,
                'position' => $position,
                'is_active' => true,
            ]);

            self::createItems($menu, $item['children'] ?? [], $created->id);
        }
    }
}
