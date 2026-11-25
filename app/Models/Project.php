<?php

namespace App\Models;

use App\Domain\Projects\Actions\GenerateProjectCode;
use App\Traits\HasCuratorGallery;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Project extends Model
{
    use HasCuratorGallery;
    use HasSlug;

    protected $fillable = [
        'project_category_id',
        'code',
        'title',
        'slug',
        'image_id',
        'gallery',
        'excerpt',
        'link',
        'year',
        'client',
        'duration',
        'cost',
        'challenge',
        'solution',
        'results',
        'deliverables',
        'meta_title',
        'meta_description',
        'is_featured',
        'is_active',
        'order',
    ];

    protected $casts = [
        'gallery' => 'array',
        'results' => 'array',
        'deliverables' => 'array',
        'year' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $project): void {
            if (blank($project->code)) {
                $project->code = app(GenerateProjectCode::class)->execute();
            }
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    /** Industries are shared with the theme catalog. */
    public function industries(): BelongsToMany
    {
        return $this->belongsToMany(TemplateCategory::class, 'project_template_category')->orderBy('name');
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class)->orderBy('order')->orderBy('name');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForCatalog(Builder $query): Builder
    {
        return $query->active()->with(['category', 'image'])->orderByDesc('is_featured')->orderBy('order')->orderBy('id');
    }

    /**
     * Other active projects, same group first, then featured ones.
     *
     * @return Collection<int, self>
     */
    public function related(int $limit = 3): Collection
    {
        return self::query()->forCatalog()->whereKeyNot($this->getKey())->get()
            ->sortByDesc(fn (self $project): int => ($project->project_category_id === $this->project_category_id ? 2 : 0) + (int) $project->is_featured)
            ->take($limit)
            ->values();
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn (): string => route('projects.show', $this->slug));
    }

    protected function challengeHtml(): Attribute
    {
        return Attribute::get(fn (): string => $this->richHtml($this->challenge));
    }

    protected function solutionHtml(): Attribute
    {
        return Attribute::get(fn (): string => $this->richHtml($this->solution));
    }

    /** Sanitized editor HTML; empty when the editor only holds blank paragraphs. */
    private function richHtml(?string $content): string
    {
        $html = (string) str($content ?? '')->sanitizeHtml();
        $text = html_entity_decode(strip_tags($html, ['img', 'hr', 'table']));

        return preg_replace('/[\s\x{00A0}]+/u', '', $text) === '' ? '' : $html;
    }

    protected function categorySlug(): Attribute
    {
        return Attribute::get(fn (): string => $this->category?->slug ?? 'du-an');
    }

    protected function categoryLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->category?->name ?? 'Dự án');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->image?->url ?? asset('images/project-corporate.webp'));
    }
}
