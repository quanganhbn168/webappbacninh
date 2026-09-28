<?php

namespace App\Domain\Pages\Rules;

use App\Models\Page;
use App\Models\Slug;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * A page slug is served from the site root, so it must not collide with
 * another page, another record in the slug registry or a fixed route.
 */
final class AvailablePageSlug implements ValidationRule
{
    public function __construct(private readonly ?Page $page = null) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $slug = (string) $value;

        if ($slug === '' || Str::slug($slug) !== $slug) {
            $fail('Đường dẫn chỉ gồm chữ thường không dấu, số và dấu gạch ngang.');

            return;
        }

        $takenByPage = Page::query()->where('slug', $slug)->when($this->page, fn ($query) => $query->whereKeyNot($this->page->getKey()))->exists();
        $takenByRecord = Slug::query()->where('key', $slug)
            ->when($this->page, fn ($query) => $query->whereNot(fn ($query) => $query
                ->where('reference_type', $this->page->getMorphClass())
                ->where('reference_id', $this->page->getKey())))
            ->exists();

        if ($takenByPage || $takenByRecord || $this->isFixedRoute($slug)) {
            $fail('Đường dẫn này đã được sử dụng. Vui lòng chọn đường dẫn khác.');
        }
    }

    private function isFixedRoute(string $slug): bool
    {
        try {
            $route = Route::getRoutes()->match(Request::create('/'.$slug));
        } catch (HttpException) {
            return false;
        }

        if ($route->getName() === 'slug.handle') {
            return false;
        }

        // Named page routes (about, legal pages…) belong to the page with the same slug.
        return ! ($route->getActionName() === 'App\Http\Controllers\Frontend\PageController@show' && ($route->defaults['slug'] ?? null) === $slug);
    }
}
