<?php

namespace App\Repositories\Admin\Orders;

use App\Models\Order;
use App\Models\Product;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    /**
     * Identifies model for current repository
     *
     * @return string
     */
    public function setRepositoryModel(): string
    {
        return Order::class;
    }

    /**
     * Return one entity by id
     *
     * @param int $id
     * @return mixed
     */
    public function findItemById(int $id)
    {
        // TODO: Implement findItemById() method.
    }

    /**
     * Return collection of entities
     * By default returns 10 items per page
     *
     * @param int $perPage
     * @return mixed
     */
    public function getItemsWithPagination(int $perPage = 10)
    {
        // TODO: Implement getItemsWithPagination() method.
    }

    /**
     * Return array of activities for current instance
     *
     * @return array
     */
    public function getActivityPerMonth(): array
    {
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $users = $this->startConditions()->get()->groupBy(function ($user) {
            return $user->created_at->format('F');
        });

        $perMonth = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        foreach ($months as $number => $month) {
            $users->map(function ($collection, $key) use ($month, &$perMonth, $number) {
                if ($key == $month) {
                    $perMonth[$number] = $collection->count();
                }
            });
        }

        return $perMonth;
    }

    /**
     * Search for items by request
     *
     * @param Request $request
     * @return Collection
     */
    public function searchWithPagination(Request $request)
    {
        // TODO: Implement searchWithPagination() method.
    }

    /**
     * Return collection of products in current order
     *
     * @param int $userId
     * @param $createdAt
     * @return Collection
     */
    public function findOrderProductsByUserAndOrderDate(int $userId, $createdAt): Collection
    {
        $productsCollection = [];

        $orders = $this->startConditions()
            ->whereHas('user', function ($query) use ($userId) {
                return $query->where('users.id', $userId);
            })
            ->where('created_at', $createdAt)
            ->orderByDesc('created_at')
            ->get();

        foreach ($orders as $order) {
            foreach (json_decode($order->order) as $product) {
                $productsCollection[] = ['product' => Product::find($product->product), 'amount' => $product->amount];
            }
        }

        return collect($productsCollection);
    }
}
