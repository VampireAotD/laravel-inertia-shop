<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\Admin\Categories\CategoryRepositoryInterface;
use App\Repositories\Admin\Products\ProductRepositoryInterface;
use App\Services\Admin\Products\ProductService;
use Illuminate\Http\Request;
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
        $maximumPrice = $this->repository->findMaximumPrice();
        $maximumAmount = $this->repository->findMaximumAmount();

        return Inertia::render('Admin/Products/Index', compact('products', 'maximumPrice', 'maximumAmount'));
    }

    /**
     * Display a listing of resources with pagination and filtered by request
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function search(Request $request)
    {
        $products = $this->repository->searchWithPagination($request);

        $name = (string)$request->input('name');
        $minimumPrice = (int)$request->input('price')[0];
        $minimumAmount = (int)$request->input('amount')[0];
        $perPage = (int)$request->input('perPage');

        $maximumPrice = $this->repository->findMaximumPrice();
        $maximumAmount = $this->repository->findMaximumAmount();

        return Inertia::render(
            'Admin/Products/Index',
            compact(
                'products',
                'name',
                'minimumPrice',
                'maximumPrice',
                'minimumAmount',
                'maximumAmount',
                'perPage'
            )
        );
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
     * @param Product $product
     * @param ProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Product $product, ProductRequest $request)
    {
        if ($this->service->upsert($product, $request)) {
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

        $product = $this->repository->getProductBySlugWithRelations($slug, ['images', 'categories:categories.id']);

        return Inertia::render('Admin/Products/Edit', compact('product', 'categoriesList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductRequest $request
     * @param string $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProductRequest $request, string $slug)
    {
        $product = $this->repository->findItemBySlug($slug);

        if ($this->service->upsert($product, $request)) {
            return redirect()
                ->route('admin.products.show', $product->slug)
                ->with('messages', [
                    'success' => 'Product was successfully added!'
                ]);
        }

        return redirect()
            ->route('admin.products.show', $product->slug)
            ->withErrors([
                'error' => 'Error while adding product'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
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
