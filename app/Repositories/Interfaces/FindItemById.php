<?php

namespace App\Repositories\Interfaces;

interface FindItemById
{
    /**
     * Return one entity by id
     *
     * @param int $id
     * @return mixed
     */
    public function findItemById(int $id);
}