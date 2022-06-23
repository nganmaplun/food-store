<?php

namespace App\Http\Controllers;

use App\Constants\BaseConstant;
use App\Constants\UserConstant;
use App\Entities\User;
use App\Http\Requests\UserLoginRequest;
use App\Repositories\TimesheetRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @var TimesheetRepository
     */
    private TimesheetRepository $timesheetRepository;

    /**
     * @param TimesheetRepository $timesheetRepository
     */
    public function __construct(TimesheetRepository $timesheetRepository)
    {
        $this->timesheetRepository = $timesheetRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|RedirectResponse
     */
    public function login()
    {
        if (Auth::check()) {
            switch (Auth::user()[UserConstant::ROLE_FIELD]) {
                case BaseConstant::ADMIN_ROLE:
                    return redirect()->route('admin-dashboard');
                    break;

                case BaseConstant::WAITER_ROLE:
                    return redirect()->route('waiter-dashboard');
                    break;

                case BaseConstant::CHEF_ROLE:
                    return redirect()->route('chef-dashboard');
                    break;

                default:
                    return redirect()->route('cashier-dashboard');
                    break;
            }
        }

        return view('user.login');
    }

    /**
     * @param UserLoginRequest $userLoginRequest
     * @return RedirectResponse
     */
    public function postLogin(UserLoginRequest $userLoginRequest): RedirectResponse
    {
        $request = $userLoginRequest->all();
        $credentials = [
            UserConstant::USERNAME_FIELD => $request[UserConstant::USERNAME_FIELD],
            UserConstant::PASSWORD_FIELD => $request[UserConstant::PASSWORD_FIELD],
        ];

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'not_valid' => 'Username or password is not correct.',
            ]);
        }
        // after login successfully, create check-in in timesheet
        $this->createCheckin(Auth::user());
        // different roles will be redirected to their dashboard
        switch (Auth::user()[UserConstant::ROLE_FIELD]) {
            case BaseConstant::ADMIN_ROLE:
                return redirect()->route('admin-dashboard');
                break;

            case BaseConstant::WAITER_ROLE:
                return redirect()->route('waiter-dashboard');
                break;

            case BaseConstant::CHEF_ROLE:
                return redirect()->route('chef-dashboard');
                break;

            default:
                return redirect()->route('cashier-dashboard');
                break;
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->createCheckout(Auth::user());

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('view.login');
    }

    /**
     * @param $user
     * @return void
     */
    public function createCheckin($user): void
    {
        $this->timesheetRepository->createCheckin($user);
    }

    /**
     * @param $user
     * @return void
     */
    public function createCheckout($user): void
    {
        $this->timesheetRepository->createCheckout($user);
    }
}
