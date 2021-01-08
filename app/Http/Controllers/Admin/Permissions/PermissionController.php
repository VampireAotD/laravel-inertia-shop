<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\CreatePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Repositories\Admin\Permissions\PermissionRepositoryInterface;
use App\Repositories\Admin\Users\UserRepositoryInterface;
use App\Services\Admin\Permissions\PermissionService;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * @var PermissionRepositoryInterface
     */
    private $permissionRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var PermissionService
     */
    private $permissionService;

    public function __construct(
        PermissionRepositoryInterface $permissionRepository,
        UserRepositoryInterface $userRepository,
        PermissionService $permissionService
    )
    {
        $this->permissionRepository = $permissionRepository;
        $this->userRepository = $userRepository;
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $allPermissions = $this->permissionRepository->getItemsWithPagination();

        return Inertia::render('Admin/Permissions/Index', compact('allPermissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Permission $permission
     * @return \Inertia\Response
     */
    public function create(Permission $permission)
    {
        return Inertia::render('Admin/Permissions/Create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePermissionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreatePermissionRequest $request)
    {
        if ($this->permissionService->store($request)) {
            return redirect()
                ->route('admin.permissions.index')
                ->with('messages', [
                    'success' => 'Successfully created new permission!'
                ]);
        }

        return back()->withErrors([
            'error' => 'Error while creating permission'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Inertia\Response
     */
    public function show(int $id)
    {
        $permission = $this->permissionRepository->findItemById($id);
        $users = $this->userRepository->getItemsCollection();

        $usersWithPermission = $this->permissionRepository->getAllUsersWithChosenPermission($permission, $users);

        return Inertia::render('Admin/Permissions/Show',
            compact('permission', 'usersWithPermission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Inertia\Response
     */
    public function edit(int $id)
    {
        $permission = $this->permissionRepository->findItemById($id);

        return Inertia::render('Admin/Permissions/Edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePermissionRequest $request, int $id)
    {
        $permission = $this->permissionRepository->findItemById($id);

        if ($this->permissionService->update($request, $permission)) {
            return redirect()
                ->route('admin.permissions.index')
                ->with('messages', [
                    'success' => 'Successfully updated permission!'
                ]);
        }

        return back()
            ->withErrors([
                'error' => 'Error while updating permission'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $permission = $this->permissionRepository->findItemById($id);

        if ($this->permissionService->delete($permission)) {
            return redirect()->route('admin.permissions.index')
                ->with('messages', [
                    'success' => 'Permission was successfully deleted!'
                ]);
        }

        return back()
            ->withErrors([
                'error' => 'Error while deleting permission'
            ]);
    }
}
