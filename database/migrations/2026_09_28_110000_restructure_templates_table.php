<?php

use App\Domain\Media\Actions\ImportLocalImage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Moves template content out of the untyped `data` JSON into real columns
 * and moves images into the Curator library.
 */
return new class extends Migration
{
    private const FEATURE_NAMES = [
        'multilang' => 'Đa ngôn ngữ',
        'ecommerce' => 'Giỏ hàng - đặt hàng',
        'booking' => 'Đặt lịch - booking',
        'lead' => 'Form thu lead',
    ];

    public function up(): void
    {
        Schema::table('templates', function (Blueprint $table): void {
            $table->foreignId('image_id')->nullable()->after('slug')->constrained('curator')->nullOnDelete();
            $table->json('gallery')->nullable()->after('image_id');
            $table->json('tags')->nullable();
            $table->json('audiences')->nullable();
            $table->json('pages')->nullable();
            $table->json('included_features')->nullable();
            $table->json('customizations')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
        });

        $images = app(ImportLocalImage::class);

        DB::table('templates')->orderBy('id')->get()->each(function (object $template) use ($images): void {
            $data = json_decode((string) $template->data, true) ?: [];
            $content = json_decode((string) $template->content, true);
            $includedFeatures = is_array($content) && array_is_list($content) ? $content : ($data['includedFeatures'] ?? []);
            $categoryId = $template->template_category_id
                ?: DB::table('template_categories')->where('slug', $template->industry)->value('id');

            $imageId = $images->execute($template->image, 'showcase', $template->name)?->id;
            $gallery = collect($data['gallery'] ?? [])
                ->map(fn (string $file): ?int => $images->execute($this->legacyImagePath($file), 'showcase', $template->name)?->id)
                ->filter()->values()->all();

            DB::table('templates')->where('id', $template->id)->update([
                'template_category_id' => $categoryId,
                'image_id' => $imageId,
                'gallery' => json_encode($gallery),
                'tags' => json_encode($data['tags'] ?? [], JSON_UNESCAPED_UNICODE),
                'audiences' => json_encode($data['audiences'] ?? [], JSON_UNESCAPED_UNICODE),
                'pages' => json_encode($data['pages'] ?? [], JSON_UNESCAPED_UNICODE),
                'included_features' => json_encode($includedFeatures, JSON_UNESCAPED_UNICODE),
                'customizations' => json_encode($data['customizations'] ?? [], JSON_UNESCAPED_UNICODE),
                'meta_title' => $data['seo']['meta_title'] ?? null,
                'meta_description' => $data['seo']['meta_description'] ?? null,
            ]);
        });

        foreach (self::FEATURE_NAMES as $slug => $name) {
            DB::table('theme_features')->where('slug', $slug)->update(['name' => $name]);
        }

        Schema::table('templates', function (Blueprint $table): void {
            $table->dropColumn(['image', 'category', 'industry', 'content', 'data', 'is_premium', 'is_free']);
        });
    }

    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table): void {
            $table->string('image')->nullable();
            $table->string('category')->nullable();
            $table->string('industry')->nullable();
            $table->longText('content')->nullable();
            $table->json('data')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_free')->default(false);
        });

        Schema::table('templates', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('image_id');
            $table->dropColumn(['gallery', 'tags', 'audiences', 'pages', 'included_features', 'customizations', 'meta_title', 'meta_description']);
        });
    }

    private function legacyImagePath(string $file): string
    {
        return str_contains($file, '/') ? $file : 'frontend/assets/images/'.$file;
    }
};
