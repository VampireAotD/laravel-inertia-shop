<?php

namespace App\Models\Traits;

trait DiffForHumansTimestampAttributes
{
    /**
     * Return understandable for humans date when entity was created at
     *
     * @return mixed
     */
    public function getCreatedDateAttribute()
    {
        return optional($this->created_at)->diffForHumans();
    }

    /**
     * Return understandable for humans date when entity was updated at
     *
     * @return mixed
     */
    public function getUpdatedDateAttribute()
    {
        return optional($this->updated_at)->diffForHumans();
    }
}