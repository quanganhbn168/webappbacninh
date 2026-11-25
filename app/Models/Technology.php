<?php

namespace App\Models;

use App\Support\Icons;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\HtmlString;

class Technology extends Model
{
    protected $fillable = ['name', 'icon', 'color', 'image_id', 'order'];

    protected $casts = [
        'order' => 'integer',
    ];

    protected static function booted(): void
    {
        // New entries go to the end of the list unless a position is given.
        static::creating(function (self $technology): void {
            $technology->order ??= (int) self::query()->max('order') + 10;
        });
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public static function findOrCreateByName(string $name): self
    {
        return self::query()->firstOrCreate(['name' => trim($name)]);
    }

    /** The uploaded logo, otherwise the built-in icon in the brand colour; empty when neither is set. */
    public function logoHtml(string $size = '1.25rem'): HtmlString
    {
        $style = 'width:'.$size.';height:'.$size.';flex:none';

        if ($this->image) {
            return new HtmlString('<img src="'.e($this->image->url).'" alt="" style="'.$style.';object-fit:contain" loading="lazy">');
        }

        $color = preg_match('/^#[0-9a-f]{3,8}$/i', (string) $this->color) ? ';color:'.$this->color : '';

        return new HtmlString(str_replace('<svg ', '<svg style="'.$style.$color.'" ', (string) Icons::svg($this->icon)));
    }
}
