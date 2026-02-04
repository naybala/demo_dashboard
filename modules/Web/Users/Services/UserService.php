<?php

namespace BasicDashboard\Web\Users\Services;

use Exception;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\ResponseFactory;
use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Foundations\Domain\Users\User;
use BasicDashboard\Web\Users\Resources\UserResource;
use BasicDashboard\Web\Users\Resources\UserEditResource;
use BasicDashboard\Foundations\Domain\Roles\Repositories\RoleRepositoryInterface;
use BasicDashboard\Foundations\Domain\Users\Repositories\UserRepositoryInterface;

// use Illuminate\Support\Facades\Response;

class UserService extends BaseController
{
    const VIEW      = 'admin.user';
    const ROUTE     = 'users';
    const LANG_PATH = "user.user";
    const ROOT      = "Users";

    public function __construct(
        private UserRepositoryInterface $userRepositoryInterface,
        private RoleRepositoryInterface $roleRepositoryInterface,
        private ResponseFactory $responseFactory
    ) {}


    public function index(array $request): View
    {
        $userList = $this->userRepositoryInterface->getUserList($request);

        $userList = UserResource::collection($userList)->response()->getData(true);

        return $this->responseFactory->successView(self::VIEW . '.index', $userList);
    }

    public function create(): mixed
    {
        return view(self::VIEW . '.create');
    }

    public function store(array $request): RedirectResponse
    {
        try {
            $image = null;
            $this->userRepositoryInterface->beginTransaction();
            if (isset($request['avatar'])) {
                $image             = $request['avatar']; //store image to another variable
                $request['avatar'] = null;               //remove value of profile photo
            }
            $roleName = $this->getRoleName($request['role_marked']);
            $request  = Arr::except($request, ['role_marked']);           //remove array key and value role_id bcz there is no role_id column in users table
            $user     = $this->userRepositoryInterface->insert($request); //insert user by repo base method
            $user->assignRole($roleName);                                 //user role assign
            $data = $this->uploadImageToCloud($user, $image); //upload image to digital ocean path(User/id/demo.jpg)
            $user->update($data);                             //update previous user of profile photo by laravel method
            $this->userRepositoryInterface->commit();
            return $this->responseFactory->successIndexRedirect(self::ROUTE, __(self::LANG_PATH . '_created'));
        } catch (Exception $e) {
            return $this->responseFactory->redirectBackWithError($this->userRepositoryInterface, $e->getMessage());
        }
    }

    public function edit(string $id): View | RedirectResponse
    {
        $user                 = $this->userRepositoryInterface->edit($id);
        $user                 = new UserEditResource($user);
        $user                 = $user->response()->getData(true);
        return $this->responseFactory->successView(self::VIEW . ".edit", $user);
    }

    public function show($id): View | RedirectResponse
    {
        $user = $this->userRepositoryInterface->show($id);
        $user = new UserResource($user);
        $user = $user->response()->getData(true)['data'];
        return $this->responseFactory->successView(self::VIEW . '.show', $user);
    }

    public function update(array $request, string $id): RedirectResponse
    {
        $image = null;
        try {
            $this->userRepositoryInterface->beginTransaction();
            $user     = $this->userRepositoryInterface->edit($id);
            $oldImage = $user->avatar;
            if (isset($request['avatar'])) {
                $image             = $request['avatar']; //store image to another variable
                $request['avatar'] = null;               //remove value of profile photo
            }
            $roleName = $this->getRoleName($request['role_marked']);
            $request  = Arr::except($request, ['role_marked']);
            $this->userRepositoryInterface->update($request, $id);
            $user->syncRoles($roleName);
            $data = $this->uploadImageToCloudForUpdate($user, $image, $oldImage); //upload image to digital ocean path(User/id/demo.jpg)
            $user->update($data);
            $this->userRepositoryInterface->commit();
            return $this->responseFactory->successShowRedirect(self::ROUTE, $id, __(self::LANG_PATH . '_updated'));
        } catch (Exception $e) {
            return $this->responseFactory->redirectBackWithError($this->userRepositoryInterface, $e->getMessage());
        }
    }

    public function destroy($request): RedirectResponse
    {
        try {
            $this->userRepositoryInterface->beginTransaction();
            $user = $this->userRepositoryInterface->edit($request['id']);
            $user->roles()->detach();
            $this->userRepositoryInterface->delete($request['id']);
            $this->userRepositoryInterface->commit();
            return $this->responseFactory->successIndexRedirect(self::ROUTE, __(self::LANG_PATH . '_deleted'));
        } catch (Exception $e) {
            return $this->responseFactory->redirectBackWithError($this->userRepositoryInterface, $e->getMessage());
        }
    }

    public function profile()
    {
        $id   = customEncoder(Auth::id());
        $user = $this->userRepositoryInterface->show($id);
        $user = new UserResource($user);
        $user = $user->response()->getData(true)['data'];
        return $this->returnView(self::VIEW . '.profile', $user);
    }

    //Private Section

    private function getRoleName(string $roleId)
    {
        return $this->roleRepositoryInterface->connection(true)->where('id', $roleId)->value('name');
    }

    private function uploadImageToCloud(User $user, object | null $image): array
    {
        $avatarPhoto = $image ? $this->uploadImage($image, self::ROOT . "/" . $user->id) : null; //return image url string
        $data        = [
            "avatar" => $avatarPhoto,
        ]; //prepare data for profile photo update for previous user
        return $data;
    }

    private function uploadImageToCloudForUpdate(User $user, object | null $image, string | null $oldImage): array
    {
        $avatarPhoto = null;
        if ($image) {
            $oldImage == null ? '' : $this->deleteImage($oldImage);
            $avatarPhoto = $this->uploadImage($image, self::ROOT . "/" . $user->id);
        }
        $data = [
            "avatar" => $avatarPhoto == null ? $oldImage : $avatarPhoto,
        ]; //prepare data for profile photo update for previous user
        return $data;
    }



}
