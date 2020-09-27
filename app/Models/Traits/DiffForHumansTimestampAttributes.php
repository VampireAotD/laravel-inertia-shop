<?php

namespace App\Models\Traits;

trait DiffForHumansCreatedAtAttribute
{
    public function getCreatedDateAttribute()
    {
        return optional($this->created_at)->diffForHumans();
    }
}