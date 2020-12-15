<?php

namespace App\Http\Controllers\Frontend\Product;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Products\ProductRepositoryInterface;
use App\Services\Frontend\Products\ProductService;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * @var ProductRepositoryInterface
     */
    private $repository;

    /**
     * @var ProductService
     */
    private $service;

    public function __construct(ProductRepositoryInterface $repository, ProductService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
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

        $this->service->additionalActions($product, request()->ip());

        return Inertia::render('Frontend/Product/Show', compact('product', 'similarProducts'));
    }
}
