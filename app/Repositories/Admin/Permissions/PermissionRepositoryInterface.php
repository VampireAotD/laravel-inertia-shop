<?php

namespace App\Repositories\Admin\Permissions;

use App\Repositories\Interfaces\FindItemById;
use App\Repositories\Interfaces\GetItemsCollection;
use App\Repositories\Interfaces\GetItemsCollectionWithPagination;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Permission;

interface PermissionRepositoryInterface extends FindItemById, GetItemsCollectionWithPagination, GetItemsCollection
{
    /**
     * Return collection of users that has chosen permission
     *
     * @param Permission $permission
     * @param Collection $users
     * @return mixed
     */
    public function getAllUsersWithChosenPermission(Permission $permission, Collection $users);
}
