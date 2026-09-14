<?php

namespace App\Domain\Content;

use App\Models\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ResolveUniqueSlug
{
    public function execute(Model $model, string $value): string
    {
        $base = Str::slug(Str::limit($value, 220, '')) ?: 'bai-viet';
        $candidate = $base;
        for ($suffix = 2; $this->exists($model, $candidate); $suffix++) {
            $candidate = $base.'-'.$suffix;
        }

        return $candidate;
    }

    private function exists(Model $model, string $key): bool
    {
        $registry = Slug::where('key', $key);
        if ($model->exists) {
            $registry->where(fn ($query) => $query->whereNull('reference_type')
                ->orWhere('reference_type', '!=', $model->getMorphClass())
                ->orWhere('reference_id', '!=', $model->getKey()));
        }
        $records = $model->newQuery()->where('slug', $key);
        if ($model->exists) {
            $records->whereKeyNot($model->getKey());
        }

        return $registry->exists() || $records->exists();
    }
}
