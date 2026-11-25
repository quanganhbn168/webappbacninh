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

    /**
     * The uploaded logo, otherwise the built-in icon in the brand colour; empty when neither is set.
     * Always an <img>: the admin's HTML sanitizer removes inline <svg>.
     */
    public function logoHtml(string $size = '1.25rem'): HtmlString
    {
        $src = $this->image?->url ?? $this->iconDataUri();

        if ($src === null) {
            return new HtmlString('');
        }

        return new HtmlString('<img src="'.e($src).'" alt="" style="width:'.$size.';height:'.$size.';flex:none;object-fit:contain">');
    }

    private function iconDataUri(): ?string
    {
        if (! Icons::has($this->icon)) {
            return null;
        }

        // An image cannot inherit the text colour, so it is written into the SVG.
        $color = preg_match('/^#[0-9a-f]{3,8}$/i', (string) $this->color) ? $this->color : '#374151';
        $svg = str_replace(
            ['<svg ', 'currentColor'],
            ['<svg xmlns="http://www.w3.org/2000/svg" ', $color],
            (string) Icons::svg($this->icon),
        );

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }
}
