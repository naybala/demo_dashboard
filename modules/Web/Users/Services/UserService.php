<?php

namespace BasicDashboard\Web\Users\Services;

use BasicDashboard\Foundations\Actions\WebFileStoreAction;
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
        private WebFileStoreAction $webFileStoreAction,
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
            $image    = $request['avatar'] ?? null;
            $roleId   = $request['role_id'];
            $roleName = $this->getRoleName($roleId);
            $payload  = Arr::except($request, ['avatar', 'role_id']);
            $payload['role_marked'] = $roleName;
            $payload['created_by']  = Auth::id();
            $user = $this->user->create($payload);
            $user->assignRole($roleName);

            if ($image) {
                $fileData = $this->webFileStoreAction->store($user, $image,self::ROOT,'avatar');
                $user->forceFill($fileData)->save();
            }

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
            $image     = $request['avatar'] ?? null;
            $roleId    = $request['role_id'] ?? null;
            $payload   = Arr::except($request, ['avatar', 'role_id']);
            $roleName  = $roleId ? $this->getRoleName($roleId) : null;

            if ($roleName) {
                $payload['role_marked'] = $roleName;
            }

            if ($image) {
                $fileData = $this->webFileStoreAction->update($user, $image,config('cache.file_system_disk'),'avatar',self::ROOT);
                $payload  = array_merge($payload, $fileData);
            }

            $user->update($payload);

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
            $user = $this->user->findOrFail($decodedId);
            $user->roles()->detach();
            $this->webFileStoreAction->delete($user, config('cache.file_system_disk'),'avatar');
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
