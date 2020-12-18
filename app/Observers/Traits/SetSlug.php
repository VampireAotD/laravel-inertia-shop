<?php

namespace App\Observers\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait SetSlug
{
    /**
     * Slug column
     *
     * @var string
     */
    protected $slugColumn = 'slug';

    /**
     * Name column
     *
     * @var string
     */
    protected $nameColumn = 'name';

    /**
     * Sets slug to current entity
     *
     * @param Model $model
     * @return void
     */
    protected function setSlug(Model $model)
    {
        if ($model->isDirty($this->slugColumn)) {
            $model->setAttribute($this->slugColumn, Str::slug($model->getAttribute($this->slugColumn)));
        }

        $model->setAttribute($this->slugColumn, Str::slug($model->getAttribute($this->nameColumn)));
    }
}
