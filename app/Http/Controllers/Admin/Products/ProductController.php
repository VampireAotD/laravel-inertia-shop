<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
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
    private $productRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        ProductService $productService
    )
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $products = $this->productRepository->getItemsWithPagination();
        $maximumPrice = $this->productRepository->findMaximumPrice();
        $maximumAmount = $this->productRepository->findMaximumAmount();

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
        $products = $this->productRepository->searchWithPagination($request);

        $name = (string)$request->input('name');
        $minimumPrice = (int)$request->input('price')[0];
        $minimumAmount = (int)$request->input('amount')[0];
        $perPage = (int)$request->input('perPage');

        $maximumPrice = $this->productRepository->findMaximumPrice();
        $maximumAmount = $this->productRepository->findMaximumAmount();

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
        if ($this->productService->upsert($product, $request)) {
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
        $product = $this->productRepository->getProductBySlugWithRelations($slug);

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

        $product = $this->productRepository->getProductBySlugWithRelations($slug, ['images', 'categories:categories.id']);

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
        $product = $this->productRepository->findItemBySlug($slug);

        if ($this->productService->upsert($product, $request)) {
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
