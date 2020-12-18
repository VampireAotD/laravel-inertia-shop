<?php

namespace App\Observers\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait SetUuid
{
    protected $column = 'uuid';

    /**
     * Set value from uuid column
     *
     * @param Model $model
     */
    protected function setUuid(Model $model)
    {
        $model->setAttribute($this->column, Str::uuid());
    }
}
