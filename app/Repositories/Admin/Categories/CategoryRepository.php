<?php

namespace App\Repositories\Admin\Categories;

use App\Models\Category;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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
     * Return collection of entities
     *
     * @return mixed
     */
    public function getItemsCollection()
    {
        return $this->startConditions()->all();
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

    public function searchWithPagination(Request $request)
    {
        $perPage = 10;

        if ($perPageRequest = $request->input('perPage')) {
            $perPage = $perPageRequest;
        }

        $query = $this->startConditions();

        return $query
            ->when($name = $request->input('name'), function ($q) use ($name) {
                $q->where('name', 'like', "%$name%");
            })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Return one category entity by slug with its relations
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
}