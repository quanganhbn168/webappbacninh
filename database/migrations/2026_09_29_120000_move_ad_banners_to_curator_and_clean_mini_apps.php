<?php

use App\Domain\Media\Actions\ImportLocalImage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ad banners use the Curator library like every other image, and the mini apps
 * (listed on /cong-cu) point at the real tool pages.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_banners', function (Blueprint $table): void {
            $table->foreignId('image_id')->nullable()->after('slot')->constrained('curator')->nullOnDelete();
        });

        // Demo row from the old seeder (random Unsplash image, link "#").
        DB::table('ad_banners')->where('image', 'like', '%source.unsplash.com%')->delete();

        $images = app(ImportLocalImage::class);
        foreach (DB::table('ad_banners')->get() as $banner) {
            $media = Schema::hasTable('media')
                ? DB::table('media')->where('model_type', 'App\\Models\\AdBanner')->where('model_id', $banner->id)->where('collection_name', 'featured')->first()
                : null;
            $path = $media ? '/storage/'.$media->id.'/'.$media->file_name : $banner->image;
            $imageId = $images->execute($path, 'banners', $banner->alt_text ?: $banner->name)?->id;
            DB::table('ad_banners')->where('id', $banner->id)->update(['image_id' => $imageId]);
        }

        Schema::table('ad_banners', fn (Blueprint $table) => $table->dropColumn('image'));

        $tools = [
            '/anh-cover' => ['name' => 'Lấy ảnh cover video', 'icon' => 'image', 'description' => 'Lấy ảnh thumbnail chất lượng cao từ video YouTube, TikTok, hỗ trợ tải hàng loạt.', 'badge' => 'Miễn phí'],
            '/tinh-thue-tncn' => ['name' => 'Tính thuế TNCN', 'icon' => 'calculator', 'description' => 'Tính thuế thu nhập cá nhân theo biểu 5 bậc mới (2026) và 7 bậc (2025).', 'badge' => null],
            '/tinh-thue-ho-kinh-doanh' => ['name' => 'Tính thuế hộ kinh doanh', 'icon' => 'store', 'description' => 'Tính thuế GTGT và TNCN cho hộ kinh doanh theo ngành nghề, ngưỡng miễn thuế 2026.', 'badge' => 'Mới'],
            '/tinh-thue-doanh-nghiep' => ['name' => 'Tính thuế doanh nghiệp', 'icon' => 'building-2', 'description' => 'Tính thuế TNDN cho doanh nghiệp nhỏ và vừa (15% / 17% / 20%).', 'badge' => 'Mới'],
            '/tools/ngan-hang' => ['name' => 'Tạo QR ngân hàng', 'icon' => 'landmark', 'description' => 'Tạo mã QR chuyển khoản VietQR kèm số tiền và nội dung.', 'badge' => 'Hot'],
            '/tools/qr-code' => ['name' => 'Tạo QR code', 'icon' => 'qr-code', 'description' => 'Tạo mã QR cho đường link, văn bản, WiFi và tải về ảnh PNG.', 'badge' => null],
            '/tools/calendar' => ['name' => 'Lịch vạn niên', 'icon' => 'calendar-days', 'description' => 'Xem lịch âm dương, đổi ngày âm – dương và năm can chi.', 'badge' => null],
            '/tools/vong-quay-an-trua' => ['name' => 'Vòng quay ăn trưa', 'icon' => 'utensils', 'description' => 'Không biết trưa nay ăn gì? Để vòng quay chọn giúp bạn.', 'badge' => 'Vui'],
        ];
        DB::table('mini_apps')->where('link', 'tools/cover')->update(['link' => '/anh-cover']);
        // Entries without a real page ("#", "#register-section") are hidden rather than deleted.
        DB::table('mini_apps')->where(fn ($query) => $query->where('link', 'like', '#%')->orWhereNull('link'))->update(['is_active' => false]);
        $order = 1;
        foreach ($tools as $link => $tool) {
            DB::table('mini_apps')->updateOrInsert(['link' => $link], $tool + ['is_active' => true, 'order' => $order++, 'updated_at' => now(), 'created_at' => now()]);
        }
    }

    public function down(): void
    {
        Schema::table('ad_banners', function (Blueprint $table): void {
            $table->string('image')->nullable();
            $table->dropConstrainedForeignId('image_id');
        });
    }
};
