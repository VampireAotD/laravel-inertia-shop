<?php

namespace App\Repositories\Interfaces;

interface GetItemsCollectionWithPagination
{
    /**
     * Return collection of entities
     * By default returns 10 items per page
     *
     * @param int $perPage
     * @return mixed
     */
    public function getItemsWithPagination(int $perPage = 10);
}