<?php
namespace BasicDashboard\Web\Permissions\Services;

use BasicDashboard\Foundations\Domain\Permissions\Permission;

class PermissionService
{
    public function __construct(
        private Permission $permission
    ) {
    }

    public function all()
    {
        return $this->permission->orderBy('name')->get();
    }

    public function paginate(array $request)
    {
        return $this->permission
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? 20);
    }

    public function store(array $request): Permission
    {
        return $this->permission->create([
            'name'       => $request['name'],
            'guard_name' => 'web',
        ]);
    }

    public function findOrFail(string $id): Permission
    {
        return $this->permission->findOrFail($id);
    }

    public function update(array $request, string $id): Permission
    {
        $permission = $this->permission->findOrFail($id);
        $permission->update([
            'name' => $request['name'],
        ]);
        return $permission;
    }

    public function delete(string $id): void
    {
        $permission = $this->permission->findOrFail($id);
        if ($permission->hasRoles()) {
            throw new \App\Exceptions\WarningException('permission.permission_in_use');
        }
        $permission->delete();
    }
}
