<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\Admin\Categories\CategoryRepositoryInterface;
use App\Repositories\Admin\Products\ProductRepositoryInterface;
use App\Services\Admin\Products\ProductService;
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

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        ProductService $productService
    )
    {
        $this->repository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->service = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $products = $this->repository->getItemsWithPagination();
        return Inertia::render('Admin/Products/Index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Product $product
     * @return \Inertia\Response
     */
    public function create(Product $product)
    {
        $categoriesList = $this->categoryRepository->getItemsCollection();
        return Inertia::render('Admin/Products/Create', compact('product', 'categoriesList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        if ($this->service->store($request)) {
            return redirect()
                ->route('admin.products.index')
                ->with('messages', [
                    'success' => 'Product was successfully added!'
                ]);
        }
        return back()
            ->withErrors([
                'error' => 'Error while adding product'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return \Inertia\Response
     */
    public function show(string $slug)
    {
        $product = $this->repository->getProductBySlugWithRelations($slug);
        return Inertia::render('Admin/Products/Show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $slug
     * @return \Inertia\Response
     */
    public function edit(string $slug)
    {
        $categoriesList = $this->categoryRepository->getItemsCollection();
        $product = $this->repository->getProductBySlugWithRelations($slug, ['images', 'categories:categories.id', 'orders']);
        return Inertia::render('Admin/Products/Edit', compact('product', 'categoriesList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductRequest $request
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, string $slug)
    {
        $product = $this->repository->findItemBySlug($slug);

        if ($this->service->update($request, $product)) {
            return redirect()
                ->route('admin.products.show', $product->slug)
                ->with('messages', [
                    'success' => 'Product was successfully added!'
                ]);
        }
        return back()
            ->withErrors([
                'error' => 'Error while adding product'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Product $product)
    {
        if ($product->delete()) {
            return redirect()
                ->route('admin.products.index')
                ->with('messages', [
                    'success' => 'Product was successfully deleted!'
                ]);
        }
        return back()
            ->withErrors([
                'error' => 'Error while deleting product'
            ]);
    }
}
