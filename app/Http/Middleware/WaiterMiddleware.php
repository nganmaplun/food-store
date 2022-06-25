<?php

namespace App\Http\Middleware;

use App\Constants\BaseConstant;
use App\Constants\UserConstant;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaiterMiddleware extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request): ?string
    {
        $role = Auth::user()[UserConstant::ROLE_FIELD];
        if (!in_array($role, [BaseConstant::ADMIN_ROLE, BaseConstant::WAITER_ROLE])) {
            return route('view.login');
        }
    }
}
