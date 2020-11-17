<?php

namespace App\Http\Controllers\Admin\Home;

use App\Repositories\Admin\Orders\OrderRepositoryInterface;
use App\Repositories\Admin\Users\UserRepositoryInterface;
use Inertia\Inertia;

class HomeController
{
    private $userRepository;
    private $orderRepository;

    public function __construct(UserRepositoryInterface $userRepository, OrderRepositoryInterface $orderRepository)
    {
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        $usersPerMonth = $this->userRepository->getActivityPerMonth();
        $ordersPerMonth = $this->orderRepository->getActivityPerMonth();

        return Inertia::render('Admin/AdminDashboard', compact('usersPerMonth', 'ordersPerMonth'));
    }
}
