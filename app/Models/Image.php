<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    /**
     * Relation for Slide and Product models
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function images()
    {
        return $this->morphTo();
    }
}
