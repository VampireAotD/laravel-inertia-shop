<?php

namespace App\Repositories\Admin\Roles;

use App\Models\Role;
use App\Repositories\Interfaces\FindItemById;
use App\Repositories\Interfaces\GetItemsCollectionWithPagination;

interface RoleRepositoryInterface extends FindItemById, GetItemsCollectionWithPagination
{
    /**
     * Return collection of role permissions
     *
     * @param int $id
     * @param array $permissions
     * @return Role
     */
    public function getRoleWithPermissions(int $id, array $permissions = ['permissions']) : Role;
}
