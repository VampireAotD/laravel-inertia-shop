<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Admin\Orders\OrderRepositoryInterface;
use App\Services\Admin\Orders\OrderService;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * @var OrderRepositoryInterface
     */
    private $repository;

    /**
     * @var OrderService
     */
    private $service;

    public function __construct(OrderRepositoryInterface $orderRepository, OrderService $service)
    {
        $this->repository = $orderRepository;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $orders = $this->repository->getItemsWithPagination();

        return Inertia::render('Admin/Orders/Index', compact('orders'));
    }

    /**
     * Accept user order
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function accept(int $id)
    {
        $order = $this->repository->findItemById($id);

        if ($this->service->accept($order)) {
            return back();
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @param $date
     * @return \Inertia\Response
     */
    public function show(User $user, $date)
    {
        $products = $this->repository->findOrderProductsByUserAndOrderDate($user->id, $date);

        return Inertia::render('Admin/Orders/Show', compact('products'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        // TODO implement order deleting
    }
}
