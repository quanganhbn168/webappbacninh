<?php

namespace App\Observers;

use App\Models\Slug;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;

class SlugObserver
{
    public function saved(Model $model)
    {
        if ($this->isDedicatedServiceLanding($model)) {
            $this->deleteSlugEntry($model);

            return;
        }

        // If the model has a 'slug' attribute and it has changed (or is new)
        // Note: We sync even if not dirty to ensure it exists in slugs table if missing
        if ($model->getAttribute('slug')) {
            $slugValue = $model->getAttribute('slug');
            
            // Check if this slug is already taken by ANOTHER entity in the slugs table
            // This is a safety check. Real validation should happen on Request validation level.
            // Here we might just auto-increment if strictly needed, or assume it's validated.
            // For now, let's updateOrInsert.
            
            Slug::updateOrCreate(
                [
                    'reference_id' => $model->getKey(),
                    'reference_type' => $model->getMorphClass(),
                ],
                [
                    'key' => $slugValue
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
