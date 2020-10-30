<?php

namespace App\Repositories\Admin\Users;

use App\Repositories\Interfaces\FindItemById;
use App\Repositories\Interfaces\GetItemsCollectionWithPagination;
use App\Repositories\Interfaces\MonthlyStatistics;
use App\Repositories\Interfaces\SearchWithPagination;

interface UserRepositoryInterface extends FindItemById, GetItemsCollectionWithPagination, SearchWithPagination, MonthlyStatistics
{
    /**
     * Find user by its id and return it with it relations
     *
     * @param int $id
     * @param array $relations
     * @return mixed
     */
    public function findUserByIdWithRelations(int $id, array $relations = ['orders', 'roles']);
}