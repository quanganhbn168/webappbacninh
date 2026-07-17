<?php

namespace App\Console\Commands;

use App\Models\AdBanner;
use App\Models\OperationService;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Template;
use App\Models\TemplateCategory;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class ImportLegacyMedia extends Command
{
    protected $signature = 'media:import-legacy {--dry-run : Only report image paths that can be reviewed}';

    protected $description = 'Import legacy image paths into their Spatie Media Library collections without deleting legacy files';

    public function handle(): int
    {
        $this->components->info($this->option('dry-run')
            ? 'Đang kiểm tra ảnh legacy có thể import...'
            : 'Đang import ảnh legacy vào Spatie Media Library...');

        $imported = 0;

        $imported += $this->importFor(Post::class, [
            'featured_image' => 'featured',
            'og_image' => 'og',
        ]);
        $imported += $this->importFor(Project::class, ['image' => 'featured']);
        $imported += $this->importFor(Template::class, ['image' => 'featured']);
        $imported += $this->importFor(AdBanner::class, ['image' => 'featured']);
        $imported += $this->importFor(TemplateCategory::class, [
            'image' => 'featured',
            'og_image' => 'og',
        ]);
        $imported += $this->importFor(Service::class, [
            'image' => 'featured',
            'secondary_image' => 'gallery',
        ]);
        $imported += $this->importFor(OperationService::class, [
            'image' => 'featured',
            'secondary_image' => 'gallery',
        ]);

        $this->components->info($this->option('dry-run')
            ? "Tìm thấy {$imported} ảnh/path legacy."
            : "Đã import {$imported} ảnh vào Spatie Media Library.");

        return self::SUCCESS;
    }

    /**
     * @param  class-string<Model>  $modelClass
     * @param  array<string, string>  $fields
     */
    private function importFor(string $modelClass, array $fields): int
    {
        $count = 0;

        $modelClass::query()
            ->where(function ($query) use ($fields): void {
                foreach (array_keys($fields) as $field) {
                    $query->orWhereNotNull($field);
                }
            })
            ->eachById(function (Model $model) use ($fields, &$count): void {
                foreach ($fields as $field => $collection) {
                    $source = $model->getAttribute($field);

                    if (blank($source)) {
                        continue;
                    }

                    if ($this->option('dry-run')) {
                        $this->line('['.class_basename($model).":{$model->getKey()}.{$field}] {$source}");
                        $count++;

                        continue;
                    }

                    $before = $model->getMedia($collection)->count();
                    $model->importMediaFromLegacyPath($source, $collection);
                    $model->unsetRelation('media');
                    $count += (int) ($model->getMedia($collection)->count() > $before);
                }
            });

        return $count;
    }
}
