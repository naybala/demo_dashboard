<?php

namespace BasicDashboard\Web\Users\Services;

use BasicDashboard\Web\Users\Services\UserImageAction;
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
        private UserImageAction $userImageAction,
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
            $roleName = $this->getRoleName($request['role_marked']);
            $payload  = Arr::except($request, ['avatar', 'role_marked']);
            $payload['created_by'] = Auth::id();
            $user = $this->user->create($payload);
            $user->assignRole($roleName);

            if ($image) {
                $fileData = $this->userImageAction->store($user, $image);
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
            $payload   = Arr::except($request, ['avatar', 'role_marked']);
            $roleName  = $this->getRoleName($request['role_marked'] ?? null);

            if ($image) {
                $fileData = $this->userImageAction->update($user, $image, config('cache.file_system_disk'));
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
            $this->userImageAction->delete($user, config('cache.file_system_disk'));
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
