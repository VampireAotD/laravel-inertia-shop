<?php

namespace App\Http\Controllers\Frontend\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\Admin\Products\ProductRepositoryInterface;
use Inertia\Inertia;

class HomeController extends Controller
{
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $products = $this->productRepository->getItemsWithPagination(12);

        return Inertia::render('Dashboard', compact('products'));
    }

    public function search()
    {
        $result = elasticsearch()->search('products', [
                'multi_match' => [
                    'query' => 'Veronica Pouros product',
                    'fields' => ['name^7', 'description^7', 'categories.name^5'],
                    'analyzer' => 'product_analyzer',
                    'fuzzyness' => 'AUTO',
                    'prefix_length' => 0
                ]
            ]
        );
    }
}