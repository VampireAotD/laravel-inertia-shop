<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShareInertiaData
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Inertia::share([
            'permissions' => array_merge(
                ...Permission::all(['name'])->map(fn($permission) => [$permission->name => $request->user()->can($permission->name)])
            ),

            'allRoles' => Role::all(['id', 'name']),

            'previousRoute' => app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName(),

            'previousRouteParameters' => app('router')->getRoutes()->match(app('request')->create(url()->previous()))->parameters(),
        ]);

        return $next($request);
    }
}
