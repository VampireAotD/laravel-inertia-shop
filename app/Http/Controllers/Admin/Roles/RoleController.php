<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\CreateRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Models\Role;
use App\Repositories\Admin\Permissions\PermissionRepositoryInterface;
use App\Repositories\Admin\Roles\RoleRepositoryInterface;
use App\Services\Admin\Roles\RoleService;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;

    /**
     * @var PermissionRepositoryInterface
     */
    private $permissionRepository;

    /**
     * @var RoleService
     */
    private $roleService;

    public function __construct(
        RoleRepositoryInterface $roleRepository,
        PermissionRepositoryInterface $permissionRepository,
        RoleService $roleService
    )
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $roles = $this->roleRepository->getItemsWithPagination();

        return Inertia::render('Admin/Roles/Index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Role $role
     * @return \Inertia\Response
     */
    public function create(Role $role)
    {
        $permissionsList = $this->permissionRepository->getItemsCollection();

        return Inertia::render('Admin/Roles/Create', compact('role', 'permissionsList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateRoleRequest $request)
    {
        if ($this->roleService->store($request)) {
            return redirect()
                ->route('admin.roles.index')
                ->with('messages', [
                    'success' => 'Successfully added new role!'
                ]);
        }

        return redirect()
            ->route('admin.roles.index')
            ->withErrors(['error' => 'Error while adding new role!']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Inertia\Response
     */
    public function show(int $id)
    {
        $role = $this->roleRepository->getRoleWithPermissions($id, ['permissions:id,name']);

        return Inertia::render('Admin/Roles/Show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Inertia\Response
     */
    public function edit(int $id)
    {
        $role = $this->roleRepository->getRoleWithPermissions($id, ['permissions:id,name']);

        $permissionsList = $this->permissionRepository->getItemsCollection();

        return Inertia::render('Admin/Roles/Edit', compact('role', 'permissionsList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRoleRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRoleRequest $request, int $id)
    {
        $role = $this->roleRepository->findItemById($id);

        if ($this->roleService->update($request, $role)) {
            return redirect()
                ->route('admin.roles.index')
                ->with('messages', [
                    'success' => 'Successfully updated role!'
                ]);
        }

        return redirect()
            ->route('admin.roles.index')
            ->withErrors(['error' => 'Error while updating role!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $role = $this->roleRepository->findItemById($id);

        if ($this->roleService->delete($role)) {
            return redirect()
                ->route('admin.roles.index')
                ->with('messages', [
                    'success' => 'Successfully deleted role!'
                ]);
        }

        return redirect()
            ->route('admin.roles.index')
            ->withErrors(['error' => 'Error while deleting role!']);
    }
}
