<?php

namespace App\Repositories\Admin\Products;

use App\Models\Product;
use App\Repositories\Interfaces\FindItemById;
use App\Repositories\Interfaces\FindItemBySlug;
use App\Repositories\Interfaces\GetItemsCollection;
use App\Repositories\Interfaces\GetItemsCollectionWithPagination;
use App\Repositories\Interfaces\SearchWithPagination;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface extends FindItemBySlug, FindItemById, GetItemsCollection, GetItemsCollectionWithPagination, SearchWithPagination
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

    /**
     * Find the maximum price among all the products
     *
     * @return mixed
     */
    public function findMaximumPrice(): int;

    /**
     * Find the maximum amount among all the products
     *
     * @return mixed
     */
    public function findMaximumAmount(): int;

    /**
     * Return products collection by array of product id's
     *
     * @param array $ids
     * @return Collection
     */
    public function getProductsByIds(array $ids) : Collection;

    /**
     * Return collection of similar products
     *
     * @param Product $product
     * @return Collection
     */
    public function findSimilarProducts(Product $product) : Collection;
}