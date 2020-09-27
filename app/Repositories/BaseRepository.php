<?php

namespace App\Repositories;

use App\Repositories\Interfaces\SetRepositoryModel;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements SetRepositoryModel
{
    /**
     * Model that repository works with
     *
     * @var Model
     */
    private $model;

    public function __construct()
    {
        $this->model = app($this->setRepositoryModel());
    }

    /**
     * Return model to work with
     *
     * @return Model
     */
    protected function startConditions() : Model
    {
        return clone $this->model;
    }
}