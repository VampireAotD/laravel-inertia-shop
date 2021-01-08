<?php

namespace App\Http\Controllers\Frontend\Product;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Products\ProductRepositoryInterface;
use App\Services\Frontend\Products\ProductService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(ProductRepositoryInterface $productRepository, ProductService $productService)
    {
        $this->productRepository = $productRepository;
        $this->productService = $productService;
    }

    /**
     * Details about specific product
     *
     * @param string $slug
     * @return \Inertia\Response
     */
    public function show(string $slug)
    {
        $product = $this->productRepository->getProductBySlugWithRelations($slug, ['images']);

        $similarProducts = $this->productRepository->findSimilarProducts($product);

        $this->productService->additionalActions($product, request()->ip());

        $breadcrumbs = Breadcrumbs::generate('product', $product);

        return Inertia::render('Frontend/Product/Show', compact('product', 'similarProducts', 'breadcrumbs'));
    }
}
