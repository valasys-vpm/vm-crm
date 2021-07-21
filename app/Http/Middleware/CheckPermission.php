<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Modules\Permission\Models\Permission;
use Modules\Role\models\RolePermission;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $route = '')
    {
        $role_id = Auth::user()->role_id;
        if(empty($route)) {
            $route = $request->route()->getName();
        }
        $permission = Permission::select('id')->whereRoute($route)->first();
        if(!RolePermission::whereRoleId($role_id)->wherePermissionId($permission->id)->exists()) {
            return redirect()->route('access.denied');
        }
        return $next($request);
    }
}
