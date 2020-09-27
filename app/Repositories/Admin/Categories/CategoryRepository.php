<?php

namespace App\Repositories\Admin\Categories;

use App\Models\Category;
use App\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * Identifies model for current repository
     *
     * @return string
     */
    public function setRepositoryModel(): string
    {
        return Category::class;
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
     * Return one entity by slug with its relations
     *
     * @param string $slug
     * @param array $relations
     * @return mixed
     */
    public function getCategoryBySlugWithRelations(string $slug, array $relations = ['products'])
    {
        $category = $this->findItemBySlug($slug);
        return $category->load($relations);
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
        return $this->startConditions()->latest()->paginate($perPage);
    }
}