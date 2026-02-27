<?php
namespace BasicDashboard\Web\Users\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Users\Services\UserService;
use BasicDashboard\Web\Users\Validation\DeleteUserRequest;
use BasicDashboard\Web\Users\Validation\StoreUserRequest;
use BasicDashboard\Web\Users\Validation\UpdateUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use BasicDashboard\Web\Roles\Services\RoleService;
use BasicDashboard\Web\Users\Resources\UserResource;
use BasicDashboard\Web\Users\Resources\UserEditResource;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class UserController extends BaseController
{
    const VIEW = 'admin.user';
    const ROUTE = 'users';
    const LANG_PATH = "user.user";

    public function __construct(
        private UserService $userService
    ) {}

    public function index(Request $request): Response
    {
        $userList = $this->userService->paginate($request->all());
        $userList = UserResource::collection($userList)->response()->getData(true);
        return Inertia::render('Users/Index', $userList);
    }

    public function create(): Response
    {
        $roles = app(RoleService::class)->all();
        return Inertia::render('Users/CreateEdit', [
            'roles' => $roles,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $this->userService->store($request->all());
            return redirect()->route(self::ROUTE . '.index')->with('success', __(self::LANG_PATH . '_created'));
        } catch (Throwable $e) {
            $this->LogError("User store failed", $e);
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(string $id): Response
    {
        $decodedId = customDecoder($id);  
        $user = $this->userService->findOrFail($decodedId);
        $user = new UserEditResource($user);
        $user = $user->response()->getData(true);
        
        $roles = app(RoleService::class)->all();

        return Inertia::render('Users/CreateEdit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function show($id): Response
    {
        $decodedId = customDecoder($id);
        $user = $this->userService->findOrFail($decodedId);
        $user = new UserResource($user);
        $user = $user->response()->getData(true)['data'];
        return Inertia::render('Users/Show', [
            'user' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, string $id): RedirectResponse
    {
        try {
            $decodedId = customDecoder($id);
            $this->userService->update($request->all(), $decodedId);
            return redirect()->route(self::ROUTE . '.index')->with('success', __(self::LANG_PATH . '_updated'));
        } catch (Throwable $e) {
            $this->LogError("User update failed", $e);
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(DeleteUserRequest $request): RedirectResponse
    {
        try {
            $this->userService->delete($request->validated()['id']);
            return redirect()->route(self::ROUTE . '.index')->with('success', __(self::LANG_PATH . '_deleted'));
        } catch (Throwable $e) {
            $this->LogError("User destroy failed", $e);
            return back()->with('error', $e->getMessage());
        }
    }

    public function profile(): Response
    {
        $user = $this->userService->profile();
        $user = new UserResource($user);
        $user = $user->response()->getData(true)['data'];
        return Inertia::render('Users/Profile', [
            'user' => $user,
        ]);
    }
}


