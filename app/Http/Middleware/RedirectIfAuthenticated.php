<?php

namespace App\Http\Middleware;

use App\Constants\BaseConstant;
use App\Constants\UserConstant;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $role = Auth::user()[UserConstant::ROLE_FIELD];
                switch (true) {
                    case ($role != BaseConstant::ADMIN_ROLE):
                        return redirect()->back();

                    case (!in_array($role, [BaseConstant::ADMIN_ROLE, BaseConstant::WAITER_ROLE])):
                        return redirect()->back();

                    case (!in_array($role, [BaseConstant::ADMIN_ROLE, BaseConstant::CHEF_ROLE])):
                        return redirect()->back();

                    case (!in_array($role, [BaseConstant::ADMIN_ROLE, BaseConstant::CASHIER_ROLE])):
                        return redirect()->back();
                }
            }
        }

        return $next($request);
    }
}
