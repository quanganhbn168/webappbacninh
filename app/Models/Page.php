<?php

namespace App\Models;

use App\Enums\PageTemplate;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasSlug;

    protected $fillable = [
        'title',
        'short_title',
        'slug',
        'template',
        'eyebrow',
        'summary',
        'notice',
        'icon',
        'content',
        'content_updated_at',
        'meta_title',
        'meta_description',
        'is_active',
        'order',
    ];

    protected $casts = [
        'template' => PageTemplate::class,
        'content' => 'array',
        'content_updated_at' => 'date',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function breadcrumbTitle(): string
    {
        return $this->short_title ?: $this->title;
    }

    /**
     * @return array<int, array{type: string, data: array<string, mixed>}>
     */
    public function blocks(): array
    {
        return array_values(array_filter($this->content ?? [], fn (mixed $block): bool => is_array($block) && isset($block['type'])));
    }

    /**
     * Section blocks with their 1-based anchor number, for the table of contents.
     *
     * @return array<int, string>
     */
    public function tableOfContents(): array
    {
        return collect($this->blocks())
            ->filter(fn (array $block): bool => $block['type'] === 'section' && filled($block['data']['heading'] ?? null))
            ->mapWithKeys(fn (array $block, int $index): array => [$index + 1 => $block['data']['heading']])
            ->all();
    }
}
