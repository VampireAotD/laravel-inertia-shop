<?php

namespace App\Repositories\Admin\Products;

use App\Models\Product;
use App\Repositories\BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * Identifies model for current repository
     *
     * @return string
     */
    public function setRepositoryModel(): string
    {
        return Product::class;
    }

    /**
     * Return one entity by id
     *
     * @param int $id
     * @return mixed
     */
    public function findItemById(int $id)
    {
        return $this->startConditions()->where('id', $id)->firstOrFail();
    }

    /**
     * Return one entity by slug
     *
     * @param string $slug
     * @return mixed
     */
    public function findItemBySlug(string $slug)
    {
        return $this->startConditions()->where('slug', $slug)->firstOrFail();
    }

    /**
     * Return collection of entities
     * By default returns 10 items per page
     *
     * @param int $perPage
     * @return mixed
     */
    public function getItemsWithPagination(int $perPage = 10)
    {
        return $this->startConditions()->with('images')->latest()->paginate($perPage);
    }

    /**
     * Return an product entity with its relations
     *
     * By default it will return all relations that this entity has
     *
     * @param string $slug
     * @param array $relations
     * @return mixed
     */
    public function getProductBySlugWithRelations(string $slug, array $relations = ['images', 'categories', 'orders'])
    {
        $product = $this->findItemBySlug($slug);
        return $product->load($relations);
    }
}