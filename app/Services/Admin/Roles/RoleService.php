<?php

namespace App\Services\Admin\Roles;

use App\Http\Requests\Roles\CreateRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleService
{

    public function store(CreateRoleRequest $request)
    {
        try {
            DB::beginTransaction();

            if ($role = Role::create($request->validated())) {
                $role->save();
                if ($role->syncPermissions($request->input('permissions'))) {
                    DB::commit();

                    return true;
                }
            }
        } catch (\Exception $exception) {
            DB::rollBack();

            return false;
        }
    }

    /**
     * Update role and its permissions
     *
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return bool
     * @throws \Throwable
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            DB::beginTransaction();

            if ($role->update($request->validated()) && $role->syncPermissions($request->input('permissions'))) {
                DB::commit();

                return true;
            }
        } catch (\Exception $exception) {
            DB::rollBack();

            return false;
        }
    }

    /**
     * Delete role
     *
     * @param Role $role
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Role $role)
    {
        return $role->delete();
    }
}
