<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class ShareInertiaData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Inertia::share([
            'permissions' => function () use ($request) {
                return auth()->check()
                    ?
                    array_merge(...$request->user()->roles->map(function ($role) {
                        return $role->permissions->pluck('name')->toArray();
                    })
                    )
                    :
                    null;
            },

            'userRole' => $request->user()->roles,

            'allRoles' => Role::all(['id', 'name']),

            'previousRoute' => app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName(),

            'previousRouteParameters' => app('router')->getRoutes()->match(app('request')->create(url()->previous()))->parameters(),
        ]);

        return $next($request);
    }
}
