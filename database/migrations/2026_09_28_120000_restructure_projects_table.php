<?php

use App\Domain\Media\Actions\ImportLocalImage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Project categories become project groups (the filter tabs on /du-an),
 * the customer's industry becomes plain text, images move to Curator and
 * the untyped `data` JSON is dropped.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->foreignId('image_id')->nullable()->after('slug')->constrained('curator')->nullOnDelete();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
        });

        $images = app(ImportLocalImage::class);
        $usedCategoryIds = [];

        DB::table('projects')->orderBy('id')->get()->each(function (object $project) use ($images, &$usedCategoryIds): void {
            $data = json_decode((string) $project->data, true) ?: [];
            $groupSlug = $project->category ?: ($data['category'] ?? 'du-an');
            $groupName = $data['category_label'] ?? str($groupSlug)->replace('-', ' ')->title()->toString();

            DB::table('project_categories')->updateOrInsert(['slug' => $groupSlug], ['name' => $groupName, 'updated_at' => now()]);
            $groupId = (int) DB::table('project_categories')->where('slug', $groupSlug)->value('id');
            $usedCategoryIds[] = $groupId;

            $gallery = collect(json_decode((string) $project->gallery, true) ?: [])
                ->map(fn (string $path): ?int => $images->execute($this->legacyPath($path), 'projects', $project->title)?->id)
                ->filter()->values()->all();

            DB::table('projects')->where('id', $project->id)->update([
                'project_category_id' => $groupId,
                'industry' => $data['industry_label'] ?? $project->industry,
                'excerpt' => $project->description ?: $project->excerpt,
                'image_id' => $images->execute($this->legacyPath((string) $project->image), 'projects', $project->title)?->id,
                'gallery' => json_encode($gallery),
                'meta_title' => $data['seo']['meta_title'] ?? null,
                'meta_description' => $data['seo']['meta_description'] ?? null,
            ]);
        });

        // Industry rows from the old seeder that no project uses as a group any more.
        DB::table('project_categories')->whereNotIn('id', $usedCategoryIds)->delete();

        Schema::table('projects', function (Blueprint $table): void {
            $table->dropColumn(['description', 'image', 'category', 'data']);
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('category')->nullable();
            $table->json('data')->nullable();
        });

        Schema::table('projects', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('image_id');
            $table->dropColumn(['meta_title', 'meta_description']);
        });
    }

    private function legacyPath(string $path): string
    {
        return str_starts_with($path, 'frontend/') ? $path : 'frontend/'.ltrim($path, '/');
    }
};
