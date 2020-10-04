<?php

namespace App\Observers\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait SetSlug
{
    /**
     * Sets slug to current entity
     *
     * @param Model $model
     * @return string
     */
    protected function setSlug(Model $model)
    {
        if ($model->isDirty('slug')) {
            return $model->slug = Str::slug($model->slug);
        }

        return $model->slug = Str::slug($model->name);
    }
}