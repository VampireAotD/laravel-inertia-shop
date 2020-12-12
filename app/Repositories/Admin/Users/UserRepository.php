<?php

namespace App\Repositories\Admin\Users;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * Identifies model for current repository
     *
     * @return string
     */
    public function setRepositoryModel(): string
    {
        return User::class;
    }

    /**
     * Return one entity by id
     *
     * @param int $id
     * @return mixed
     */
    public function findItemById(int $id)
    {
        return $this->startConditions()->where('id', $id)->firstOrFail();
    }

    /**
     * Find user by its id and return it with it relations
     *
     * @param int $id
     * @param array $relations
     * @return mixed
     */
    public function findUserByIdWithRelations(int $id, array $relations = ['roles', 'orders.user', 'orders'])
    {
        $user = $this->findItemById($id);
        return $user->load($relations);
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
        return $this->startConditions()->withCount('orders')->latest()->paginate($perPage);
    }

    /**
     * Search for items by request
     *
     * @param Request $request
     * @return Collection
     */
    public function searchWithPagination(Request $request)
    {
        $perPage = 10;

        if ($perPageRequest = $request->input('perPage')) {
            $perPage = $perPageRequest;
        }

        $query = $this->startConditions();

        return $query
            ->when($name = $request->input('name'), function ($q) use ($name) {
                $q->where('name', 'like', "%$name%");
            })
            ->when($email = $request->input('email'), function ($q) use ($email) {
                $q->where('email', 'like', "%$email%");
            })
            ->latest()
            ->paginate($perPage);
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
}
