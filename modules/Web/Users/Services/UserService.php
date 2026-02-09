<?php

namespace BasicDashboard\Web\Users\Services;

use BasicDashboard\Foundations\Actions\WebFileStoreAction;
use BasicDashboard\Foundations\Domain\Roles\Role;
use BasicDashboard\Foundations\Domain\Users\User;
use BasicDashboard\Foundations\Shared\BaseCrudService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService extends BaseCrudService
{
    const ROOT = "Users";

    public function __construct(
        User $user,
        private Role $role,
        private WebFileStoreAction $webFileStoreAction,
    ) {
        parent::__construct($user);
    }

    public function paginate(array $request): LengthAwarePaginator
    {
        return $this->model
            ->withUserRelations()
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function store(array $request): Model
    {
        return DB::transaction(function () use ($request) {
            $image    = $request['avatar'] ?? null;
            $roleName = $this->getRoleName($request['role_marked']);
            $payload  = Arr::except($request, ['avatar', 'role_marked']);
            
            $user = parent::store($payload);
            $user->assignRole($roleName);

            if ($image) {
                $fileData = $this->webFileStoreAction->store($user, $image, self::ROOT, 'avatar');
                $user->forceFill($fileData)->save();
            }

            return $user;
        });
    }

    public function find($id): ?User
    {
        return $this->model->find($id);
    }

    public function update(array $request, string $id): Model
    {
        return DB::transaction(function () use ($request, $id) {
            $image    = $request['avatar'] ?? null;
            $payload  = Arr::except($request, ['avatar', 'role_marked']);
            $roleName = $this->getRoleName($request['role_marked'] ?? null);

            $user = parent::update($payload, $id);

            if ($image) {
                $fileData = $this->webFileStoreAction->update($user, $image, config('cache.file_system_disk'), 'avatar', self::ROOT);
                $user->forceFill($fileData)->save();
            }

            if ($roleName) {
                $user->syncRoles($roleName);
            }

            return $user;
        });
    }

    public function delete(string $id): void
    {
        DB::transaction(function () use ($id) {
            $user = $this->findOrFail($id);
            $user->roles()->detach();
            $this->webFileStoreAction->delete($user, config('cache.file_system_disk'), 'avatar');
            parent::delete($id);
        });
    }

    public function profile(): User
    {
        $id = Auth::id();
        return $this->model->findOrFail($id);
    }

    // ==========================================
    // Private Helper Methods
    // ==========================================
    private function getRoleName(string $roleId)
    {
        return $this->role->where('id', $roleId)->value('name');
    }
}
