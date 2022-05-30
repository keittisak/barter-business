<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role = null)
    {
        if ( !Auth::check() ){
            return redirect()->route('login.form');
        }

        $roles = explode('|', $role);
        $userRoles = $request->user()->roles;
        $isRoleAccess = false;
        foreach($userRoles as $item){
            if($item->name == 'member'){
                continue;
            }
            if( in_array($item->name, $roles) ){
                $isRoleAccess = true;
            }else{
                $isRoleAccess = false;
            }
        }

        if($isRoleAccess){
            return $next($request);
        }else{
            abort(403, 'Unauthorized action.');
        }
    }
}
