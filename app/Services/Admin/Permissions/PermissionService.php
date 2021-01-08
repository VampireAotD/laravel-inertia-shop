<?php

namespace App\Services\Admin\Permissions;

use App\Http\Requests\Permission\CreatePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Models\Permission;

class PermissionService
{
    /**
     * Store new permission
     *
     * @param CreatePermissionRequest $request
     * @return bool
     */
    public function store(CreatePermissionRequest $request)
    {
        return Permission::create($request->validated())->save();
    }

    /**
     * Update permission
     *
     * @param UpdatePermissionRequest $request
     * @param Permission $permission
     * @return bool
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        return $permission->update($request->validated());
    }

    /**
     * Delete permission
     *
     * @param Permission $permission
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Permission $permission)
    {
        return $permission->delete();
    }
}
