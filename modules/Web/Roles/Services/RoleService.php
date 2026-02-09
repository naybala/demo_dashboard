<?php
namespace BasicDashboard\Web\Roles\Services;

use App\Exceptions\WarningException;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use BasicDashboard\Foundations\Domain\Roles\Role;



class RoleService
{
    public function __construct(
        private Role $role,
        private Permission $permission,
    ) {
    }

    public function paginate(array $request)
    {
        return $this->role
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? 20);
    }

    public function getFormattedPermissions(): array
    {
        $features = config('numbers.permissions');
        $permissions      = $this->permission->orderBy('id', 'asc')->get(['id', 'name'])->toArray(); 
        $finalPermissions = [];
        foreach ($features as $feature) {
            $finalPermissions[$feature] = array_filter($permissions, function ($permission) use ($feature) {
                $getPermissionFeature = explode(' ', $permission['name'])[1];
                return $getPermissionFeature == $feature;
            });
        }
        return $finalPermissions;
    }

    public function store(array $request): Role
    {
        return DB::transaction(function () use ($request) {
            $role = $this->role->create([
                'name'             => $request['name'],
                'guard_name'       => 'web',
                'can_access_panel' => $request['can_access_panel'],
                'created_by'       => auth()->id(),
            ]);
            $role->givePermissionTo($request['permissions']);
            return $role;
        });
    }

    public function findOrFail(string $decodedId): array
    {
        $role = $this->role->findOrFail($decodedId);
        $getAllPermissions     = $this->getFormattedPermissions();                     
        $getCurrentPermissions = $role->getAllPermissions()->pluck('name')->toArray(); 
        return [
            'role' => $role,
            'getAllPermissions' => $getAllPermissions,
            'getCurrentPermissions' => $getCurrentPermissions,
        ];
    }


    public function update(array $request, string $decodedId): Role
    {
        return DB::transaction(function () use ($request, $decodedId) {
            $role = $this->role->findOrFail($decodedId);
            $role->update([
                'name'             => $request['name'],
                'can_access_panel' => $request['can_access_panel'] ?? 0,
            ]);
            $role->syncPermissions($request['permissions']); 
            return $role;
        });
    }

    public function delete(string $id): void
    {
         DB::transaction(function () use ($id) {
            $role = $this->role->findOrFail($id);
            if($role->hasUsers()){
                throw new WarningException('role.role_in_use');
            }
            $role->delete();
        });
    }

}
