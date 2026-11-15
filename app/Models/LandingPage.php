<?php

namespace App\Models;

use App\Enums\LandingTemplate;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class LandingPage extends Model
{
    protected $fillable = [
        'slug',
        'template',
        'name',
        'is_active',
        'noindex',
        'meta_title',
        'meta_description',
        'price',
        'compare_price',
        'content',
    ];

    protected $casts = [
        'template' => LandingTemplate::class,
        'content' => 'array',
        'is_active' => 'boolean',
        'noindex' => 'boolean',
        'price' => 'integer',
        'compare_price' => 'integer',
    ];

    /** @var Collection<int, Media>|null */
    private ?Collection $media = null;

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function url(): string
    {
        return route('landing.show', $this->slug);
    }

    /**
     * The stored content laid over the template defaults, so a field added to a
     * template later still has a value on landing pages saved before it existed.
     *
     * @return array<string, mixed>
     */
    public function data(): array
    {
        $stored = array_filter($this->content ?? [], fn (mixed $value): bool => $value !== null && $value !== '');

        return array_merge($this->template->defaults(), $stored);
    }

    /** Curator image chosen for `$key` (for example `hero_image`), or the bundled sample file. */
    public function image(string $key, string $fallback): string
    {
        $id = $this->data()[$key] ?? null;

        if (! $id) {
            return asset($fallback);
        }

        $this->media ??= Media::query()
            ->whereIn('id', collect($this->data())->filter(fn (mixed $value, string $name): bool => str_ends_with($name, '_image') && is_numeric($value))->values())
            ->get()
            ->keyBy('id');

        return $this->media->get((int) $id)?->url ?? asset($fallback);
    }

    /** 3.990.000 — the digits only, so a template can place the currency mark itself. */
    public function money(?int $amount = null): string
    {
        return number_format($amount ?? $this->price, 0, ',', '.');
    }

    /** Percent saved against the compare-at price, or null when there is no discount to show. */
    public function discountPercent(): ?int
    {
        if (! $this->compare_price || $this->compare_price <= $this->price) {
            return null;
        }

        return (int) round((1 - $this->price / $this->compare_price) * 100);
    }
}
