<?php

namespace App\Domain\Content;

use App\Models\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
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

        return in_array($key, self::reserved(), true) || $registry->exists() || $records->exists();
    }

    /**
     * First path segments of the site's own routes (/blog, /lien-he, /du-an…): a post or
     * category served from the root with one of these slugs would never be reached.
     *
     * @return array<int, string>
     */
    public static function reserved(): array
    {
        return collect(Route::getRoutes()->getRoutes())
            ->reject(fn ($route): bool => $route->getName() === 'slug.handle')
            ->map(fn ($route): string => explode('/', trim($route->uri(), '/'))[0])
            ->reject(fn (string $segment): bool => $segment === '' || str_starts_with($segment, '{'))
            ->unique()->values()->all();
    }
}
