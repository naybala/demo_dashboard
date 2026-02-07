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

class UserService
{
    const ROOT      = "Users";

    public function __construct(
        private User $user,                          
        private Role $role,                         
        private FilesystemManager $fileSystemMananger,
    ) {}

    public function paginate(array $request)
    {
        return $this->user
            ->withUserRelations()                                    
            ->filterByKeyword($request['keyword'] ?? null)          
            ->orderByLatest()                                        
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function store(array $request): User
    {
        return \DB::transaction(function () use ($request) {
            $image = null;
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
            return $user;
        });
    }

    public function findOrFail(string $id): User
    {
        return $this->user->findOrFail($id);
    }

    public function find($id): ?User
    {
        return $this->user->find($id);
    }

    public function update(array $request, string $id): User
    {
        return \DB::transaction(function () use ($request, $id) {
            $image = null;
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
            return $user;
        });
    }

    public function delete(string $id): void
    {
        \DB::transaction(function () use ($id) {
            $decodedId = customDecoder($id);
            $user = $this->user->where('id', $decodedId)->first();
            $user->roles()->detach();
            if(config('cache.file_system_disk') == 'local'){
                $this->fileSystemMananger->deleteFileFromLocal($user->avatar); 
            }else{
                $this->fileSystemMananger->deleteFileFromCloud("digitalocean",$user->avatar); 
            }
            $user->delete();
        });
    }

    public function profile(): User
    {
        $id = Auth::id();
        return $this->user->findOrFail($id);
    }

    // ==========================================
    // Private Helper Methods
    // ==========================================
    private function getRoleName(string $roleId)
    {
        return $this->role->where('id', $roleId)->value('name');
    }


}
