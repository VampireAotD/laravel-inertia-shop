<?php

namespace App\Http\Controllers\Frontend\Product;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Products\ProductRepositoryInterface;
use Inertia\Inertia;

class ProductController extends Controller
{
    private $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Details about specific product
     *
     * @param string $slug
     * @return \Inertia\Response
     */
    public function show(string $slug)
    {
        $product = $this->repository->findItemBySlug($slug);

        $similarProducts = $this->repository->findSimilarProducts($product)->random(4);

        return Inertia::render('Product/Show', compact('product', 'similarProducts'));
    }
}
