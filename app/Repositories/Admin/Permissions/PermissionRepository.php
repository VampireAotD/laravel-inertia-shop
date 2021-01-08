<?php

namespace App\Repositories\Admin\Permissions;

use App\Models\Permission;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    /**
     * Identifies model for current repository
     *
     * @return string
     */
    public function setRepositoryModel(): string
    {
        return Permission::class;
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
     * Return collection of entities
     * By default returns 10 items per page
     *
     * @param int $perPage
     * @return mixed
     */
    public function getItemsWithPagination(int $perPage = 10)
    {
        return $this->startConditions()->paginate($perPage);
    }

    /**
     * Return collection of users that has chosen permission
     *
     * @param Permission $permission
     * @param Collection $users
     * @return mixed
     */
    public function getAllUsersWithChosenPermission(Permission $permission, Collection $users)
    {
        return $users->reduce(function ($filteredUsers, $user) use ($permission){
            if($user->hasPermissionTo($permission)){
                $filteredUsers[] = $user->only(['id', 'name']);
            }

            return $filteredUsers;
        }, []);
    }

    /**
     * Return collection of entities
     *
     * @return mixed
     */
    public function getItemsCollection()
    {
        return $this->startConditions()->all();
    }
}
