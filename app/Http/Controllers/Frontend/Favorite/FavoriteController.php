<?php

namespace App\Http\Controllers\Frontend\Favorite;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Products\ProductRepositoryInterface;
use App\Services\Frontend\Favorite\FavoriteService;
use Inertia\Inertia;

class FavoriteController extends Controller
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var FavoriteService
     */
    private $favoriteService;

    public function __construct(ProductRepositoryInterface $productRepository, FavoriteService $favoriteService)
    {
        $this->productRepository = $productRepository;
        $this->favoriteService = $favoriteService;
    }

    /**
     * List of all products in favorite list
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $ids = \Cookie::get('favorite_list') ?? json_encode([]);

        $products = $this->productRepository->getProductsByIds(json_decode($ids));

        return Inertia::render('Favorite/Index', compact('products'));
    }

    /**
     * Add product to favorite list
     *
     * @param string $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(string $slug)
    {
        $product = $this->productRepository->findItemBySlug($slug);

        $this->favoriteService->addToFavoriteList($product);

        return back();
    }

    /**
     * Remove product from favorite list
     *
     * @param string $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(string $slug)
    {
        $product = $this->productRepository->findItemBySlug($slug);

        $this->favoriteService->removeFromFavoriteList($product);

        return back();
    }

    /**
     * Delete favorite list
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        $this->favoriteService->deleteFavoriteList();

        return back();
    }
}