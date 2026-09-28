<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ThemeFeature extends Model
{
    protected $fillable = ['name', 'slug'];

    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(Template::class, 'template_theme_feature');
    }
}
