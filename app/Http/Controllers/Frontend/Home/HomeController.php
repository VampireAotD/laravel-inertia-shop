<?php

namespace App\Http\Controllers\Frontend\Home;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Products\ProductRepositoryInterface;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Main page
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $products = $this->productRepository->getItemsWithPagination(12);

        return Inertia::render('Frontend/Dashboard', compact('products'));
    }
}
