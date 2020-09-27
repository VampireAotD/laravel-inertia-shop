<?php

namespace App\Repositories\Interfaces;

interface FindItemBySlug
{
    /**
     * Return one entity by slug
     *
     * @param string $slug
     * @return mixed
     */
    public function findItemBySlug(string $slug);
}