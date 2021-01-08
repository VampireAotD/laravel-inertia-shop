<?php

namespace App\Repositories\Admin\Roles;

use App\Models\Role;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    /**
     * Identifies model for current repository
     *
     * @return string
     */
    public function setRepositoryModel(): string
    {
        return Role::class;
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
        return $this->startConditions()->latest()->paginate($perPage);
    }

    /**
     * Return collection of role permissions
     *
     * @param int $id
     * @param array $permissions
     * @return Role
     */
    public function getRoleWithPermissions(int $id, array $permissions = ['permissions']): Role
    {
        $role = $this->findItemById($id);

        return $role->load($permissions);
    }
}
