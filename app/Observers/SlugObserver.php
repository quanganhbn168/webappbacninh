<?php

namespace App\Observers;

use App\Domain\Content\ResolveUniqueSlug;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Service;
use App\Models\Slug;
use Illuminate\Database\Eloquent\Model;

class SlugObserver
{
    public function saving(Model $model): void
    {
        if ($model instanceof Post || $model instanceof PostCategory) {
            $value = $model->slug ?: ($model->title ?? $model->name ?? 'bai-viet');
            $model->slug = app(ResolveUniqueSlug::class)->execute($model, $value);
        }
    }

    public function saved(Model $model)
    {
        if ($this->isDedicatedServiceLanding($model)) {
            $this->deleteSlugEntry($model);

            return;
        }

        // Keep the central registry synchronized, including records missing an entry.
        if ($model->getAttribute('slug')) {
            $slugValue = $model->getAttribute('slug');

            Slug::updateOrCreate(
                [
                    'reference_id' => $model->getKey(),
                    'reference_type' => $model->getMorphClass(),
                ],
                [
                    'key' => $slugValue,
                ]
            );
        }
    }

    public function deleted(Model $model)
    {
        $this->deleteSlugEntry($model);
    }

    private function isDedicatedServiceLanding(Model $model): bool
    {
        if (! $model instanceof Service) {
            return false;
        }

        return collect(config('website_services'))->contains(
            fn (array $service): bool => $service['slug'] === $model->slug
        );
    }

    private function deleteSlugEntry(Model $model): void
    {
        Slug::where('reference_id', $model->getKey())
            ->where('reference_type', $model->getMorphClass())
            ->delete();
    }
}
