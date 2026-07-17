<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slug extends Model
{
    protected $fillable = ['key', 'reference_id', 'reference_type'];

    public function reference()
    {
        return $this->morphTo();
    }
}
