<?php

namespace BasicDashboard\Web\Users\Services;

use Exception;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\ResponseFactory;
use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Users\Resources\UserResource;
use BasicDashboard\Web\Users\Resources\UserEditResource;
use BasicDashboard\Foundations\Domain\Users\User;
use BasicDashboard\Foundations\Domain\Roles\Role;
use Illuminate\Filesystem\FilesystemManager;

class UserService extends BaseController
{
    const VIEW      = 'admin.user';
    const ROUTE     = 'users';
    const LANG_PATH = "user.user";
    const ROOT      = "Users";
    const COLUD_SERVICE = "digitalocean";
    const CLOUD_PRIVACY = "public";
    const COLUMN_KEY = "avatar";

    public function __construct(
        private User $user,                          
        private Role $role,                         
        private ResponseFactory $responseFactory,
        private FilesystemManager $fileSystemMananger,
 
    ) {}

    public function index(array $request): View
    {
        $userList = $this->user
            ->withUserRelations()                                    
            ->filterByKeyword($request['keyword'] ?? null)          
            ->orderByLatest()                                        
            ->paginate($request['paginate'] ?? config('numbers.paginate'));

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
            \DB::beginTransaction();  
            
            if (isset($request['avatar'])) {
                $image             = $request['avatar']; 
                $request['avatar'] = null;               
            }
            $roleName = $this->getRoleName($request['role_marked']);
            $request  = Arr::except($request, ['role_marked']);
            $request['created_by'] = Auth::id();  
            $user = $this->user->create($request);
            $user->assignRole($roleName);
            if(config('cache.file_system_disk') == 'local'){
                $data = $this->fileSystemMananger->uploadFileToLocal($image,"users","avatar"); 
            }else{
                $cloudPath = self::ROOT . "/" . $user->id;
                $data = $this->fileSystemMananger->uploadFileToCloud("digitalocean",$image,$cloudPath,"public","avatar"); 
            }
            $user->update($data);
            \DB::commit();  
            return $this->responseFactory->successIndexRedirect(self::ROUTE, __(self::LANG_PATH . '_created'));
        } catch (Exception $e) {
            \DB::rollBack();  
            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

    public function edit(string $id): View | RedirectResponse
    {
        $id = customDecoder($id);  
        $user = $this->user->where('id', $id)->first();
        $user = new UserEditResource($user);
        $user = $user->response()->getData(true);
        return $this->responseFactory->successView(self::VIEW . ".edit", $user);
    }

    public function show($id): View | RedirectResponse
    {
        $id = customDecoder($id);
        $user = $this->user->findOrFail($id);
        $user = new UserResource($user);
        $user = $user->response()->getData(true)['data'];
        return $this->responseFactory->successView(self::VIEW . '.show', $user);
    }

    public function update(array $request, string $id): RedirectResponse
    {
        $image = null;
        try {
            \DB::beginTransaction();
            $decodedId = customDecoder($id);
            $user = $this->user->find($decodedId);
            $oldImage = $user->avatar;
            if (isset($request['avatar'])) {
                $image             = $request['avatar']; 
                $request['avatar'] = null; 
            }
            $roleName = $this->getRoleName($request['role_marked']);
            $request  = Arr::except($request, ['role_marked']);
            $user->update($request);
            $user->syncRoles($roleName);
            if(config('cache.file_system_disk') == 'local'){
                $data = $this->fileSystemMananger->updateFileFromLocal($oldImage,$image,'avatar','users'); 
            }else{
                $cloudPath = self::ROOT . "/" . $user->id;
                $data = $this->fileSystemMananger->updateFileFromCloud("digitalocean",$oldImage,$image,$cloudPath,'public','avatar');
            }
            $user->update($data);
            \DB::commit();
            return $this->responseFactory->successShowRedirect(self::ROUTE, $id, __(self::LANG_PATH . '_updated'));
        } catch (Exception $e) {
            \DB::rollBack();
            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

    public function destroy($request): RedirectResponse
    {
        try {
            \DB::beginTransaction();
            
            $id = customDecoder($request['id']);
            $user = $this->user->where('id', $id)->first();
            $user->roles()->detach();
            $this->user->destroy($id);
            if(config('cache.file_system_disk') == 'local'){
                $this->fileSystemMananger->deletFileFromLocal($user->avatar); 
            }else{
                $this->fileSystemMananger->deleteFileFromCloud("digitalocean",$user->avatar); 
            }
            
            \DB::commit();
            return $this->responseFactory->successIndexRedirect(self::ROUTE, __(self::LANG_PATH . '_deleted'));
        } catch (Exception $e) {
            \DB::rollBack();
            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

    public function profile()
    {
        $id = Auth::id();
        $user = $this->user->findOrFail($id);
        $user = new UserResource($user);
        $user = $user->response()->getData(true)['data'];
        return $this->returnView(self::VIEW . '.profile', $user);
    }

    // ==========================================
    // Private Helper Methods
    // ==========================================
    private function getRoleName(string $roleId)
    {
        return $this->role->where('id', $roleId)->value('name');
    }


}
