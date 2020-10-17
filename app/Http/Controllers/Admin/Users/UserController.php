<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Repositories\Admin\Users\UserRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->repository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $users = $this->repository->getItemsWithPagination();

        return Inertia::render('Admin/Users/Index', compact('users'));
    }

    /**
     * Display a listing of resources with pagination and filtered by request
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function search(Request $request)
    {
        $users = $this->repository->searchWithPagination($request);

        $name = (string)$request->input('name');
        $email = (string)$request->input('email');
        $perPage = (int)$request->input('perPage');

        return Inertia::render('Admin/Users/Index', compact(
            'users',
            'name',
            'email',
            'perPage'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Inertia\Response
     */
    public function show($id)
    {
        $user = $this->repository->findUserByIdWithRelations($id);
        return Inertia::render('Admin/Users/Show', compact('user'));
    }

    /**
     * Change role for specified user
     *
     * @param UserRequest $request
     * @param int $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function changeRole(UserRequest $request, int $id)
    {
        $user = $this->repository->findItemById($id);

        if ($user->syncRoles($request->input('role'))) {
            return back();
        }

        return redirect()->route('admin.users.index')->withErrors([
            'error' => 'Error while changing role'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->repository->findItemById($id);

        if ($user->delete()) {
            return redirect()->route('admin.users.index')->with([
                'messages' => [
                    'success' => 'Successfully deleted user'
                ]
            ]);
        }

        return redirect()->route('admin.users.index')->withErrors([
            'error' => 'Error while deleting user'
        ]);
    }
}
