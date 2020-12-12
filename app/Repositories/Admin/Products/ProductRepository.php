<?php

namespace App\Repositories\Admin\Products;

use App\Models\Product;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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
        return $this->startConditions()->with('images')->latest()->paginate($perPage);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
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
            ->when($priceArray = $request->input('price'), function ($q) use ($priceArray) {
                $q->whereBetween('price', $priceArray);
            })
            ->when($amountArray = $request->input('amount'), function ($q) use ($amountArray) {
                $q->whereBetween('amount', $amountArray);
            })
            ->latest()
            ->paginate($perPage);
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

    /**
     * Find the maximum price among all the products
     *
     * @return mixed
     */
    public function findMaximumPrice(): int
    {
        $products = $this->getItemsCollection();
        return $products->max('price');
    }

    /**
     * Find the maximum amount among all the products
     *
     * @return mixed
     */
    public function findMaximumAmount(): int
    {
        $products = $this->getItemsCollection();
        return $products->max('amount');
    }

    /**
     * Return products collection by array of product id's
     *
     * @param array $ids
     * @return Collection
     */
    public function getProductsByIds(array $ids): Collection
    {
        return $this->startConditions()->whereIn('id', $ids)->get();
    }

    /**
     * Return collection of similar products
     *
     * @param Product $product
     * @return Collection
     */
    public function findSimilarProducts(Product $product): Collection
    {
        $categories = $product->categories->pluck('id')->toArray();

        return $this
            ->startConditions()
            ->whereHas('categories', function ($query) use ($categories){
                return $query->whereIn('categories.id', $categories);
            })
            ->where('id', '!=', $product->id)
            ->with('images')
            ->get();
    }
}
