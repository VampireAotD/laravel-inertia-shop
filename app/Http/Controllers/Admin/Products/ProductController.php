<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\Admin\Products\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * @var ProductRepositoryInterface
     */
    private $repository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->repository = $productRepository;
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
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
                'error' => 'Error while deleting category'
            ]);
    }
}
