<?php

namespace BasicDashboard\Web\Users\Services;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use BasicDashboard\Foundations\Domain\Users\User;
use BasicDashboard\Foundations\Domain\Roles\Role;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\DB;

class UserService
{
    const ROOT      = "Users";

    public function __construct(
        private User $user,                          
        private Role $role,                         
        private FilesystemManager $fileSystemManager,
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
        return DB::transaction(function () use ($request) {
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
            $cloudPath = self::ROOT . "/" . $user->id;
            config('cache.file_system_disk') == 'local' ?
            $data = $this->fileSystemManager->uploadFileToLocal($image,"users","avatar") :
            $data = $this->fileSystemManager->uploadFileToCloud("digitalocean",$image,$cloudPath,"public","avatar"); 
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
        return DB::transaction(function () use ($request, $id) {
            $decodedId = customDecoder($id);
            $user      = $this->user->findOrFail($decodedId);
            $oldImage = $user->avatar;
            $image    = $request['avatar'] ?? null;
            // Prepare payload (do not mutate original request)
            $payload = Arr::except($request, ['avatar', 'role_marked']);
            // Resolve role name
            $roleName = $this->getRoleName($request['role_marked'] ?? null);
            // -------- Handle avatar upload first --------
            if ($image) {
                $cloudPath = self::ROOT . "/" . $user->id;
                $fileData = config('cache.file_system_disk') === 'local'
                    ? $this->fileSystemManager->updateFileFromLocal($oldImage, $image, 'avatar', 'users')
                    : $this->fileSystemManager->updateFileFromCloud(
                        "digitalocean",
                        $oldImage,
                        $image,
                        $cloudPath,
                        'public',
                        'avatar'
                    );

                $payload = array_merge($payload, $fileData);
            }
            // -------- Single DB update --------
            $user->update($payload);
            // -------- Sync roles after update --------
            if ($roleName) {
                $user->syncRoles($roleName);
            }
            return $user;
        });
    }


    public function delete(string $id): void
    {
        DB::transaction(function () use ($id) {
            $decodedId = customDecoder($id);
            $user = $this->user->where('id', $decodedId)->first();
            $user->roles()->detach();
            config('cache.file_system_disk') == 'local' ?
                $this->fileSystemManager->deleteFileFromLocal($user->avatar) :
                $this->fileSystemManager->deleteFileFromCloud("digitalocean",$user->avatar); 
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
