<?php

namespace App\Repositories\Admin\Products;

use App\Repositories\Interfaces\FindItemById;
use App\Repositories\Interfaces\FindItemBySlug;
use App\Repositories\Interfaces\GetItemsCollectionWithPagination;

interface ProductRepositoryInterface extends FindItemBySlug, FindItemById, GetItemsCollectionWithPagination
{
    /**
     * Return an product entity with its relations
     *
     * By default it will return all relations that this entity has
     *
     * @param string $slug
     * @param array $relations
     * @return mixed
     */
    public function getProductBySlugWithRelations(string $slug, array $relations = ['images', 'categories', 'orders']);
}