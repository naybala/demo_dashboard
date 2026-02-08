<?php

namespace BasicDashboard\Web\Auth\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Auth\Services\AuthService;
use BasicDashboard\Web\Auth\Validation\AuthLoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends BaseController
{
    public function __construct(private AuthService $authService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function login()
    {
        if (Auth::user() == null) {
            return view('auth.login');
        } else {
            $permissionArr = $this->authService->getAuthPermissions();
            if ($permissionArr) {
                Session::put('permission_key', implode(',', $permissionArr));
            }
            return redirect("/");
        }
    }

    public function authorizeOperator(AuthLoginRequest $request)
    {
        if ($this->authService->authorizeOperator($request->validated())) {
            return redirect("/login");
        }
        return redirect()->back()->with("message", 'Invalid credentials');
    }

    public function logout()
    {
        $this->authService->logout();
        return redirect('/login');
    }
}