<?php

namespace App\Repositories\Admin\Categories;

use App\Repositories\Interfaces\FindItemById;
use App\Repositories\Interfaces\FindItemBySlug;
use App\Repositories\Interfaces\GetItemsCollection;
use App\Repositories\Interfaces\GetItemsCollectionWithPagination;
use App\Repositories\Interfaces\SearchWithPagination;

interface CategoryRepositoryInterface extends FindItemById, FindItemBySlug, GetItemsCollection, GetItemsCollectionWithPagination, SearchWithPagination
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