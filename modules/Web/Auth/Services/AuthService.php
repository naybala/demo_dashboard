<?php
namespace BasicDashboard\Web\Auth\Services;

use App\Observers\AuthObserver;
use BasicDashboard\Foundations\Domain\Users\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    const LOGIN  = "Login Event";
    const LOGOUT = "Logout Event";

    public function __construct
    (
        private AuthObserver $authObserver,
    ) {

    }

    /**
     * Display a listing of the resource.
     */
    public function getAuthPermissions(): ?array
    {
        if (Auth::check()) {
            return User::find(Auth::id())->getAllPermissions()->pluck('name')->toArray();
        }
        return null;
    }

    public function authorizeOperator(array $request): bool
    {
        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']], true)) {
            $this->authObserver->AuthDetection(self::LOGIN);
            return true;
        }
        return false;
    }

    public function logout(): void
    {
        $this->authObserver->AuthDetection(self::LOGOUT);
        Auth::logout();
    }
}
