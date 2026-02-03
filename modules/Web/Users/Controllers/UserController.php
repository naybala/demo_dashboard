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

class UserController extends BaseController
{
    public function __construct(
        private UserService $userService
    ) {}

    public function index(Request $request): View
    {
        return $this->userService->index($request->all());
    }

    public function create(): mixed
    {
        return $this->userService->create();
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        return $this->userService->store($request->all());
    }

    public function edit(string $id): View | RedirectResponse
    {
        return $this->userService->edit($id);
    }

    public function show($id): View | RedirectResponse
    {
        return $this->userService->show($id);
    }

    public function update(UpdateUserRequest $request, string $id): RedirectResponse
    {
        return $this->userService->update($request->all(), $id);
    }

    public function destroy(DeleteUserRequest $request): RedirectResponse
    {
        return $this->userService->destroy($request->validated());
    }

    public function profile()
    {
        return $this->userService->profile();
    }
}


