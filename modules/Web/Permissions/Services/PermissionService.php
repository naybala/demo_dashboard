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
        $decodedId = customDecoder($id);
        return $this->permission->findOrFail($decodedId);
    }

    public function update(array $request, string $id): Permission
    {
        $decodedId = customDecoder($id);
        $permission = $this->permission->findOrFail($decodedId);
        $permission->update([
            'name' => $request['name'],
        ]);
        return $permission;
    }

    public function delete(string $id): void
    {
        $decodedId = customDecoder($id);
        $permission = $this->permission->findOrFail($decodedId);
        if ($permission->hasRoles()) {
            throw new \App\Exceptions\WarningException('permission.permission_in_use');
        }
        $permission->delete();
    }
}
