<?php

namespace BasicDashboard\Web\Auth\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Auth\Services\AuthService;
use BasicDashboard\Web\Auth\Validation\AuthLoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class AuthController extends BaseController
{
    public function __construct(private AuthService $authService)
    {
    }

    /**
     * Display the login page.
     */
    public function login(): Response|RedirectResponse
    {
        if (Auth::user() == null) {
            return Inertia::render('Auth/Login');
        } else {
            $permissionArr = $this->authService->getAuthPermissions();
            if ($permissionArr) {
                Session::put('permission_key', implode(',', $permissionArr));
            }
            return redirect("/");
        }
    }

    public function authorizeOperator(AuthLoginRequest $request): RedirectResponse
    {
        if ($this->authService->authorizeOperator($request->validated())) {
            return redirect("/");
        }
        return back()->with("error", 'Invalid credentials');
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();
        return redirect('/login');
    }
}