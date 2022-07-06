<?php

namespace App\Http\Middleware;

use App\Constants\BaseConstant;
use App\Constants\UserConstant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChefMiddleware
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
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $role = Auth::user()[UserConstant::ROLE_FIELD];
                if (!in_array($role, [BaseConstant::CHEF_SALAD_ROLE, BaseConstant::CHEF_STEAM_ROLE, BaseConstant::CHEF_GRILL_ROLE, BaseConstant::CHEF_DRYING_ROLE, BaseConstant::CHEF_DRINK_ROLE])) {
                    return redirect()->back();
                }
            }
        }

        return $next($request);
    }
}
