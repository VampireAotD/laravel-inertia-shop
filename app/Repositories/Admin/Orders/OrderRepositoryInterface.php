<?php

namespace App\Repositories\Admin\Orders;

use App\Repositories\Interfaces\FindItemById;
use App\Repositories\Interfaces\GetItemsCollectionWithPagination;
use App\Repositories\Interfaces\MonthlyStatistics;
use App\Repositories\Interfaces\SearchWithPagination;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface extends FindItemById, GetItemsCollectionWithPagination, SearchWithPagination, MonthlyStatistics
{
    /**
     * Return collection of products in current order
     *
     * @param int $userId
     * @param $createdAt
     * @return Collection
     */
    public function findOrderProductsByUserAndOrderDate(int $userId, $createdAt) : Collection;
}