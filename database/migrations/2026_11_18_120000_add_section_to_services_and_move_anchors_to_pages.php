<?php

use App\Domain\Media\Actions\ImportLocalImage;
use App\Models\PricingPlan;
use App\Support\FrontendMenuCache;
use Database\Seeders\PricingPlanSeeder;
use Database\Seeders\ServiceSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Services outside website design get their own pages under /dich-vu/{slug}
 * instead of anchors on /dich-vu, the Hosting add-ons become editable price
 * plans, and menu links that pointed at the old anchors move to the new pages.
 */
return new class extends Migration
{
    private const MENU_URLS = [
        '/dich-vu#thiet-ke-website' => '/thiet-ke-website',
        '/dich-vu#phan-mem' => '/dich-vu/phan-mem',
        '/dich-vu#crm-booking' => '/dich-vu/crm-booking',
        '/dich-vu#seo-quang-cao' => '/dich-vu/seo-quang-cao',
        '/dich-vu#quy-trinh' => '/dich-vu-van-hanh',
    ];

    public function up(): void
    {
        if (! Schema::hasColumn('services', 'section')) {
            Schema::table('services', function (Blueprint $table): void {
                $table->string('section', 20)->default('website')->after('is_landing')->index();
            });
        }

        // Only the new rows: pages and plans an admin has deleted must stay deleted.
        (new ServiceSeeder)->run(app(ImportLocalImage::class), ['phan-mem', 'crm-booking', 'seo-quang-cao']);
        foreach (['Tên miền', 'Email doanh nghiệp', 'SSL cao cấp', 'Sao lưu dữ liệu'] as $name) {
            if (! PricingPlan::query()->where('group', 'hosting_addon')->where('name', $name)->exists()) {
                $this->seedHostingAddon($name);
            }
        }

        $this->moveMenuLinks();
    }

    public function down(): void {}

    private function seedHostingAddon(string $name): void
    {
        $plan = collect((new PricingPlanSeeder)->plans())
            ->first(fn (array $row): bool => $row['group'] === 'hosting_addon' && $row['name'] === $name);

        if ($plan) {
            PricingPlan::query()->create($plan + ['order' => 100, 'is_active' => true]);
        }
    }

    private function moveMenuLinks(): void
    {
        foreach (self::MENU_URLS as $old => $new) {
            DB::table('menu_items')->where('url', $old)->update(['url' => $new]);
        }
        // "Landing Page" pointed at the price table; it has a page of its own.
        DB::table('menu_items')->where('url', '/dich-vu#bang-gia')->where('title', 'Landing Page')->update(['url' => '/thiet-ke-website/landing-page']);

        DB::table('products')->where('detail_url', '/dich-vu#phan-mem')->update(['detail_url' => '/dich-vu/phan-mem']);

        app(FrontendMenuCache::class)->forgetAll();
    }
};
