<?php

use App\Support\ShieldPermissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('short_title')->nullable();
            $table->string('slug')->unique();
            $table->string('template')->default('document');
            $table->string('eyebrow')->nullable();
            $table->text('summary')->nullable();
            $table->text('notice')->nullable();
            $table->string('icon')->nullable();
            $table->json('content')->nullable();
            $table->date('content_updated_at')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        if (Schema::hasTable('legal_pages')) {
            DB::table('legal_pages')->orderBy('id')->get()->each(function (object $legal): void {
                $data = json_decode((string) $legal->data, true) ?: [];

                DB::table('pages')->insert([
                    'title' => $legal->title,
                    'short_title' => $legal->short_title,
                    'slug' => $legal->slug,
                    'template' => 'document',
                    'eyebrow' => 'THÔNG TIN VÀ CHÍNH SÁCH',
                    'summary' => $data['intro'] ?? null,
                    'notice' => $data['notice'] ?? null,
                    'icon' => $legal->icon,
                    'content' => json_encode(self::sectionBlocks($data['sections'] ?? []), JSON_UNESCAPED_UNICODE),
                    'content_updated_at' => $legal->content_updated_at,
                    'meta_description' => $legal->description,
                    'is_active' => $legal->is_active,
                    'created_at' => $legal->created_at,
                    'updated_at' => $legal->updated_at,
                ]);
            });

            Schema::drop('legal_pages');
        }

        ShieldPermissions::grantToSuperAdmin(['Page']);
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
        ShieldPermissions::revoke(['Page']);
    }

    /**
     * @param  array<int, array{title?: string, content?: string, items?: array<int, string>}>  $sections
     * @return array<int, array{type: string, data: array<string, string>}>
     */
    private static function sectionBlocks(array $sections): array
    {
        return array_map(fn (array $section): array => [
            'type' => 'section',
            'data' => [
                'heading' => $section['title'] ?? '',
                'content' => $section['content'] ?? '',
                'items' => implode("\n", $section['items'] ?? []),
            ],
        ], $sections);
    }
};
