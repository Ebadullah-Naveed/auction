<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Gate;

class AuthGate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user=auth()->user();
        if(!app()->runningInConsole() && $user){
            $permissionArray = [];
            $role = $user->role;

            foreach ($role->permissions as $permissions) {
                $permissionArray[$permissions->name][]=$role->id;
            }

            $user_role_ids = $user->role->pluck('id')->toArray();
            foreach ($permissionArray as $title => $roles) {
                Gate::define($title,function(User $user) use($roles,$user_role_ids){
                    return count(array_intersect( $user_role_ids ,$roles)) > 0;
                });
            }
        }
        return $next($request);
    }
}
