<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tag extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function posts(): \Illuminate\Database\Eloquent\Relations\MorphedByMany
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function templates(): \Illuminate\Database\Eloquent\Relations\MorphedByMany
    {
        return $this->morphedByMany(Template::class, 'taggable');
    }
}
