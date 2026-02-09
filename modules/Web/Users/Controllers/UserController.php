<?php
namespace BasicDashboard\Web\Users\Controllers;

use BasicDashboard\Foundations\Shared\BaseCrudController;
use BasicDashboard\Web\Users\Services\UserService;
use BasicDashboard\Web\Users\Validation\DeleteUserRequest;
use BasicDashboard\Web\Users\Validation\StoreUserRequest;
use BasicDashboard\Web\Users\Validation\UpdateUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Routing\ResponseFactory;
use BasicDashboard\Web\Users\Resources\UserResource;
use BasicDashboard\Web\Users\Resources\UserEditResource;

class UserController extends BaseCrudController
{
    protected string $viewPath = 'admin.user';
    protected string $routePrefix = 'users';
    protected string $langPath = "user.user";
    protected string $resourceClass = UserResource::class;
    protected ?string $editResourceClass = UserEditResource::class;

    public function __construct(
        protected UserService $userService,
        protected ResponseFactory $responseFactory
    ) {
        parent::__construct($responseFactory);
        $this->service = $userService;
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        return parent::performStore($request);
    }

    public function update(UpdateUserRequest $request, string $id): RedirectResponse
    {
        return parent::performUpdate($request, $id);
    }

    public function destroy(DeleteUserRequest $request): RedirectResponse
    {
        return parent::performDestroy($request);
    }

    public function profile(): View
    {
        $user = $this->userService->profile();
        $user = new UserResource($user);
        $user = $user->response()->getData(true)['data'];
        return $this->responseFactory->successView($this->viewPath . '.profile', $user);
    }
}


