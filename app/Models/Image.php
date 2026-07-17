<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'order',
    ];

    /**
     * Get the parent imageable model (user, post, etc).
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}
