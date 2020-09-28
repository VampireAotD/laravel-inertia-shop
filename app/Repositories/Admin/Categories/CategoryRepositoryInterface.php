<?php

namespace App\Repositories\Admin\Categories;

use App\Repositories\Interfaces\FindItemById;
use App\Repositories\Interfaces\FindItemBySlug;
use App\Repositories\Interfaces\GetItemsCollectionWithPagination;

interface CategoryRepositoryInterface extends FindItemById, FindItemBySlug, GetItemsCollectionWithPagination
{
    /**
     * Return one category entity by slug with its relations
     *
     * @param string $slug
     * @param array $relations
     * @return mixed
     */
    public function getCategoryBySlugWithRelations(string $slug, array $relations = ['products']);
}