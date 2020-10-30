<?php

namespace App\Http\Controllers\Frontend\Cart;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Products\ProductRepositoryInterface;
use App\Services\Frontend\Cart\CartService;
use Illuminate\Http\Request;

class CartContoller extends Controller
{
    private $cartService;
    private $productRepository;

    public function __construct(CartService $cartService, ProductRepositoryInterface $productRepository)
    {
        $this->cartService = $cartService;
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $ids = session('cart');
        dd($this->productRepository->getProductsByIds(json_decode($ids)));
    }

    /**
     * Add product to cart
     *
     * @param string $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(string $slug)
    {
        $product = $this->productRepository->findItemBySlug($slug);

        $this->cartService->addToCart($product);

        return back();
    }

    /**
     * Remove product from cart
     *
     * @param string $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(string $slug)
    {
        $product = $this->productRepository->findItemBySlug($slug);

        $this->cartService->removeFromCart($product);

        return back();
    }

    /**
     * Delete cart
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        $this->cartService->deleteCart();

        return back();
    }
}
