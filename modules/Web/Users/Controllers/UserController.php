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
use Illuminate\Routing\ResponseFactory;
use BasicDashboard\Web\Users\Resources\UserResource;
use BasicDashboard\Web\Users\Resources\UserEditResource;

class UserController extends BaseController
{
    const VIEW = 'admin.user';
    const ROUTE = 'users';
    const LANG_PATH = "user.user";

    public function __construct(
        private UserService $userService,
        private ResponseFactory $responseFactory
    ) {}

    public function index(Request $request): View
    {
        $userList = $this->userService->paginate($request->all());
        $userList = UserResource::collection($userList)->response()->getData(true);
        return $this->responseFactory->successView(self::VIEW . '.index', $userList);
    }

    public function create(): mixed
    {
        return view(self::VIEW . '.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $this->userService->store($request->all());
            return $this->responseFactory->successIndexRedirect(self::ROUTE, __(self::LANG_PATH . '_created'));
        } catch (\Throwable $e) {
            $this->LogError("User store failed", $e);
            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

    public function edit(string $id): View | RedirectResponse
    {
        $decodedId = customDecoder($id);  
        $user = $this->userService->findOrFail($decodedId);
        $user = new UserEditResource($user);
        $user = $user->response()->getData(true);
        return $this->responseFactory->successView(self::VIEW . ".edit", $user);
    }

    public function show($id): View | RedirectResponse
    {
        $user = $this->userService->findOrFail($id);
        $user = new UserResource($user);
        $user = $user->response()->getData(true)['data'];
        return $this->responseFactory->successView(self::VIEW . '.show', $user);
    }

    public function update(UpdateUserRequest $request, string $id): RedirectResponse
    {
        try {
            $this->userService->update($request->all(), $id);
            return $this->responseFactory->successShowRedirect(self::ROUTE, $id, __(self::LANG_PATH . '_updated'));
        } catch (\Throwable $e) {
            $this->LogError("User update failed", $e);
            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

    public function destroy(DeleteUserRequest $request): RedirectResponse
    {
        try {
            $this->userService->delete($request->validated()['id']);
            return $this->responseFactory->successIndexRedirect(self::ROUTE, __(self::LANG_PATH . '_deleted'));
        } catch (\Throwable $e) {
            $this->LogError("User destroy failed", $e);
            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

    public function profile(): View
    {
        $user = $this->userService->profile();
        $user = new UserResource($user);
        $user = $user->response()->getData(true)['data'];
        return $this->responseFactory->successView(self::VIEW . '.profile', $user);
    }
}


