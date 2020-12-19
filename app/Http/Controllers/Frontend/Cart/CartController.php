<?php

namespace App\Http\Controllers\Frontend\Cart;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Products\ProductRepositoryInterface;
use App\Services\Frontend\Cart\CartService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    /**
     * @var CartService
     */
    private $cartService;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(CartService $cartService, ProductRepositoryInterface $productRepository)
    {
        $this->cartService = $cartService;
        $this->productRepository = $productRepository;
    }

    /**
     * List of products in cart
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $ids = session('cart') ?? json_encode([]);

        $products = $this->productRepository->getProductsByIds(json_decode($ids));

        $breadcrumbs = Breadcrumbs::generate('cart');

        return Inertia::render('Frontend/Cart/Index', compact('products', 'breadcrumbs'));
    }

    /**
     * Order and clear cart
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function order(Request $request)
    {
        if($order = $this->cartService->order($request->user())){
            return back();
        }

        return back();
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
